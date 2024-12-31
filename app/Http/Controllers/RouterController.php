<?php

namespace App\Http\Controllers;

use App\Models\Router;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RouterController extends Controller
{
    public function index()
    {
        $router = Router::all();
        return view('router.index', compact('router'));
    }

    public function checkConnection($id)
    {
        $router = Router::findOrFail($id); // Ambil data router berdasarkan ID
        $API = new RouterosAPI();
        $API->debug = false;

        try {
            // Cek koneksi menggunakan data router
            if ($API->connect($router->ip, $router->username, $router->password)) {
                $API->disconnect();

                // Tampilkan alert sukses
                Alert::success('Success', 'Router berhasil terhubung.');
                return redirect()->route('router.index');
            }

            // Jika gagal koneksi
            throw new \Exception('Gagal terhubung ke router.');
        } catch (\Exception $e) {
            // Tampilkan alert error
            Alert::error('Error', 'Koneksi gagal: ' . $e->getMessage());
            return redirect()->route('router.index');
        }
    }
    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ip' => 'required',
            'username' => 'required|string',
            'password' => 'required',
        ]);

        $API = new RouterosAPI();
        $API->debug = false;

        try {
            if ($API->connect($request->ip, $request->username, $request->password)) {
                Router::create($request->all());
                $API->disconnect();
                Alert::success('success', 'Router berhasil ditambahkan dan berhasil terhubung.');
                return redirect()->route('router.index');
               
            }

            throw new \Exception('Gagal terhubung ke RouterOS API.');
        } catch (\Exception $e) {
            Alert::error('success', 'Gagal terhubung ke RouterOS API silahkan cek kembali.');
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

 

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ip' => 'required', // Pastikan kolom IP juga divalidasi
            'username' => 'required|string',
            'password' => 'required',
        ]);

        $API = new RouterosAPI();
        $API->debug = false;

        try {
            // Temukan data router berdasarkan ID
            $router = Router::findOrFail($id);

            // Coba koneksi ke RouterOS API
            if ($API->connect($request->ip, $request->username, $request->password)) {
                // Jika berhasil, update data di database
                $router->update($request->all());
                $API->disconnect();
                Alert::success('success', 'Router berhasil diperbarui dan berhasil terhubung.');
                return redirect()->route('router.index');
            }

            throw new \Exception('Gagal terhubung ke RouterOS API.');
        } catch (\Exception $e) {
            Alert::error('success', 'Gagal terhubung ke RouterOS API.');
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        $router = Router::findOrFail($id);
        $API = new RouterosAPI();
        $API->debug = false;

        try {
            if ($API->connect($router->ip, $router->username, $router->password)) {
                $API->disconnect();
                $router->delete();
                Alert::success('success', 'Router berhasil di hapus dan disconnect.');
                return redirect()->route('router.index');
            }

            throw new \Exception('Gagal terhubung ke RouterOS API.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
