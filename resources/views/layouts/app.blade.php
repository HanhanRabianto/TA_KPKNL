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
        header { background:var(--pr); color:#fff; padding:12px 20px; display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
        header h1 { font-size:16px; margin:0; flex:1; }
        header small { display:block; font-weight:400; opacity:.8; }
        header form { margin:0; }
        .wrap { display:flex; min-height:calc(100vh - 56px); }
        aside { width:210px; background:var(--card); border-right:1px solid var(--bd); padding:14px 10px; flex-shrink:0; }
        aside a { display:block; padding:8px 12px; border-radius:8px; color:var(--mu); text-decoration:none; margin-bottom:2px; }
        aside a.on { background:var(--pr2); color:var(--pr); font-weight:600; }
        aside .grp { font-size:11px; text-transform:uppercase; color:var(--mu); margin:14px 12px 4px; }
        main { flex:1; padding:20px; max-width:1100px; }
        h2 { font-size:18px; margin:0 0 14px; } h3 { font-size:15px; margin:18px 0 8px; }
        .grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(170px,1fr)); gap:12px; margin-bottom:16px; }
        .card { background:var(--card); border:1px solid var(--bd); border-radius:10px; padding:16px; margin-bottom:14px; }
        .grid .card { margin:0; }
        .num { font-size:28px; font-weight:700; color:var(--pr); } .lbl { color:var(--mu); font-size:12px; }
        .btn { display:inline-block; background:var(--pr); color:#fff; border:0; padding:8px 14px; border-radius:8px; cursor:pointer; font-size:13px; text-decoration:none; }
        .btn.s { background:var(--pr2); color:var(--pr); } .btn.d { background:var(--er); color:#fff; } .btn.w { background:#fff; color:var(--pr); }
        .bar { display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:14px; }
        .tw { overflow-x:auto; } table { width:100%; border-collapse:collapse; font-size:13px; }
        th, td { text-align:left; padding:9px 8px; border-bottom:1px solid var(--bd); vertical-align:top; }
        th { color:var(--mu); font-size:12px; font-weight:600; }
        .b { display:inline-block; padding:2px 9px; border-radius:99px; font-size:12px; font-weight:600; }
        .b.ok { background:var(--okb); color:var(--ok); } .b.wr { background:var(--wrb); color:var(--wr); } .b.er { background:var(--erb); color:var(--er); } .b.in { background:var(--pr2); color:var(--pr); }
        .note { color:var(--mu); font-size:12px; }
        .flash { padding:10px 12px; border-radius:8px; margin-bottom:14px; }
        .flash.ok { background:var(--okb); color:var(--ok); } .flash.err { background:var(--erb); color:var(--er); }
        label { display:block; font-size:12px; color:var(--mu); margin:12px 0 4px; }
        textarea { width:100%; padding:9px; border:1px solid var(--bd); border-radius:8px; font:inherit; background:#fff; }
        .tabs { display:flex; gap:6px; margin-bottom:14px; flex-wrap:wrap; }
        input, select { width:100%; padding:9px; border:1px solid var(--bd); border-radius:8px; font:inherit; background:#fff; }
        input[readonly] { background:#eef1f6; color:var(--mu); }
        .row { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:0 14px; }
        .ferr { color:var(--er); font-size:12px; margin-top:3px; }
        @media (max-width:760px) { .wrap { flex-direction:column; } aside { width:100%; border-right:0; border-bottom:1px solid var(--bd); } }
    </style>
</head>
<body>
    <header>
        <h1>SIMOVDIN <small>Monitoring Kendaraan Dinas KPKNL Bandung</small></h1>
        <span>{{ auth()->user()->nama_seksi }} ({{ auth()->user()->peran }})</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn w" type="submit">Keluar</button>
        </form>
    </header>
    <div class="wrap">
        @php
            $adalahAdmin = auth()->user()->peran === 'admin';
            $nMenunggu = $adalahAdmin ? \App\Models\Peminjaman::where('status', 'menunggu')->count() : 0;
            $nKembali = $adalahAdmin ? \App\Models\Pengembalian::where('status', 'menunggu')->count() : 0;
        @endphp
        <aside>
            @if ($adalahAdmin)
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}">Dashboard</a>
                <a href="{{ route('admin.peminjaman.index') }}" class="{{ request()->routeIs('admin.peminjaman.*') ? 'on' : '' }}">
                    Verifikasi Pinjaman
                    @if ($nMenunggu) <span class="b er" style="float:right">{{ $nMenunggu }}</span> @endif
                </a>
                <a href="{{ route('admin.pengembalian.index') }}" class="{{ request()->routeIs('admin.pengembalian.*') ? 'on' : '' }}">
                    Verifikasi Pengembalian
                    @if ($nKembali) <span class="b er" style="float:right">{{ $nKembali }}</span> @endif
                </a>
                <div class="grp">Data</div>
                <a href="{{ route('admin.kendaraan.index') }}" class="{{ request()->routeIs('admin.kendaraan.*') ? 'on' : '' }}">Kendaraan</a>
                <a href="{{ route('admin.pengemudi.index') }}" class="{{ request()->routeIs('admin.pengemudi.*') ? 'on' : '' }}">Pengemudi</a>
                <a href="{{ route('admin.pengguna.index') }}" class="{{ request()->routeIs('admin.pengguna.*') ? 'on' : '' }}">Pengguna</a>
            @else
                <a href="{{ route('pegawai.beranda') }}" class="{{ request()->routeIs('pegawai.beranda') ? 'on' : '' }}">Beranda</a>
                <a href="{{ route('pegawai.peminjaman.create') }}" class="{{ request()->routeIs('pegawai.peminjaman.create') ? 'on' : '' }}">Ajukan Pinjaman</a>
                <a href="{{ route('pegawai.peminjaman.index') }}" class="{{ request()->routeIs('pegawai.peminjaman.index') ? 'on' : '' }}">Riwayat Pinjaman</a>
                <a href="{{ route('pegawai.pengembalian.index') }}" class="{{ request()->routeIs('pegawai.pengembalian.*') ? 'on' : '' }}">Riwayat Pengembalian</a>
            @endif
        </aside>
        <main>
            @if (session('ok')) <div class="flash ok">{{ session('ok') }}</div> @endif
            @if (session('err')) <div class="flash err">{{ session('err') }}</div> @endif
            @yield('isi')
        </main>
    </div>
</body>
</html>
