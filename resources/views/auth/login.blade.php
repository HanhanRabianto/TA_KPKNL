<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - SIMOVDIN KPKNL Bandung</title>
    <style>
        * { box-sizing: border-box; }
        body { margin:0; min-height:100vh; display:grid; place-items:center; background:#f4f6fa; font:14px/1.5 system-ui,-apple-system,"Segoe UI",sans-serif; color:#1b2433; }
        .box { width:100%; max-width:380px; background:#fff; border:1px solid #dde3ec; border-radius:12px; padding:28px; }
        h1 { font-size:20px; margin:0 0 4px; color:#0b3d91; }
        p.sub { margin:0 0 20px; color:#6b7686; font-size:13px; }
        label { display:block; font-size:12px; color:#6b7686; margin:12px 0 4px; }
        input[type=text], input[type=password] { width:100%; padding:10px; border:1px solid #dde3ec; border-radius:8px; font:inherit; }
        .cek { display:flex; align-items:center; gap:6px; margin-top:12px; font-size:13px; }
        .err { background:#fde4e1; color:#b42318; padding:8px 10px; border-radius:8px; margin-bottom:10px; font-size:13px; }
        button { width:100%; margin-top:18px; padding:11px; background:#0b3d91; color:#fff; border:0; border-radius:8px; font-size:14px; cursor:pointer; }
    </style>
</head>
<body>
    <div class="box">
        <h1>SIMOVDIN</h1>
        <p class="sub">Monitoring dan Peminjaman Kendaraan Dinas KPKNL Bandung</p>

        @if ($errors->any())
            <div class="err">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" autofocus required>

            <label for="password">Kata sandi</label>
            <input type="password" id="password" name="password" required>

            <label class="cek"><input type="checkbox" name="ingat" value="1"> Ingat saya</label>

            <button type="submit">Masuk</button>
        </form>
    </div>
</body>
</html>
