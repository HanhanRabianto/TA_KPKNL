@extends('layouts.app')
@section('judul', 'Servis Kendaraan')

@section('isi')
    <div class="bar">
        <h2 style="margin:0">Servis Kendaraan</h2>
        <a class="btn" href="{{ route('admin.servis.create') }}">+ Catat Servis</a>
    </div>

    <div class="grid">
        <div class="card"><div class="num">{{ $jumlahKendaraan }}</div><div class="lbl">Jumlah kendaraan</div></div>
        <div class="card"><div class="num">{{ $berjalan }}</div><div class="lbl">Sedang di servis</div></div>
        <div class="card"><div class="num">{{ $selesai }}</div><div class="lbl">Selesai di servis</div></div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Riwayat Servis</h3>
        <div class="tw">
            <table>
                <tr><th>Tanggal masuk</th><th>Kendaraan</th><th>Jenis servis</th><th>Bengkel</th><th>Biaya</th><th>Status</th><th>Aksi</th></tr>
                @forelse ($daftar as $s)
                    <tr>
                        <td>{{ $s->tgl_masuk->format('d/m/Y') }}</td>
                        <td>{{ $s->kendaraan->plat }}<br><span class="note">{{ $s->kendaraan->jenis }}</span></td>
                        <td>{{ $s->jenis }}@if ($s->catatan)<br><span class="note">{{ $s->catatan }}</span>@endif</td>
                        <td>{{ $s->bengkel ?: '-' }}</td>
                        <td>
                            @if ($s->status === 'Selesai')
                                Rp {{ number_format($s->biaya_akhir, 0, ',', '.') }}
                            @else
                                <span class="note">Estimasi</span><br>Rp {{ number_format($s->biaya, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>
                            <span class="b {{ $s->status === 'Selesai' ? 'ok' : 'wr' }}">{{ $s->status }}</span>
                            @if ($s->status === 'Selesai')<br><span class="note">{{ $s->tgl_selesai->format('d/m/Y') }}</span>
                            @elseif ($s->estimasi_selesai)<br><span class="note">Estimasi {{ $s->estimasi_selesai->format('d/m/Y') }}</span>@endif
                        </td>
                        <td>
                            @if ($s->status === 'Berjalan')
                                <a class="btn" href="{{ route('admin.servis.selesai', $s) }}">Selesaikan</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="note" style="text-align:center; padding:24px">Belum ada catatan servis.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
