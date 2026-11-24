@extends('layouts.app')
@section('judul', 'Verifikasi Pinjaman')

@section('isi')
    <h2>Verifikasi Pengajuan Pinjaman</h2>

    <div class="tabs">
        @foreach (['menunggu' => 'Menunggu', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $k => $nama)
            <a class="btn {{ $tab === $k ? '' : 's' }}" href="{{ route('admin.peminjaman.index', ['tab' => $k]) }}">{{ $nama }} ({{ $jumlah[$k] }})</a>
        @endforeach
    </div>

    <div class="card">
        <div class="tw">
            <table>
                <tr><th>Seksi</th><th>Tujuan</th><th>Waktu mulai</th><th>Kebutuhan</th><th>Status</th><th>Keterangan / Aksi</th></tr>
                @forelse ($daftar as $p)
                    <tr>
                        <td><strong>{{ $p->pengguna->nama_seksi }}</strong><br><span class="note">Petugas: {{ $p->petugas }}</span></td>
                        <td>
                            {{ $p->tujuan }}<br>
                            <span class="note">{{ $p->lokasi_tujuan }}, {{ $p->menginap ? 'menginap' : 'tidak menginap' }}</span>
                        </td>
                        <td>{{ $p->mulai->format('d/m/Y H:i') }}</td>
                        <td>
                            {{ $p->penumpang }} penumpang<br>
                            <span class="note">Pilihan seksi: {{ $p->kendaraan?->plat }} - {{ $p->kendaraan?->jenis }}; pengemudi: {{ $p->pengemudi?->nama ?? 'tanpa pengemudi' }}</span><br>
                            <span class="note">{{ $p->catatan ?: '-' }}</span>
                        </td>
                        <td>@include('partials.status', ['p' => $p])</td>
                        <td style="white-space:nowrap">
                            @if ($p->status === 'menunggu')
                                <a class="btn" href="{{ route('admin.peminjaman.show', $p) }}">Verifikasi</a>
                            @elseif ($p->status === 'ditolak')
                                <span class="note">Alasan: {{ $p->alasan_tolak }}</span>
                            @else
                                {{ $p->kendaraan?->plat }} - {{ $p->kendaraan?->jenis }}<br>
                                <span class="note">Pengemudi: {{ $p->pengemudi?->nama ?? 'tanpa pengemudi' }} | {{ $p->nomor_surat }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="note" style="text-align:center; padding:24px">Tidak ada data pada tab ini.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
