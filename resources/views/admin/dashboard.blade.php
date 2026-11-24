@extends('layouts.app')
@section('judul', 'Dashboard')

@php
    $maks = max(1, collect($statistik)->max('n'));
@endphp

@section('isi')
    @include('partials.judul', ['judul' => 'Dashboard', 'sub' => 'Ringkasan kendaraan, pengajuan, pengemudi, dan seksi'])

    <h3>Kendaraan</h3>
    @include('partials.kartu', ['kartu' => [
        ['Kendaraan', $totalKendaraan, '#0b3d91'],
        ['Kendaraan tersedia', $tersedia, '#127a3d'],
        ['Sedang dipakai', $dipakai, '#0b3d91'],
        ['Sedang perbaikan', $diservis, '#a76400'],
        ['STNK jatuh tempo 30 hari', $stnkTempo, '#a76400'],
        ['BPKB jatuh tempo 30 hari', $bpkbTempo, '#a76400'],
    ]])

    <h3>Pengajuan, pengemudi, dan seksi</h3>
    @include('partials.kartu', ['kartu' => [
        ['Pengajuan pinjaman', $menunggu->count(), '#a76400'],
        ['Persetujuan pengembalian', $verifikasi->count(), '#a76400'],
        ['Total driver', $totalPengemudi, '#0b3d91'],
        ['Driver tersedia', $pengemudiSedia, '#127a3d'],
        ['Total seksi', $totalSeksi, '#0b3d91'],
    ]])

    {{-- Pengajuan menunggu --}}
    <div class="card">
        <h3 style="margin-top:0">Pengajuan Peminjaman Menunggu ({{ $menunggu->count() }})</h3>
        <div class="tw">
            <table>
                <tr><th>Seksi</th><th>Tujuan</th><th>Waktu</th><th>Kendaraan</th><th>Pengemudi</th><th>Aksi</th></tr>
                @forelse ($menunggu as $l)
                    <tr>
                        <td>{{ $l->pengguna->nama_seksi }}<br><span class="note">{{ $l->petugas }}</span></td>
                        <td>{{ $l->tujuan }}<br><span class="note">{{ $l->lokasi_tujuan }}, {{ $l->menginap ? 'menginap' : 'tidak menginap' }}</span></td>
                        <td>{{ $l->mulai->format('d/m/Y H:i') }}</td>
                        <td>{{ $l->kendaraan?->nama ?? '-' }}<br><span class="note">{{ $l->penumpang }} penumpang</span></td>
                        <td>{{ $l->pengemudi?->nama ?? 'Tanpa pengemudi' }}</td>
                        <td><a class="btn" href="{{ route('admin.peminjaman.show', $l) }}">Verifikasi</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="note" style="text-align:center; padding:20px">Tidak ada pengajuan yang menunggu persetujuan.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    {{-- Sedang dipinjam --}}
    <div class="card">
        <h3 style="margin-top:0">Sedang Dipinjam ({{ $dipinjam->count() }})</h3>
        <div class="tw">
            <table>
                <tr><th>Kendaraan</th><th>Dipinjam oleh</th><th>Petugas</th><th>Tujuan</th><th>Pengemudi</th><th>Waktu pinjam</th><th>Status</th></tr>
                @forelse ($dipinjam as $l)
                    @php
                        [$teks, $kls] = match (true) {
                            $l->status === 'kembali_ditolak' => ['Pengembalian ditolak', 'er'],
                            $l->status === 'verifikasi'      => ['Menunggu verifikasi', 'wr'],
                            $l->mulai->isFuture()            => ['Belum mulai', 'in'],
                            default                          => ['Sedang berjalan', 'ok'],
                        };
                    @endphp
                    <tr>
                        <td>{{ $l->kendaraan->nama }}</td>
                        <td>{{ $l->pengguna->nama_seksi }}</td>
                        <td>{{ $l->petugas }}</td>
                        <td>{{ $l->tujuan }}</td>
                        <td>{{ $l->pengemudi?->nama ?? 'Tanpa pengemudi' }}</td>
                        <td>{{ $l->mulai->format('d/m/Y H:i') }}</td>
                        <td><span class="b {{ $kls }}">{{ $teks }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="note" style="text-align:center; padding:20px">Tidak ada kendaraan yang sedang dipinjam.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    {{-- Pengembalian menunggu --}}
    <div class="card">
        <h3 style="margin-top:0">Persetujuan Pengembalian Menunggu ({{ $verifikasi->count() }})</h3>
        <div class="tw">
            <table>
                <tr><th>Seksi</th><th>Kendaraan</th><th>Tujuan</th><th>Waktu kembali</th><th>Laporan</th><th>Aksi</th></tr>
                @forelse ($verifikasi as $r)
                    <tr>
                        <td>{{ $r->peminjaman->pengguna->nama_seksi }}</td>
                        <td>{{ $r->peminjaman->kendaraan->nama }}</td>
                        <td>{{ $r->peminjaman->tujuan }}</td>
                        <td>{{ $r->kembali_pada->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($r->jarak, 0, ',', '.') }} km, {{ $r->kondisi }}</td>
                        <td><a class="btn" href="{{ route('admin.pengembalian.show', $r) }}">Verifikasi</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="note" style="text-align:center; padding:20px">Tidak ada laporan pengembalian yang menunggu.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    {{-- Sedang diservis --}}
    <div class="card">
        <h3 style="margin-top:0">Sedang Diservis ({{ $servisBerjalan->count() }})</h3>
        <div class="tw">
            <table>
                <tr><th>Kendaraan</th><th>Jenis servis</th><th>Bengkel</th><th>Masuk servis</th><th>Estimasi selesai</th><th>Lama diservis</th><th>Biaya (estimasi)</th><th>Status</th></tr>
                @forelse ($servisBerjalan as $s)
                    @php $lewat = $s->estimasi_selesai && $s->estimasi_selesai->lt(now()->startOfDay()); @endphp
                    <tr>
                        <td>{{ $s->kendaraan->nama }}</td>
                        <td>{{ $s->jenis }}</td>
                        <td>{{ $s->bengkel ?: '-' }}</td>
                        <td>{{ $s->tgl_masuk->format('d/m/Y') }}</td>
                        <td>{{ $s->estimasi_selesai?->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ \App\Http\Controllers\Admin\DashboardController::durasi($s->tgl_masuk) }}</td>
                        <td>Rp {{ number_format($s->biaya, 0, ',', '.') }}</td>
                        <td><span class="b {{ $lewat ? 'er' : 'wr' }}">{{ $lewat ? 'Lewat estimasi' : 'Sedang diservis' }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="note" style="text-align:center; padding:20px">Tidak ada kendaraan yang sedang diservis.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    {{-- Pajak jatuh tempo --}}
    <div class="card">
        <h3 style="margin-top:0">Pajak Jatuh Tempo ({{ $pajakTempo->count() }})</h3>
        <div class="tw">
            <table>
                <tr><th>Kendaraan</th><th>Jenis</th><th>Berlaku sampai</th><th>Sisa waktu</th><th>Aksi</th></tr>
                @forelse ($pajakTempo as $r)
                    <tr>
                        <td>{{ $r['k']->nama }}</td>
                        <td>{{ $r['jenis'] }}</td>
                        <td>{{ $r['tgl']->format('d/m/Y') }}</td>
                        <td>
                            @if ($r['sisa'] < 0) <span class="b er">Lewat {{ abs($r['sisa']) }} hari</span>
                            @else <span class="b wr">{{ $r['sisa'] }} hari lagi</span> @endif
                        </td>
                        <td><a class="btn s" href="{{ route('admin.pajak.index', $r['slug']) }}">Buka {{ $r['jenis'] }}</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="note" style="text-align:center; padding:20px">Tidak ada pajak yang jatuh tempo dalam 30 hari.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="card">
        <h3 style="margin-top:0">Statistik Peminjaman Bulanan {{ $tahun }}</h3>
        <div class="bars">
            @foreach ($statistik as $s)
                <div>
                    <span>{{ $s['n'] }}</span>
                    <i style="height:{{ $s['n'] ? max(6, round($s['n'] / $maks * 140)) : 2 }}px"></i>
                    {{ $s['nama'] }}
                </div>
            @endforeach
        </div>
    </div>
@endsection
