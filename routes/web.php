<?php

use App\Http\Controllers\Admin\VerifikasiPeminjamanController;
use App\Http\Controllers\Admin\VerifikasiPengembalianController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\Pegawai\PeminjamanController;
use App\Http\Controllers\Pegawai\PengembalianController;
use App\Http\Controllers\PengemudiController;
use App\Http\Controllers\PenggunaController;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'arah'])->middleware('auth')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'form'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------------- Admin ----------------
Route::middleware(['auth', 'peran:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'totalKendaraan'   => DB::table('kendaraan')->count(),
            'tersedia'         => DB::table('kendaraan')->where('status', 'Tersedia')->count(),
            'dipakai'          => DB::table('kendaraan')->where('status', 'Sedang dipakai')->count(),
            'diservis'         => DB::table('kendaraan')->where('status', 'Sedang diservis')->count(),
            'totalPengemudi'   => DB::table('pengemudi')->count(),
            'pengemudiSedia'   => DB::table('pengemudi')->where('status', 'Tersedia')->count(),
            'totalSeksi'       => DB::table('pengguna')->where('peran', 'pegawai')->count(),
            'pengajuanMenunggu' => Peminjaman::where('status', 'menunggu')->count(),
            'pengembalianMenunggu' => Pengembalian::where('status', 'menunggu')->count(),
        ]);
    })->name('dashboard');

    
    Route::get('/peminjaman', [VerifikasiPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{peminjaman}/setujui', [VerifikasiPeminjamanController::class, 'formSetujui'])->name('peminjaman.setujui');
    Route::post('/peminjaman/{peminjaman}/setujui', [VerifikasiPeminjamanController::class, 'setujui']);
    Route::get('/peminjaman/{peminjaman}/tolak', [VerifikasiPeminjamanController::class, 'formTolak'])->name('peminjaman.tolak');
    Route::post('/peminjaman/{peminjaman}/tolak', [VerifikasiPeminjamanController::class, 'tolak']);

    
    Route::get('/pengembalian', [VerifikasiPengembalianController::class, 'index'])->name('pengembalian.index');
    Route::post('/pengembalian/{pengembalian}/terima', [VerifikasiPengembalianController::class, 'terima'])->name('pengembalian.terima');
    Route::get('/pengembalian/{pengembalian}/tolak', [VerifikasiPengembalianController::class, 'formTolak'])->name('pengembalian.tolak');
    Route::post('/pengembalian/{pengembalian}/tolak', [VerifikasiPengembalianController::class, 'tolak']);

    
    Route::resource('kendaraan', KendaraanController::class)->except('show');
    Route::resource('pengemudi', PengemudiController::class)->except('show');
    Route::resource('pengguna', PenggunaController::class)->except('show');
});

// ---------------- Pegawai ----------------
Route::middleware(['auth', 'peran:pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/beranda', function () {
        $uid = auth()->id();

        return view('pegawai.beranda', [
            'aktif'   => Peminjaman::jumlahAktif($uid),
            'maks'    => Peminjaman::MAKS_AKTIF,
            'blokir'  => Peminjaman::alasanBlokir($uid),
            'menunggu' => Peminjaman::where('pengguna_id', $uid)->where('status', 'menunggu')->count(),
            'selesai' => Peminjaman::where('pengguna_id', $uid)->where('status', 'selesai')->count(),
        ]);
    })->name('beranda');

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/buat', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');

    
    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/peminjaman/{peminjaman}/pengembalian', [PengembalianController::class, 'create'])->name('pengembalian.create');
    Route::post('/peminjaman/{peminjaman}/pengembalian', [PengembalianController::class, 'store'])->name('pengembalian.store');
});
