@extends('layouts.app')
@section('judul', 'Riwayat Saya')

@php
    $ajuan = ['all' => 'Semua', 'menunggu' => 'Menunggu persetujuan', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'];
    $kembali = ['all' => 'Semua', 'belum' => 'Belum dilaporkan', 'verifikasi' => 'Menunggu verifikasi', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'];
    $labelKembali = ['belum' => ['Belum dilaporkan', 'wr'], 'verifikasi' => ['Menunggu verifikasi', 'wr'], 'selesai' => ['Selesai', 'ok'], 'ditolak' => ['Ditolak', 'er']];
    $labelAjuan = ['menunggu' => ['Menunggu', 'wr'], 'disetujui' => ['Disetujui', 'ok'], 'ditolak' => ['Ditolak', 'er']];
@endphp

@section('isi')
    @include('partials.judul', [
        'judul' => 'Riwayat Saya',
        'sub'   => 'Pengajuan pinjaman dan pengembalian Anda, dari yang terbaru',
        'aksi'  => '<a class="btn" href="'.route('pegawai.peminjaman.create').'">+ Ajukan Peminjaman</a>',
    ])

    {{-- ===== Pengajuan peminjaman ===== --}}
    <div class="card">
        <div class="bar" style="margin-bottom:8px">
            <h3 style="margin:0">Pengajuan Peminjaman</h3>
            <button class="btn s no-print" type="button" onclick="window.print()">Cetak PDF</button>
        </div>
        <div class="chips no-print">
            @foreach ($ajuan as $k => $nama)
                <a class="btn {{ $fa === $k ? '' : 's' }}" href="{{ route('pegawai.peminjaman.index', ['fa' => $k, 'fk' => $fk]) }}">{{ $nama }}</a>
            @endforeach
        </div>
        <div class="tw">
            <table>
                <tr><th>Diajukan</th><th>Tujuan</th><th>Tanggal perjalanan</th><th>Kendaraan</th><th>Status pengajuan</th><th>Keterangan</th><th class="no-print">Aksi</th></tr>
                @forelse ($pengajuan as $l)
                    @php $st = $l->status_pengajuan; @endphp
                    <tr>
                        <td>{{ $l->diajukan_pada?->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $l->tujuan }}</strong><br>
                            <span class="note">{{ $l->lokasi_tujuan }}, {{ $l->menginap ? 'menginap' : 'tidak menginap' }}</span>
                        </td>
                        <td>{{ $l->mulai->format('d/m/Y H:i') }}</td>
                        <td>{{ $l->kendaraan?->nama ?? '-' }}<br><span class="note">{{ $l->pengemudi?->nama ?? 'Tanpa pengemudi' }}</span></td>
                        <td><span class="b {{ $labelAjuan[$st][1] }}">{{ $labelAjuan[$st][0] }}</span></td>
                        <td>
                            @if ($st === 'ditolak')
                                <span class="note" style="color:var(--er)">Alasan: {{ $l->alasan_tolak }}</span>
                            @elseif ($st === 'disetujui')
                                <span class="note">Surat tugas: {{ $l->nomor_surat }}</span>
                            @else
                                <span class="note">Menunggu persetujuan Bagian Umum</span>
                            @endif
                        </td>
                        <td class="no-print" style="white-space:nowrap">
                            @if ($st === 'disetujui')
                                <a class="btn s" href="{{ route('surat-tugas.show', $l) }}" target="_blank">Lihat Surat Tugas</a>
                            @elseif ($st === 'ditolak')
                                <a class="btn" href="{{ route('pegawai.peminjaman.create', ['ulang' => $l->id]) }}">Ajukan Ulang</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="note" style="text-align:center; padding:24px">Tidak ada data pada filter ini.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    {{-- ===== Pengembalian pinjaman ===== --}}
    <div class="card">
        <div class="bar" style="margin-bottom:8px">
            <h3 style="margin:0">Pengembalian Pinjaman</h3>
            <button class="btn s no-print" type="button" onclick="window.print()">Cetak PDF</button>
        </div>
        <div class="chips no-print">
            @foreach ($kembali as $k => $nama)
                <a class="btn {{ $fk === $k ? '' : 's' }}" href="{{ route('pegawai.peminjaman.index', ['fa' => $fa, 'fk' => $k]) }}">{{ $nama }}</a>
            @endforeach
        </div>
        <div class="tw">
            <table>
                <tr><th>Tanggal perjalanan</th><th>Tujuan</th><th>Kendaraan</th><th>Waktu kembali</th><th>Status pengembalian</th><th>Keterangan</th><th class="no-print">Aksi</th></tr>
                @forelse ($pengembalian as $l)
                    @php $sk = $l->status_pengembalian; @endphp
                    <tr>
                        <td>{{ $l->mulai->format('d/m/Y H:i') }}</td>
                        <td>{{ $l->tujuan }}</td>
                        <td>{{ $l->kendaraan->nama }}</td>
                        <td>{{ $sk === 'belum' ? '-' : $l->selesai?->format('d/m/Y H:i') }}</td>
                        <td><span class="b {{ $labelKembali[$sk][1] }}">{{ $labelKembali[$sk][0] }}</span></td>
                        <td>
                            @if ($sk === 'ditolak')
                                <span class="note" style="color:var(--er)">Alasan: {{ $l->pengembalian?->alasan_tolak }}</span>
                            @elseif ($sk === 'selesai')
                                <span class="note">Jarak tempuh {{ number_format($l->pengembalian?->jarak ?? 0, 0, ',', '.') }} km</span>
                            @elseif ($sk === 'verifikasi')
                                <span class="note">Menunggu verifikasi Bagian Umum</span>
                            @else
                                <span class="note">Isi laporan setelah kendaraan dikembalikan</span>
                            @endif
                        </td>
                        <td class="no-print" style="white-space:nowrap">
                            @if ($sk === 'belum')
                                <a class="btn" href="{{ route('pegawai.pengembalian.create', $l) }}">Isi Laporan Pemakaian</a>
                            @elseif ($sk === 'ditolak')
                                <a class="btn d" href="{{ route('pegawai.pengembalian.create', $l) }}">Perbaiki Laporan</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="note" style="text-align:center; padding:24px">Tidak ada data pada filter ini.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
