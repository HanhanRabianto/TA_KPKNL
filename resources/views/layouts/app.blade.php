<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'SIMOVDIN') - KPKNL Bandung</title>
    <style>
        :root { --pr:#0b3d91; --bg:#f4f6fa; --card:#fff; --tx:#1b2433; --mu:#6b7686; --bd:#dde3ec; }
        * { box-sizing: border-box; }
        body { margin:0; font:14px/1.5 system-ui,-apple-system,"Segoe UI",sans-serif; background:var(--bg); color:var(--tx); }
        header { background:var(--pr); color:#fff; padding:12px 20px; display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
        header h1 { font-size:16px; margin:0; flex:1; }
        header small { display:block; font-weight:400; opacity:.8; }
        header form { margin:0; }
        .btn { background:#fff; color:var(--pr); border:0; padding:7px 14px; border-radius:6px; cursor:pointer; font-size:13px; }
        main { max-width:1100px; margin:0 auto; padding:20px; }
        .grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(170px,1fr)); gap:12px; margin-bottom:16px; }
        .card { background:var(--card); border:1px solid var(--bd); border-radius:10px; padding:16px; }
        .num { font-size:28px; font-weight:700; color:var(--pr); }
        .lbl { color:var(--mu); font-size:12px; }
    </style>
</head>
<body>
    <header>
        <h1>SIMOVDIN <small>Monitoring Kendaraan Dinas KPKNL Bandung</small></h1>
        <span>{{ auth()->user()->nama_seksi }} ({{ auth()->user()->peran }})</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn" type="submit">Keluar</button>
        </form>
    </header>
    <main>
        @yield('isi')
    </main>
</body>
</html>
