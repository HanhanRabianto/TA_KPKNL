<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('judul', 'SIMOVDIN') - KPKNL Bandung</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-kementerian-keuangan.png') }}">
    <style>
        :root { --pr:#0b3d91; --pr2:#e8eefb; --bg:#f4f6fa; --card:#fff; --tx:#1b2433; --mu:#6b7686; --bd:#dde3ec;
                --ok:#127a3d; --okb:#e0f5e8; --wr:#a76400; --wrb:#fff1d6; --er:#b42318; --erb:#fde4e1;
                --pg:#f4f6fa; --hd:#f8fafc; --sh:0 1px 2px rgba(16,24,40,.04),0 6px 16px rgba(16,24,40,.05); }
        * { box-sizing: border-box; }
        body { margin:0; font:14px/1.5 system-ui,-apple-system,"Segoe UI",sans-serif; background:var(--pg); color:var(--tx); }
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
        .col { flex:1; min-width:0; display:flex; flex-direction:column; }
        .top { display:flex; align-items:center; gap:16px; padding:14px 32px; background:var(--card); border-bottom:1px solid var(--bd); position:sticky; top:0; z-index:5; }
        .sr { flex:1; max-width:680px; display:flex; align-items:center; gap:10px; background:var(--hd); border:1px solid var(--bd); border-radius:12px; padding:0 14px; min-height:46px; color:var(--mu); margin:0; }
        .sr:focus-within { outline:3px solid #6FA8E8; outline-offset:1px; }
        .sr svg { width:18px; height:18px; flex-shrink:0; }
        .sr input { border:0; background:transparent; min-height:44px; padding:0; flex:1; color:var(--tx); outline:none; border-radius:0; width:auto; }
        .tr { display:flex; align-items:center; gap:12px; margin-left:auto; }
        .ib { position:relative; width:42px; height:42px; border-radius:12px; border:1px solid var(--bd); background:var(--card); color:var(--pr); display:flex; align-items:center; justify-content:center; text-decoration:none; }
        .ib svg { width:19px; height:19px; }
        .dot { position:absolute; top:-7px; right:-7px; background:#E5484D; color:#fff; font:700 11px/1 system-ui,sans-serif; font-style:normal; min-width:19px; height:19px; border-radius:99px; display:flex; align-items:center; justify-content:center; padding:0 5px; }
        .av.ac { width:40px; height:40px; border-radius:50%; background:var(--side,#0B2F5B); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; text-decoration:none; }
        .main { flex:1; min-width:0; padding:30px 32px; display:flex; flex-direction:column; gap:20px; }

        h1 { font-size:28px; font-weight:800; margin:0; letter-spacing:-.01em; } h2 { font-size:17px; font-weight:700; margin:0; } h3 { font-size:15px; margin:0; }
        .head .note { margin-top:6px; font-size:14px; }
        .grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(170px,1fr)); gap:12px; }
        .card, .pn { background:var(--card); border:1px solid var(--bd); border-radius:16px; padding:20px; box-shadow:var(--sh); }
        .pn { display:flex; flex-direction:column; gap:14px; } .pn > .card { box-shadow:none; background:var(--hd); }
        .card > h3 + .tw, .card > h3 + p { margin-top:12px; }
        .cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(230px,1fr)); gap:18px; }
        .st { display:flex; align-items:center; gap:16px; }
        .ico { width:56px; height:56px; border-radius:16px; background:var(--pr2); color:var(--pr); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .ico svg { width:26px; height:26px; }
        .k { font-size:13px; color:var(--tx); font-weight:600; } .v { font-size:32px; font-weight:800; color:var(--pr); line-height:1.15; }
        .num { font-size:28px; font-weight:700; color:var(--pr); } .lbl { color:var(--mu); font-size:12px; }
        .btn { display:inline-flex; align-items:center; justify-content:center; background:var(--pr); color:#fff; border:1px solid transparent; padding:10px 18px; min-height:44px; border-radius:10px; cursor:pointer; font:inherit; font-weight:700; font-size:14px; text-decoration:none; }
        .btn.s { background:var(--pr2); color:var(--pr); } .btn.d { background:var(--er); color:#fff; } .btn.w { background:var(--card); color:var(--tx); border:1px solid var(--bd); }
        .btn:disabled { opacity:.5; cursor:not-allowed; }
        td .btn, .tw .btn { min-height:34px; padding:6px 12px; font-size:13px; }
        .bar { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap; }
        .bar > div:last-child { display:flex; gap:10px; flex-wrap:wrap; }
        .chips { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }
        .tw { overflow-x:auto; background:var(--card); border:1px solid var(--bd); border-radius:14px; }
        table { width:100%; border-collapse:collapse; font-size:13px; min-width:640px; }
        th { text-align:left; color:var(--mu); font-weight:600; font-size:12.5px; padding:12px 16px; background:var(--hd); border-bottom:1px solid var(--bd); white-space:nowrap; }
        td { text-align:left; padding:14px 16px; border-bottom:1px solid var(--bd); vertical-align:top; }
        tr:last-child td { border-bottom:0; }
        .b { display:inline-block; padding:4px 10px; border-radius:99px; font-size:12px; font-weight:700; white-space:nowrap; }
        .b.ok { background:var(--okb); color:var(--ok); } .b.wr { background:var(--wrb); color:var(--wr); } .b.er { background:var(--erb); color:var(--er); } .b.in { background:var(--pr2); color:var(--pr); }
        .note { color:var(--mu); font-size:12px; }
        .flash { padding:10px 12px; border-radius:8px; }
        .flash.ok { background:var(--okb); color:var(--ok); } .flash.err { background:var(--erb); color:var(--er); } .flash.info { background:#e9edf3; color:#3d4a5c; }
        label { display:block; font-size:12px; color:var(--mu); margin:12px 0 4px; }
        input, select, textarea { width:100%; padding:9px; border:1px solid var(--bd); border-radius:10px; font:inherit; background:#fff; color:var(--tx); }
        input[readonly] { background:#eef1f6; color:var(--mu); }
        .row { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:0 14px; }
        .ferr { color:var(--er); font-size:12px; margin-top:3px; }
        .bars { display:flex; align-items:flex-end; gap:14px; height:220px; margin-top:14px; padding:0 4px; border-bottom:1px solid var(--bd); }
        .bars > div { flex:1; max-width:84px; display:flex; flex-direction:column; align-items:center; justify-content:flex-end; gap:4px; font-size:12px; height:100%; }
        .bars > div span { position:relative; top:22px; margin-top:-18px; color:var(--mu); }
        .bars i { display:block; width:100%; background:var(--pr); border-radius:8px 8px 0 0; }
        .dl { display:grid; grid-template-columns:190px 1fr; gap:6px 12px; margin:10px 0; } .dl dt { color:var(--mu); } .dl dd { margin:0; }

        .print-only { display:none; }
        .mobile-menu,.mobile-title,.mobile-close,.mobile-nav,.mobile-scrim { display:none; }
        @media print {
            nav.side, .top, .no-print, .flash { display:none !important; }
            .print-only { display:block !important; }
            td.print-only, span.print-only { display:inline !important; }
            .app { display:block; } .main { padding:0; max-width:none; }
            body { background:#fff; } .card, .pn { border:0; padding:0; box-shadow:none; }
        }
        @media (max-width:860px) {
            :root { --mobile-edge:max(0px, calc((100vw - 480px) / 2)); --mobile-width:min(100vw, 480px); }
            html { -webkit-text-size-adjust:100%; }
            body { -webkit-tap-highlight-color:transparent; background:var(--pg); }
            .app { max-width:480px; width:100%; margin:0 auto; flex-direction:column; position:relative; background:var(--pg); }
            @media (min-width:520px) { body { background:#c9d2df; } .app { box-shadow:0 0 0 1px rgba(0,0,0,.08),0 12px 48px rgba(0,0,0,.18); } }

            .top { flex-wrap:wrap; gap:10px; padding:10px 14px; position:sticky; }
            .mobile-menu { display:flex; }
            .mobile-title { display:block; }
            .mobile-menu { display:flex; align-items:center; justify-content:center; width:42px; height:42px; border:1px solid var(--bd); border-radius:12px; background:var(--card); color:var(--pr); cursor:pointer; }
            .mobile-menu svg { width:20px; height:20px; }
            .mobile-title { display:block; flex:1; font-size:17px; color:var(--pr); }
            .top .sr { order:3; flex:1 1 100%; max-width:none; min-height:42px; }
            .top .sr input { min-height:40px; font-size:16px; }
            .top .tr { margin-left:0; gap:8px; }

            nav.side { position:fixed; inset:0 auto 0 var(--mobile-edge); width:min(86%,320px); height:100vh; height:100dvh; max-height:100dvh; align-self:stretch; overflow-y:auto; z-index:40; transform:translateX(-102%); transition:transform .25s ease; padding-top:calc(18px + env(safe-area-inset-top,0px)); padding-bottom:calc(18px + env(safe-area-inset-bottom,0px)); }
            .app.mobile-open nav.side { transform:none; box-shadow:8px 0 32px rgba(0,0,0,.3); }
            .mobile-close { display:flex; position:absolute; top:calc(10px + env(safe-area-inset-top,0px)); right:10px; width:38px; height:38px; border:0; border-radius:10px; background:rgba(255,255,255,.12); color:#fff; align-items:center; justify-content:center; cursor:pointer; }
            .mobile-close svg { width:18px; height:18px; }
            nav.side .brand { padding-right:48px; }
            .mobile-scrim { display:flex; position:fixed; inset:0 auto 0 var(--mobile-edge); width:var(--mobile-width); background:rgba(0,0,0,.45); z-index:35; opacity:0; pointer-events:none; transition:opacity .25s; }
            .app.mobile-open .mobile-scrim { opacity:1; pointer-events:auto; }

            .mobile-nav { display:flex; position:fixed; bottom:0; left:var(--mobile-edge); width:var(--mobile-width); z-index:30; background:var(--card); border-top:1px solid var(--bd); padding:6px 6px calc(6px + env(safe-area-inset-bottom,0px)); box-shadow:0 -4px 16px rgba(16,24,40,.06); }
            .mobile-nav a, .mobile-nav button { flex:1; min-width:0; min-height:48px; position:relative; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:3px; border:0; background:transparent; color:var(--mu); text-decoration:none; font:600 11px/1.2 system-ui,sans-serif; padding:6px 2px; border-radius:12px; cursor:pointer; }
            .mobile-nav a svg, .mobile-nav button svg { width:22px; height:22px; }
            .mobile-nav a.on { color:var(--pr); background:var(--pr2); }
            .mobile-nav .dot { top:0; right:calc(50% - 22px); }

            .main { padding:16px 14px calc(92px + env(safe-area-inset-bottom,0px)); gap:16px; }
            h1 { font-size:22px; }
            h2 { font-size:16px; }
            .bar { flex-direction:column; align-items:stretch; }
            .bar .note { font-size:13px; }
            .bar > div:last-child { width:100%; }
            .bar > div:last-child .btn { flex:1; white-space:nowrap; }
            .card,.pn { padding:14px; border-radius:14px; }
            .cards { grid-template-columns:1fr 1fr; gap:10px; }
            .cards .card:last-child:nth-child(odd) { grid-column:1/-1; }
            .card.st { flex-direction:column; align-items:flex-start; gap:10px; }
            .ico { width:40px; height:40px; border-radius:12px; }
            .ico svg { width:20px; height:20px; }
            .k { font-size:12.5px; }
            .v { font-size:26px; }
            .row { grid-template-columns:1fr; }
            .dl { grid-template-columns:1fr; }
            .btn { min-height:46px; }
            .btn.s { min-height:40px; }
            input,select,textarea { font-size:16px; min-height:46px; }
            .chips { flex-wrap:nowrap; overflow-x:auto; margin:0 -14px!important; padding:0 14px 4px; scrollbar-width:none; }
            .chips::-webkit-scrollbar { display:none; }
            .chips .btn { flex-shrink:0; white-space:nowrap; }

            .tw { border:0; background:transparent; overflow:visible; border-radius:0; }
            .main table,.main tbody { display:block; min-width:0; width:100%; }
            .main table tr:first-child:has(>th) { display:none; }
            .main table tr { display:block; background:var(--card); border:1px solid var(--bd); border-radius:14px; padding:10px 14px; margin-bottom:10px; box-shadow:var(--sh); }
            .main .pn table tr { box-shadow:none; background:var(--hd); }
            .main table td { display:grid; grid-template-columns:96px 1fr; gap:8px; padding:6px 0; border:0; font-size:13.5px; word-break:break-word; }
            .main table td::before { content:attr(data-label); color:var(--mu); font-size:12px; font-weight:600; }
            .main table td:first-child { font-weight:700; font-size:14.5px; padding-top:2px; }
            .main table td[data-label=""],.main table td[data-label="Aksi"],.main table td[data-label="Keputusan"] { display:block; }
            .main table td[data-label=""]::before,.main table td[data-label="Aksi"]::before,.main table td[data-label="Keputusan"]::before { display:none; }
            .main table td[data-label="Aksi"],.main table td[data-label="Keputusan"] { border-top:1px dashed var(--bd); margin-top:6px; padding-top:10px; }
            .main table td .btn { margin:0 6px 6px 0; }

            .bars { height:170px; gap:8px; }

            .lg { flex-direction:column; }
            .lg .hero { flex:none; min-height:190px; padding:32px 20px 24px; text-align:center; }
            .lg .logo img { height:82px; }
            .lg .hero p { max-width:300px; font-size:13px; }
            .lg .pane { align-items:flex-start; padding:16px 12px calc(20px + env(safe-area-inset-bottom,0px)); }
            .lg .card { max-width:none; border-radius:14px; }

            .ov { position:fixed; inset:0 auto 0 var(--mobile-edge); width:var(--mobile-width); padding:0; align-items:flex-end; }
            .box { max-width:none; max-height:90vh; max-height:90dvh; border-radius:20px 20px 0 0; padding:22px 16px calc(18px + env(safe-area-inset-bottom,0px)); animation:mobile-sheet-up .25s ease; }
            .box::before { content:""; display:block; width:40px; height:4px; border-radius:4px; background:var(--bd); margin:-10px auto 14px; }
            @keyframes mobile-sheet-up { from { transform:translateY(40px); opacity:0; } }
        }
    </style>
</head>
<body>
@php
    $u = auth()->user();
    $admin = $u->peran === 'admin';
    $on = fn (string ...$pola) => request()->routeIs(...$pola) ? 'on' : '';

    $svg = fn (string $n) => \App\Support\Ikon::svg($n);
    $ic = fn (string $n) => '<span class="ic">'.$svg($n).'</span>';
    $inisial = mb_strtoupper(collect(preg_split('/\s+/', trim($u->nama_seksi)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''));

    if ($admin) {
        $nPinjam = \App\Models\Peminjaman::where('status', 'menunggu')->count();
        $nKembali = \App\Models\Pengembalian::where('status', 'menunggu')->count();
        $nStnk = \App\Models\Kendaraan::jumlahJatuhTempo('stnk_berlaku');
        $nBpkb = \App\Models\Kendaraan::jumlahJatuhTempo('bpkb_berlaku');
        $nData = \App\Models\Kendaraan::jumlahHampirHabis(90);
        $lonceng = ['n' => $nPinjam + $nKembali, 'url' => route($nPinjam || ! $nKembali ? 'admin.peminjaman.index' : 'admin.pengembalian.index')];
    } else {
        $lonceng = [
            'n'   => \App\Models\Peminjaman::where('pengguna_id', $u->id)->whereIn('status', ['disetujui', 'kembali_ditolak'])->count(),
            'url' => route('pegawai.peminjaman.index'),
        ];
    }
@endphp
<div class="app" style="--side:{{ $admin ? '#0B2F5B' : '#0F4C5C' }}">
    <nav class="side">
        <button class="mobile-close" type="button" onclick="bukaMenu(false)" aria-label="Tutup menu">{!! $svg('x') !!}</button>
        <div class="brand">
            <span class="bi">{!! $svg('truck') !!}</span>
            <span><b>KPKNL</b><small>{{ $admin ? 'Panel Admin' : 'Portal Seksi' }} &middot; KPKNL Bandung</small></span>
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

    <div class="col">
        <header class="top">
            <button class="mobile-menu" type="button" onclick="bukaMenu(true)" aria-label="Buka menu">{!! $svg('menu') !!}</button>
            <b class="mobile-title">KPKNL</b>
            <label class="sr">{!! $svg('search') !!}<input type="search" placeholder="{{ $admin ? 'Cari kendaraan, seksi, atau tujuan...' : 'Cari pengajuan, tujuan, atau kendaraan...' }}" oninput="cariTabel(this.value)" aria-label="Cari pada tabel di halaman ini"></label>
            <div class="tr">
                <a class="ib" href="{{ $lonceng['url'] }}" title="{{ $lonceng['n'] ? $lonceng['n'].' perlu ditindaklanjuti' : 'Tidak ada pemberitahuan' }}" aria-label="Pemberitahuan">{!! $svg('bell') !!}@if ($lonceng['n'])<i class="dot">{{ $lonceng['n'] }}</i>@endif</a>
                <a class="av ac" href="{{ route('profil.show') }}" title="Profil" aria-label="Profil">{{ $inisial }}</a>
            </div>
        </header>

        <main class="main">
            @if (session('ok')) <div class="flash ok">{{ session('ok') }}</div> @endif
            @if (session('err')) <div class="flash err">{{ session('err') }}</div> @endif
            @yield('isi')
        </main>
    </div>
    <nav class="mobile-nav noprint" aria-label="Navigasi utama">
        @if ($admin)
            <a href="{{ route('admin.dashboard') }}" class="{{ $on('admin.dashboard') }}">{!! $svg('grid') !!}<span>Dashboard</span></a>
            <a href="{{ route('admin.peminjaman.index') }}" class="{{ $on('admin.peminjaman.*', 'admin.pengembalian.*') }}">{!! $svg('check') !!}<span>Persetujuan</span></a>
            <a href="{{ route('admin.kendaraan.index') }}" class="{{ $on('admin.kendaraan.*') }}">{!! $svg('truck') !!}<span>Kendaraan</span></a>
            <a href="{{ route('admin.servis.index') }}" class="{{ $on('admin.servis.*') }}">{!! $svg('tool') !!}<span>Servis</span></a>
            <button type="button" onclick="bukaMenu(true)">{!! $svg('menu') !!}<span>Menu</span></button>
        @else
            <a href="{{ route('pegawai.beranda') }}" class="{{ $on('pegawai.beranda') }}">{!! $svg('home') !!}<span>Beranda</span></a>
            <a href="{{ route('pegawai.peminjaman.create') }}" class="{{ $on('pegawai.peminjaman.create') }}">{!! $svg('plus') !!}<span>Ajukan</span></a>
            <a href="{{ route('pegawai.peminjaman.index') }}" class="{{ $on('pegawai.peminjaman.index', 'pegawai.pengembalian.*') }}">{!! $svg('clock') !!}<span>Riwayat</span></a>
            <a href="{{ route('profil.show') }}" class="{{ $on('profil.*') }}">{!! $svg('user') !!}<span>Profil</span></a>
        @endif
    </nav>
    <div class="mobile-scrim" onclick="bukaMenu(false)"></div>
</div>

<script>
    
    function cariTabel(q) {
        q = q.trim().toLowerCase();
        document.querySelectorAll('.main table tr').forEach(function (r) {
            if (r.querySelector('th')) return;
            r.style.display = (!q || r.textContent.toLowerCase().indexOf(q) > -1) ? '' : 'none';
        });
    }

    function bukaMenu(open) {
        document.querySelectorAll('.app').forEach(function (app) {
            app.classList.toggle('mobile-open', open);
        });
    }

    function beriLabelTabel() {
        document.querySelectorAll('.main table').forEach(function (table) {
            const headers = Array.from(table.querySelectorAll('tr:first-child th')).map(function (th) {
                return th.textContent.trim();
            });
            if (!headers.length) return;
            table.querySelectorAll('tr:not(:first-child)').forEach(function (row) {
                Array.from(row.children).forEach(function (cell, index) {
                    cell.setAttribute('data-label', headers[index] || '');
                });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', beriLabelTabel);
    window.addEventListener('load', beriLabelTabel);
</script>
</body>
</html>
