<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\PembayaranPajak;
use App\Models\Peminjaman;
use App\Models\Servis;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public const JENIS = ['servis', 'stnk', 'bpkb', 'pemakaian-kendaraan', 'pemakaian-seksi'];

    public function tampil(Request $request, string $jenis): View
    {
        abort_unless(in_array($jenis, self::JENIS, true), 404);

        $bulan = preg_match('/^\d{4}-\d{2}$/', (string) $request->query('bulan')) ? $request->query('bulan') : null;
        $kid   = $request->filled('kendaraan_id') ? (int) $request->query('kendaraan_id') : null;

        $laporan = match ($jenis) {
            'servis'              => $this->servis($bulan, $kid),
            'stnk'                => $this->pajak('STNK', $bulan, $kid),
            'bpkb'                => $this->pajak('BPKB', $bulan, $kid),
            'pemakaian-kendaraan' => $this->pemakaianKendaraan($bulan, $kid),
            'pemakaian-seksi'     => $this->pemakaianSeksi($bulan, $kid),
        };

        $laporan['periode'] = $bulan ? Carbon::createFromFormat('!Y-m', $bulan)->locale('id')->translatedFormat('F Y') : 'Semua periode';

        return view('admin.laporan.tampil', [
            'laporan'   => $laporan,
            'jenis'     => $jenis,
            'bulan'     => $bulan,
            'kid'       => $kid,
            'kendaraan' => Kendaraan::orderBy('plat')->get(),
        ]);
    }

    private function filterBulan($query, string $kolom, ?string $bulan): void
    {
        if ($bulan) {
            [$y, $m] = explode('-', $bulan);
            $query->whereYear($kolom, $y)->whereMonth($kolom, $m);
        }
    }

    private function rp($n): string
    {
        return 'Rp '.number_format((float) $n, 0, ',', '.');
    }

    private function servis(?string $bulan, ?int $kid): array
    {
        $q = Servis::with('kendaraan')->orderByDesc('tgl_masuk')->orderByDesc('id');
        $this->filterBulan($q, 'tgl_masuk', $bulan);
        $kid && $q->where('kendaraan_id', $kid);
        $data = $q->get();

        $biaya = fn ($s) => $s->status === 'Selesai' ? $s->biaya_akhir : $s->biaya;

        return [
            'judul' => 'Laporan Servis',
            'sub'   => 'Rekap servis kendaraan dinas',
            'kartu' => ['Jumlah servis' => $data->count(), 'Total biaya' => $this->rp($data->sum($biaya))],
            'kolom' => ['Tanggal masuk', 'Kendaraan', 'Jenis servis', 'Bengkel', 'Biaya', 'Status'],
            'baris' => $data->map(fn ($s) => [
                $s->tgl_masuk->format('d/m/Y'), $s->kendaraan->plat.' - '.$s->kendaraan->jenis, $s->jenis,
                $s->bengkel ?: '-', $this->rp($biaya($s)), $s->status,
            ])->all(),
        ];
    }

    private function pajak(string $jenis, ?string $bulan, ?int $kid): array
    {
        $q = PembayaranPajak::with('kendaraan')->where('jenis', $jenis)->orderByDesc('tgl_bayar')->orderByDesc('id');
        $this->filterBulan($q, 'tgl_bayar', $bulan);
        $kid && $q->where('kendaraan_id', $kid);
        $data = $q->get();

        return [
            'judul' => "Laporan Pembayaran $jenis",
            'sub'   => "Rekap pembayaran pajak $jenis kendaraan dinas",
            'kartu' => ['Jumlah pembayaran' => $data->count(), 'Total nominal' => $this->rp($data->sum('nominal'))],
            'kolom' => ['Tanggal bayar', 'Kendaraan', 'Nominal', 'Berlaku sampai', 'Keterangan'],
            'baris' => $data->map(fn ($p) => [
                $p->tgl_bayar->format('d/m/Y'), $p->kendaraan->plat.' - '.$p->kendaraan->jenis,
                $this->rp($p->nominal), $p->berlaku_sampai->format('d/m/Y'), $p->keterangan ?: '-',
            ])->all(),
        ];
    }

    
    private function selesai(?string $bulan, ?int $kid)
    {
        $q = Peminjaman::with(['pengguna', 'kendaraan', 'pengembalian'])->where('status', 'selesai')->orderByDesc('mulai');
        $this->filterBulan($q, 'mulai', $bulan);
        $kid && $q->where('kendaraan_id', $kid);

        return $q->get();
    }

    private function pemakaianKendaraan(?string $bulan, ?int $kid): array
    {
        $data = $this->selesai($bulan, $kid)->sortBy(fn ($p) => $p->kendaraan->plat)->values();

        return [
            'judul' => 'Laporan Pemakaian Kendaraan',
            'sub'   => 'Rekap pemakaian kendaraan dinas',
            'kartu' => ['Jumlah pemakaian' => $data->count(), 'Total jarak' => number_format($data->sum(fn ($p) => $p->pengembalian->jarak), 0, ',', '.').' km'],
            'kolom' => ['Kendaraan', 'Tanggal mulai', 'Tanggal selesai', 'Seksi', 'Petugas', 'Tujuan', 'Jarak'],
            'baris' => $data->map(fn ($p) => [
                $p->kendaraan->plat.' - '.$p->kendaraan->jenis, $p->mulai->format('d/m/Y H:i'), $p->selesai?->format('d/m/Y H:i') ?? '-',
                $p->pengguna->nama_seksi, $p->petugas, $p->tujuan, number_format($p->pengembalian->jarak, 0, ',', '.').' km',
            ])->all(),
        ];
    }

    private function pemakaianSeksi(?string $bulan, ?int $kid): array
    {
        $data = $this->selesai($bulan, $kid);

        return [
            'judul' => 'Laporan Pemakaian Seksi',
            'sub'   => 'Pengembalian yang sudah disetujui admin',
            'kartu' => ['Jumlah pemakaian' => $data->count()],
            'kolom' => ['Tanggal mulai', 'Tanggal selesai', 'Seksi', 'Petugas', 'Tujuan', 'Kendaraan', 'Odometer awal', 'Odometer akhir'],
            'baris' => $data->map(fn ($p) => [
                $p->mulai->format('d/m/Y H:i'), $p->selesai?->format('d/m/Y H:i') ?? '-', $p->pengguna->nama_seksi,
                $p->petugas, $p->tujuan, $p->kendaraan->plat.' - '.$p->kendaraan->jenis,
                number_format($p->pengembalian->odometer_awal, 0, ',', '.'), number_format($p->pengembalian->odometer_akhir, 0, ',', '.'),
            ])->all(),
        ];
    }
}
