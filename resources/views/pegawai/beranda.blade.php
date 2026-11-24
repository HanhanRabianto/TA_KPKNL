@extends('layouts.app')
@section('judul', 'Beranda')

@section('isi')
    @include('partials.judul', [
        'judul' => 'Beranda',
        'sub'   => 'Pantau status pengajuan peminjaman Anda',
        'aksi'  => '<a class="btn" href="'.route('pegawai.peminjaman.create').'">+ Ajukan Peminjaman</a>',
    ])

    @include('partials.kartu', ['kartu' => [
        ['Menunggu persetujuan', $menunggu, '#a76400'],
        ['Disetujui', $disetujui, '#0b3d91'],
        ['Selesai bulan ini', $selesaiBulan, '#127a3d'],
    ]])

    <div class="card">
        <h3 style="margin-top:0">Pengajuan Saya</h3>
        <div class="tw">
            <table>
                <tr><th>Tanggal</th><th>Tujuan</th><th>Kendaraan</th><th>Status</th></tr>
                @forelse ($terbaru as $l)
                    @php
                        $st = $l->status_pengajuan;
                        $teks = ['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'][$st];
                        $kls = ['menunggu' => 'wr', 'disetujui' => 'ok', 'ditolak' => 'er'][$st];
                    @endphp
                    <tr>
                        <td>{{ $l->mulai->format('d/m/Y H:i') }}</td>
                        <td>{{ $l->tujuan }}</td>
                        <td>{{ $l->kendaraan?->nama ?? '-' }}</td>
                        <td><span class="b {{ $kls }}">{{ $teks }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="note" style="text-align:center; padding:24px">Belum ada pengajuan.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Informasi</h3>
        <p class="note" style="font-size:13px; margin:0">
            Pilih kendaraan dan pengemudi sendiri saat mengajukan, lalu Bagian Umum akan memverifikasi. Setelah disetujui,
            surat tugas tersedia di <strong>Riwayat Saya</strong>. Setelah perjalanan selesai, isi laporan pemakaian di halaman yang sama
            supaya pengembalian dapat diverifikasi.
        </p>
    </div>
@endsection
