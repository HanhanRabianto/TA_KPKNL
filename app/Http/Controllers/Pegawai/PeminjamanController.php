<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\Pengemudi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    private const FILTER_AJUAN = ['all', 'menunggu', 'disetujui', 'ditolak'];
    private const FILTER_KEMBALI = ['all', 'belum', 'verifikasi', 'selesai', 'ditolak'];

    // Riwayat Saya: pengajuan dan pengembalian dalam satu halaman
    public function index(Request $request): View
    {
        $fa = in_array($request->query('fa'), self::FILTER_AJUAN, true) ? $request->query('fa') : 'all';
        $fk = in_array($request->query('fk'), self::FILTER_KEMBALI, true) ? $request->query('fk') : 'all';

        $semua = Peminjaman::with(['kendaraan', 'pengemudi', 'pengembalian'])
            ->where('pengguna_id', $request->user()->id)
            ->orderByDesc('id')
            ->get();

        return view('pegawai.peminjaman.index', [
            'pengajuan'    => $semua->filter(fn ($l) => $fa === 'all' || $l->status_pengajuan === $fa)->values(),
            'pengembalian' => $semua->filter(fn ($l) => $l->status_pengajuan === 'disetujui' && $l->kendaraan_id
                && ($fk === 'all' || $l->status_pengembalian === $fk))->values(),
            'fa' => $fa,
            'fk' => $fk,
        ]);
    }

    public function create(Request $request): View
    {
        $uid = $request->user()->id;

        // "Ajukan ulang" dari pengajuan yang ditolak: isi form dengan data lama
        $isi = null;
        if ($request->filled('ulang')) {
            $isi = Peminjaman::where('pengguna_id', $uid)->where('status', 'ditolak')->find($request->query('ulang'));
        }

        return view('pegawai.peminjaman.form', [
            'penghalang' => Peminjaman::penghalang($uid),
            'isi'        => $isi,
            // seksi memilih sendiri kendaraan dan pengemudi (hanya yang tersedia)
            'kendaraan'  => Kendaraan::where('status', 'Tersedia')->orderBy('plat')->get(),
            'pengemudi'  => Pengemudi::where('status', 'Tersedia')->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kendaraan_id'  => ['required', 'exists:kendaraan,id'],
            'pengemudi_id'  => ['nullable', 'exists:pengemudi,id'],
            'lokasi_tujuan' => ['required', 'in:Dalam kota,Luar kota'],
            'menginap'      => ['required', 'in:0,1'],
            'tujuan'        => ['required', 'string', 'max:255'],
            'petugas'       => ['required', 'string', 'max:100'],
            'penumpang'     => ['required', 'integer', 'min:1', 'max:60'],
            'catatan'       => ['nullable', 'string', 'max:1000'],
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'     => ['required', 'date_format:H:i'],
        ], [
            'kendaraan_id.required'        => 'Pilih kendaraan yang tersedia.',
            'tujuan.required'              => 'Tujuan perjalanan wajib diisi.',
            'petugas.required'             => 'Nama pegawai yang bertugas wajib diisi.',
            'penumpang.required'           => 'Jumlah penumpang wajib diisi.',
            'penumpang.min'                => 'Jumlah penumpang minimal 1.',
            'tanggal_mulai.required'       => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'jam_mulai.required'           => 'Jam mulai wajib diisi.',
            'jam_mulai.date_format'        => 'Format jam mulai tidak valid.',
        ]);

        // tanggal dan jam digabung menjadi satu kolom waktu mulai
        $data['mulai'] = Carbon::parse($data['tanggal_mulai'].' '.$data['jam_mulai']);
        unset($data['tanggal_mulai'], $data['jam_mulai']);
        $data['pengemudi_id'] = $data['pengemudi_id'] ?? null;
        $data['menginap'] = (bool) $data['menginap'];

        $uid = $request->user()->id;

        // Semua aturan dicek di dalam transaksi supaya dua pengajuan bersamaan
        // tidak bisa melewati batas maksimal atau memilih kendaraan yang sama.
        $hasil = DB::transaction(function () use ($uid, $data) {
            User::whereKey($uid)->lockForUpdate()->first();

            if ($alasan = Peminjaman::alasanBlokir($uid)) {
                return $alasan;
            }

            $k = Kendaraan::whereKey($data['kendaraan_id'])->lockForUpdate()->first();
            if ($k->status !== 'Tersedia') {
                return 'Pilih kendaraan yang tersedia.';
            }
            if ($k->kapasitas < $data['penumpang']) {
                return 'Kapasitas kendaraan kurang dari jumlah penumpang.';
            }

            if (! empty($data['pengemudi_id'])) {
                $s = Pengemudi::whereKey($data['pengemudi_id'])->lockForUpdate()->first();
                if ($s->status !== 'Tersedia') {
                    return 'Pengemudi yang Anda pilih sedang bertugas. Silakan pilih pengemudi lain.';
                }
            }

            Peminjaman::create($data + ['pengguna_id' => $uid, 'status' => 'menunggu']);

            return null;
        });

        if ($hasil) {
            return redirect()->route('pegawai.peminjaman.create')->withInput()->with('err', $hasil);
        }

        return redirect()->route('pegawai.peminjaman.index')->with('ok', 'Pengajuan terkirim, menunggu persetujuan.');
    }
}
