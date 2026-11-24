@extends('layouts.app')
@section('judul', 'Dashboard Admin')

@section('isi')
    <h2>Dashboard Admin</h2>

    <h3>Peminjaman</h3>
    <div class="grid">
        <div class="card">
            <div class="num">{{ $pengajuanMenunggu }}</div>
            <div class="lbl">Pengajuan menunggu verifikasi</div>
            @if ($pengajuanMenunggu)<a class="btn" style="margin-top:8px" href="{{ route('admin.peminjaman.index') }}">Verifikasi</a>@endif
        </div>
        <div class="card">
            <div class="num">{{ $pengembalianMenunggu }}</div>
            <div class="lbl">Persetujuan pengembalian</div>
            @if ($pengembalianMenunggu)<a class="btn" style="margin-top:8px" href="{{ route('admin.pengembalian.index') }}">Verifikasi</a>@endif
        </div>
    </div>

    <h3>Kendaraan</h3>
    <div class="grid">
        <div class="card"><div class="num">{{ $totalKendaraan }}</div><div class="lbl">Kendaraan</div></div>
        <div class="card"><div class="num">{{ $tersedia }}</div><div class="lbl">Kendaraan tersedia</div></div>
        <div class="card"><div class="num">{{ $dipakai }}</div><div class="lbl">Sedang dipakai</div></div>
        <div class="card"><div class="num">{{ $diservis }}</div><div class="lbl">Sedang perbaikan</div></div>
        <div class="card"><div class="num">{{ $stnkTempo }}</div><div class="lbl">STNK jatuh tempo 30 hari</div></div>
        <div class="card"><div class="num">{{ $bpkbTempo }}</div><div class="lbl">BPKB jatuh tempo 30 hari</div></div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Pajak Jatuh Tempo ({{ $pajakTempo->count() }})</h3>
        <div class="tw">
            <table>
                <tr><th>Kendaraan</th><th>Jenis</th><th>Berlaku sampai</th><th>Sisa waktu</th><th>Aksi</th></tr>
                @forelse ($pajakTempo as $r)
                    <tr>
                        <td>{{ $r['k']->plat }}<br><span class="note">{{ $r['k']->jenis }}</span></td>
                        <td>{{ $r['jenis'] }}</td>
                        <td>{{ $r['tgl']->format('d/m/Y') }}</td>
                        <td>
                            @if ($r['sisa'] < 0) <span class="b er">Lewat {{ abs($r['sisa']) }} hari</span>
                            @else <span class="b wr">{{ $r['sisa'] }} hari lagi</span> @endif
                        </td>
                        <td><a class="btn s" href="{{ route('admin.pajak.index', $r['slug']) }}">Buka {{ $r['jenis'] }}</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="note" style="text-align:center; padding:20px">Tidak ada pajak yang jatuh tempo dalam 30 hari.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    <h3>Pengemudi dan seksi</h3>
    <div class="grid">
        <div class="card"><div class="num">{{ $totalPengemudi }}</div><div class="lbl">Total pengemudi</div></div>
        <div class="card"><div class="num">{{ $pengemudiSedia }}</div><div class="lbl">Pengemudi tersedia</div></div>
        <div class="card"><div class="num">{{ $totalSeksi }}</div><div class="lbl">Total seksi (pegawai)</div></div>
    </div>
@endsection
