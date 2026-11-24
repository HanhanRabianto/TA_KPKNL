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
    
    private const STATUS_MANUAL = ['Tersedia', 'Rusak'];

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $kendaraan = Kendaraan::query()
            ->when($q !== '', fn ($w) => $w->where(function ($s) use ($q) {
                $s->where('plat', 'like', "%{$q}%")->orWhere('jenis', 'like', "%{$q}%")->orWhere('tipe', 'like', "%{$q}%");
            }))
            ->when($status !== '', fn ($w) => $w->where('status', $status))
            ->orderBy('plat')
            ->get();

        return view('admin.kendaraan.index', [
            'kendaraan' => $kendaraan,
            'q'         => $q,
            'status'    => $status,
            'total'     => Kendaraan::count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.kendaraan.form', ['kendaraan' => new Kendaraan(['tipe' => 'Minibus', 'status' => 'Tersedia'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->aturan() + [
            'stnk_berlaku' => ['nullable', 'date'],
            'bpkb_berlaku' => ['nullable', 'date'],
            'status'       => ['required', Rule::in(self::STATUS_MANUAL)],
        ], $this->pesan());

        $data['plat'] = strtoupper(trim($data['plat']));

        Kendaraan::create($data);

        return redirect()->route('admin.kendaraan.index')->with('ok', 'Data kendaraan tersimpan.');
    }

    public function edit(Kendaraan $kendaraan): View
    {
        return view('admin.kendaraan.form', compact('kendaraan'));
    }

    public function update(Request $request, Kendaraan $kendaraan): RedirectResponse
    {
        
        $data = $request->validate($this->aturan($kendaraan->id), $this->pesan());
        $data['plat'] = strtoupper(trim($data['plat']));

        
        if (in_array($kendaraan->status, self::STATUS_MANUAL, true) && $request->filled('status')) {
            $request->validate(['status' => [Rule::in(self::STATUS_MANUAL)]], $this->pesan());
            $data['status'] = $request->input('status');
        }

        $kendaraan->update($data);

        return redirect()->route('admin.kendaraan.index')->with('ok', 'Data kendaraan tersimpan.');
    }

    public function destroy(Kendaraan $kendaraan): RedirectResponse
    {
        if ($kendaraan->status === 'Sedang dipakai') {
            return back()->with('err', 'Kendaraan sedang dipakai dalam peminjaman yang belum selesai.');
        }

        if ($kendaraan->status === 'Sedang diservis') {
            return back()->with('err', 'Kendaraan sedang diservis. Tandai servis selesai terlebih dahulu.');
        }

        if (DB::table('peminjaman')->where('kendaraan_id', $kendaraan->id)->exists()) {
            return back()->with('err', 'Kendaraan ini punya riwayat peminjaman sehingga tidak dapat dihapus.');
        }

        $kendaraan->delete();

        return redirect()->route('admin.kendaraan.index')->with('ok', 'Kendaraan dihapus.');
    }

    private function aturan(?int $abaikanId = null): array
    {
        return [
            'plat'      => ['required', 'string', 'max:15', Rule::unique('kendaraan', 'plat')->ignore($abaikanId)],
            'jenis'     => ['required', 'string', 'max:80'],
            'tipe'      => ['required', Rule::in(Kendaraan::TIPE)],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:60'],
            'tahun'     => ['required', 'integer', 'min:1980', 'max:'.(now()->year + 1)],
            'odometer'  => ['required', 'integer', 'min:0'],
        ];
    }

    private function pesan(): array
    {
        return [
            'plat.required'      => 'Plat nomor wajib diisi.',
            'plat.unique'        => 'Plat nomor sudah terdaftar.',
            'jenis.required'     => 'Merek/tipe wajib diisi.',
            'kapasitas.required' => 'Kapasitas wajib diisi.',
            'kapasitas.min'      => 'Kapasitas minimal 1 penumpang.',
            'tahun.required'     => 'Tahun wajib diisi.',
            'tahun.min'          => 'Tahun tidak valid.',
            'tahun.max'          => 'Tahun tidak valid.',
            'odometer.required'  => 'Odometer wajib diisi.',
            'odometer.integer'   => 'Odometer harus berupa angka bulat.',
            'odometer.min'       => 'Odometer tidak boleh negatif.',
            'status.in'          => 'Status tidak valid. Status Dipakai dan Servis diatur otomatis oleh sistem.',
        ];
    }
}
