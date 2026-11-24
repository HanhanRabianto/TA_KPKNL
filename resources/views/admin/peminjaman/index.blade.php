@extends('layouts.app')
@section('judul', 'Persetujuan Peminjaman')

@section('isi')
    @include('partials.judul', [
        'judul' => 'Persetujuan Peminjaman',
        'sub'   => 'Tinjau pengajuan peminjaman yang menunggu keputusan',
        'aksi'  => '<button class="btn s" type="button" onclick="window.print()">Cetak PDF</button>',
    ])

    @include('partials.kartu', ['kartu' => [
        ['Menunggu', $menunggu->count(), '#a76400'],
        ['Disetujui', $disetujui->count(), '#127a3d'],
        ['Ditolak', $ditolak->count(), '#b42318'],
    ]])

    <div class="card">
        <h3 style="margin-top:0">Menunggu Persetujuan ({{ $menunggu->count() }})</h3>
        <div class="tw">
            <table>
                <tr><th>Seksi</th><th>Tujuan</th><th>Tanggal</th><th>Kendaraan</th><th>Pengemudi</th><th>Ketersediaan</th><th class="no-print">Keputusan</th></tr>
                @forelse ($menunggu as $p)
                    @php
                        $ada = $p->kendaraan && $p->kendaraan->status === 'Tersedia' && (! $p->pengemudi || $p->pengemudi->status === 'Tersedia');
                    @endphp
                    <tr>
                        <td>{{ $p->pengguna->nama_seksi }}<br><span class="note">{{ $p->petugas }}</span></td>
                        <td>{{ $p->tujuan }}<br><span class="note">{{ $p->lokasi_tujuan }}, {{ $p->menginap ? 'menginap' : 'tidak menginap' }}</span></td>
                        <td>{{ $p->mulai->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->kendaraan?->nama ?? '-' }}<br><span class="note">{{ $p->penumpang }} penumpang{{ $p->catatan ? ' - '.$p->catatan : '' }}</span></td>
                        <td>{{ $p->pengemudi?->nama ?? 'Tanpa pengemudi' }}</td>
                        <td><span class="b {{ $ada ? 'ok' : 'er' }}">{{ $ada ? 'Tersedia' : 'Perlu ditinjau' }}</span></td>
                        <td class="no-print"><a class="btn" href="{{ route('admin.peminjaman.show', $p) }}">Verifikasi</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="note" style="text-align:center; padding:20px">Tidak ada pengajuan yang menunggu persetujuan.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Disetujui ({{ $disetujui->count() }})</h3>
        <div class="tw">
            <table>
                <tr><th>Seksi</th><th>Tujuan</th><th>Tanggal</th><th>Kendaraan</th><th>Pengemudi</th><th>Status</th><th class="no-print">Surat tugas</th></tr>
                @forelse ($disetujui as $p)
                    <tr>
                        <td>{{ $p->pengguna->nama_seksi }}<br><span class="note">{{ $p->petugas }}</span></td>
                        <td>{{ $p->tujuan }}</td>
                        <td>{{ $p->mulai->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->kendaraan?->nama ?? '-' }}</td>
                        <td>{{ $p->pengemudi?->nama ?? 'Tanpa pengemudi' }}</td>
                        <td><span class="b {{ $p->kelas_status }}">{{ $p->label_status }}</span></td>
                        <td class="no-print"><a href="{{ route('surat-tugas.show', $p) }}" target="_blank">{{ $p->nomor_surat }}</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="note" style="text-align:center; padding:20px">Belum ada pengajuan yang disetujui.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Ditolak ({{ $ditolak->count() }})</h3>
        <div class="tw">
            <table>
                <tr><th>Seksi</th><th>Tujuan</th><th>Tanggal perjalanan</th><th>Preferensi</th><th>Alasan penolakan</th><th>Status</th></tr>
                @forelse ($ditolak as $p)
                    <tr>
                        <td>{{ $p->pengguna->nama_seksi }}<br><span class="note">{{ $p->petugas }}</span></td>
                        <td>{{ $p->tujuan }}</td>
                        <td>{{ $p->mulai->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->catatan ?: '-' }}</td>
                        <td>{{ $p->alasan_tolak }}</td>
                        <td><span class="b er">Ditolak</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="note" style="text-align:center; padding:20px">Belum ada pengajuan yang ditolak.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
