@extends('layouts.app')
@section('judul', 'Pajak '.$nama)

@section('isi')
    <h2>Pajak Kendaraan: {{ $nama }}</h2>
    <p class="note">Pantau masa berlaku dan catat pembayaran {{ $nama }} kendaraan dinas.</p>

    <div class="grid">
        <div class="card"><div class="num">{{ $terlambat }}</div><div class="lbl">Terlambat</div></div>
        <div class="card"><div class="num">{{ $segera }}</div><div class="lbl">Jatuh tempo &le; 30 hari</div></div>
        <div class="card"><div class="num">{{ $tahunIni }}</div><div class="lbl">Dibayar tahun ini</div></div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Status {{ $nama }} per Kendaraan</h3>
        <p class="note" style="margin-top:0">Hanya kendaraan yang jatuh tempo 30 hari atau kurang, sudah lewat, atau belum punya tanggal. Setelah dibayar, kendaraan hilang dari daftar ini dan masuk riwayat.</p>
        <div class="tw">
            <table>
                <tr><th>Kendaraan</th><th>Berlaku sampai</th><th>Sisa waktu</th><th>Aksi</th></tr>
                @forelse ($perlu as $k)
                    @php $sisa = $k->sisaHari($kolom); @endphp
                    <tr>
                        <td><strong>{{ $k->plat }}</strong><br><span class="note">{{ $k->jenis }}</span></td>
                        <td>{{ $k->{$kolom}?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            @if ($sisa === null) <span class="b in">Belum ada data</span>
                            @elseif ($sisa < 0) <span class="b er">Lewat {{ abs($sisa) }} hari</span>
                            @else <span class="b wr">{{ $sisa }} hari lagi</span> @endif
                        </td>
                        <td><a class="btn" href="{{ route('admin.pajak.bayar', [$slug, $k]) }}">Catat Pembayaran</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="note" style="text-align:center; padding:24px">Tidak ada kendaraan yang jatuh tempo.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Riwayat Pembayaran {{ $nama }}</h3>
        <div class="tw">
            <table>
                <tr><th>Tanggal bayar</th><th>Kendaraan</th><th>Nominal</th><th>Berlaku sampai</th><th>Keterangan</th></tr>
                @forelse ($riwayat as $r)
                    <tr>
                        <td>{{ $r->tgl_bayar->format('d/m/Y') }}</td>
                        <td>{{ $r->kendaraan->plat }}<br><span class="note">{{ $r->kendaraan->jenis }}</span></td>
                        <td>Rp {{ number_format($r->nominal, 0, ',', '.') }}</td>
                        <td>{{ $r->berlaku_sampai->format('d/m/Y') }}</td>
                        <td>{{ $r->keterangan ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="note" style="text-align:center; padding:24px">Belum ada pembayaran tercatat.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
