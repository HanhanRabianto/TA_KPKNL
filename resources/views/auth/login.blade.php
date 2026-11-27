<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - SIMOVDIN KPKNL Bandung</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root { --pr:#0B2F5B; --tx:#1B2433; --mu:#4A5568; --bd:#DDE3EC; --pg:#F4F6FA; --rd:#FBE0E0; --rdt:#7A1B1B; }
        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; }
        body { font-family: "Plus Jakarta Sans", system-ui, -apple-system, "Segoe UI", sans-serif; background: var(--pg); color: var(--tx); font-size: 14px; }
        .lg { display: flex; min-height: 100vh; min-height: 100dvh; }

        
        .hero { flex: 1; background: var(--pr); color: #fff; padding: 48px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; gap: 12px; }
        .logo { margin-bottom: 10px; }
        .logo img { display: block; height: 200px; width: auto; max-width: 100%; margin: 0 auto; }
        .hero h1 { margin: 0; font-size: 38px; font-weight: 800; }
        .hero p { max-width: 440px; color: #D6E2F2; line-height: 1.5; margin: 0 auto; }

        
        .pane { flex: 1; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .card { width: 100%; max-width: 400px; background: #fff; border: 1px solid var(--bd); border-radius: 16px; padding: 26px; box-shadow: 0 1px 2px rgba(16,24,40,.04), 0 6px 16px rgba(16,24,40,.05); }
        .card h2 { margin: 0; font-size: 24px; font-weight: 800; color: var(--pr); }
        .sub { color: var(--mu); font-size: 12px; margin: 4px 0 16px; }
        .f { display: flex; flex-direction: column; gap: 6px; margin-bottom: 12px; }
        label { font-size: 13px; font-weight: 500; }
        input { width: 100%; min-height: 44px; border: 1px solid var(--bd); border-radius: 10px; padding: 10px 12px; font: inherit; color: var(--tx); background: #fff; }
        input:focus-visible, button:focus-visible { outline: 3px solid #6FA8E8; outline-offset: 2px; }
        .err { background: var(--rd); color: var(--rdt); padding: 10px 12px; border-radius: 8px; font-size: 13px; margin: 8px 0; }
        .btn { width: 100%; min-height: 44px; background: var(--pr); color: #fff; border: 0; border-radius: 10px; font: inherit; font-weight: 700; cursor: pointer; margin-top: 4px; }
        .note { color: var(--mu); font-size: 12px; margin-top: 12px; }

        @media (max-width: 800px) {
            .lg { flex-direction: column; }
            .hero { flex:0 0 auto; min-height:220px; padding:24px 20px; }
            .logo { margin-bottom:6px; }
            .logo img { height:96px; }
            .hero p { max-width:340px; font-size:13px; }
            .pane { align-items:flex-start; flex:1; padding:16px 12px calc(20px + env(safe-area-inset-bottom, 0)); }
            .card { max-width:none; padding:20px 16px; border-radius:14px; }
            .card h2 { font-size:22px; }
        }
        @media (max-width:480px) {
            .hero { min-height:190px; }
            .logo img { height:82px; }
            .hero p { max-width:300px; }
        }
    </style>
</head>
<body>
    <div class="lg">
        <section class="hero">
            <div class="logo">
                <img src="{{ asset('images/logo-kpknl.png') }}" alt="Logo KPKNL, Kementerian Keuangan Republik Indonesia">
            </div>
            <p>Sistem Informasi Monitoring Kendaraan Dinas. Ajukan, pantau, dan laporkan pemakaian kendaraan dinas dalam satu tempat.</p>
        </section>

        <section class="pane">
            <div class="card">
                <h2>Masuk</h2>
                <div class="sub">Gunakan username seksi dan kata sandi.</div>

                @if ($errors->any())
                    <div class="err" role="alert">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="f">
                        <label for="username">Username</label>
                        <input id="username" type="text" name="username" value="{{ old('username') }}" autocomplete="username" autofocus required>
                    </div>
                    <div class="f">
                        <label for="password">Kata sandi</label>
                        <input id="password" type="password" name="password" autocomplete="current-password" required>
                    </div>

                    <button class="btn" type="submit">Masuk</button>
                </form>

                <div class="note">Lupa kata sandi? Hubungi admin untuk mereset akun Anda.</div>
            </div>
        </section>
    </div>
</body>
</html>
