<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Helpers\MessageHelper;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class PembayaranController extends Controller
{

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
