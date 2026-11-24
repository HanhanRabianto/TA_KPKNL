<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\PembayaranPajak;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PajakController extends Controller
{
    
    private const JENIS = [
        'stnk' => ['STNK', 'stnk_berlaku', 1],
        'bpkb' => ['BPKB', 'bpkb_berlaku', 5],
    ];

    private function jenis(string $slug): array
    {
        abort_unless(isset(self::JENIS[$slug]), 404);

        return self::JENIS[$slug];
    }

    public function index(string $jenis): View
    {
        [$nama, $kolom] = $this->jenis($jenis);

        $semua = Kendaraan::orderBy('plat')->get();

        
        $perlu = $semua
            ->filter(fn ($k) => $k->{$kolom} === null || $k->sisaHari($kolom) <= 30)
            ->sortBy(fn ($k) => $k->sisaHari($kolom) ?? PHP_INT_MAX)
            ->values();

        return view('admin.pajak.index', [
            'slug'      => $jenis,
            'nama'      => $nama,
            'kolom'     => $kolom,
            'perlu'     => $perlu,
            'terlambat' => $semua->filter(fn ($k) => $k->{$kolom} !== null && $k->sisaHari($kolom) < 0)->count(),
            'segera'    => $semua->filter(fn ($k) => $k->{$kolom} !== null && $k->sisaHari($kolom) >= 0 && $k->sisaHari($kolom) <= 30)->count(),
            'tahunIni'  => PembayaranPajak::where('jenis', $nama)->whereYear('tgl_bayar', now()->year)->count(),
            'riwayat'   => PembayaranPajak::with('kendaraan')->where('jenis', $nama)->orderByDesc('tgl_bayar')->orderByDesc('id')->get(),
        ]);
    }

    public function formBayar(string $jenis, Kendaraan $kendaraan): View
    {
        [$nama, $kolom, $tahun] = $this->jenis($jenis);

        $berlaku = $kendaraan->{$kolom};
        $dasar   = ($berlaku && $berlaku->isFuture()) ? $berlaku->copy() : now();

        return view('admin.pajak.bayar', [
            'slug'      => $jenis,
            'nama'      => $nama,
            'kolom'     => $kolom,
            'k'         => $kendaraan,
            'saran'     => $dasar->addYears($tahun)->format('Y-m-d'),
        ]);
    }

    public function bayar(Request $request, string $jenis, Kendaraan $kendaraan): RedirectResponse
    {
        [$nama, $kolom] = $this->jenis($jenis);

        $data = $request->validate([
            'tgl_bayar'      => ['required', 'date'],
            'nominal'        => ['required', 'numeric', 'min:0'],
            'berlaku_sampai' => ['required', 'date', 'after_or_equal:tgl_bayar'],
            'keterangan'     => ['nullable', 'string', 'max:255'],
        ], [
            'tgl_bayar.required'            => 'Tanggal bayar wajib diisi.',
            'nominal.required'              => 'Nominal wajib diisi.',
            'berlaku_sampai.required'       => 'Tanggal berlaku baru wajib diisi.',
            'berlaku_sampai.after_or_equal' => 'Tanggal berlaku tidak boleh sebelum tanggal bayar.',
        ]);

        $lama = $kendaraan->{$kolom};
        if ($lama && strtotime($data['berlaku_sampai']) <= $lama->getTimestamp()) {
            return back()->withInput()->withErrors([
                'berlaku_sampai' => 'Tanggal berlaku baru harus setelah tanggal berlaku saat ini ('.$lama->format('d/m/Y').').',
            ]);
        }

        DB::transaction(function () use ($kendaraan, $nama, $kolom, $data) {
            PembayaranPajak::create($data + ['kendaraan_id' => $kendaraan->id, 'jenis' => $nama]);
            
            $kendaraan->update([$kolom => $data['berlaku_sampai']]);
        });

        return redirect()->route('admin.pajak.index', $jenis)->with('ok', "Pembayaran $nama {$kendaraan->plat} dicatat. Berlaku sampai ".date('d/m/Y', strtotime($data['berlaku_sampai'])).'.');
    }
}
