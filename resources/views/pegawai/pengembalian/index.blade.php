@extends('layouts.app')
@section('judul', 'Riwayat Pengembalian')

@section('isi')
    <h2>Riwayat Pengajuan Pengembalian</h2>

    <div class="card">
        <div class="tw">
            <table>
                <tr><th>Kendaraan</th><th>Tujuan</th><th>Odometer</th><th>Kembali</th><th>Kondisi</th><th>Status</th></tr>
                @forelse ($daftar as $r)
                    <tr>
                        <td>{{ $r->peminjaman->kendaraan->plat }}<br><span class="note">{{ $r->peminjaman->kendaraan->jenis }}</span></td>
                        <td>{{ $r->peminjaman->tujuan }}</td>
                        <td>{{ $r->odometer_awal }} &rarr; {{ $r->odometer_akhir }}<br><span class="note">{{ $r->jarak }} km</span></td>
                        <td>{{ $r->kembali_pada->format('d/m/Y H:i') }}</td>
                        <td>{{ $r->kondisi }}@if ($r->catatan_kondisi)<br><span class="note">{{ $r->catatan_kondisi }}</span>@endif</td>
                        <td>
                            <span class="b {{ $r->kelas_status }}">{{ $r->label_status }}</span>
                            @if ($r->status === 'ditolak')
                                <div class="flash err" style="margin:6px 0">Alasan: {{ $r->alasan_tolak }}</div>
                                <a class="btn" href="{{ route('pegawai.pengembalian.create', $r->peminjaman_id) }}">Ajukan kembali</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="note" style="text-align:center; padding:24px">Belum ada laporan pengembalian.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
