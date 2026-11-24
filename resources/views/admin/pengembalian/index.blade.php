@extends('layouts.app')
@section('judul', 'Persetujuan Pengembalian')

@section('isi')
    @include('partials.judul', [
        'judul' => 'Persetujuan Pengembalian',
        'sub'   => 'Periksa laporan pemakaian dari seksi, lalu terima atau tolak pengembalian',
        'aksi'  => '<button class="btn s" type="button" onclick="window.print()">Cetak PDF</button>',
    ])

    @include('partials.kartu', ['kartu' => [
        ['Menunggu verifikasi', $menunggu->count(), '#a76400'],
        ['Disetujui', $diterima->count(), '#127a3d'],
        ['Ditolak', $ditolak->count(), '#b42318'],
    ]])

    <div class="card">
        <h3 style="margin-top:0">Menunggu Verifikasi ({{ $menunggu->count() }})</h3>
        <div class="tw">
            <table>
                <tr><th>Seksi</th><th>Kendaraan</th><th>Tujuan</th><th>Waktu kembali</th><th>Laporan</th><th>Status</th><th class="no-print">Keputusan</th></tr>
                @forelse ($menunggu as $r)
                    <tr>
                        <td>{{ $r->peminjaman->pengguna->nama_seksi }}</td>
                        <td>{{ $r->peminjaman->kendaraan->nama }}</td>
                        <td>{{ $r->peminjaman->tujuan }}</td>
                        <td>{{ $r->kembali_pada->format('d/m/Y H:i') }}</td>
                        <td>
                            {{ number_format($r->odometer_awal, 0, ',', '.') }} &rarr; {{ number_format($r->odometer_akhir, 0, ',', '.') }}
                            ({{ number_format($r->jarak, 0, ',', '.') }} km)<br>
                            <span class="b {{ $r->kondisi === 'Baik' ? 'ok' : ($r->kondisi === 'Rusak' ? 'er' : 'wr') }}">{{ $r->kondisi }}</span>
                        </td>
                        <td><span class="b wr">Menunggu</span></td>
                        <td class="no-print"><a class="btn" href="{{ route('admin.pengembalian.show', $r) }}">Verifikasi</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="note" style="text-align:center; padding:20px">Tidak ada laporan pengembalian yang menunggu.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Disetujui ({{ $diterima->count() }})</h3>
        <div class="tw">
            <table>
                <tr><th>Seksi</th><th>Kendaraan</th><th>Tujuan</th><th>Waktu kembali</th><th>Laporan</th><th>Status</th></tr>
                @forelse ($diterima as $r)
                    <tr>
                        <td>{{ $r->peminjaman->pengguna->nama_seksi }}</td>
                        <td>{{ $r->peminjaman->kendaraan->nama }}</td>
                        <td>{{ $r->peminjaman->tujuan }}</td>
                        <td>{{ $r->kembali_pada->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($r->jarak, 0, ',', '.') }} km, {{ $r->kondisi }}@if ($r->catatan_kondisi)<br><span class="note">{{ $r->catatan_kondisi }}</span>@endif</td>
                        <td><span class="b ok">Selesai</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="note" style="text-align:center; padding:20px">Belum ada pengembalian yang disetujui.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Ditolak ({{ $ditolak->count() }})</h3>
        <div class="tw">
            <table>
                <tr><th>Seksi</th><th>Kendaraan</th><th>Tujuan</th><th>Waktu kembali</th><th>Alasan penolakan</th><th>Status</th></tr>
                @forelse ($ditolak as $r)
                    <tr>
                        <td>{{ $r->peminjaman->pengguna->nama_seksi }}</td>
                        <td>{{ $r->peminjaman->kendaraan->nama }}</td>
                        <td>{{ $r->peminjaman->tujuan }}</td>
                        <td>{{ $r->kembali_pada->format('d/m/Y H:i') }}</td>
                        <td>{{ $r->alasan_tolak }}</td>
                        <td><span class="b er">Ditolak</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="note" style="text-align:center; padding:20px">Belum ada laporan yang ditolak.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
