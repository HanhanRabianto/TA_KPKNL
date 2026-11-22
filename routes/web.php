<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PengemudiController;
use App\Http\Controllers\PenggunaController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'arah'])->middleware('auth')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'form'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Admin
Route::middleware(['auth', 'peran:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'totalKendaraan' => DB::table('kendaraan')->count(),
            'tersedia'       => DB::table('kendaraan')->where('status', 'Tersedia')->count(),
            'dipakai'        => DB::table('kendaraan')->where('status', 'Sedang dipakai')->count(),
            'diservis'       => DB::table('kendaraan')->where('status', 'Sedang diservis')->count(),
            'totalPengemudi' => DB::table('pengemudi')->count(),
            'pengemudiSedia' => DB::table('pengemudi')->where('status', 'Tersedia')->count(),
            'totalSeksi'     => DB::table('pengguna')->where('peran', 'pegawai')->count(),
        ]);
    })->name('dashboard');

    
    Route::resource('kendaraan', KendaraanController::class)->except('show');
    Route::resource('pengemudi', PengemudiController::class)->except('show');
    Route::resource('pengguna', PenggunaController::class)->except('show');
});

// Pegawai
Route::middleware(['auth', 'peran:pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/beranda', fn () => view('pegawai.beranda'))->name('beranda');
});
