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
    public function index(): View
    {
        $dengan = ['pengguna', 'kendaraan', 'pengemudi'];

        return view('admin.peminjaman.index', [
            'menunggu'  => Peminjaman::with($dengan)->where('status', 'menunggu')->orderBy('id')->get(),
            'disetujui' => Peminjaman::with($dengan)->whereIn('status', ['disetujui', 'verifikasi', 'kembali_ditolak', 'selesai'])->orderByDesc('id')->get(),
            'ditolak'   => Peminjaman::with($dengan)->where('status', 'ditolak')->orderByDesc('id')->get(),
        ]);
    }

    
    public function show(Peminjaman $peminjaman): View
    {
        return view('admin.peminjaman.show', ['p' => $peminjaman->load(['pengguna', 'kendaraan', 'pengemudi'])]);
    }

    public function setujui(Peminjaman $peminjaman): RedirectResponse
    {
        $pesan = DB::transaction(function () use ($peminjaman) {
            $p = Peminjaman::whereKey($peminjaman->id)->lockForUpdate()->first();
            if ($p->status !== 'menunggu') {
                return 'Pengajuan ini sudah diproses.';
            }

            
            $k = Kendaraan::whereKey($p->kendaraan_id)->lockForUpdate()->first();
            if (! $k || $k->status !== 'Tersedia') {
                return 'Kendaraan pilihan seksi sudah tidak tersedia. Tolak pengajuan ini dengan alasan, lalu seksi dapat mengajukan ulang.';
            }

            $sopir = null;
            if ($p->pengemudi_id) {
                $sopir = Pengemudi::whereKey($p->pengemudi_id)->lockForUpdate()->first();
                if (! $sopir || $sopir->status !== 'Tersedia') {
                    return 'Pengemudi pilihan seksi sedang bertugas. Tolak pengajuan ini dengan alasan, lalu seksi dapat mengajukan ulang.';
                }
            }

            
            $p->update([
                'status'          => 'disetujui',
                'nomor_surat'     => sprintf('ST-%03d/KPKNL-Bandung/%s', $p->id, $p->mulai->format('Y')),
                'diputuskan_pada' => now(),
            ]);

            
            $k->update(['status' => 'Sedang dipakai']);
            $sopir?->update(['status' => 'Bertugas']);

            return null;
        });

        if ($pesan) {
            return back()->with('err', $pesan);
        }

        return redirect()->route('admin.peminjaman.index')->with('ok', 'Pengajuan disetujui. Surat tugas terbit dan kendaraan berstatus Sedang dipakai.');
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
