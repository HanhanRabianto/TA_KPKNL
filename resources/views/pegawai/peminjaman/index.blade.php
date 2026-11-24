@extends('layouts.app')
@section('judul', 'Riwayat Pinjaman')

@section('isi')
    <div class="bar">
        <h2 style="margin:0">Riwayat Pengajuan Pinjaman</h2>
        <a class="btn" href="{{ route('pegawai.peminjaman.create') }}">+ Ajukan Peminjaman</a>
    </div>

    <div class="tabs">
        @foreach (['menunggu' => 'Menunggu', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $k => $nama)
            <a class="btn {{ $tab === $k ? '' : 's' }}" href="{{ route('pegawai.peminjaman.index', ['tab' => $k]) }}">{{ $nama }} ({{ $jumlah[$k] }})</a>
        @endforeach
    </div>

    <div class="card">
        <div class="tw">
            <table>
                <tr><th>Tujuan</th><th>Waktu mulai</th><th>Penumpang</th><th>Status</th><th>Keterangan</th></tr>
                @forelse ($daftar as $p)
                    <tr>
                        <td>
                            <strong>{{ $p->tujuan }}</strong><br>
                            <span class="note">{{ $p->lokasi_tujuan }}, {{ $p->menginap ? 'menginap' : 'tidak menginap' }}</span>
                        </td>
                        <td>{{ $p->mulai->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->penumpang }}</td>
                        <td>@include('partials.status', ['p' => $p])</td>
                        <td>
                            @if ($p->status === 'ditolak')
                                <div class="flash err" style="margin:0 0 6px">Alasan: {{ $p->alasan_tolak }}</div>
                                <a class="btn s" href="{{ route('pegawai.peminjaman.create', ['ulang' => $p->id]) }}">Ajukan lagi</a>
                            @elseif ($p->status === 'menunggu')
                                {{ $p->kendaraan?->plat }} - {{ $p->kendaraan?->jenis }}<br>
                                <span class="note">Pengemudi: {{ $p->pengemudi?->nama ?? 'tanpa pengemudi' }}</span><br>
                                <span class="note">Menunggu verifikasi admin</span>
                            @else
                                {{ $p->kendaraan?->plat }} - {{ $p->kendaraan?->jenis }}<br>
                                <span class="note">Pengemudi: {{ $p->pengemudi?->nama ?? 'tanpa pengemudi' }}</span><br>
                                <span class="note">Surat tugas: {{ $p->nomor_surat }}</span><br>
                                <a class="btn s" style="margin-top:4px" href="{{ route('surat-tugas.show', $p) }}" target="_blank">Lampiran Surat Tugas</a>
                                @if ($p->status === 'disetujui')
                                    <br><a class="btn" style="margin-top:6px" href="{{ route('pegawai.pengembalian.create', $p) }}">Pengembalian</a>
                                @elseif ($p->status === 'kembali_ditolak')
                                    <br><a class="btn d" style="margin-top:6px" href="{{ route('pegawai.pengembalian.create', $p) }}">Perbaiki &amp; ajukan kembali</a>
                                @elseif ($p->status === 'verifikasi')
                                    <br><span class="note">Laporan pengembalian menunggu verifikasi</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="note" style="text-align:center; padding:24px">Belum ada pengajuan pada tab ini.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
