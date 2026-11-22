<?php

namespace App\Http\Controllers;

use App\Models\Pengemudi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PengemudiController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $pengemudi = Pengemudi::query()
            ->when($q !== '', fn ($w) => $w->where(function ($s) use ($q) {
                $s->where('nama', 'like', "%{$q}%")->orWhere('kontak', 'like', "%{$q}%");
            }))
            ->orderBy('nama')
            ->get();

        return view('admin.pengemudi.index', compact('pengemudi', 'q'));
    }

    public function create(): View
    {
        return view('admin.pengemudi.form', ['pengemudi' => new Pengemudi()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validasi($request);
        $data['status'] = 'Tersedia';

        Pengemudi::create($data);

        return redirect()->route('admin.pengemudi.index')->with('ok', 'Pengemudi berhasil ditambahkan.');
    }

    public function edit(Pengemudi $pengemudi): View
    {
        return view('admin.pengemudi.form', compact('pengemudi'));
    }

    public function update(Request $request, Pengemudi $pengemudi): RedirectResponse
    {
        
        $pengemudi->update($this->validasi($request));

        return redirect()->route('admin.pengemudi.index')->with('ok', 'Data pengemudi diperbarui.');
    }

    public function destroy(Pengemudi $pengemudi): RedirectResponse
    {
        if ($pengemudi->status === 'Bertugas') {
            return back()->with('err', 'Pengemudi yang sedang bertugas tidak dapat dihapus.');
        }

        if (DB::table('peminjaman')->where('pengemudi_id', $pengemudi->id)->exists()) {
            return back()->with('err', 'Pengemudi ini punya riwayat penugasan sehingga tidak dapat dihapus.');
        }

        $pengemudi->delete();

        return redirect()->route('admin.pengemudi.index')->with('ok', 'Pengemudi dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'nama'   => ['required', 'string', 'max:100'],
            'kontak' => ['nullable', 'string', 'max:30'],
        ], [
            'nama.required' => 'Nama pengemudi wajib diisi.',
            'nama.max'      => 'Nama terlalu panjang (maksimal 100 karakter).',
            'kontak.max'    => 'Kontak terlalu panjang (maksimal 30 karakter).',
        ]);
    }
}
