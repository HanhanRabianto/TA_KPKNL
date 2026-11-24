@extends('layouts.app')
@section('judul', 'Pengajuan Peminjaman')

@php
    // nilai awal: old() lebih diutamakan, lalu data pengajuan lama (ajukan ulang)
    $v = fn ($k, $default = null) => old($k, $isi?->{$k} ?? $default);
    $keteranganBlokir = [
        'menunggu'        => 'masih menunggu persetujuan.',
        'disetujui'       => 'sudah disetujui. Kembalikan kendaraan dan isi laporan pemakaian terlebih dahulu.',
        'verifikasi'      => 'laporan pengembaliannya sedang diverifikasi.',
        'kembali_ditolak' => 'laporan pengembaliannya ditolak. Perbaiki laporan lalu ajukan kembali.',
    ];
@endphp

@section('isi')
    @include('partials.judul', ['judul' => 'Pengajuan Peminjaman', 'sub' => 'Isi data perjalanan dinas, lalu kirim untuk persetujuan'])

    @if ($penghalang)
        <div class="card" style="max-width:720px">
            <h3 style="margin-top:0">Belum bisa mengajukan peminjaman baru</h3>
            @if ($penghalang->status !== 'kembali_ditolak')
                <p>Anda sudah meminjam maksimal {{ \App\Models\Peminjaman::MAKS_AKTIF }} kendaraan.</p>
            @endif
            <p>
                Peminjaman Anda untuk <strong>{{ $penghalang->tujuan }}</strong> ({{ $penghalang->mulai->format('d/m/Y') }})
                {{ $keteranganBlokir[$penghalang->status] ?? '' }}
            </p>
            <p class="note">Pengajuan baru dibuka setelah pengembalian kendaraan diterima admin.</p>
            <a class="btn" href="{{ route('pegawai.peminjaman.index') }}">Lihat Riwayat Saya</a>
            @if ($penghalang->status === 'kembali_ditolak')
                <a class="btn d" href="{{ route('pegawai.pengembalian.create', $penghalang) }}">Perbaiki Laporan</a>
            @endif
        </div>
    @else
        <div class="card" style="max-width:720px">
            <form method="POST" action="{{ route('pegawai.peminjaman.store') }}">
                @csrf

                <div class="row">
                    <div>
                        <label for="lokasi_tujuan">Lokasi tujuan</label>
                        <select id="lokasi_tujuan" name="lokasi_tujuan">
                            @foreach (['Dalam kota', 'Luar kota'] as $o)
                                <option value="{{ $o }}" @selected($v('lokasi_tujuan', 'Dalam kota') === $o)>{{ $o }}</option>
                            @endforeach
                        </select>
                        @error('lokasi_tujuan') <div class="ferr">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="menginap">Menginap</label>
                        <select id="menginap" name="menginap">
                            <option value="1" @selected((string) (int) $v('menginap', 0) === '1')>Ya</option>
                            <option value="0" @selected((string) (int) $v('menginap', 0) === '0')>Tidak</option>
                        </select>
                        @error('menginap') <div class="ferr">{{ $message }}</div> @enderror
                    </div>
                </div>

                <label for="tujuan">Tujuan perjalanan</label>
                <input id="tujuan" name="tujuan" value="{{ $v('tujuan') }}" required>
                @error('tujuan') <div class="ferr">{{ $message }}</div> @enderror

                <label for="petugas">Nama pegawai yang bertugas (pisahkan dengan koma)</label>
                <input id="petugas" name="petugas" value="{{ $v('petugas') }}" required>
                @error('petugas') <div class="ferr">{{ $message }}</div> @enderror

                <div class="row">
                    <div>
                        <label for="tanggal_mulai">Tanggal mulai</label>
                        <input id="tanggal_mulai" type="date" name="tanggal_mulai" min="{{ now()->format('Y-m-d') }}" value="{{ old('tanggal_mulai') }}" required>
                        @error('tanggal_mulai') <div class="ferr">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="jam_mulai">Jam mulai</label>
                        <input id="jam_mulai" type="time" name="jam_mulai" value="{{ old('jam_mulai', $isi?->mulai?->format('H:i') ?? '08:00') }}" required>
                        @error('jam_mulai') <div class="ferr">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label for="penumpang">Jumlah penumpang</label>
                        <input id="penumpang" type="number" min="1" max="60" name="penumpang" value="{{ $v('penumpang', 1) }}" required>
                        @error('penumpang') <div class="ferr">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="kendaraan_id">Kendaraan (hanya yang tersedia)</label>
                        <select id="kendaraan_id" name="kendaraan_id" required>
                            <option value="">{{ $kendaraan->isEmpty() ? 'Tidak ada kendaraan tersedia' : 'Pilih kendaraan' }}</option>
                            @foreach ($kendaraan as $k)
                                <option value="{{ $k->id }}" @selected($v('kendaraan_id') == $k->id)>{{ $k->plat }} - {{ $k->jenis }} (kapasitas {{ $k->kapasitas }})</option>
                            @endforeach
                        </select>
                        @error('kendaraan_id') <div class="ferr">{{ $message }}</div> @enderror
                    </div>
                </div>

                <label for="pengemudi_id">Pengemudi (pilih sendiri atau tanpa pengemudi)</label>
                <select id="pengemudi_id" name="pengemudi_id">
                    <option value="">Tanpa pengemudi (mengemudi sendiri)</option>
                    @foreach ($pengemudi as $s)
                        <option value="{{ $s->id }}" @selected($v('pengemudi_id') == $s->id)>{{ $s->nama }}</option>
                    @endforeach
                </select>
                @error('pengemudi_id') <div class="ferr">{{ $message }}</div> @enderror

                <label for="catatan">Preferensi atau kebutuhan khusus (opsional)</label>
                <textarea id="catatan" name="catatan" rows="3">{{ $v('catatan') }}</textarea>
                @error('catatan') <div class="ferr">{{ $message }}</div> @enderror

                <p class="note">Pilih kendaraan yang tersedia dan tentukan apakah memakai pengemudi. Bagian Umum akan memverifikasi pengajuan Anda.</p>

                <div>
                    <button class="btn" type="submit" @if ($kendaraan->isEmpty()) disabled @endif>Kirim Pengajuan</button>
                    <a class="btn s" href="{{ route('pegawai.beranda') }}">Batal</a>
                </div>
            </form>
        </div>
    @endif
@endsection
