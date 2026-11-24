@extends('layouts.app')
@section('judul', 'BBM')

@section('isi')
    <div class="bar no-print">
        <div>
            <h2 style="margin:0">Laporan BBM</h2>
            <span class="note">Catatan pengisian BBM yang diisi manual oleh admin</span>
        </div>
        <div>
            <a class="btn" href="{{ route('admin.bbm.create') }}">+ Catat BBM</a>
            <button class="btn s" type="button" onclick="window.print()">Cetak / Simpan PDF</button>
        </div>
    </div>

    <form method="GET" class="card no-print" style="display:flex; gap:10px; align-items:end; flex-wrap:wrap">
        <div>
            <label for="bulan" style="margin-top:0">Bulan (tanggal pengembalian kupon)</label>
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
        <a class="btn s" href="{{ route('admin.bbm.index') }}">Reset</a>
    </form>

    <div class="print-only" style="margin-bottom:12px">
        <div style="font-size:12px">KEMENTERIAN KEUANGAN REPUBLIK INDONESIA<br>KANTOR PELAYANAN KEKAYAAN NEGARA DAN LELANG BANDUNG</div>
        <h2 style="margin:8px 0 0">Laporan BBM</h2>
        <div style="font-size:12px">Periode: {{ $periode }} | Dicetak: {{ now()->locale('id')->translatedFormat('d F Y H:i') }}</div>
    </div>

    <div class="grid">
        <div class="card"><div class="num" style="font-size:22px">{{ $daftar->count() }}</div><div class="lbl">Jumlah catatan</div></div>
        <div class="card"><div class="num" style="font-size:22px">{{ $daftar->sum('jumlah_kupon') }}</div><div class="lbl">Total kupon</div></div>
        <div class="card"><div class="num" style="font-size:22px">Rp {{ number_format($daftar->sum('total'), 0, ',', '.') }}</div><div class="lbl">Total biaya BBM</div></div>
    </div>

    <div class="card">
        <div class="tw">
            <table>
                <tr>
                    <th>Tanggal pemberian kupon</th><th>Tanggal pengembalian kupon</th><th>Petugas</th><th>Jumlah kupon</th>
                    <th>Struk SPBU</th><th>Mobil</th><th>Total</th><th class="no-print">Aksi</th>
                </tr>
                @forelse ($daftar as $b)
                    <tr>
                        <td>{{ $b->tgl_pemberian->format('d/m/Y') }}</td>
                        <td>{{ $b->tgl_pengembalian->format('d/m/Y') }}</td>
                        <td>{{ $b->petugas }}</td>
                        <td>{{ $b->jumlah_kupon }}</td>
                        <td>
                            <a class="no-print" href="{{ route('admin.bbm.struk', $b) }}" target="_blank">Lihat struk</a>
                            <span class="print-only">Terlampir</span>
                        </td>
                        <td>{{ $b->kendaraan->plat }}<br><span class="note">{{ $b->kendaraan->jenis }}</span></td>
                        <td>Rp {{ number_format($b->total, 0, ',', '.') }}</td>
                        <td class="no-print">
                            <form method="POST" action="{{ route('admin.bbm.destroy', $b) }}" onsubmit="return confirm('Hapus catatan BBM ini?')">
                                @csrf @method('DELETE')
                                <button class="btn d" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="note" style="text-align:center; padding:24px">Belum ada catatan BBM pada filter ini.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
