<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Tugas {{ $p->nomor_surat }}</title>
    <style>
        body { font:14px/1.6 "Times New Roman", Times, serif; color:#000; background:#eee; margin:0; }
        .kertas { width:210mm; min-height:297mm; margin:16px auto; background:#fff; padding:22mm 24mm; box-sizing:border-box; }
        .kop { text-align:center; border-bottom:3px double #000; padding-bottom:8px; margin-bottom:18px; line-height:1.3; }
        .kop strong { display:block; font-size:16px; }
        h1 { text-align:center; font-size:16px; text-decoration:underline; margin:0; }
        .no { text-align:center; margin:2px 0 18px; }
        table.d { border-collapse:collapse; margin:8px 0 8px 8mm; }
        table.d td { padding:2px 8px 2px 0; vertical-align:top; }
        ol { margin:4px 0 8px; }
        .ttd { margin-top:36px; margin-left:55%; text-align:left; }
        .aksi { text-align:center; margin:12px; }
        .aksi button, .aksi a { font:14px system-ui, sans-serif; padding:8px 16px; margin:0 4px; border:1px solid #0b3d91; background:#0b3d91; color:#fff; border-radius:6px; cursor:pointer; text-decoration:none; }
        .aksi a { background:#fff; color:#0b3d91; }
        @media print { body { background:#fff; } .kertas { margin:0; width:auto; min-height:0; padding:0; } .aksi { display:none; } }
    </style>
</head>
<body>
    <div class="aksi">
        <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
        <a href="javascript:history.back()">Kembali</a>
    </div>

    <div class="kertas">
        <div class="kop">
            KEMENTERIAN KEUANGAN REPUBLIK INDONESIA<br>
            DIREKTORAT JENDERAL KEKAYAAN NEGARA<br>
            <strong>KANTOR PELAYANAN KEKAYAAN NEGARA DAN LELANG BANDUNG</strong>
        </div>

        <h1>SURAT TUGAS</h1>
        <div class="no">Nomor: {{ $p->nomor_surat }}</div>

        <p>Kepala Kantor Pelayanan Kekayaan Negara dan Lelang Bandung dengan ini menugaskan pegawai berikut:</p>
        <ol>
            @foreach (array_filter(array_map('trim', explode(',', $p->petugas))) as $nama)
                <li>{{ $nama }}</li>
            @endforeach
        </ol>

        <p>untuk melaksanakan perjalanan dinas dengan rincian:</p>
        <table class="d">
            <tr><td>Unit / seksi</td><td>:</td><td>{{ $p->pengguna->nama_seksi }}</td></tr>
            <tr><td>Tujuan</td><td>:</td><td>{{ $p->tujuan }}</td></tr>
            <tr><td>Lokasi</td><td>:</td><td>{{ $p->lokasi_tujuan }} ({{ $p->menginap ? 'menginap' : 'tidak menginap' }})</td></tr>
            <tr><td>Waktu berangkat</td><td>:</td><td>{{ $p->mulai->locale('id')->translatedFormat('l, d F Y') }}, pukul {{ $p->mulai->format('H:i') }} WIB</td></tr>
            <tr><td>Kendaraan dinas</td><td>:</td><td>{{ $p->kendaraan->jenis }}, nomor polisi {{ $p->kendaraan->plat }}</td></tr>
            <tr><td>Pengemudi</td><td>:</td><td>{{ $p->pengemudi?->nama ?? 'Tanpa pengemudi (dikemudikan sendiri)' }}</td></tr>
            <tr><td>Jumlah penumpang</td><td>:</td><td>{{ $p->penumpang }} orang</td></tr>
        </table>

        <p>Demikian surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab. Setelah tugas selesai, kendaraan dikembalikan dan laporan pengembalian disampaikan melalui sistem.</p>

        <div class="ttd">
            Bandung, {{ ($p->diputuskan_pada ?? now())->locale('id')->translatedFormat('d F Y') }}<br>
            Kepala Bagian Umum,
            <br><br><br><br>
            (........................................)
        </div>
    </div>
</body>
</html>
