<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Helpers\MessageHelper;
use Yajra\DataTables\Facades\DataTables;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class PembayaranController extends Controller
{

    public function data()
    {
        $pembayaran = Pembayaran::with(['pelanggan', 'pelanggan.package'])->select('pembayarans.*');

        return DataTables::of($pembayaran)
            ->addIndexColumn()
            ->addColumn('pelanggan_nama', function ($row) {
                return $row->pelanggan->nama ?? 'Tidak ada nama';
            })
            ->addColumn('pelanggan_alamat', function ($row) {
                return $row->pelanggan->alamat ?? 'Tidak ada alamat';
            })
            ->addColumn('periode', function ($row) {
                return $row->periode ?? 'Tidak ada periode';
            })
            ->addColumn('tgl_pembayaran', function ($row) {
                return $row->tgl_pembayaran
                    ? Carbon::parse($row->tgl_pembayaran)->format('d M Y')
                    : 'Belum ada tanggal';
            })
            ->addColumn('package_nama', function ($row) {
                return $row->pelanggan->package->nama ?? 'Tidak ada paket';
            })
            ->addColumn('jumlah', function ($row) {
                return $row->jumlah ? number_format($row->jumlah, 0, ',', '.') : '0';
            })
            ->addColumn('status_badge', function ($row) {
                $badgeClass = $row->status == 'Lunas' ? 'badge-success' : 'badge-warning';
                return '<span class="badge ' . $badgeClass . '">' . ($row->status ?? 'Tidak ada status') . '</span>';
            })
            ->addColumn('bukti', function ($row) {
                if ($row->bukti) {
                    return '<a href="' . asset($row->bukti) . '" class="btn btn-sm btn-light download-btn" download>
                                <i class="fas fa-download me-1"></i> Download
                            </a>';
                } else {
                    return '<span class="text-muted small">No file</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $actions = '';

                if ($row->status == 'Lunas') {
                    $actions .= '<a href="' . route('pembayaran.invoice', $row->id) . '" class="btn btn-link btn-warning btn-lg" data-toggle="tooltip" data-original-title="Print Invoice" target="_blank">
                                    <i class="fa fa-print"></i>
                                 </a>';
                }

                $actions .= '<a href="' . route('pembayaran.delete', $row->id) . '" class="btn btn-link btn-danger btn-lg" data-toggle="tooltip" data-original-title="Hapus" onclick="return confirm(\'Apakah anda yakin menghapus Pembayaran?\')">
                                <i class="fa fa-times"></i>
                             </a>';

                return $actions;
            })
            ->rawColumns(['status_badge', 'bukti', 'action'])
            ->make(true);
    }



    public function index()
    {
        $pembayaran = Pembayaran::with('pelanggan')->get();
        return view('pembayaran.index', compact('pembayaran'));
    }

    public function create($id)
    {
        // Cari data pelanggan berdasarkan ID
        $pelanggan = Pelanggan::findOrFail($id);

        // Tampilkan form pembayaran dengan data pelanggan
        return view('pembayaran.create', compact('pelanggan'));
    }

    public function add(Request $request)
    {
        try {
            // Cek apakah sudah ada pembayaran untuk pelanggan ini di periode yang sama
            $existingPayment = Pembayaran::where('id_pelanggan', $request->id_pelanggan)
                ->where('periode', $request->periode)
                ->where('status', 'Lunas')
                ->first();

            if ($existingPayment) {
                Alert::error('Error', 'Pelanggan sudah melakukan pembayaran di periode bulan ini.');
                return redirect()->back();
            }

            // Simpan bukti pembayaran
            $buktiPath = null;
            if ($request->hasFile('bukti')) {
                $fileName = $request->file('bukti')->getClientOriginalName();
                $filePath = public_path('bukti');
                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777, true);
                }
                $request->file('bukti')->move($filePath, $fileName);
                $buktiPath = 'bukti/' . $fileName;
            }

            // Buat pembayaran baru
            $pembayaran = Pembayaran::create([
                'id_pelanggan' => $request->id_pelanggan,
                'tgl_pembayaran' => $request->tgl_pembayaran,
                'jumlah' => $request->jumlah,
                'status' => 'Lunas',
                'periode' => $request->periode,
                'metode' => $request->metode,
                'bukti' => $buktiPath,
            ]);

            // Ambil data pelanggan
            $pelanggan = Pelanggan::with('package')->find($request->id_pelanggan);

            if ($pelanggan) {
                $data = [
                    'nama' => $pelanggan->nama,
                    'periode' => $request->periode,
                    'jumlah' => number_format($request->jumlah, 0, ',', '.'),
                    'tanggal' => Carbon::parse($request->tgl_pembayaran)->format('d-m-Y'),
                    'link_invoice' => route('invoice.show', ['id' => $pembayaran->id]),
                ];

                // Proses template pesan
                $message = MessageHelper::processTemplate('pembayaran_berhasil', $data);

                if ($message && $pelanggan->no_hp) {
                    $whatsapp = new WhatsappService();
                    $whatsapp->sendMessage($pelanggan->no_hp, $message);
                }
            }

            Alert::success('Success', 'Berhasil menambahkan Pembayaran.');
            return redirect()->route('pembayaran.index')->with([
                'print_pembayaran_id' => $pembayaran->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error dalam proses pembayaran: ' . $e->getMessage());
            Alert::error('Error', 'Terjadi kesalahan dalam proses pembayaran.');
            return redirect()->back();
        }
    }
    public function showInvoice($id)
    {
        $pembayaran = Pembayaran::with('pelanggan')->findOrFail($id);

        return view('pembayaran.invoicelink', compact('pembayaran'));
    }

    // server side
    public function dataLunas()
    {
        $currentPeriode = now()->format('F Y');

        $pelangganLunas = Pelanggan::with(['package', 'pembayaran' => function ($query) {
            $query->latest();
        }])
            ->whereHas('pembayaran', function ($query) use ($currentPeriode) {
                $query->where('status', 'Lunas')
                    ->where('periode', $currentPeriode);
            })
            ->select('pelanggans.*');

        return DataTables::of($pelangganLunas)
            ->addIndexColumn()
            ->addColumn('ktp', function ($row) {
                if ($row->ktp) {
                    return '<img src="' . asset($row->ktp) . '" alt="" style="width: 50px;">
                        <a href="' . asset($row->ktp) . '" class="btn btn-sm btn-light download-btn" download>
                            <i class="fas fa-download me-1"></i> Download
                        </a>';
                } else {
                    return '<span class="text-muted small">No file</span>';
                }
            })
            ->addColumn('periode', function ($row) {
                return optional($row->pembayaran->first())->periode ?? 'Belum ada pembayaran';
            })
            ->rawColumns(['ktp'])
            ->make(true);
    }


    public function showLunas()
    {
        // Format periode sesuai dengan yang disimpan (Format: "January 2024")
        $currentPeriode = now()->format('F Y');

        // Get customers who have paid for current month
        $pelangganLunas = Pelanggan::whereHas('pembayaran', function ($query) use ($currentPeriode) {
            $query->where('status', 'Lunas')
                ->where('periode', $currentPeriode);
        })->get();

        return view('pembayaran.lunas', compact('pelangganLunas', 'currentPeriode'));
    }

    // server side
    public function getBelumLunasData(Request $request)
    {
        $currentPeriode = now()->format('F Y');

        // Ambil pelanggan yang belum lunas
        $query = Pelanggan::with(['package', 'pembayaran' => function ($query) {
            $query->latest('id'); // Ambil pembayaran terbaru
        }])->where(function ($query) use ($currentPeriode) {
            $query->whereDoesntHave('pembayaran', function ($subQuery) use ($currentPeriode) {
                $subQuery->where('periode', $currentPeriode)
                    ->where('status', 'Lunas');
            });
        });

        return DataTables::of($query)
            ->addIndexColumn() // Tambahkan nomor urut
            ->addColumn('action', function ($item) {
                return '<div class="form-button-action d-flex flex-wrap gap-1" style="min-width: 200px;">
                    <a href="' . route('pembayaran.create', $item->id) . '" class="btn btn-link btn-primary btn-lg p-1" data-toggle="tooltip" data-original-title="Pembayaran">
                        <i class="fa fa-money-bill"></i>
                    </a>
                </div>';
            })
            ->editColumn('tgl_daftar', function ($item) {
                return Carbon::parse($item->tgl_daftar)->format('d F Y');
            })
            ->addColumn('ktp', function ($item) {
                if ($item->ktp) {
                    return '<img src="' . asset($item->ktp) . '" alt="" style="width: 50px">
                        <a href="' . asset($item->ktp) . '" class="btn btn-sm btn-light" download="' . basename($item->ktp) . '">
                            <i class="fas fa-download me-1"></i> Download
                        </a>';
                }
                return '<span class="text-muted small">No file</span>';
            })
            ->addColumn('periode', function ($item) {
                // Ambil periode dari pembayaran terakhir jika ada
                $latestPembayaran = $item->pembayaran->first();
                return $latestPembayaran ? $latestPembayaran->periode : 'Belum ada pembayaran';
            })
            ->rawColumns(['action', 'ktp']) // Pastikan kolom raw HTML dirender dengan benar
            ->make(true);
    }

    public function showBelumLunas()
    {
        $currentPeriode = now()->format('F Y');

        // Get customers who haven't paid or have different status than 'Lunas'
        $pelangganBelumLunas = Pelanggan::where(function ($query) use ($currentPeriode) {
            $query->whereDoesntHave('pembayaran', function ($subQuery) use ($currentPeriode) {
                $subQuery->where('periode', $currentPeriode)
                    ->where('status', 'Lunas');
            });
        })->get();
        return view('pembayaran.belum_lunas', compact('pelangganBelumLunas', 'currentPeriode'));
    }
    public function filterPembayaran(Request $request)
    {
        $status = $request->input('status');
        $periode = $request->input('periode');

        $query = Pembayaran::query()->with('pelanggan');

        if ($periode) {
            if ($status === 'Lunas') {
                $pembayaran = $query->where('status', 'Lunas')
                    ->where('periode', $periode)
                    ->get();
            } elseif ($status === 'Belum Lunas') {
                $pelanggan = Pelanggan::whereDoesntHave('pembayaran', function ($query) use ($periode) {
                    $query->where('periode', $periode)
                        ->where('status', 'Lunas');
                })->get();

                $pembayaran = $pelanggan->map(function ($pelanggan) use ($periode) {
                    return (object)[
                        'id' => null,
                        'pelanggan' => $pelanggan,
                        'periode' => $periode,
                        'tgl_pembayaran' => null,
                        'jumlah' => 0,
                        'metode' => null,
                        'status' => 'Belum Lunas',
                        'bukti' => null
                    ];
                });
            } else {
                // If no status selected, show all for the selected period
                $pembayaran = $query->where('periode', $periode)->get();
            }
        } else {
            $pembayaran = $query->get();
        }

        return view('pembayaran.index', compact('pembayaran', 'status', 'periode'));
    }

    public function invoice($id)
    {
        $pembayaran = Pembayaran::with(['pelanggan.package'])->findOrFail($id);
        return view('pembayaran.invoice', compact('pembayaran'));
    }
    public function delete($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->delete();
        Alert::success('Berhasil', 'Pembayaran berhasil dihapus.');
        return redirect()->route('pembayaran.index');
    }
}
