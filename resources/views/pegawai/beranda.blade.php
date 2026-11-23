@extends('layouts.app')
@section('judul', 'Beranda')

@section('isi')
    <h2>Beranda</h2>

    @if ($blokir)
        <div class="flash err">{{ $blokir }}</div>
    @endif

    <div class="grid">
        <div class="card"><div class="num">{{ $aktif }}/{{ $maks }}</div><div class="lbl">Peminjaman aktif (maksimal {{ $maks }})</div></div>
        <div class="card"><div class="num">{{ $menunggu }}</div><div class="lbl">Menunggu verifikasi</div></div>
        <div class="card"><div class="num">{{ $selesai }}</div><div class="lbl">Selesai</div></div>
    </div>

    <div class="card">
        <p>Selamat datang, <strong>{{ auth()->user()->nama_seksi }}</strong>.</p>
        @if (! $blokir)
            <a class="btn" href="{{ route('pegawai.peminjaman.create') }}">+ Ajukan Peminjaman</a>
        @endif
        <a class="btn s" href="{{ route('pegawai.peminjaman.index') }}">Lihat Riwayat</a>
    </div>
@endsection
