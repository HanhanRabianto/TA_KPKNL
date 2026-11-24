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
    private const TABS = ['menunggu', 'diterima', 'ditolak'];

    public function index(Request $request): View
    {
        $tab = in_array($request->query('tab'), self::TABS, true) ? $request->query('tab') : 'menunggu';

        $daftar = Pengembalian::with(['peminjaman.pengguna', 'peminjaman.kendaraan', 'peminjaman.pengemudi'])
            ->where('status', $tab)
            ->orderByDesc('id')
            ->get();

        $jumlah = Pengembalian::selectRaw('status, count(*) as n')->groupBy('status')->pluck('n', 'status');

        return view('admin.pengembalian.index', compact('daftar', 'tab', 'jumlah'));
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
            : redirect()->route('admin.pengembalian.index')->with('ok', 'Pengembalian diterima. Kendaraan kembali berstatus Tersedia dan odometer diperbarui.');
    }

    public function formTolak(Pengembalian $pengembalian): View|RedirectResponse
    {
        if ($pengembalian->status !== 'menunggu') {
            return redirect()->route('admin.pengembalian.index')->with('err', 'Laporan ini sudah diproses.');
        }

        return view('admin.pengembalian.tolak', ['r' => $pengembalian->load('peminjaman.pengguna', 'peminjaman.kendaraan')]);
    }

    public function tolak(Request $request, Pengembalian $pengembalian): RedirectResponse
    {
        $data = $request->validate([
            'alasan_tolak' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan_tolak.required' => 'Alasan penolakan wajib diisi.',
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
            ->with($ok ? 'ok' : 'err', $ok ? 'Laporan pengembalian ditolak.' : 'Laporan ini sudah diproses.');
    }
}
