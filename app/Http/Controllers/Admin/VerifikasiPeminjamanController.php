<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\Pengemudi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VerifikasiPeminjamanController extends Controller
{
    private const TAB = [
        'menunggu' => ['menunggu'],
        'diterima' => ['disetujui', 'verifikasi', 'kembali_ditolak', 'selesai'],
        'ditolak'  => ['ditolak'],
    ];

    public function index(Request $request): View
    {
        $tab = array_key_exists($request->query('tab'), self::TAB) ? $request->query('tab') : 'menunggu';

        $daftar = Peminjaman::with(['pengguna', 'kendaraan', 'pengemudi'])
            ->whereIn('status', self::TAB[$tab])
            ->orderByDesc('id')
            ->get();

        $per = Peminjaman::selectRaw('status, count(*) as n')->groupBy('status')->pluck('n', 'status');
        $jumlah = [];
        foreach (self::TAB as $nama => $statuses) {
            $jumlah[$nama] = collect($statuses)->sum(fn ($s) => (int) ($per[$s] ?? 0));
        }

        return view('admin.peminjaman.index', compact('daftar', 'tab', 'jumlah'));
    }

    public function formSetujui(Peminjaman $peminjaman): View|RedirectResponse
    {
        if ($peminjaman->status !== 'menunggu') {
            return redirect()->route('admin.peminjaman.index')->with('err', 'Pengajuan ini sudah diproses.');
        }

        return view('admin.peminjaman.setujui', [
            'p'          => $peminjaman->load('pengguna'),
            'kendaraan'  => Kendaraan::where('status', 'Tersedia')->orderBy('plat')->get(),
            'pengemudi'  => Pengemudi::where('status', 'Tersedia')->orderBy('nama')->get(),
            'nomorSaran' => sprintf('ST-%03d/KPKNL-BDG/%s', $peminjaman->id, now()->format('Y')),
        ]);
    }

    public function setujui(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $data = $request->validate([
            'kendaraan_id' => ['required', 'exists:kendaraan,id'],
            'pengemudi_id' => ['nullable', 'exists:pengemudi,id'],
            'nomor_surat'  => ['required', 'string', 'max:60'],
        ], [
            'kendaraan_id.required' => 'Pilih kendaraan yang akan ditugaskan.',
            'nomor_surat.required'  => 'Nomor surat tugas wajib diisi.',
        ]);

        $pesan = DB::transaction(function () use ($peminjaman, $data) {
            $p = Peminjaman::whereKey($peminjaman->id)->lockForUpdate()->first();
            if ($p->status !== 'menunggu') {
                return 'Pengajuan ini sudah diproses.';
            }

            $k = Kendaraan::whereKey($data['kendaraan_id'])->lockForUpdate()->first();
            if ($k->status !== 'Tersedia') {
                return 'Kendaraan yang dipilih sudah tidak tersedia. Pilih kendaraan lain.';
            }

            $sopir = null;
            if (! empty($data['pengemudi_id'])) {
                $sopir = Pengemudi::whereKey($data['pengemudi_id'])->lockForUpdate()->first();
                if ($sopir->status !== 'Tersedia') {
                    return 'Pengemudi yang dipilih sudah bertugas. Pilih pengemudi lain.';
                }
            }

            $p->update([
                'status'          => 'disetujui',
                'kendaraan_id'    => $k->id,
                'pengemudi_id'    => $sopir?->id,
                'nomor_surat'     => $data['nomor_surat'],
                'diputuskan_pada' => now(),
            ]);

            // status kendaraan dan pengemudi berubah otomatis
            $k->update(['status' => 'Sedang dipakai']);
            $sopir?->update(['status' => 'Bertugas']);

            return null;
        });

        if ($pesan) {
            return back()->withInput()->with('err', $pesan);
        }

        return redirect()->route('admin.peminjaman.index')->with('ok', 'Pengajuan disetujui. Kendaraan berstatus Sedang dipakai.');
    }

    public function formTolak(Peminjaman $peminjaman): View|RedirectResponse
    {
        if ($peminjaman->status !== 'menunggu') {
            return redirect()->route('admin.peminjaman.index')->with('err', 'Pengajuan ini sudah diproses.');
        }

        return view('admin.peminjaman.tolak', ['p' => $peminjaman->load('pengguna')]);
    }

    public function tolak(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $data = $request->validate([
            'alasan_tolak' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan_tolak.required' => 'Alasan penolakan wajib diisi.',
            'alasan_tolak.min'      => 'Alasan penolakan terlalu singkat (minimal 5 karakter).',
        ]);

        $ok = DB::transaction(function () use ($peminjaman, $data) {
            $p = Peminjaman::whereKey($peminjaman->id)->lockForUpdate()->first();
            if ($p->status !== 'menunggu') {
                return false;
            }
            $p->update(['status' => 'ditolak', 'alasan_tolak' => $data['alasan_tolak'], 'diputuskan_pada' => now()]);

            return true;
        });

        return redirect()->route('admin.peminjaman.index')
            ->with($ok ? 'ok' : 'err', $ok ? 'Pengajuan ditolak.' : 'Pengajuan ini sudah diproses.');
    }
}
