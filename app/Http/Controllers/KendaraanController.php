<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KendaraanController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $kendaraan = Kendaraan::query()
            ->when($q !== '', fn ($w) => $w->where(function ($s) use ($q) {
                $s->where('plat', 'like', "%{$q}%")->orWhere('jenis', 'like', "%{$q}%");
            }))
            ->orderBy('plat')
            ->get();

        return view('admin.kendaraan.index', compact('kendaraan', 'q'));
    }

    public function create(): View
    {
        return view('admin.kendaraan.form', ['kendaraan' => new Kendaraan()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'plat'         => ['required', 'string', 'max:15', 'unique:kendaraan,plat'],
            'jenis'        => ['required', 'string', 'max:80'],
            'odometer'     => ['required', 'integer', 'min:0'],
            'stnk_berlaku' => ['nullable', 'date'],
            'bpkb_berlaku' => ['nullable', 'date'],
        ], $this->pesan());

        $data['plat']   = strtoupper(trim($data['plat']));
        $data['status'] = 'Tersedia';

        Kendaraan::create($data);

        return redirect()->route('admin.kendaraan.index')->with('ok', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Kendaraan $kendaraan): View
    {
        return view('admin.kendaraan.form', compact('kendaraan'));
    }

    public function update(Request $request, Kendaraan $kendaraan): RedirectResponse
    {
 
        $data = $request->validate([
            'plat'     => ['required', 'string', 'max:15', Rule::unique('kendaraan', 'plat')->ignore($kendaraan->id)],
            'jenis'    => ['required', 'string', 'max:80'],
            'odometer' => ['required', 'integer', 'min:0'],
        ], $this->pesan());

        $data['plat'] = strtoupper(trim($data['plat']));

        $kendaraan->update($data);

        return redirect()->route('admin.kendaraan.index')->with('ok', 'Data kendaraan diperbarui.');
    }

    public function destroy(Kendaraan $kendaraan): RedirectResponse
    {
        if ($kendaraan->status !== 'Tersedia') {
            return back()->with('err', 'Kendaraan yang sedang dipakai atau diservis tidak dapat dihapus.');
        }

        if (DB::table('peminjaman')->where('kendaraan_id', $kendaraan->id)->exists()) {
            return back()->with('err', 'Kendaraan ini punya riwayat peminjaman sehingga tidak dapat dihapus.');
        }

        $kendaraan->delete();

        return redirect()->route('admin.kendaraan.index')->with('ok', 'Kendaraan dihapus.');
    }

    private function pesan(): array
    {
        return [
            'plat.required'    => 'Nomor polisi wajib diisi.',
            'plat.unique'      => 'Nomor polisi sudah terdaftar.',
            'jenis.required'   => 'Jenis kendaraan wajib diisi.',
            'odometer.required' => 'Odometer wajib diisi.',
            'odometer.integer' => 'Odometer harus berupa angka bulat.',
            'odometer.min'     => 'Odometer tidak boleh negatif.',
            'stnk_berlaku.date' => 'Tanggal berlaku STNK tidak valid.',
            'bpkb_berlaku.date' => 'Tanggal berlaku BPKB tidak valid.',
        ];
    }
}
