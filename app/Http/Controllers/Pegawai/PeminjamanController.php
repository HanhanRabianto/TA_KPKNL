<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\Pengemudi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    private const TAB = [
        'menunggu' => ['menunggu'],
        'diterima' => ['disetujui', 'verifikasi', 'kembali_ditolak', 'selesai'],
        'ditolak'  => ['ditolak'],
    ];

    // Riwayat pengajuan milik pengguna yang sedang masuk
    public function index(Request $request): View
    {
        $tab = array_key_exists($request->query('tab'), self::TAB) ? $request->query('tab') : 'menunggu';
        $uid = $request->user()->id;

        $daftar = Peminjaman::with(['kendaraan', 'pengemudi'])
            ->where('pengguna_id', $uid)
            ->whereIn('status', self::TAB[$tab])
            ->orderByDesc('id')
            ->get();

        $per = Peminjaman::where('pengguna_id', $uid)->selectRaw('status, count(*) as n')->groupBy('status')->pluck('n', 'status');
        $jumlah = [];
        foreach (self::TAB as $nama => $statuses) {
            $jumlah[$nama] = collect($statuses)->sum(fn ($s) => (int) ($per[$s] ?? 0));
        }

        return view('pegawai.peminjaman.index', compact('daftar', 'tab', 'jumlah'));
    }

    public function create(Request $request): View
    {
        $uid = $request->user()->id;
        $blokir = Peminjaman::alasanBlokir($uid);

        // "Ajukan lagi" dari pengajuan yang ditolak: isi form dengan data lama
        $isi = null;
        if ($request->filled('ulang')) {
            $isi = Peminjaman::where('pengguna_id', $uid)->where('status', 'ditolak')->find($request->query('ulang'));
        }

        return view('pegawai.peminjaman.form', [
            'blokir' => $blokir,
            'isi'    => $isi,
            'aktif'  => Peminjaman::jumlahAktif($uid),
            // seksi memilih sendiri kendaraan dan pengemudi (hanya yang tersedia)
            'kendaraan' => Kendaraan::where('status', 'Tersedia')->orderBy('plat')->get(),
            'pengemudi' => Pengemudi::where('status', 'Tersedia')->orderBy('nama')->get(),
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
            'penumpang'     => ['required', 'integer', 'min:1', 'max:20'],
            'catatan'       => ['nullable', 'string', 'max:1000'],
            'mulai'         => ['required', 'date', 'after_or_equal:today'],
        ], [
            'kendaraan_id.required' => 'Pilih kendaraan yang akan dipinjam.',
            'tujuan.required'       => 'Tujuan perjalanan wajib diisi.',
            'petugas.required'      => 'Nama petugas wajib diisi.',
            'penumpang.required'    => 'Jumlah penumpang wajib diisi.',
            'penumpang.min'         => 'Jumlah penumpang minimal 1.',
            'penumpang.max'         => 'Jumlah penumpang maksimal 20.',
            'mulai.required'        => 'Tanggal dan jam mulai wajib diisi.',
            'mulai.after_or_equal'  => 'Waktu mulai tidak boleh sebelum hari ini.',
        ]);

        $uid = $request->user()->id;

        $data['pengemudi_id'] = $data['pengemudi_id'] ?? null;

        // Cek aturan di dalam transaksi supaya dua pengajuan bersamaan
        // tidak bisa melewati batas maksimal.
        $hasil = DB::transaction(function () use ($uid, $data) {
            User::whereKey($uid)->lockForUpdate()->first();

            if ($alasan = Peminjaman::alasanBlokir($uid)) {
                return $alasan;
            }

            // pastikan pilihan masih tersedia saat dikirim
            $k = \App\Models\Kendaraan::whereKey($data['kendaraan_id'])->first();
            if ($k->status !== 'Tersedia') {
                return 'Kendaraan yang Anda pilih sudah tidak tersedia. Silakan pilih kendaraan lain.';
            }
            if (! empty($data['pengemudi_id'])) {
                $s = \App\Models\Pengemudi::whereKey($data['pengemudi_id'])->first();
                if ($s->status !== 'Tersedia') {
                    return 'Pengemudi yang Anda pilih sedang bertugas. Silakan pilih pengemudi lain.';
                }
            }

            Peminjaman::create($data + [
                'pengguna_id' => $uid,
                'status'      => 'menunggu',
            ]);

            return null;
        });

        if ($hasil) {
            return redirect()->route('pegawai.peminjaman.create')->withInput()->with('err', $hasil);
        }

        return redirect()->route('pegawai.peminjaman.index')->with('ok', 'Pengajuan peminjaman terkirim dan menunggu verifikasi admin.');
    }
}
