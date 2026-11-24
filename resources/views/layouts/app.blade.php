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
        nav.side { width:272px; flex-shrink:0; background:var(--side,#0B2F5B); color:#fff; padding:18px 14px; display:flex; flex-direction:column; gap:6px; position:sticky; top:0; height:100vh; overflow-y:auto; align-self:flex-start; }
        nav.side .brand { display:flex; align-items:center; gap:12px; padding:4px 6px 16px; }
        nav.side .brand b { display:block; font-size:17px; line-height:1.2; }
        nav.side .brand small { display:block; color:#d6e2f2; font-size:12px; margin-top:2px; }
        nav.side .bi { width:42px; height:42px; border-radius:12px; background:rgba(255,255,255,.14); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        nav.side .bi svg { width:22px; height:22px; }
        nav.side .navs { display:flex; flex-direction:column; gap:4px; }
        nav.side a, nav.side summary { display:flex; align-items:center; gap:12px; color:#d6e2f2; text-decoration:none; padding:8px 10px; border-radius:12px; cursor:pointer; font-weight:600; list-style:none; }
        nav.side summary { color:#fff; }
        nav.side summary::-webkit-details-marker { display:none; }
        nav.side summary::after { content:'\25BE'; margin-left:8px; opacity:.7; font-size:12px; }
        nav.side a:hover, nav.side summary:hover { background:rgba(255,255,255,.08); }
        nav.side a.on { background:#fff; color:var(--side,#0B2F5B); }
        nav.side .lb { flex:1; min-width:0; }
        nav.side .ic { width:34px; height:34px; border-radius:10px; background:rgba(255,255,255,.12); display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; }
        nav.side .ic svg { width:18px; height:18px; }
        nav.side a.on .ic { background:rgba(0,0,0,.08); }
        nav.side details > a, nav.side details > details > a { font-weight:500; font-size:13px; padding:8px 10px 8px 56px; }
        nav.side details details > a { padding-left:72px; }
        nav.side details details > summary { font-weight:500; font-size:13px; color:#d6e2f2; padding:8px 10px 8px 56px; }
        nav.side .nb { background:#E5484D; color:#fff; border-radius:99px; font-size:11px; font-weight:700; min-width:20px; height:20px; padding:0 6px; display:inline-flex; align-items:center; justify-content:center; margin-left:8px; }
        nav.side .bawah { margin-top:auto; display:flex; flex-direction:column; gap:10px; padding-top:14px; }
        nav.side a.prof { border:1px solid rgba(255,255,255,.28); border-radius:14px; padding:10px 12px; gap:10px; font-weight:400; color:#fff; }
        nav.side a.prof.on { background:#fff; color:var(--side,#0B2F5B); }
        nav.side a.prof b { display:block; font-size:14px; }
        nav.side a.prof small { opacity:.8; font-size:12px; }
        nav.side .av { width:36px; height:36px; border-radius:50%; background:#fff; color:var(--side,#0B2F5B); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; flex-shrink:0; }
        nav.side a.prof.on .av { background:var(--side,#0B2F5B); color:#fff; }
        nav.side form { margin:0; }
        nav.side .keluar { width:100%; min-height:44px; background:transparent; color:#fff; border:1px solid rgba(255,255,255,.4); border-radius:10px; font:inherit; font-weight:700; cursor:pointer; }
        nav.side .keluar:hover { background:rgba(255,255,255,.08); }
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
            nav.side { width:100%; height:auto; position:static; align-self:stretch; overflow:visible; }
            nav.side .navs { flex-direction:row; flex-wrap:wrap; }
            nav.side details { width:100%; }
            .dl { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
@php
    $u = auth()->user();
    $admin = $u->peran === 'admin';
    $on = fn (string ...$pola) => request()->routeIs(...$pola) ? 'on' : '';

    // ikon sidebar (garis, mengikuti warna teks)
    $ikon = [
        'grid'   => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>',
        'check'  => '<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>',
        'layers' => '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>',
        'db'     => '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>',
        'home'   => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        'plus'   => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>',
        'clock'  => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'truck'  => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
    ];
    $svg = fn (string $n) => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$ikon[$n].'</svg>';
    $ic = fn (string $n) => '<span class="ic">'.$svg($n).'</span>';
    $inisial = mb_strtoupper(collect(preg_split('/\s+/', trim($u->nama_seksi)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''));

    if ($admin) {
        $nPinjam = \App\Models\Peminjaman::where('status', 'menunggu')->count();
        $nKembali = \App\Models\Pengembalian::where('status', 'menunggu')->count();
        $nStnk = \App\Models\Kendaraan::jumlahJatuhTempo('stnk_berlaku');
        $nBpkb = \App\Models\Kendaraan::jumlahJatuhTempo('bpkb_berlaku');
        $nData = \App\Models\Kendaraan::jumlahHampirHabis(90);
    }
@endphp
<div class="app" style="--side:{{ $admin ? '#0B2F5B' : '#0F4C5C' }}">
    <nav class="side">
        <div class="brand">
            <span class="bi">{!! $svg('truck') !!}</span>
            <span><b>SIMOVDIN</b><small>{{ $admin ? 'Panel Admin' : 'Portal Seksi' }} &middot; KPKNL Bandung</small></span>
        </div>

        <div class="navs">
        @if ($admin)
            <a href="{{ route('admin.dashboard') }}" class="{{ $on('admin.dashboard') }}">{!! $ic('grid') !!}<span class="lb">Dashboard</span></a>

            <details {{ request()->routeIs('admin.peminjaman.*', 'admin.pengembalian.*') ? 'open' : '' }}>
                <summary>{!! $ic('check') !!}<span class="lb">Persetujuan</span>@if ($nPinjam + $nKembali)<span class="nb">{{ $nPinjam + $nKembali }}</span>@endif</summary>
                <a href="{{ route('admin.peminjaman.index') }}" class="{{ $on('admin.peminjaman.*') }}"><span class="lb">Peminjaman</span>@if ($nPinjam)<span class="nb">{{ $nPinjam }}</span>@endif</a>
                <a href="{{ route('admin.pengembalian.index') }}" class="{{ $on('admin.pengembalian.*') }}"><span class="lb">Pengembalian</span>@if ($nKembali)<span class="nb">{{ $nKembali }}</span>@endif</a>
            </details>

            <details {{ request()->routeIs('admin.servis.*', 'admin.pajak.*', 'admin.bbm.*', 'admin.laporan.*') ? 'open' : '' }}>
                <summary>{!! $ic('layers') !!}<span class="lb">Operasional</span>@if ($nStnk + $nBpkb)<span class="nb">{{ $nStnk + $nBpkb }}</span>@endif</summary>
                <a href="{{ route('admin.servis.index') }}" class="{{ $on('admin.servis.*') }}"><span class="lb">Servis</span></a>
                <details {{ request()->routeIs('admin.pajak.*') ? 'open' : '' }}>
                    <summary><span class="lb">Pajak Kendaraan</span>@if ($nStnk + $nBpkb)<span class="nb">{{ $nStnk + $nBpkb }}</span>@endif</summary>
                    <a href="{{ route('admin.pajak.index', 'stnk') }}" class="{{ request()->is('admin/pajak/stnk*') ? 'on' : '' }}"><span class="lb">STNK</span>@if ($nStnk)<span class="nb">{{ $nStnk }}</span>@endif</a>
                    <a href="{{ route('admin.pajak.index', 'bpkb') }}" class="{{ request()->is('admin/pajak/bpkb*') ? 'on' : '' }}"><span class="lb">BPKB</span>@if ($nBpkb)<span class="nb">{{ $nBpkb }}</span>@endif</a>
                </details>
                <a href="{{ route('admin.bbm.index') }}" class="{{ $on('admin.bbm.*') }}"><span class="lb">BBM</span></a>
                <details {{ request()->routeIs('admin.laporan.*') ? 'open' : '' }}>
                    <summary><span class="lb">Laporan</span></summary>
                    <a href="{{ route('admin.laporan.tampil', 'servis') }}" class="{{ request()->is('admin/laporan/servis') ? 'on' : '' }}"><span class="lb">Laporan Servis</span></a>
                    <a href="{{ route('admin.laporan.tampil', 'stnk') }}" class="{{ request()->is('admin/laporan/stnk') ? 'on' : '' }}"><span class="lb">Laporan Pembayaran STNK</span></a>
                    <a href="{{ route('admin.laporan.tampil', 'bpkb') }}" class="{{ request()->is('admin/laporan/bpkb') ? 'on' : '' }}"><span class="lb">Laporan Pembayaran BPKB</span></a>
                    <a href="{{ route('admin.laporan.tampil', 'pemakaian-kendaraan') }}" class="{{ request()->is('admin/laporan/pemakaian-kendaraan') ? 'on' : '' }}"><span class="lb">Laporan Pemakaian Kendaraan</span></a>
                    <a href="{{ route('admin.laporan.tampil', 'pemakaian-seksi') }}" class="{{ request()->is('admin/laporan/pemakaian-seksi') ? 'on' : '' }}"><span class="lb">Laporan Pemakaian Seksi</span></a>
                </details>
            </details>

            <details {{ request()->routeIs('admin.kendaraan.*', 'admin.pengemudi.*', 'admin.pengguna.*') ? 'open' : '' }}>
                <summary>{!! $ic('db') !!}<span class="lb">Data</span>@if ($nData)<span class="nb">{{ $nData }}</span>@endif</summary>
                <a href="{{ route('admin.kendaraan.index') }}" class="{{ $on('admin.kendaraan.*') }}"><span class="lb">Kendaraan</span></a>
                <a href="{{ route('admin.pengemudi.index') }}" class="{{ $on('admin.pengemudi.*') }}"><span class="lb">Pengemudi</span></a>
                <a href="{{ route('admin.pengguna.index') }}" class="{{ $on('admin.pengguna.*') }}"><span class="lb">Pengguna</span></a>
            </details>
        @else
            <a href="{{ route('pegawai.beranda') }}" class="{{ $on('pegawai.beranda') }}">{!! $ic('home') !!}<span class="lb">Beranda</span></a>
            <a href="{{ route('pegawai.peminjaman.create') }}" class="{{ $on('pegawai.peminjaman.create') }}">{!! $ic('plus') !!}<span class="lb">Ajukan Peminjaman</span></a>
            <a href="{{ route('pegawai.peminjaman.index') }}" class="{{ $on('pegawai.peminjaman.index', 'pegawai.pengembalian.*') }}">{!! $ic('clock') !!}<span class="lb">Riwayat Saya</span></a>
        @endif
        </div>

        <div class="bawah">
            <a href="{{ route('profil.show') }}" class="prof {{ $on('profil.*') }}">
                <span class="av">{{ $inisial }}</span>
                <span><b>{{ $u->nama_seksi }}</b><small>{{ $u->label_peran }}</small></span>
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
