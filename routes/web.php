<?php

use App\Http\Controllers\Admin\BbmController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PajakController;
use App\Http\Controllers\Admin\ServisController;
use App\Http\Controllers\Admin\VerifikasiPeminjamanController;
use App\Http\Controllers\Admin\VerifikasiPengembalianController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\Pegawai\BerandaController;
use App\Http\Controllers\Pegawai\PeminjamanController;
use App\Http\Controllers\Pegawai\PengembalianController;
use App\Http\Controllers\PengemudiController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SuratTugasController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'arah'])->middleware('auth')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'form'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


Route::middleware('auth')->group(function () {
   
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');
    Route::post('/profil', [ProfilController::class, 'simpan'])->name('profil.simpan');
    Route::post('/profil/sandi', [ProfilController::class, 'sandi'])->name('profil.sandi');

    
    Route::get('/surat-tugas/{peminjaman}', [SuratTugasController::class, 'show'])->whereNumber('peminjaman')->name('surat-tugas.show');
});

// ---------------- Admin ----------------
Route::middleware(['auth', 'peran:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    
    Route::get('/peminjaman', [VerifikasiPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{peminjaman}', [VerifikasiPeminjamanController::class, 'show'])->whereNumber('peminjaman')->name('peminjaman.show');
    Route::post('/peminjaman/{peminjaman}/setujui', [VerifikasiPeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
    Route::get('/peminjaman/{peminjaman}/tolak', [VerifikasiPeminjamanController::class, 'formTolak'])->name('peminjaman.tolak');
    Route::post('/peminjaman/{peminjaman}/tolak', [VerifikasiPeminjamanController::class, 'tolak']);

    
    Route::get('/pengembalian', [VerifikasiPengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/{pengembalian}', [VerifikasiPengembalianController::class, 'show'])->whereNumber('pengembalian')->name('pengembalian.show');
    Route::post('/pengembalian/{pengembalian}/terima', [VerifikasiPengembalianController::class, 'terima'])->name('pengembalian.terima');
    Route::post('/pengembalian/{pengembalian}/tolak', [VerifikasiPengembalianController::class, 'tolak'])->name('pengembalian.tolak');

    
    Route::get('/servis', [ServisController::class, 'index'])->name('servis.index');
    Route::get('/servis/catat', [ServisController::class, 'create'])->name('servis.create');
    Route::post('/servis', [ServisController::class, 'store'])->name('servis.store');
    Route::get('/servis/{servis}/selesai', [ServisController::class, 'formSelesai'])->name('servis.selesai');
    Route::post('/servis/{servis}/selesai', [ServisController::class, 'selesai']);

    
    Route::get('/pajak/{jenis}', [PajakController::class, 'index'])->whereIn('jenis', ['stnk', 'bpkb'])->name('pajak.index');
    Route::get('/pajak/{jenis}/{kendaraan}/bayar', [PajakController::class, 'formBayar'])->whereIn('jenis', ['stnk', 'bpkb'])->name('pajak.bayar');
    Route::post('/pajak/{jenis}/{kendaraan}/bayar', [PajakController::class, 'bayar'])->whereIn('jenis', ['stnk', 'bpkb'])->name('pajak.simpan');

    
    Route::get('/bbm', [BbmController::class, 'index'])->name('bbm.index');
    Route::get('/bbm/catat', [BbmController::class, 'create'])->name('bbm.create');
    Route::post('/bbm', [BbmController::class, 'store'])->name('bbm.store');
    Route::get('/bbm/{bbm}/struk', [BbmController::class, 'struk'])->name('bbm.struk');
    Route::delete('/bbm/{bbm}', [BbmController::class, 'destroy'])->name('bbm.destroy');

    Route::get('/laporan/{jenis}', [LaporanController::class, 'tampil'])
        ->whereIn('jenis', LaporanController::JENIS)->name('laporan.tampil');

    
    Route::resource('kendaraan', KendaraanController::class)->except('show');
    Route::resource('pengemudi', PengemudiController::class)->except('show');
    Route::post('/pengguna/{pengguna}/reset', [PenggunaController::class, 'reset'])->name('pengguna.reset');
    Route::resource('pengguna', PenggunaController::class)->except('show');
});

// ---------------- Pegawai ----------------
Route::middleware(['auth', 'peran:pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');

   
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/buat', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');

    
    Route::get('/peminjaman/{peminjaman}/pengembalian', [PengembalianController::class, 'create'])->name('pengembalian.create');
    Route::post('/peminjaman/{peminjaman}/pengembalian', [PengembalianController::class, 'store'])->name('pengembalian.store');
});
