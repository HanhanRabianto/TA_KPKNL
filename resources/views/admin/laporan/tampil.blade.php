@extends('layouts.app')
@section('judul', $laporan['judul'])

@section('isi')
    <div class="bar no-print">
        <div>
            <h2 style="margin:0">{{ $laporan['judul'] }}</h2>
            <span class="note">{{ $laporan['sub'] }}</span>
        </div>
        <button class="btn" type="button" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <form method="GET" class="card no-print" style="display:flex; gap:10px; align-items:end; flex-wrap:wrap">
        <div>
            <label for="bulan" style="margin-top:0">Bulan</label>
            <input id="bulan" type="month" name="bulan" value="{{ $bulan }}">
        </div>
        <div style="min-width:220px">
            <label for="kendaraan_id" style="margin-top:0">Kendaraan</label>
            <select id="kendaraan_id" name="kendaraan_id">
                <option value="">Semua kendaraan</option>
                @foreach ($kendaraan as $k)
                    <option value="{{ $k->id }}" @selected($kid === $k->id)>{{ $k->plat }} - {{ $k->jenis }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn" type="submit">Terapkan</button>
        <a class="btn s" href="{{ route('admin.laporan.tampil', $jenis) }}">Reset</a>
    </form>

    {{-- kop laporan, hanya tampil saat dicetak --}}
    <div class="print-only" style="margin-bottom:12px">
        <div style="font-size:12px">KEMENTERIAN KEUANGAN REPUBLIK INDONESIA<br>KANTOR PELAYANAN KEKAYAAN NEGARA DAN LELANG BANDUNG</div>
        <h2 style="margin:8px 0 0">{{ $laporan['judul'] }}</h2>
        <div style="font-size:12px">Periode: {{ $laporan['periode'] }} | Dicetak: {{ now()->locale('id')->translatedFormat('d F Y H:i') }}</div>
    </div>

    <div class="grid">
        @foreach ($laporan['kartu'] as $label => $nilai)
            <div class="card"><div class="num" style="font-size:22px">{{ $nilai }}</div><div class="lbl">{{ $label }}</div></div>
        @endforeach
    </div>

    <div class="card">
        <div class="tw">
            <table>
                <tr>@foreach ($laporan['kolom'] as $k) <th>{{ $k }}</th> @endforeach</tr>
                @forelse ($laporan['baris'] as $baris)
                    <tr>@foreach ($baris as $sel) <td>{{ $sel }}</td> @endforeach</tr>
                @empty
                    <tr><td colspan="{{ count($laporan['kolom']) }}" class="note" style="text-align:center; padding:24px">Tidak ada data pada filter ini.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
