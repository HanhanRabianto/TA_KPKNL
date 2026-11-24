<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatatanBbm;
use App\Models\Kendaraan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BbmController extends Controller
{
    public function index(Request $request): View
    {
        $bulan = preg_match('/^\d{4}-\d{2}$/', (string) $request->query('bulan')) ? $request->query('bulan') : null;
        $kid   = $request->filled('kendaraan_id') ? (int) $request->query('kendaraan_id') : null;

        $daftar = CatatanBbm::with('kendaraan')
            ->when($bulan, function ($q) use ($bulan) {
                [$y, $m] = explode('-', $bulan);
                $q->whereYear('tgl_pengembalian', $y)->whereMonth('tgl_pengembalian', $m);
            })
            ->when($kid, fn ($q) => $q->where('kendaraan_id', $kid))
            ->orderByDesc('tgl_pengembalian')
            ->orderByDesc('id')
            ->get();

        return view('admin.bbm.index', [
            'daftar'    => $daftar,
            'bulan'     => $bulan,
            'kid'       => $kid,
            'kendaraan' => Kendaraan::orderBy('plat')->get(),
            'periode'   => $bulan ? Carbon::createFromFormat('!Y-m', $bulan)->locale('id')->translatedFormat('F Y') : 'Semua periode',
        ]);
    }

    public function create(): View
    {
        return view('admin.bbm.create', ['kendaraan' => Kendaraan::orderBy('plat')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kendaraan_id'     => ['required', 'exists:kendaraan,id'],
            'tgl_pemberian'    => ['required', 'date'],
            'tgl_pengembalian' => ['required', 'date', 'after_or_equal:tgl_pemberian'],
            'petugas'          => ['required', 'string', 'max:100'],
            'jumlah_kupon'     => ['required', 'integer', 'min:1', 'max:1000'],
            'total'            => ['required', 'numeric', 'min:0'],
            'foto_struk'       => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
        ], [
            'kendaraan_id.required'            => 'Pilih mobil.',
            'tgl_pemberian.required'           => 'Tanggal pemberian kupon wajib diisi.',
            'tgl_pengembalian.required'        => 'Tanggal pengembalian kupon wajib diisi.',
            'tgl_pengembalian.after_or_equal'  => 'Tanggal pengembalian kupon tidak boleh sebelum tanggal pemberian.',
            'petugas.required'                 => 'Nama petugas wajib diisi.',
            'jumlah_kupon.required'            => 'Jumlah kupon wajib diisi.',
            'jumlah_kupon.min'                 => 'Jumlah kupon minimal 1.',
            'total.required'                   => 'Total wajib diisi.',
            'foto_struk.required'              => 'Foto struk SPBU wajib dilampirkan.',
            'foto_struk.image'                 => 'Berkas harus berupa gambar.',
            'foto_struk.mimes'                 => 'Foto struk harus berformat JPG atau PNG.',
            'foto_struk.max'                   => 'Ukuran foto struk maksimal 4 MB.',
        ]);

        
        $data['foto_struk'] = $request->file('foto_struk')->store('struk-bbm');

        CatatanBbm::create($data);

        return redirect()->route('admin.bbm.index')->with('ok', 'Catatan BBM disimpan.');
    }

    public function struk(CatatanBbm $bbm): StreamedResponse
    {
        abort_unless(Storage::exists($bbm->foto_struk), 404);

        return Storage::response($bbm->foto_struk);
    }

    public function destroy(CatatanBbm $bbm): RedirectResponse
    {
        Storage::delete($bbm->foto_struk);
        $bbm->delete();

        return redirect()->route('admin.bbm.index')->with('ok', 'Catatan BBM dihapus.');
    }
}
