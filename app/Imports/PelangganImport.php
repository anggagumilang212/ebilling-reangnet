<?php

namespace App\Imports;

use Exception;
use App\Models\Router;
use App\Models\Package;
use App\Models\Pelanggan;
use App\Models\RouterosAPI;
use Illuminate\Support\Collection;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
class PelangganImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {

// dd($rows);
        foreach ($rows as $row) {
            try {
                // Konversi tanggal dari Excel
                $tglDaftar = is_numeric($row['tgl_daftar'])
                    ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_daftar']))
                    : Carbon::parse($row['tgl_daftar']);

                // Simpan ke database
                Pelanggan::create([
                    'nama' => $row['nama'],
                    'jenis' => $row['jenis'],
                    'tgl_daftar' => $tglDaftar->format('Y-m-d'),
                    'tanggal_jatuh_tempo' => $row['tanggal_jatuh_tempo'],
                    'alamat' => $row['alamat'],
                    'no_hp' => $row['no_hp'],
                    'titik_koordinat' => $row['titik_koordinat'],
                ]);

            } catch (Exception $e) {
                // Log error untuk setiap baris jika ada kesalahan
                Alert::error('Error', $e->getMessage());
            }
        }
    }

}
