<?php

use App\Http\Controllers\Admin\BbmController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PajakController;
use App\Http\Controllers\Admin\ServisController;
use App\Http\Controllers\Admin\VerifikasiPeminjamanController;
use App\Http\Controllers\Admin\VerifikasiPengembalianController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\Pegawai\PeminjamanController;
use App\Http\Controllers\Pegawai\PengembalianController;
use App\Http\Controllers\PengemudiController;
use App\Http\Controllers\SuratTugasController;
use App\Http\Controllers\PenggunaController;
use App\Models\Kendaraan;
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


Route::get('/surat-tugas/{peminjaman}', [SuratTugasController::class, 'show'])->middleware('auth')->whereNumber('peminjaman')->name('surat-tugas.show');

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
            'stnkTempo' => Kendaraan::jumlahJatuhTempo('stnk_berlaku'),
            'bpkbTempo' => Kendaraan::jumlahJatuhTempo('bpkb_berlaku'),
            
            'pajakTempo' => Kendaraan::orderBy('plat')->get()
                ->flatMap(fn ($k) => collect([['STNK', 'stnk_berlaku', 'stnk'], ['BPKB', 'bpkb_berlaku', 'bpkb']])
                    ->map(fn ($j) => ['k' => $k, 'jenis' => $j[0], 'tgl' => $k->{$j[1]}, 'sisa' => $k->sisaHari($j[1]), 'slug' => $j[2]]))
                ->filter(fn ($r) => $r['sisa'] !== null && $r['sisa'] <= 30)
                ->sortBy('sisa')
                ->values(),
        ]);
    })->name('dashboard');

   
    Route::get('/peminjaman', [VerifikasiPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{peminjaman}', [VerifikasiPeminjamanController::class, 'show'])->whereNumber('peminjaman')->name('peminjaman.show');
    Route::post('/peminjaman/{peminjaman}/setujui', [VerifikasiPeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
    Route::get('/peminjaman/{peminjaman}/tolak', [VerifikasiPeminjamanController::class, 'formTolak'])->name('peminjaman.tolak');
    Route::post('/peminjaman/{peminjaman}/tolak', [VerifikasiPeminjamanController::class, 'tolak']);

    
    Route::get('/pengembalian', [VerifikasiPengembalianController::class, 'index'])->name('pengembalian.index');
    Route::post('/pengembalian/{pengembalian}/terima', [VerifikasiPengembalianController::class, 'terima'])->name('pengembalian.terima');
    Route::get('/pengembalian/{pengembalian}/tolak', [VerifikasiPengembalianController::class, 'formTolak'])->name('pengembalian.tolak');
    Route::post('/pengembalian/{pengembalian}/tolak', [VerifikasiPengembalianController::class, 'tolak']);

    
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
