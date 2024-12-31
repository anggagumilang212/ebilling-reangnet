<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }


    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'ip' => 'required',
    //         'user' => 'required'
    //     ]);

    //     $ip = $request->post('ip');
    //     $user = $request->post('user');
    //     $password = $request->post('password');

    //     $data = [
    //         'ip' => $ip,
    //         'user' => $user,
    //         'password' => $password,
    //     ];

    //     $request->session()->put($data);

    //     return redirect('dashboard');
    // }


    // public function logout(Request $request)
    // {
    //     $request->session()->forget('ip');
    //     $request->session()->forget('user');
    //     $request->session()->forget('password');

    //     return redirect('login');
    // }



    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required',
            ],
            [
                'email.required' => 'email harus diisi',
                'password.required' => 'Password harus diisi',
            ]
        );

        $credentials = $request->only('email', 'password');

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->remember)) {

            $user = Auth::user();
            // if ($user->hasRole('admin')) {
            //     return redirect()->route('list-peserta');
            // } else {
            //     return redirect()->route('formulir-pendaftaran')->with('status', 'login_success');
            // }

            Alert::success('Success', 'Berhasil Login.');
            return redirect()->route('dashboard.index');
        }

        Alert::error('Error', 'Gagal Login silahkan cek kembali.');
        return redirect()->back();
    }


    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }
}
