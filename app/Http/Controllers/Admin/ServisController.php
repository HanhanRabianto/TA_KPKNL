<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Servis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ServisController extends Controller
{
    public function index(): View
    {
        return view('admin.servis.index', [
            'daftar'   => Servis::with('kendaraan')->orderByRaw("status = 'Berjalan' desc")->orderByDesc('tgl_masuk')->orderByDesc('id')->get(),
            'jumlahKendaraan' => Kendaraan::count(),
            'berjalan' => Servis::where('status', 'Berjalan')->count(),
            'selesai'  => Servis::where('status', 'Selesai')->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.servis.create', [
            'kendaraan' => Kendaraan::where('status', 'Tersedia')->orderBy('plat')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kendaraan_id'     => ['required', 'exists:kendaraan,id'],
            'tgl_masuk'        => ['required', 'date'],
            'jenis'            => ['required', 'string', 'max:150'],
            'bengkel'          => ['nullable', 'string', 'max:100'],
            'odometer'         => ['nullable', 'integer', 'min:0'],
            'biaya'            => ['nullable', 'numeric', 'min:0'],
            'estimasi_selesai' => ['nullable', 'date', 'after_or_equal:tgl_masuk'],
            'catatan'          => ['nullable', 'string', 'max:1000'],
        ], [
            'kendaraan_id.required'          => 'Pilih kendaraan yang akan diservis.',
            'tgl_masuk.required'             => 'Tanggal masuk servis wajib diisi.',
            'jenis.required'                 => 'Jenis servis wajib diisi.',
            'estimasi_selesai.after_or_equal' => 'Estimasi selesai tidak boleh sebelum tanggal masuk.',
        ]);

        $data['biaya'] = $data['biaya'] ?? 0;

        $pesan = DB::transaction(function () use ($data) {
            $k = Kendaraan::whereKey($data['kendaraan_id'])->lockForUpdate()->first();
            if ($k->status !== 'Tersedia') {
                return 'Kendaraan hanya bisa diservis saat berstatus Tersedia. Status sekarang: '.$k->status.'.';
            }

            Servis::create($data + ['status' => 'Berjalan']);
            
            $k->update(['status' => 'Sedang diservis']);

            return null;
        });

        if ($pesan) {
            return back()->withInput()->with('err', $pesan);
        }

        return redirect()->route('admin.servis.index')->with('ok', 'Servis dicatat. Kendaraan berstatus Sedang diservis.');
    }

    public function formSelesai(Servis $servis): View|RedirectResponse
    {
        if ($servis->status !== 'Berjalan') {
            return redirect()->route('admin.servis.index')->with('err', 'Servis ini sudah selesai.');
        }

        return view('admin.servis.selesai', ['s' => $servis->load('kendaraan')]);
    }

    public function selesai(Request $request, Servis $servis): RedirectResponse
    {
        $data = $request->validate([
            'tgl_selesai'     => ['required', 'date', 'after_or_equal:'.$servis->tgl_masuk->format('Y-m-d')],
            'biaya_akhir'     => ['required', 'numeric', 'min:0'],
            'odometer_keluar' => ['nullable', 'integer', 'min:0'],
            'kondisi_setelah' => ['required', 'in:Baik,Perlu dicek'],
            'catatan_selesai' => ['nullable', 'string', 'max:1000'],
        ], [
            'tgl_selesai.required'       => 'Tanggal selesai wajib diisi.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal masuk.',
            'biaya_akhir.required'       => 'Biaya akhir wajib diisi.',
        ]);

        $pesan = DB::transaction(function () use ($servis, $data) {
            $s = Servis::whereKey($servis->id)->lockForUpdate()->first();
            if ($s->status !== 'Berjalan') {
                return 'Servis ini sudah selesai.';
            }

            $s->update($data + ['status' => 'Selesai']);

            $k = $s->kendaraan;
            $ubah = ['status' => 'Tersedia'];
            
            if (isset($data['odometer_keluar']) && $data['odometer_keluar'] > $k->odometer) {
                $ubah['odometer'] = $data['odometer_keluar'];
            }
            $k->update($ubah);

            return null;
        });

        return $pesan
            ? back()->with('err', $pesan)
            : redirect()->route('admin.servis.index')->with('ok', 'Servis selesai. Kendaraan kembali berstatus Tersedia.');
    }
}
