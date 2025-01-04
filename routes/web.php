<?php

use App\Models\TemplateNotifikasi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PPPoEController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RouterController;
use App\Http\Controllers\HotspotController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InterfaceController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\UseractiveController;
use App\Http\Controllers\TemplateNotifikasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/failed', function () {
    return view('failed');
});


// Auth Login & Logout
Route::get('login', [AuthController::class, 'index'])->name('auth.index');
Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Dashboard
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

// Check Traffic Interface
Route::get('interface', [InterfaceController::class, 'index'])->name('interface.index');

// Fitur Pelanggan
Route::get('pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
Route::get('pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
Route::post('pelanggan/add', [PelangganController::class, 'add'])->name('pelanggan.add');
Route::get('pelanggan/edit/{id}', [PelangganController::class, 'edit'])->name('pelanggan.edit');
Route::post('pelanggan/update', [PelangganController::class, 'update'])->name('pelanggan.update');
Route::get('pelanggan/delete/{id}', [PelangganController::class, 'delete'])->name('pelanggan.delete');

// Fitur Router
Route::get('router', [RouterController::class, 'index'])->name('router.index');
Route::post('router/add', [RouterController::class, 'add'])->name('router.add');
Route::post('router/update/{id}', [RouterController::class, 'update'])->name('router.update');
Route::get('router/delete/{id}', [RouterController::class, 'delete'])->name('router.delete');
Route::get('/router/check/{id}', [RouterController::class, 'checkConnection'])->name('router.check');
// get profile with router id
Route::get('/getProfiles/{routerId}', [PelangganController::class, 'getProfiles']);
// get secret with router id
Route::get('/getSecrets/{routerId}', [PelangganController::class, 'getSecrets']);
// get detail secret
Route::get('/get-secret-details/{secretName}', [PelangganController::class, 'getSecretDetails'])->name('get.secret.details');
// fitur isolir
Route::post('/isolir/{id}', [PelangganController::class, 'isolir'])->name('isolir');
Route::post('/bukaisolir/{id}', [PelangganController::class, 'bukaIsolir'])->name('bukaisolir');


// Fitur PPPoE
Route::get('pppoe/secret', [PPPoEController::class, 'secret'])->name('pppoe.secret');
Route::get('pppoe/secret/active', [PPPoEController::class, 'active'])->name('pppoe.active');
Route::post('pppoe/secret/add', [PPPoEController::class, 'add'])->name('pppoe.add');
Route::get('pppoe/secret/edit/{id}', [PPPoEController::class, 'edit'])->name('pppoe.edit');
Route::post('pppoe/secret/update', [PPPoEController::class, 'update'])->name('pppoe.update');
Route::get('pppoe/secret/delete/{id}', [PPPoEController::class, 'delete'])->name('pppoe.delete');

// fitur pembayaran
Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
Route::get('/pembayaran/create/{id}', [PembayaranController::class, 'create'])->name('pembayaran.create');
Route::post('/pembayaran/add', [PembayaranController::class, 'add'])->name('pembayaran.add');
Route::get('/pelanggan/lunas', [PembayaranController::class, 'showLunas'])->name('pelanggan.lunas');
Route::get('/pelanggan/belum-lunas', [PembayaranController::class, 'showBelumLunas'])->name('pelanggan.belum_lunas');
Route::get('/pembayaran/filter', [PembayaranController::class, 'filterPembayaran'])->name('pembayaran.filter');


// Fitur Notification
Route::get('notifikasi', [TemplateNotifikasiController::class, 'index'])->name('notifikasi.index');
Route::post('notifikasi/add', [TemplateNotifikasiController::class, 'add'])->name('notifikasi.add');
Route::post('notifikasi/update/{id}', [TemplateNotifikasiController::class, 'update'])->name('notifikasi.update');
Route::get('notifikasi/delete/{id}', [TemplateNotifikasiController::class, 'delete'])->name('notifikasi.delete');

// Fitur Hotspot
Route::get('hotspot/users', [HotspotController::class, 'users'])->name('hotspot.users');
Route::get('hotspot/users/active', [HotspotController::class, 'active'])->name('hotspot.active');
Route::post('hotspot/users/add', [HotspotController::class, 'add'])->name('hotspot.add');
Route::get('hotspot/users/edit/{id}', [HotspotController::class, 'edit'])->name('hotspot.edit');
Route::post('hotspot/users/update', [HotspotController::class, 'update'])->name('hotspot.update');
Route::get('hotspot/users/delete/{id}', [HotspotController::class, 'delete'])->name('hotspot.delete');


// Fitur Package
Route::get('package', [PackageController::class, 'index'])->name('package.index');
Route::post('package/add', [PackageController::class, 'add'])->name('package.add');
Route::post('package/update/{id}', [PackageController::class, 'update'])->name('package.update');
Route::get('package/delete/{id}', [PackageController::class, 'delete'])->name('package.delete');

// Realtime
Route::get('dashboard/cpu', [DashboardController::class, 'cpu'])->name('dashboard.cpu');
Route::get('dashboard/load', [DashboardController::class, 'load'])->name('dashboard.load');
Route::get('dashboard/uptime', [DashboardController::class, 'uptime'])->name('dashboard.uptime');
Route::get('dashboard/{traffic}', [DashboardController::class, 'traffic'])->name('dashboard.traffic');

// Report Traffic UP & Search
Route::get('report-up', [ReportController::class, 'index'])->name('report-up.index');
Route::get('report-up/load', [ReportController::class, 'load'])->name('report-up.load');
Route::get('report-up/search', [ReportController::class, 'search'])->name('search.report');

// User Active Mikrotik
Route::get('useractive', [UseractiveController::class, 'index'])->name('user.index');
Route::get('realtime/useractive', [UseractiveController::class, 'useractive'])->name('realtime.useractive');

// Store Data Up & Down
Route::get('/up', [ReportController::class, 'up']);
Route::get('/down', [ReportController::class, 'down']);
