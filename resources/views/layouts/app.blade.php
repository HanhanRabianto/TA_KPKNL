<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'SIMOVDIN') - KPKNL Bandung</title>
    <style>
        :root { --pr:#0b3d91; --pr2:#e8eefb; --bg:#f4f6fa; --card:#fff; --tx:#1b2433; --mu:#6b7686; --bd:#dde3ec;
                --ok:#127a3d; --okb:#e0f5e8; --wr:#a76400; --wrb:#fff1d6; --er:#b42318; --erb:#fde4e1; }
        * { box-sizing: border-box; }
        body { margin:0; font:14px/1.5 system-ui,-apple-system,"Segoe UI",sans-serif; background:var(--bg); color:var(--tx); }
        a { color:var(--pr); }

       
        .app { display:flex; min-height:100vh; }
        nav.side { width:262px; flex-shrink:0; color:#fff; padding:18px 12px; display:flex; flex-direction:column; gap:2px; position:sticky; top:0; height:100vh; overflow-y:auto; }
        nav.side .brand { font-size:18px; padding:0 12px; }
        nav.side .sub { color:#d6e2f2; padding:0 12px 14px; font-size:12px; }
        nav.side a { display:flex; justify-content:space-between; align-items:center; gap:8px; padding:8px 12px; border-radius:8px; color:#d6e2f2; text-decoration:none; }
        nav.side a:hover { background:rgba(255,255,255,.08); }
        nav.side a.on { background:rgba(255,255,255,.18); color:#fff; font-weight:600; }
        nav.side summary { display:flex; justify-content:space-between; align-items:center; gap:8px; padding:8px 12px; border-radius:8px; color:#fff; font-weight:600; cursor:pointer; list-style:none; }
        nav.side summary::-webkit-details-marker { display:none; }
        nav.side summary::after { content:'\25BE'; opacity:.7; margin-left:6px; }
        nav.side summary > span.t { flex:1; }
        nav.side details > a, nav.side details > details { margin-left:10px; }
        nav.side details details > a { margin-left:10px; }
        nav.side details details > summary { font-weight:500; color:#d6e2f2; }
        nav.side .nb { background:#f5b942; color:#3b2a00; border-radius:99px; padding:0 8px; font-size:11px; font-weight:700; }
        nav.side .bawah { margin-top:auto; border-top:1px solid rgba(255,255,255,.25); padding-top:12px; display:flex; flex-direction:column; gap:8px; }
        nav.side .av { width:34px; height:34px; border-radius:50%; background:rgba(255,255,255,.25); display:grid; place-items:center; font-weight:700; flex-shrink:0; }
        nav.side a.prof { justify-content:flex-start; gap:10px; }
        nav.side a.prof small { opacity:.8; }
        nav.side form { margin:0; }
        nav.side .keluar { width:100%; background:transparent; color:#fff; border:1px solid rgba(255,255,255,.5); padding:8px; border-radius:8px; cursor:pointer; }
        .main { flex:1; min-width:0; padding:24px; max-width:1240px; }

        h1 { font-size:22px; margin:0; } h2 { font-size:18px; margin:0 0 12px; } h3 { font-size:15px; margin:18px 0 8px; }
        .head { margin-bottom:16px; } .head .note { margin-top:2px; }
        .grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(170px,1fr)); gap:12px; margin-bottom:16px; }
        .card { background:var(--card); border:1px solid var(--bd); border-radius:10px; padding:16px; margin-bottom:14px; }
        .grid .card { margin:0; border-left-width:4px; }
        .num { font-size:28px; font-weight:700; color:var(--pr); } .lbl { color:var(--mu); font-size:12px; }
        .btn { display:inline-block; background:var(--pr); color:#fff; border:0; padding:8px 14px; border-radius:8px; cursor:pointer; font-size:13px; text-decoration:none; }
        .btn.s { background:var(--pr2); color:var(--pr); } .btn.d { background:var(--er); color:#fff; } .btn.w { background:#fff; color:var(--pr); border:1px solid var(--bd); }
        .btn:disabled { opacity:.5; cursor:not-allowed; }
        .bar { display:flex; justify-content:space-between; align-items:flex-start; gap:10px; flex-wrap:wrap; margin-bottom:16px; }
        .chips { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:10px; align-items:center; }
        .tw { overflow-x:auto; } table { width:100%; border-collapse:collapse; font-size:13px; }
        th, td { text-align:left; padding:9px 8px; border-bottom:1px solid var(--bd); vertical-align:top; }
        th { color:var(--mu); font-size:12px; font-weight:600; }
        .b { display:inline-block; padding:2px 9px; border-radius:99px; font-size:12px; font-weight:600; }
        .b.ok { background:var(--okb); color:var(--ok); } .b.wr { background:var(--wrb); color:var(--wr); } .b.er { background:var(--erb); color:var(--er); } .b.in { background:var(--pr2); color:var(--pr); }
        .note { color:var(--mu); font-size:12px; }
        .flash { padding:10px 12px; border-radius:8px; margin-bottom:14px; }
        .flash.ok { background:var(--okb); color:var(--ok); } .flash.err { background:var(--erb); color:var(--er); } .flash.info { background:#e9edf3; color:#3d4a5c; }
        label { display:block; font-size:12px; color:var(--mu); margin:12px 0 4px; }
        input, select, textarea { width:100%; padding:9px; border:1px solid var(--bd); border-radius:8px; font:inherit; background:#fff; color:var(--tx); }
        input[readonly] { background:#eef1f6; color:var(--mu); }
        .row { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:0 14px; }
        .ferr { color:var(--er); font-size:12px; margin-top:3px; }
        .bars { display:flex; align-items:flex-end; gap:10px; height:190px; padding-top:8px; }
        .bars > div { flex:1; text-align:center; font-size:11px; color:var(--mu); display:flex; flex-direction:column; justify-content:flex-end; align-items:center; }
        .bars i { display:block; width:100%; background:var(--pr); border-radius:4px 4px 0 0; margin:4px 0; }
        .dl { display:grid; grid-template-columns:190px 1fr; gap:6px 12px; margin:10px 0; } .dl dt { color:var(--mu); } .dl dd { margin:0; }

        .print-only { display:none; }
        @media print {
            nav.side, .no-print, .flash { display:none !important; }
            .print-only { display:block !important; }
            td.print-only, span.print-only { display:inline !important; }
            .app { display:block; } .main { padding:0; max-width:none; }
            body { background:#fff; } .card { border:0; padding:0; }
        }
        @media (max-width:860px) {
            .app { flex-direction:column; }
            nav.side { width:100%; height:auto; position:static; }
            .dl { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
@php
    $u = auth()->user();
    $admin = $u->peran === 'admin';
    $on = fn (string $pola) => request()->routeIs($pola) ? 'on' : '';
    if ($admin) {
        $nPinjam = \App\Models\Peminjaman::where('status', 'menunggu')->count();
        $nKembali = \App\Models\Pengembalian::where('status', 'menunggu')->count();
        $nStnk = \App\Models\Kendaraan::jumlahJatuhTempo('stnk_berlaku');
        $nBpkb = \App\Models\Kendaraan::jumlahJatuhTempo('bpkb_berlaku');
        $nData = \App\Models\Kendaraan::jumlahHampirHabis(90);
    }
@endphp
<div class="app">
    <nav class="side" style="background:{{ $admin ? '#0B2F5B' : '#0F4C5C' }}">
        <b class="brand">SIMOVDIN</b>
        <div class="sub">{{ $admin ? 'Panel Admin' : 'Portal Seksi' }} &middot; KPKNL Bandung</div>

        @if ($admin)
            <a href="{{ route('admin.dashboard') }}" class="{{ $on('admin.dashboard') }}">Dashboard</a>

            <details {{ request()->routeIs('admin.peminjaman.*', 'admin.pengembalian.*') ? 'open' : '' }}>
                <summary><span class="t">Persetujuan</span>@if ($nPinjam + $nKembali)<span class="nb">{{ $nPinjam + $nKembali }}</span>@endif</summary>
                <a href="{{ route('admin.peminjaman.index') }}" class="{{ $on('admin.peminjaman.*') }}">Peminjaman @if ($nPinjam)<span class="nb">{{ $nPinjam }}</span>@endif</a>
                <a href="{{ route('admin.pengembalian.index') }}" class="{{ $on('admin.pengembalian.*') }}">Pengembalian @if ($nKembali)<span class="nb">{{ $nKembali }}</span>@endif</a>
            </details>

            <details {{ request()->routeIs('admin.servis.*', 'admin.pajak.*', 'admin.bbm.*', 'admin.laporan.*') ? 'open' : '' }}>
                <summary><span class="t">Operasional</span>@if ($nStnk + $nBpkb)<span class="nb">{{ $nStnk + $nBpkb }}</span>@endif</summary>
                <a href="{{ route('admin.servis.index') }}" class="{{ $on('admin.servis.*') }}">Servis</a>
                <details {{ request()->routeIs('admin.pajak.*') ? 'open' : '' }}>
                    <summary><span class="t">Pajak Kendaraan</span>@if ($nStnk + $nBpkb)<span class="nb">{{ $nStnk + $nBpkb }}</span>@endif</summary>
                    <a href="{{ route('admin.pajak.index', 'stnk') }}" class="{{ request()->is('admin/pajak/stnk*') ? 'on' : '' }}">STNK @if ($nStnk)<span class="nb">{{ $nStnk }}</span>@endif</a>
                    <a href="{{ route('admin.pajak.index', 'bpkb') }}" class="{{ request()->is('admin/pajak/bpkb*') ? 'on' : '' }}">BPKB @if ($nBpkb)<span class="nb">{{ $nBpkb }}</span>@endif</a>
                </details>
                <a href="{{ route('admin.bbm.index') }}" class="{{ $on('admin.bbm.*') }}">BBM</a>
                <details {{ request()->routeIs('admin.laporan.*') ? 'open' : '' }}>
                    <summary><span class="t">Laporan</span></summary>
                    <a href="{{ route('admin.laporan.tampil', 'servis') }}" class="{{ request()->is('admin/laporan/servis') ? 'on' : '' }}">Laporan Servis</a>
                    <a href="{{ route('admin.laporan.tampil', 'stnk') }}" class="{{ request()->is('admin/laporan/stnk') ? 'on' : '' }}">Laporan Pembayaran STNK</a>
                    <a href="{{ route('admin.laporan.tampil', 'bpkb') }}" class="{{ request()->is('admin/laporan/bpkb') ? 'on' : '' }}">Laporan Pembayaran BPKB</a>
                    <a href="{{ route('admin.laporan.tampil', 'pemakaian-kendaraan') }}" class="{{ request()->is('admin/laporan/pemakaian-kendaraan') ? 'on' : '' }}">Laporan Pemakaian Kendaraan</a>
                    <a href="{{ route('admin.laporan.tampil', 'pemakaian-seksi') }}" class="{{ request()->is('admin/laporan/pemakaian-seksi') ? 'on' : '' }}">Laporan Pemakaian Seksi</a>
                </details>
            </details>

            <details {{ request()->routeIs('admin.kendaraan.*', 'admin.pengemudi.*', 'admin.pengguna.*') ? 'open' : '' }}>
                <summary><span class="t">Data</span>@if ($nData)<span class="nb">{{ $nData }}</span>@endif</summary>
                <a href="{{ route('admin.kendaraan.index') }}" class="{{ $on('admin.kendaraan.*') }}">Kendaraan</a>
                <a href="{{ route('admin.pengemudi.index') }}" class="{{ $on('admin.pengemudi.*') }}">Pengemudi</a>
                <a href="{{ route('admin.pengguna.index') }}" class="{{ $on('admin.pengguna.*') }}">Pengguna</a>
            </details>
        @else
            <a href="{{ route('pegawai.beranda') }}" class="{{ $on('pegawai.beranda') }}">Beranda</a>
            <a href="{{ route('pegawai.peminjaman.create') }}" class="{{ $on('pegawai.peminjaman.create') }}">Ajukan Peminjaman</a>
            <a href="{{ route('pegawai.peminjaman.index') }}" class="{{ request()->routeIs('pegawai.peminjaman.index', 'pegawai.pengembalian.*') ? 'on' : '' }}">Riwayat Saya</a>
        @endif

        <div class="bawah">
            <a href="{{ route('profil.show') }}" class="prof {{ $on('profil.*') }}">
                <span class="av">{{ strtoupper(mb_substr($u->nama_seksi, 0, 1)) }}</span>
                <span><b>{{ $u->nama_seksi }}</b><br><small>{{ $u->label_peran }} &middot; Profil</small></span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="keluar" type="submit">Keluar</button>
            </form>
        </div>
    </nav>

    <main class="main">
        @if (session('ok')) <div class="flash ok">{{ session('ok') }}</div> @endif
        @if (session('err')) <div class="flash err">{{ session('err') }}</div> @endif
        @yield('isi')
    </main>
</div>
</body>
</html>
