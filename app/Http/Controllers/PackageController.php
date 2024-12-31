<?php

namespace App\Http\Controllers;

use App\Models\Router;
use App\Models\Package;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PackageController extends Controller
{
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

        $packages = Package::all();
        return view('package.index', $data, compact('packages'));
    }

    public function add(Request $request)
    {

        Package::create(
            [
                'nama' => $request->nama,
                'harga' => str_replace('.', '', $request->harga),
                'kecepatan' => $request->kecepatan,
                'router_id' => $request->router_id,
                'profile' => $request->profile
            ]
        );
        Alert::success('Success', 'Berhasil menambahkan Paket.');
        return redirect()->route('package.index');
    }

    public function update(Request $request, $id)
    {
        $package = Package::find($id);
        $package->update(
            [
                'nama' => $request->nama,
                'harga' => str_replace('.', '', $request->harga),
                'kecepatan' => $request->kecepatan,
                'router_id' => $request->router_id,
                'profile' => $request->profile
            ]
        );
        Alert::success('Success', 'Berhasil mengubah Paket.');
        return redirect()->route('package.index');
    }

    public function delete($id)
    {
        $package = Package::find($id);
        $package->delete();
        Alert::success('Success', 'Berhasil menghapus Paket.');
        return redirect()->route('package.index');
    }
}
