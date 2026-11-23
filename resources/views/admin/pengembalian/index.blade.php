@extends('layouts.app')
@section('judul', 'Verifikasi Pengembalian')

@section('isi')
    <h2>Verifikasi Pengajuan Pengembalian</h2>

    <div class="tabs">
        @foreach (['menunggu' => 'Menunggu', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $k => $nama)
            <a class="btn {{ $tab === $k ? '' : 's' }}" href="{{ route('admin.pengembalian.index', ['tab' => $k]) }}">{{ $nama }} ({{ $jumlah[$k] ?? 0 }})</a>
        @endforeach
    </div>

    <div class="card">
        <div class="tw">
            <table>
                <tr><th>Seksi</th><th>Kendaraan</th><th>Odometer</th><th>Kembali</th><th>Kondisi</th><th>Keterangan / Aksi</th></tr>
                @forelse ($daftar as $r)
                    @php $p = $r->peminjaman; @endphp
                    <tr>
                        <td><strong>{{ $p->pengguna->nama_seksi }}</strong><br><span class="note">{{ $p->tujuan }}</span></td>
                        <td>{{ $p->kendaraan->plat }}<br><span class="note">{{ $p->kendaraan->jenis }}</span></td>
                        <td>{{ $r->odometer_awal }} &rarr; {{ $r->odometer_akhir }}<br><span class="note">{{ $r->jarak }} km</span></td>
                        <td>{{ $r->kembali_pada->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="b {{ $r->kondisi === 'Baik' ? 'ok' : ($r->kondisi === 'Rusak' ? 'er' : 'wr') }}">{{ $r->kondisi }}</span>
                            @if ($r->catatan_kondisi)<br><span class="note">{{ $r->catatan_kondisi }}</span>@endif
                        </td>
                        <td style="white-space:nowrap">
                            @if ($r->status === 'menunggu')
                                <form method="POST" action="{{ route('admin.pengembalian.terima', $r) }}" style="display:inline"
                                      onsubmit="return confirm('Terima pengembalian ini? Kendaraan akan kembali Tersedia.')">
                                    @csrf
                                    <button class="btn" type="submit">Terima</button>
                                </form>
                                <a class="btn d" href="{{ route('admin.pengembalian.tolak', $r) }}">Tolak</a>
                            @elseif ($r->status === 'ditolak')
                                <span class="note">Alasan: {{ $r->alasan_tolak }}</span>
                            @else
                                <span class="note">Diterima {{ $r->diputuskan_pada?->format('d/m/Y H:i') }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="note" style="text-align:center; padding:24px">Tidak ada data pada tab ini.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
