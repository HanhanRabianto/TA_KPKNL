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
    </div>

    <h3>Kendaraan</h3>
    <div class="grid">
        <div class="card"><div class="num">{{ $totalKendaraan }}</div><div class="lbl">Kendaraan</div></div>
        <div class="card"><div class="num">{{ $tersedia }}</div><div class="lbl">Kendaraan tersedia</div></div>
        <div class="card"><div class="num">{{ $dipakai }}</div><div class="lbl">Sedang dipakai</div></div>
        <div class="card"><div class="num">{{ $diservis }}</div><div class="lbl">Sedang perbaikan</div></div>
    </div>

    <h3>Pengemudi dan seksi</h3>
    <div class="grid">
        <div class="card"><div class="num">{{ $totalPengemudi }}</div><div class="lbl">Total pengemudi</div></div>
        <div class="card"><div class="num">{{ $pengemudiSedia }}</div><div class="lbl">Pengemudi tersedia</div></div>
        <div class="card"><div class="num">{{ $totalSeksi }}</div><div class="lbl">Total seksi (pegawai)</div></div>
    </div>
@endsection
