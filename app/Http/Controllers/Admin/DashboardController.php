<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Pengemudi;
use App\Models\Servis;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $status = Kendaraan::selectRaw('status, count(*) as n')->groupBy('status')->pluck('n', 'status');

        $perBulan = Peminjaman::whereYear('mulai', now()->year)
            ->selectRaw('MONTH(mulai) as bulan, count(*) as n')
            ->groupBy('bulan')
            ->pluck('n', 'bulan');

        $statistik = [];
        foreach (['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'] as $i => $nama) {
            $statistik[] = ['nama' => $nama, 'n' => (int) ($perBulan[$i + 1] ?? 0)];
        }

        $pajakTempo = Kendaraan::orderBy('plat')->get()
            ->flatMap(fn ($k) => collect([['STNK', 'stnk_berlaku', 'stnk'], ['BPKB', 'bpkb_berlaku', 'bpkb']])
                ->map(fn ($j) => ['k' => $k, 'jenis' => $j[0], 'tgl' => $k->{$j[1]}, 'sisa' => $k->sisaHari($j[1]), 'slug' => $j[2]]))
            ->filter(fn ($r) => $r['sisa'] !== null && $r['sisa'] <= 30)
            ->sortBy('sisa')
            ->values();

        return view('admin.dashboard', [
            'totalKendaraan' => (int) $status->sum(),
            'tersedia' => (int) ($status['Tersedia'] ?? 0),
            'tidakTersedia' => (int) ($status['Tidak tersedia'] ?? 0),
            'dipakai' => (int) ($status['Sedang dipakai'] ?? 0),
            'diservis' => (int) ($status['Sedang diservis'] ?? 0),
            'stnkTempo' => Kendaraan::jumlahJatuhTempo('stnk_berlaku'),
            'bpkbTempo' => Kendaraan::jumlahJatuhTempo('bpkb_berlaku'),
            'totalPengemudi' => Pengemudi::count(),
            'pengemudiSedia' => Pengemudi::where('status', 'Tersedia')->count(),
            'totalSeksi' => User::where('peran', 'pegawai')->count(),
            'menunggu' => Peminjaman::with(['pengguna', 'kendaraan', 'pengemudi'])->where('status', 'menunggu')->orderBy('id')->get(),
            'dipinjam' => Peminjaman::with(['pengguna', 'kendaraan', 'pengemudi'])->whereIn('status', Peminjaman::STATUS_DIPINJAM)->orderBy('mulai')->get(),
            'verifikasi' => Pengembalian::with(['peminjaman.pengguna', 'peminjaman.kendaraan'])->where('status', 'menunggu')->orderBy('id')->get(),
            'servisBerjalan' => Servis::with('kendaraan')->where('status', 'Berjalan')->orderBy('tgl_masuk')->get(),
            'pajakTempo' => $pajakTempo,
            'statistik' => $statistik,
            'tahun' => now()->year,
        ]);
    }

    public static function durasi(Carbon $dari): string
    {
        $m = max(0, (int) round($dari->diffInMinutes(now(), false)));
        $hari = intdiv($m, 1440);
        $jam = intdiv($m % 1440, 60);
        $menit = $m % 60;

        if ($hari) {
            return $hari.' hari'.($jam ? ' '.$jam.' jam' : '');
        }

        return $jam ? $jam.' jam'.($menit ? ' '.$menit.' menit' : '') : $menit.' menit';
    }
}
