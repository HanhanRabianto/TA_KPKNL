<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PengembalianController extends Controller
{
    // Riwayat laporan pengembalian milik pengguna
    public function index(Request $request): View
    {
        $daftar = Pengembalian::with('peminjaman.kendaraan')
            ->whereHas('peminjaman', fn ($q) => $q->where('pengguna_id', $request->user()->id))
            ->orderByDesc('id')
            ->get();

        return view('pegawai.pengembalian.index', compact('daftar'));
    }

    // Form laporan (baru, atau diajukan kembali setelah ditolak)
    public function create(Request $request, Peminjaman $peminjaman): View|RedirectResponse
    {
        if ($tolak = $this->tolakAkses($request, $peminjaman)) {
            return $tolak;
        }

        return view('pegawai.pengembalian.form', [
            'p'          => $peminjaman->load('kendaraan'),
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
            'kembali_pada'    => ['required', 'date', 'after_or_equal:'.$peminjaman->mulai->format('Y-m-d H:i')],
            'kondisi'         => ['required', 'in:Baik,Perlu dicek,Rusak'],
            'catatan_kondisi' => ['nullable', 'string', 'max:1000', 'required_if:kondisi,Rusak'],
        ], [
            'odometer_akhir.required'      => 'Odometer akhir wajib diisi.',
            'odometer_akhir.integer'       => 'Odometer akhir harus berupa angka bulat.',
            'kembali_pada.required'        => 'Tanggal dan jam kembali wajib diisi.',
            'kembali_pada.after_or_equal'  => 'Waktu kembali tidak boleh sebelum waktu mulai peminjaman.',
            'catatan_kondisi.required_if'  => 'Catatan kerusakan wajib diisi jika kondisi kendaraan Rusak.',
        ]);

        // odometer awal selalu diambil dari data kendaraan, bukan dari isian pengguna
        $awal = (int) $peminjaman->kendaraan->odometer;
        if ($data['odometer_akhir'] < $awal) {
            return back()->withInput()->withErrors([
                'odometer_akhir' => "Odometer akhir tidak boleh lebih kecil dari odometer awal ($awal km).",
            ]);
        }

        if ($data['kondisi'] === 'Baik') {
            $data['catatan_kondisi'] = null;
        }

        DB::transaction(function () use ($peminjaman, $data, $awal) {
            Pengembalian::updateOrCreate(
                ['peminjaman_id' => $peminjaman->id],
                $data + [
                    'odometer_awal'   => $awal,
                    'status'          => 'menunggu',
                    'alasan_tolak'    => null,
                    'diajukan_pada'   => now(),
                    'diputuskan_pada' => null,
                ]
            );

            // tanggal selesai peminjaman berasal dari waktu kembali
            $peminjaman->update([
                'status'  => 'verifikasi',
                'selesai' => $data['kembali_pada'],
            ]);
        });

        return redirect()->route('pegawai.pengembalian.index')->with('ok', 'Laporan pengembalian terkirim dan menunggu verifikasi admin.');
    }

    // null = boleh; selain itu berisi redirect penolakan
    private function tolakAkses(Request $request, Peminjaman $peminjaman): ?RedirectResponse
    {
        abort_unless($peminjaman->pengguna_id === $request->user()->id, 403);

        if (! in_array($peminjaman->status, ['disetujui', 'kembali_ditolak'], true)) {
            return redirect()->route('pegawai.peminjaman.index', ['tab' => 'diterima'])
                ->with('err', 'Laporan pengembalian untuk peminjaman ini tidak dapat diisi saat ini.');
        }

        return null;
    }
}
