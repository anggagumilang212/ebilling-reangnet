<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemplateNotifikasi;
use RealRashid\SweetAlert\Facades\Alert;

class TemplateNotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = TemplateNotifikasi::all();
        return view('notifikasi.index', compact('notifikasi'));
    }

    public function add(Request $request)
    {
        TemplateNotifikasi::create(
            [
                'kategori' => $request->kategori,
                'template_pesan' => $request->template_pesan
            ]
        );
        Alert::success('Success', 'Berhasil menambahkan Template Notifikasi.');
        return redirect()->route('notifikasi.index');
    }

    public function update(Request $request, $id)
    {
        $notifikasi = TemplateNotifikasi::find($id);
        $notifikasi->update(
            [
                'kategori' => $request->kategori,
                'template_pesan' => $request->template_pesan
            ]
        );
        Alert::success('Success', 'Berhasil mengubah Template Notifikasi.');
        return redirect()->route('notifikasi.index');
    }

    public function delete($id)
    {
        $notifikasi = TemplateNotifikasi::find($id);
        $notifikasi->delete();
        Alert::success('Success', 'Berhasil menghapus Template Notifikasi.');
        return redirect()->route('notifikasi.index');
    }
}
