<?php

namespace App\Http\Controllers;

use App\Models\Pengemudi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PengemudiController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $pengemudi = Pengemudi::query()
            ->when($q !== '', fn ($w) => $w->where(function ($s) use ($q) {
                $s->where('nama', 'like', "%{$q}%")->orWhere('kontak', 'like', "%{$q}%");
            }))
            ->when($status !== '', fn ($w) => $w->where('status', $status))
            ->orderBy('nama')
            ->get();

        
        $perjalanan = DB::table('peminjaman')
            ->whereNotNull('pengemudi_id')
            ->whereIn('status', ['disetujui', 'verifikasi', 'kembali_ditolak', 'selesai'])
            ->whereYear('mulai', now()->year)->whereMonth('mulai', now()->month)
            ->selectRaw('pengemudi_id, count(*) as n')->groupBy('pengemudi_id')
            ->pluck('n', 'pengemudi_id');

        return view('admin.pengemudi.index', [
            'pengemudi'  => $pengemudi,
            'perjalanan' => $perjalanan,
            'q'          => $q,
            'status'     => $status,
            'total'      => Pengemudi::count(),
            'tersedia'   => Pengemudi::where('status', 'Tersedia')->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.pengemudi.form', ['pengemudi' => new Pengemudi(['jenis_sim' => 'A', 'status' => 'Tersedia'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validasi($request);
        $data['status'] = $request->input('status') === 'Cuti' ? 'Cuti' : 'Tersedia';

        Pengemudi::create($data);

        return redirect()->route('admin.pengemudi.index')->with('ok', 'Data pengemudi tersimpan.');
    }

    public function edit(Pengemudi $pengemudi): View
    {
        return view('admin.pengemudi.form', compact('pengemudi'));
    }

    public function update(Request $request, Pengemudi $pengemudi): RedirectResponse
    {
        $data = $this->validasi($request);

        
        if ($pengemudi->status !== 'Bertugas') {
            $data['status'] = $request->input('status') === 'Cuti' ? 'Cuti' : 'Tersedia';
        }

        $pengemudi->update($data);

        return redirect()->route('admin.pengemudi.index')->with('ok', 'Data pengemudi tersimpan.');
    }

    public function destroy(Pengemudi $pengemudi): RedirectResponse
    {
        if ($pengemudi->status === 'Bertugas') {
            return back()->with('err', 'Pengemudi sedang bertugas dalam peminjaman yang belum selesai.');
        }

        if (DB::table('peminjaman')->where('pengemudi_id', $pengemudi->id)->exists()) {
            return back()->with('err', 'Pengemudi ini punya riwayat penugasan sehingga tidak dapat dihapus.');
        }

        $pengemudi->delete();

        return redirect()->route('admin.pengemudi.index')->with('ok', 'Pengemudi dihapus.');
    }

    private function validasi(Request $request): array
    {
        $data = $request->validate([
            'nama'        => ['required', 'string', 'max:100'],
            'kontak'      => ['nullable', 'string', 'max:30'],
            'no_sim'      => ['nullable', 'string', 'max:30'],
            'jenis_sim'   => ['required', Rule::in(Pengemudi::JENIS_SIM)],
            'sim_berlaku' => ['nullable', 'date'],
        ], [
            'nama.required' => 'Nama pengemudi wajib diisi.',
            'nama.max'      => 'Nama terlalu panjang (maksimal 100 karakter).',
        ]);

        return $data;
    }
}
