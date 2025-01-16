<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use RouterOS\Query;
use App\Models\Router;
use App\Models\Package;
use PEAR2\Net\RouterOS;
use App\Models\Pelanggan;
use PEAR2\Cache\SHM\Cache;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use App\Helpers\MessageHelper;
use PEAR2\Net\RouterOS\Client;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PelangganImport;
use Yajra\DataTables\Facades\DataTables;

class PelangganController extends Controller
{





    public function data(Request $request)
    {
        $pelanggan = Pelanggan::with(['package', 'pembayaran'])->select('pelanggans.*');

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status !== null) {
            $pelanggan->where('status', $request->status);
        }

        return DataTables::of($pelanggan)
            ->addIndexColumn()
            ->addColumn('ktp', function ($row) {
                if ($row->ktp) {
                    return '<a href="' . asset($row->ktp) . '" class="btn btn-sm btn-light" download>
                            <i class="fas fa-download"></i> Download
                        </a>';
                }
                return '<span class="text-muted">No file</span>';
            })
            ->addColumn('pembayaran_terakhir', function ($row) {
                $pembayaranTerakhir = optional($row->pembayaran()->latest()->first())->periode;
                return $pembayaranTerakhir ? $pembayaranTerakhir : 'Belum ada pembayaran';
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="form-button-action d-flex flex-wrap gap-1" style="min-width: 200px;">
                    <button class="btn btn-link btn-danger btn-isolir btn-md p-1"
                        data-toggle="tooltip" data-original-title="Isolir"
                        data-id="' . $row->id . '">
                        <i class="fa fa-lock"></i>
                    </button>

                    <button class="btn btn-link btn-success btn-bukaisolir btn-md p-1"
                        data-toggle="tooltip" data-original-title="Buka Isolir"
                        data-id="' . $row->id . '">
                        <i class="fa fa-unlock"></i>
                    </button>

                    <a href="' . route('pembayaran.create', $row->id) . '"
                        class="btn btn-link btn-primary btn-md p-1" data-toggle="tooltip"
                        data-original-title="Pembayaran">
                        <i class="fa fa-money-bill"></i>
                    </a>

                    <a href="' . route('pelanggan.edit', $row->id) . '"
                        class="btn btn-link btn-primary btn-md p-1" data-toggle="tooltip"
                        data-original-title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>

                    <a href="' . route('pelanggan.delete', $row->id) . '"
                        class="btn btn-link btn-danger btn-md p-1" data-toggle="tooltip"
                        data-original-title="Hapus"
                        onclick="return confirm(\'Apakah anda yakin menghapus pelanggan ' . $row->nama . '?\')">
                        <i class="fa fa-times"></i>
                    </a>
                </div>';
            })
            ->rawColumns(['ktp', 'action'])
            ->make(true);
    }



    public function index()
    {
        $routers = Router::all();

        $API = new RouterosAPI();
        $API->debug = false;

        // Data yang akan ditampilkan
        $data = [
            'menu' => 'PPPoE',
            'totalsecret' => 0,
            'secret' => [],
            'profile' => [],
            'routers' => $routers,
        ];

        foreach ($routers as $router) {
            if ($API->connect($router->ip, $router->username, $router->password)) {
                // Ambil data dari router yang terhubung
                $secrets = $API->comm('/ppp/secret/print');
                $profiles = $API->comm('/ppp/profile/print');

                $data['totalsecret'] += count($secrets); // Tambahkan jumlah secret
                $data['secret'] = array_merge($data['secret'], $secrets); // Gabungkan data secret
                $data['profile'] = array_merge($data['profile'], $profiles); // Gabungkan data profile

                $API->disconnect(); // Putus koneksi setelah selesai
            } else {
                // Tambahkan log atau penanganan jika salah satu router gagal terhubung
                $data['failed_routers'][] = $router->ip;
            }
        }

        // Ambil periode saat ini
        $periode = Carbon::now()->translatedFormat('F Y');

        $pelanggan = Pelanggan::with(['pembayaran' => function ($query) use ($periode) {
            $query->where('periode', $periode);
        }])->get();

        return view('pelanggan.index', $data, compact('pelanggan', 'periode'));
    }

    public function import(Request $request)
    {
        try {
            // Validasi file
            // $request->validate([
            //     'file' => 'required|mimes:xlsx,xls',
            // ]);

            // Proses file Excel
            Excel::import(new PelangganImport, $request->file('file'));

            Alert::success('Success', 'Data pelanggan berhasil diimpor.');
            return redirect()->route('pelanggan.index');
        } catch (Exception $e) {
            Alert::error('Error', 'Gagal mengimpor data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function create()
    {
        $routers = Router::all();

        $API = new RouterosAPI();
        $API->debug = false;

        // Data yang akan ditampilkan
        $data = [
            'menu' => 'PPPoE',
            'totalsecret' => 0,
            'secret' => [],
            'profile' => [],
            'routers' => $routers,
        ];

        foreach ($routers as $router) {
            if ($API->connect($router->ip, $router->username, $router->password)) {
                // Ambil data dari router yang terhubung
                $secrets = $API->comm('/ppp/secret/print');
                $profiles = $API->comm('/ppp/profile/print');

                $data['totalsecret'] += count($secrets); // Tambahkan jumlah secret
                $data['secret'] = array_merge($data['secret'], $secrets); // Gabungkan data secret
                $data['profile'] = array_merge($data['profile'], $profiles); // Gabungkan data profile

                $API->disconnect(); // Putus koneksi setelah selesai
            } else {
                // Tambahkan log atau penanganan jika salah satu router gagal terhubung
                $data['failed_routers'][] = $router->ip;
            }
        }

        $pelanggan = Pelanggan::all();
        $packages = Package::all();
        return view('pelanggan.create', $data, compact('pelanggan', 'packages'));
    }

    public function getProfiles($routerId)
    {
        $router = Router::findOrFail($routerId);

        $API = new RouterosAPI();
        $API->debug = false;

        $profiles = [];

        // Attempt to connect to the router
        if ($API->connect($router->ip, $router->username, $router->password)) {
            // Fetch profiles from the router
            $profiles = $API->comm('/ppp/profile/print');
            $API->disconnect(); // Disconnect after fetching profiles

            // Check if profiles are empty
            if (empty($profiles)) {
                return response()->json(['error' => 'No profiles found on this router'], 404);
            }
        } else {
            // Handle failed connection if necessary
            return response()->json(['error' => 'Failed to connect to router'], 500);
        }

        // Debug output
        // Check what data you have
        Log::debug('Profiles: ', $profiles);

        // Return profiles as JSON response
        return response()->json([
            'profiles' => $profiles,
        ]);
    }
    public function getSecrets($routerId, Request $request)
    {
        $router = Router::findOrFail($routerId);
        $searchTerm = $request->get('search', ''); // Ambil parameter search

        $API = new RouterosAPI();
        $API->debug = false;

        $secrets = [];

        if ($API->connect($router->ip, $router->username, $router->password)) {
            // Fetch semua secret dari router
            $secrets = $API->comm('/ppp/secret/print');
            $API->disconnect();

            if (empty($secrets)) {
                return response()->json(['error' => 'No secret found on this router'], 404);
            }

            // Filter secrets berdasarkan search term jika ada
            if (!empty($searchTerm)) {
                $secrets = array_filter($secrets, function ($secret) use ($searchTerm) {
                    return stripos($secret['name'], $searchTerm) !== false;
                });
            }

            // Reset array keys setelah filtering
            $secrets = array_values($secrets);
        } else {
            return response()->json(['error' => 'Failed to connect to router'], 500);
        }

        return response()->json([
            'secrets' => $secrets
        ]);
    }


    // public function add(Request $request)
    // {
    //     // Simpan data pelanggan ke database
    //     $pelanggan = Pelanggan::create(array_merge(
    //         $request->only(['nama', 'jenis', 'alamat', 'no_hp', 'tgl_daftar', 'ktp', 'router_id', 'metode']),
    //         [
    //             'profile' => $request->profile ?? 'default',
    //             'paket' => $request->profile ?? 'default',
    //             'username_pppoe' => $request->user, // Simpan username PPPoE
    //             'remote_address' => $request->remoteaddress ?: null,
    //             'local_address' => $request->localaddress ?: null,
    //         ]
    //     ));

    //     // Upload KTP jika ada
    //     if ($request->hasFile('ktp')) {
    //         $fileName = $request->file('ktp')->getClientOriginalName();
    //         $filePath = public_path('ktp');
    //         if (!file_exists($filePath)) {
    //             mkdir($filePath, 0777, true);
    //         }
    //         $request->file('ktp')->move($filePath, $fileName);
    //         $pelanggan->update(['ktp' => 'ktp/' . $fileName]);
    //     }

    //     // Ambil router yang sesuai dari database
    //     $router = Router::findOrFail($request->router_id); // Pastikan Anda mengirimkan `router_id` di form

    //     // RouterOS API
    //     $API = new RouterosAPI();
    //     $API->debug = false;

    //     try {
    //         if ($API->connect($router->ip, $router->username, $router->password)) {
    //             // Siapkan parameter dasar untuk ppp secret
    //             $parameters = [
    //                 'name' => $request->user,
    //                 'password' => $request->password,
    //                 'service' => $request->service ?? 'any',
    //                 'profile' => $request->profile ?? 'default',
    //                 'comment' => $request->comment ?? '',
    //             ];

    //             // Tambahkan local-address hanya jika diisi
    //             if (!empty($request->localaddress)) {
    //                 $parameters['local-address'] = $request->localaddress;
    //             }

    //             // Tambahkan remote-address hanya jika diisi
    //             if (!empty($request->remoteaddress)) {
    //                 $parameters['remote-address'] = $request->remoteaddress;
    //             }

    //             // Tambahkan secret ke MikroTik
    //             $API->comm('/ppp/secret/add', $parameters);

    //             // Update profile di database jika berhasil ditambahkan ke MikroTik
    //             $pelanggan->update(['profile' => $request->profile ?? 'default']);

    //             Alert::success('Success', 'Berhasil menambahkan pelanggan.');
    //             return redirect()->route('pelanggan.index');
    //         }

    //         throw new Exception('Gagal terhubung ke RouterOS API.');
    //     } catch (Exception $e) {
    //         Alert::error('Error', $e->getMessage());
    //         return redirect()->back();
    //     } finally {
    //         $API->disconnect();
    //     }
    // }



    public function add(Request $request)
    {
        try {
            // Handle file upload
            if ($request->hasFile('ktp')) {
                $fileName = $request->file('ktp')->getClientOriginalName();
                $filePath = public_path('ktp');
                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777, true);
                }
                $request->file('ktp')->move($filePath, $fileName);
                $ktpPath = 'ktp/' . $fileName;
            }

            // Ambil data paket
            $package = Package::findOrFail($request->package_id);

            // Pastikan profile yang digunakan sesuai paket
            $profile = $package->profile; // Profile dari paket yang dipilih

            // Get router
            $router = Router::findOrFail($request->router_id);

            // Connect to RouterOS
            $API = new RouterosAPI();
            $API->debug = false;

            if (!$API->connect($router->ip, $router->username, $router->password)) {
                throw new Exception('Gagal terhubung ke router.');
            }

            // Handle berdasarkan metode
            if ($request->metode === 'sinkronisasi') {
                // Untuk sinkronisasi, gunakan detail secret yang sudah ada
                $pppoeDetails = [
                    'username_pppoe' => $request->sync_username,
                    'remote_address' => $request->sync_remote_address,
                    'local_address' => $request->sync_local_address,
                    'profile' => $profile, // Pastikan profile sinkron dengan paket
                    // 'profile' => $request->sync_profile,
                    'service' => $request->sync_service,

                ];
            } else {
                // Untuk PPPoE baru, buat secret baru
                $parameters = [
                    'name' => $request->user,
                    'password' => $request->password,
                    'service' => $request->service ?? 'any',
                    'profile' => $profile, // Gunakan profile dari paket
                    'comment' => $request->comment ?? '',
                ];

                if ($request->localaddress) {
                    $parameters['local-address'] = $request->localaddress;
                }
                if ($request->remoteaddress) {
                    $parameters['remote-address'] = $request->remoteaddress;
                }

                // Tambahkan secret baru ke RouterOS
                $API->comm('/ppp/secret/add', $parameters);

                $pppoeDetails = [
                    'username_pppoe' => $request->user,
                    'remote_address' => $request->remoteaddress,
                    'local_address' => $request->localaddress,
                    'profile' => $profile, // Profile tetap sinkron
                    'service' => $request->service,
                ];
            }

            // Simpan data pelanggan ke database
            $pelanggan = Pelanggan::create(array_merge(
                $request->only(['nama', 'jenis', 'alamat', 'no_hp', 'tgl_daftar', 'router_id', 'metode', 'titik_koordinat', 'package_id', 'tanggal_jatuh_tempo']),
                $pppoeDetails,
                ['ktp' => $ktpPath ?? null]
            ));

            // Kirim pesan hanya jika jenis adalah "baru"
            if ($pelanggan->jenis === 'baru') {
                $data = [
                    'nama' => $pelanggan->nama,
                    'paket' => $package->nama,
                    'tagihan' => number_format($package->harga, 0, ',', '.'),
                    'tanggal' => now()->format('d-m-Y'),
                ];

                $message = MessageHelper::processTemplate('pelanggan_baru', $data);

                if ($message && $pelanggan->no_hp) {
                    $whatsapp = new WhatsappService();
                    $whatsapp->sendMessage($pelanggan->no_hp, $message);
                }
            }
            Alert::success('Success', 'Berhasil menambahkan pelanggan.');
            return redirect()->route('pelanggan.index');
        } catch (Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        } finally {
            if (isset($API) && $API->connected) {
                $API->disconnect();
            }
        }
    }


    // Add this new method to get secret details
    public function getSecretDetails($secretName)
    {
        try {
            $router = Router::first(); // Or get the appropriate router
            $API = new RouterosAPI();

            if ($API->connect($router->ip, $router->username, $router->password)) {
                $secrets = $API->comm('/ppp/secret/print', [
                    '?name' => $secretName,
                ]);

                if (!empty($secrets)) {
                    $secret = $secrets[0];

                    // Pastikan local_address dan remote_address tersedia
                    $response = [
                        'name' => $secret['name'] ?? '',
                        'password' => $secret['password'] ?? '',
                        'service' => $secret['service'] ?? '',
                        'profile' => $secret['profile'] ?? '',
                        'local_address' => $secret['local-address'] ?? '', // Sesuai dengan RouterOS
                        'remote_address' => $secret['remote-address'] ?? '', // Sesuai dengan RouterOS
                    ];

                    return response()->json($response);
                }

                return response()->json(['error' => 'Secret not found'], 404);
            }

            return response()->json(['error' => 'Failed to connect to router'], 500);
        } finally {
            if (isset($API) && $API->connected) {
                $API->disconnect();
            }
        }
    }





    public function isolirlama($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosAPI();
        $API->debug = false;

        try {
            if ($API->connect($ip, $user, $password)) {
                // Hapus active session PPPoE jika ada
                $active = $API->comm('/ppp/active/print', [
                    '?name' => $pelanggan->username_pppoe
                ]);

                if (!empty($active)) {
                    $API->comm('/ppp/active/remove', [
                        '.id' => $active[0]['.id']
                    ]);
                }

                // Cari secret berdasarkan username PPPoE
                $secret = $API->comm('/ppp/secret/print', [
                    '?name' => $pelanggan->username_pppoe
                ]);

                // Pengecekan apakah secret ditemukan
                if (empty($secret) || !isset($secret[0])) {
                    throw new Exception('PPPoE secret tidak ditemukan.');
                }


                // Ubah profile menjadi "ISOLIR" dan kosongkan local-address serta remote-address
                $API->comm('/ppp/secret/set', [
                    '.id' => $secret[0]['.id'],
                    'profile' => 'ISOLIR',
                    'local-address' => 'b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       ',
                    'remote-address' => '0.0.0.0',
                ]);

                // Update status dan profile di database
                $pelanggan->update([
                    'status' => 'isolir',
                    'profile' => 'ISOLIR',

                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Pelanggan berhasil diisolir.'
                ]);
            }

            throw new Exception('Gagal terhubung ke Router MikroTik.');
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        } finally {
            $API->disconnect();
        }
    }

    public function isolir($id)
    {
        $pelanggan = Pelanggan::with('router', 'package')->findOrFail($id);

        // Pastikan router terkait ditemukan
        if (!$pelanggan->router) {
            return response()->json([
                'success' => false,
                'message' => 'Router tidak ditemukan untuk pelanggan ini.'
            ], 404);
        }

        $API = new RouterosAPI();
        $API->debug = false;

        try {
            // Ambil data router dari relasi
            $ip = $pelanggan->router->ip;
            $user = $pelanggan->router->username;
            $password = $pelanggan->router->password;

            // Tambahkan ini untuk menangkap log debug
            ob_start();
            $API->connect($ip, $user, $password);
            $debugOutput = ob_get_clean();

            // Tulis log debug ke file Laravel
            Log::info('RouterOS Debug Output:', ['debug' => $debugOutput]);

            if ($API->connect($ip, $user, $password)) {
                // Hapus active session PPPoE jika ada
                $active = $API->comm('/ppp/active/print', [
                    '?name' => $pelanggan->username_pppoe
                ]);

                if (!empty($active)) {
                    $API->comm('/ppp/active/remove', [
                        '.id' => $active[0]['.id']
                    ]);
                }

                // Cari secret berdasarkan username PPPoE
                $secret = $API->comm('/ppp/secret/print', [
                    '?name' => $pelanggan->username_pppoe
                ]);

                if (empty($secret) || !isset($secret[0])) {
                    throw new Exception('PPPoE secret tidak ditemukan.');
                }

                // Ubah profile menjadi "ISOLIR" dan kosongkan local-address serta remote-address
                $API->comm('/ppp/secret/set', [
                    '.id' => $secret[0]['.id'],
                    'profile' => 'ISOLIR',
                ]);

                $response3 = $API->comm('/ppp/secret/set', [
                    '.id' => $secret[0]['.id'],
                    'local-address' => '0.0.0.0',
                    'remote-address' => '0.0.0.0',

                ]);


                // \Log::info('Set Address Response:', ['response' => $response3]);

                // Update status dan profile di database
                $pelanggan->update([
                    'status' => 'isolir',
                    'profile' => 'ISOLIR',
                ]);

                // Siapkan data untuk template
                $data = [
                    'nama' => $pelanggan->nama,
                    'paket' => $pelanggan->package->nama,
                    'tagihan' => number_format($pelanggan->package->harga, 0, ',', '.'),
                    'tanggal' => now()->format('d-m-Y')
                ];
                // Proses template pesan
                $message = MessageHelper::processTemplate('isolir', $data);

                if ($message && $pelanggan->no_hp) {
                    $whatsapp = new WhatsappService();
                    $whatsapp->sendMessage($pelanggan->no_hp, $message);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Pelanggan berhasil diisolir.'
                ]);
            }



            throw new Exception('Gagal terhubung ke Router MikroTik.');
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        } finally {
            $API->disconnect();
        }
    }



    public function bukaIsolir($id)
    {
        $pelanggan = Pelanggan::with(['router', 'package'])->findOrFail($id);

        // Pastikan router dan package terkait ditemukan
        if (!$pelanggan->router || !$pelanggan->package) {
            return response()->json([
                'success' => false,
                'message' => 'Router atau package tidak ditemukan untuk pelanggan ini.'
            ], 404);
        }

        $API = new RouterosAPI();
        $API->debug = false;

        try {
            // Ambil data router dari relasi
            $ip = $pelanggan->router->ip;
            $user = $pelanggan->router->username;
            $password = $pelanggan->router->password;

            if ($API->connect($ip, $user, $password)) {

                // Hapus active session PPPoE jika ada
                $active = $API->comm('/ppp/active/print', [
                    '?name' => $pelanggan->username_pppoe
                ]);

                if (!empty($active)) {
                    $API->comm('/ppp/active/remove', [
                        '.id' => $active[0]['.id']
                    ]);
                }

                // Cari secret berdasarkan username PPPoE
                $secret = $API->comm('/ppp/secret/print', [
                    '?name' => $pelanggan->username_pppoe
                ]);

                // Pengecekan apakah secret ditemukan
                if (empty($secret) || !isset($secret[0])) {
                    throw new Exception('PPPoE secret tidak ditemukan.');
                }

                // Ambil profile dari package
                $profile = $pelanggan->package->profile;

                // Kembalikan profile ke package profile
                $API->comm('/ppp/secret/set', [
                    '.id' => $secret[0]['.id'],
                    'profile' => $profile,
                ]);

                // Reset local-address dan remote-address jika diperlukan
                $API->comm('/ppp/secret/set', [
                    '.id' => $secret[0]['.id'],
                    'local-address' => $pelanggan->local_address,
                    'remote-address' => $pelanggan->remote_address,
                ]);

                // Hapus pelanggan dari address list 'isolir'
                $API->comm('/ip/firewall/address-list/remove', [
                    'list' => 'isolir',
                    'address' => $pelanggan->remote_address,
                ]);

                // Update status pelanggan di database
                $pelanggan->update([
                    'status' => 'aktif', // Ganti dengan status yang sesuai
                    'profile' => $profile,
                ]);

                // Siapkan data untuk template
                $data = [
                    'nama' => $pelanggan->nama,
                    'paket' => $pelanggan->package->nama,
                    'tagihan' => number_format($pelanggan->package->harga, 0, ',', '.'),
                    'tanggal' => now()->format('d-m-Y')
                ];
                // Proses template pesan
                $message = MessageHelper::processTemplate('internet_aktif', $data);

                if ($message && $pelanggan->no_hp) {
                    $whatsapp = new WhatsappService();
                    $whatsapp->sendMessage($pelanggan->no_hp, $message);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Pelanggan berhasil dibuka isolirnya.'
                ]);
            }

            throw new Exception('Gagal terhubung ke Router MikroTik.');
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        } finally {
            $API->disconnect();
        }
    }

    public function edit($id)
    {
        $routers = Router::all();
        $pelanggan = Pelanggan::findOrFail($id); // Get specific pelanggan
        $packages = Package::all();

        $API = new RouterosAPI();
        $API->debug = false;

        $data = [
            'menu' => 'PPPoE',
            'totalsecret' => 0,
            'secret' => [],
            'profile' => [],
            'routers' => $routers,
            'pelanggan' => $pelanggan, // Add pelanggan to data array
            'packages' => $packages    // Add packages to data array
        ];

        foreach ($routers as $router) {
            if ($API->connect($router->ip, $router->username, $router->password)) {
                $secrets = $API->comm('/ppp/secret/print');
                $profiles = $API->comm('/ppp/profile/print');

                $data['totalsecret'] += count($secrets);
                $data['secret'] = array_merge($data['secret'], $secrets);
                $data['profile'] = array_merge($data['profile'], $profiles);

                $API->disconnect();
            } else {
                $data['failed_routers'][] = $router->ip;
            }
        }

        return view('pelanggan.edit', $data);
    }

    public function update(Request $request)
    {
        try {
            // Cari pelanggan berdasarkan ID yang dikirim dari form
            $pelanggan = Pelanggan::findOrFail($request->id);

            // Handle KTP file update
            if ($request->hasFile('ktp')) {
                // Delete old KTP file
                if ($pelanggan->ktp && file_exists(public_path($pelanggan->ktp))) {
                    unlink(public_path($pelanggan->ktp));
                }

                $fileName = $request->file('ktp')->getClientOriginalName();
                $filePath = public_path('ktp');
                $request->file('ktp')->move($filePath, $fileName);
                $ktpPath = 'ktp/' . $fileName;
            }

            // Get package and profile
            $package = Package::findOrFail($request->package_id);
            $profile = $package->profile;

            // Get router
            $router = Router::findOrFail($request->router_id);

            // Connect to RouterOS
            $API = new RouterosAPI();
            $API->debug = false;

            if (!$API->connect($router->ip, $router->username, $router->password)) {
                throw new Exception('Gagal terhubung ke router.');
            }

            // Update PPPoE details based on method
            if ($request->metode === 'sinkronisasi') {
                $pppoeDetails = [
                    'username_pppoe' => $request->sync_username,
                    'remote_address' => $request->sync_remote_address,
                    'local_address' => $request->sync_local_address,
                    'profile' => $profile,
                    'service' => $request->sync_service,
                ];
            } else {
                // Find and update existing secret
                $secrets = $API->comm('/ppp/secret/print', [
                    '?name' => $pelanggan->username_pppoe
                ]);

                if (count($secrets) > 0) {
                    $secretId = $secrets[0]['.id'];
                    $parameters = [
                        '.id' => $secretId,
                        'name' => $request->user,
                        'password' => $request->password,
                        'service' => $request->service ?? 'any',
                        'profile' => $profile,
                        'comment' => $request->comment ?? '',
                    ];

                    if ($request->localaddress) {
                        $parameters['local-address'] = $request->localaddress;
                    }
                    if ($request->remoteaddress) {
                        $parameters['remote-address'] = $request->remoteaddress;
                    }

                    $API->comm('/ppp/secret/set', $parameters);
                }

                $pppoeDetails = [
                    'username_pppoe' => $request->user,
                    'remote_address' => $request->remoteaddress,
                    'local_address' => $request->localaddress,
                    'profile' => $profile,
                    'service' => $request->service,
                ];
            }

            // Update database record
            $pelanggan->update(array_merge(
                $request->only(['nama', 'jenis', 'alamat', 'no_hp', 'tgl_daftar', 'router_id', 'metode', 'titik_koordinat', 'package_id', 'tanggal_jatuh_tempo']),
                $pppoeDetails,
                ['ktp' => $ktpPath ?? $pelanggan->ktp]
            ));

            Alert::success('Success', 'Berhasil mengupdate pelanggan.');
            return redirect()->route('pelanggan.index');
        } catch (Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        } finally {
            if (isset($API) && $API->connected) {
                $API->disconnect();
            }
        }
    }


    public function sendReminder(Request $request)
    {
        try {
            $pelanggan = Pelanggan::where('tanggal_jatuh_tempo', $request->tanggal_jatuh_tempo)
                ->get();

            $berhasil = 0;
            $gagal = 0;

            foreach ($pelanggan as $p) {
                try {
                    // Siapkan data untuk template
                    $data = [
                        'nama' => $p->nama,
                        'paket' => $p->package->nama,
                        'tagihan' => number_format($p->package->harga, 0, ',', '.'),
                        'tanggal' => now()->format('d-m-Y'),
                        'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo
                    ];

                    // Pilih template berdasarkan tipe pesan

                    // Proses template pesan
                    $message = MessageHelper::processTemplate('peringatan_jatuh_tempo', $data);


                    if ($message && $p->no_hp) {
                        $whatsapp = new WhatsappService();
                        $whatsapp->sendMessage($p->no_hp, $message);
                        $berhasil++;
                    } else {
                        $gagal++;
                    }
                } catch (\Exception $e) {
                    $gagal++;
                    Log::error('Error sending WhatsApp message: ' . $e->getMessage());
                    continue;
                }
            }
            Alert::success('Success', "Berhasil mengirim $berhasil pesan, Gagal mengirim $gagal pesan");
            return redirect()->back()->with('success', "Berhasil mengirim $berhasil pesan, Gagal mengirim $gagal pesan");
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();
        Alert::success('Berhasil', 'Pelanggan berhasil dihapus.');
        return redirect()->route('pelanggan.index');
    }
}
