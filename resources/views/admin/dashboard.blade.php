@extends('layouts.app')
@section('judul', 'Dashboard')

@php
    $maks = max(1, collect($statistik)->max('n'));
    $kuning = '#E5C97A';
@endphp

@section('isi')
    @include('partials.judul', ['judul' => 'Dashboard', 'sub' => 'Ringkasan kendaraan, pengajuan, pengemudi, dan seksi'])

    <section class="pn">
        <h2>Kendaraan</h2>
        @include('partials.kartu', ['kartu' => [
            ['Kendaraan', $totalKendaraan],
            ['Tersedia', $tersedia],
            ['Tidak tersedia', $tidakTersedia],
            ['Sedang dipakai', $dipakai],
            ['Sedang perbaikan', $diservis],
            ['STNK jatuh tempo 30 hari', $stnkTempo, $kuning],
            ['BPKB jatuh tempo 30 hari', $bpkbTempo, $kuning],
        ]])
    </section>

    <section class="pn">
        <h2>Pengajuan, pengemudi, dan seksi</h2>
        @include('partials.kartu', ['kartu' => [
            ['Pengajuan pinjaman', $menunggu->count(), $kuning],
            ['Persetujuan Pengembalian', $verifikasi->count(), $kuning],
            ['Total driver', $totalPengemudi],
            ['Driver tersedia', $pengemudiSedia],
            ['Total seksi', $totalSeksi],
        ]])
    </section>

    {{-- Pengajuan menunggu --}}
    <section class="pn">
        <h2>Pengajuan Peminjaman Menunggu ({{ $menunggu->count() }})</h2>
        <div class="tw">
            <table>
                <tr><th>Seksi</th><th>Tujuan</th><th>Tanggal</th><th>Kendaraan</th><th>Pengemudi</th><th>Diajukan</th><th>Aksi</th></tr>
                @forelse ($menunggu as $l)
                    <tr>
                        <td>{{ $l->pengguna->nama_seksi }}</td>
                        <td>{{ $l->tujuan }}</td>
                        <td>{{ $l->mulai->format('d/m/Y H:i') }}</td>
                        <td>
                            {{ $l->kendaraan?->nama ?? '-' }}
                            <div class="note">{{ $l->penumpang }} penumpang, {{ $l->lokasi_tujuan }}, {{ $l->menginap ? 'Menginap' : 'Tidak menginap' }}{{ $l->catatan ? ' - '.$l->catatan : '' }}</div>
                        </td>
                        <td>{{ $l->pengemudi?->nama ?? 'Tanpa pengemudi' }}</td>
                        <td>{{ $l->diajukan_pada?->format('d/m/Y') }}</td>
                        <td><a class="btn s" href="{{ route('admin.peminjaman.show', $l) }}">Verifikasi</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="note" style="text-align:center; padding:22px">Tidak ada pengajuan yang menunggu persetujuan.</td></tr>
                @endforelse
            </table>
        </div>
    </section>

    {{-- Sedang dipinjam --}}
    <section class="pn">
        <h2>Sedang Dipinjam ({{ $dipinjam->count() }})</h2>
        <div class="tw">
            <table>
                <tr><th>Kendaraan</th><th>Dipinjam Oleh</th><th>Petugas</th><th>Tujuan</th><th>Pengemudi</th><th>Waktu Pinjam</th><th>Status</th></tr>
                @forelse ($dipinjam as $l)
                    @php
                        [$teks, $kls] = match (true) {
                            $l->status === 'kembali_ditolak' => ['Pengembalian ditolak', 'er'],
                            $l->status === 'verifikasi'      => ['Menunggu verifikasi', 'wr'],
                            $l->mulai->isFuture()            => ['Belum mulai', 'wr'],
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
                    <tr><td colspan="7" class="note" style="text-align:center; padding:22px">Tidak ada kendaraan yang sedang dipinjam.</td></tr>
                @endforelse
            </table>
        </div>
    </section>

    {{-- Pengembalian menunggu --}}
    <section class="pn">
        <h2>Persetujuan Pengembalian Menunggu ({{ $verifikasi->count() }})</h2>
        <div class="tw">
            <table>
                <tr><th>Seksi</th><th>Kendaraan</th><th>Tujuan</th><th>Waktu kembali</th><th>Laporan</th><th>Aksi</th></tr>
                @forelse ($verifikasi as $r)
                    <tr>
                        <td>{{ $r->peminjaman->pengguna->nama_seksi }}</td>
                        <td>{{ $r->peminjaman->kendaraan->nama }}</td>
                        <td>{{ $r->peminjaman->tujuan }}</td>
                        <td>{{ $r->kembali_pada->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($r->jarak, 0, ',', '.') }} km</td>
                        <td><a class="btn s" href="{{ route('admin.pengembalian.show', $r) }}">Verifikasi</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="note" style="text-align:center; padding:22px">Tidak ada laporan pengembalian yang menunggu.</td></tr>
                @endforelse
            </table>
        </div>
    </section>

    {{-- Sedang diservis --}}
    <section class="pn">
        <h2>Sedang Diservis ({{ $servisBerjalan->count() }})</h2>
        <div class="tw">
            <table>
                <tr><th>Kendaraan</th><th>Jenis servis</th><th>Bengkel</th><th>Masuk servis</th><th>Estimasi selesai</th><th>Lama diservis</th><th>Biaya estimasi</th><th>Status</th></tr>
                @forelse ($servisBerjalan as $s)
                    @php $lewat = $s->estimasi_selesai && $s->estimasi_selesai->lt(now()->startOfDay()); @endphp
                    <tr>
                        <td>{{ $s->kendaraan->nama }}</td>
                        <td>{{ $s->jenis }}</td>
                        <td>{{ $s->bengkel ?: '-' }}</td>
                        <td>{{ $s->tgl_masuk->format('d/m/Y') }}</td>
                        <td>{{ $s->estimasi_selesai?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            {{ \App\Http\Controllers\Admin\DashboardController::durasi($s->tgl_masuk) }}
                            <div class="note">Odometer {{ number_format($s->odometer ?? 0, 0, ',', '.') }} km</div>
                        </td>
                        <td>Rp {{ number_format($s->biaya, 0, ',', '.') }}</td>
                        <td><span class="b {{ $lewat ? 'er' : 'wr' }}">{{ $lewat ? 'Lewat estimasi' : 'Sedang diservis' }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="note" style="text-align:center; padding:22px">Tidak ada kendaraan yang sedang diservis.</td></tr>
                @endforelse
            </table>
        </div>
    </section>

    {{-- Pajak jatuh tempo --}}
    <section class="pn">
        <h2>Pajak Jatuh Tempo ({{ $pajakTempo->count() }})</h2>
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
                    <tr><td colspan="5" class="note" style="text-align:center; padding:22px">Tidak ada pajak yang jatuh tempo dalam 30 hari.</td></tr>
                @endforelse
            </table>
        </div>
    </section>

    {{-- Statistik --}}
    <section class="pn">
        <div>
            <h2>Statistik Peminjaman Bulanan</h2>
            <div class="note">Jumlah pengajuan per bulan, tahun {{ $tahun }}</div>
        </div>
        <div class="bars">
            @foreach ($statistik as $s)
                <div>
                    <b>{{ $s['n'] }}</b>
                    <i style="height:{{ round($s['n'] / $maks * 140) }}px"></i>
                    <span>{{ $s['nama'] }}</span>
                </div>
            @endforeach
        </div>
    </section>
@endsection
