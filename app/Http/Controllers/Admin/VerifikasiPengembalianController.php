<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VerifikasiPengembalianController extends Controller
{
    public function index(): View
    {
        $dengan = ['peminjaman.pengguna', 'peminjaman.kendaraan'];

        return view('admin.pengembalian.index', [
            'menunggu'  => Pengembalian::with($dengan)->where('status', 'menunggu')->orderBy('id')->get(),
            'diterima'  => Pengembalian::with($dengan)->where('status', 'diterima')->orderByDesc('id')->get(),
            'ditolak'   => Pengembalian::with($dengan)->where('status', 'ditolak')->orderByDesc('id')->get(),
        ]);
    }

    
    public function show(Pengembalian $pengembalian): View
    {
        return view('admin.pengembalian.show', ['r' => $pengembalian->load('peminjaman.pengguna', 'peminjaman.kendaraan')]);
    }

    public function terima(Pengembalian $pengembalian): RedirectResponse
    {
        $pesan = DB::transaction(function () use ($pengembalian) {
            $r = Pengembalian::whereKey($pengembalian->id)->lockForUpdate()->first();
            if ($r->status !== 'menunggu') {
                return 'Laporan ini sudah diproses.';
            }

            $p = $r->peminjaman()->with(['kendaraan', 'pengemudi'])->first();

            $r->update(['status' => 'diterima', 'diputuskan_pada' => now()]);
            $p->update(['status' => 'selesai']);

            
            $p->kendaraan?->update(['status' => 'Tersedia', 'odometer' => $r->odometer_akhir]);
            $p->pengemudi?->update(['status' => 'Tersedia']);

            return null;
        });

        return $pesan
            ? back()->with('err', $pesan)
            : redirect()->route('admin.pengembalian.index')->with('ok', 'Pengembalian disetujui. Data masuk ke Laporan Pemakaian.');
    }

    public function tolak(Request $request, Pengembalian $pengembalian): RedirectResponse
    {
        $data = $request->validate([
            'alasan_tolak' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan_tolak.required' => 'Isi alasan penolakan.',
            'alasan_tolak.min'      => 'Alasan penolakan terlalu singkat (minimal 5 karakter).',
        ]);

        $ok = DB::transaction(function () use ($pengembalian, $data) {
            $r = Pengembalian::whereKey($pengembalian->id)->lockForUpdate()->first();
            if ($r->status !== 'menunggu') {
                return false;
            }

            $r->update(['status' => 'ditolak', 'alasan_tolak' => $data['alasan_tolak'], 'diputuskan_pada' => now()]);
            
            $r->peminjaman()->update(['status' => 'kembali_ditolak']);

            return true;
        });

        return redirect()->route('admin.pengembalian.index')
            ->with($ok ? 'ok' : 'err', $ok ? 'Pengembalian ditolak.' : 'Laporan ini sudah diproses.');
    }
}
