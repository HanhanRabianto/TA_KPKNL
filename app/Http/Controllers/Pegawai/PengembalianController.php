<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PengembalianController extends Controller
{
    // Form "Lapor Pemakaian" (baru) atau "Perbaiki Laporan" (setelah ditolak)
    public function create(Request $request, Peminjaman $peminjaman): View|RedirectResponse
    {
        if ($tolak = $this->tolakAkses($request, $peminjaman)) {
            return $tolak;
        }

        return view('pegawai.pengembalian.form', [
            'p'            => $peminjaman->load('kendaraan'),
            'pengembalian' => $peminjaman->pengembalian,
        ]);
    }

    public function store(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($tolak = $this->tolakAkses($request, $peminjaman)) {
            return $tolak;
        }

        $data = $request->validate([
            'odometer_akhir'  => ['required', 'integer', 'min:0'],
            'tanggal_kembali' => ['required', 'date'],
            'jam_kembali'     => ['required', 'date_format:H:i'],
            'kondisi'         => ['required', 'in:Baik,Perlu dicek,Rusak'],
            'catatan_kondisi' => ['nullable', 'string', 'max:1000', 'required_if:kondisi,Rusak'],
        ], [
            'odometer_akhir.required'      => 'Odometer akhir wajib diisi.',
            'odometer_akhir.integer'       => 'Odometer akhir harus berupa angka bulat.',
            'tanggal_kembali.required'     => 'Tanggal dan jam kembali wajib diisi.',
            'jam_kembali.required'         => 'Tanggal dan jam kembali wajib diisi.',
            'jam_kembali.date_format'      => 'Format jam kembali tidak valid.',
            'catatan_kondisi.required_if'  => 'Isi catatan kerusakan kendaraan.',
        ]);

        $kembali = Carbon::parse($data['tanggal_kembali'].' '.$data['jam_kembali']);
        if ($kembali->lt($peminjaman->mulai)) {
            return back()->withInput()->withErrors(['tanggal_kembali' => 'Waktu kembali tidak boleh sebelum waktu mulai peminjaman.']);
        }

        // odometer awal selalu diambil dari data kendaraan, bukan dari isian pengguna
        $awal = (int) $peminjaman->kendaraan->odometer;
        if ($data['odometer_akhir'] < $awal) {
            return back()->withInput()->withErrors(['odometer_akhir' => 'Odometer akhir tidak boleh lebih kecil dari odometer awal.']);
        }

        DB::transaction(function () use ($peminjaman, $data, $kembali, $awal) {
            Pengembalian::updateOrCreate(
                ['peminjaman_id' => $peminjaman->id],
                [
                    'odometer_awal'   => $awal,
                    'odometer_akhir'  => $data['odometer_akhir'],
                    'kembali_pada'    => $kembali,
                    'kondisi'         => $data['kondisi'],
                    'catatan_kondisi' => $data['kondisi'] === 'Baik' ? null : $data['catatan_kondisi'],
                    'status'          => 'menunggu',
                    'alasan_tolak'    => null,
                    'diajukan_pada'   => now(),
                    'diputuskan_pada' => null,
                ]
            );

            // tanggal selesai peminjaman berasal dari waktu kembali
            $peminjaman->update(['status' => 'verifikasi', 'selesai' => $kembali]);
        });

        return redirect()->route('pegawai.peminjaman.index')->with('ok', 'Laporan terkirim, menunggu verifikasi.');
    }

    // null = boleh; selain itu berisi redirect penolakan
    private function tolakAkses(Request $request, Peminjaman $peminjaman): ?RedirectResponse
    {
        abort_unless($peminjaman->pengguna_id === $request->user()->id, 403);

        if (! in_array($peminjaman->status, ['disetujui', 'kembali_ditolak'], true)) {
            return redirect()->route('pegawai.peminjaman.index')
                ->with('err', 'Laporan pengembalian untuk peminjaman ini tidak dapat diisi saat ini.');
        }

        return null;
    }
}
