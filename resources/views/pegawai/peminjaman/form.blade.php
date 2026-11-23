@extends('layouts.app')
@section('judul', 'Ajukan Peminjaman')

@php
    // nilai awal: old() lebih diutamakan, lalu data pengajuan lama (ajukan lagi)
    $v = fn ($k, $default = null) => old($k, $isi?->{$k} ?? $default);
@endphp

@section('isi')
    <h2>Ajukan Peminjaman Kendaraan</h2>

    @if ($blokir)
        <div class="flash err">
            <strong>Belum bisa mengajukan peminjaman baru.</strong><br>{{ $blokir }}
        </div>
        <a class="btn s" href="{{ route('pegawai.peminjaman.index') }}">Lihat Riwayat Saya</a>
    @else
        <p class="note">Peminjaman aktif Anda: {{ $aktif }} dari {{ \App\Models\Peminjaman::MAKS_AKTIF }}. Anda memilih sendiri kendaraan dan pengemudi, lalu admin memverifikasi. Tanggal dan jam selesai diisi nanti saat melaporkan pengembalian.</p>

        <div class="card" style="max-width:760px">
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

                <div class="row">
                    <div>
                        <label for="petugas">Nama petugas</label>
                        <input id="petugas" name="petugas" value="{{ $v('petugas', auth()->user()->nama_seksi) }}" required>
                        @error('petugas') <div class="ferr">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="penumpang">Jumlah penumpang</label>
                        <input id="penumpang" type="number" min="1" max="20" name="penumpang" value="{{ $v('penumpang', 1) }}" required>
                        @error('penumpang') <div class="ferr">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="mulai">Tanggal dan jam mulai</label>
                        <input id="mulai" type="datetime-local" name="mulai" min="{{ now()->format('Y-m-d\T00:00') }}"
                               value="{{ old('mulai') }}" required>
                        @error('mulai') <div class="ferr">{{ $message }}</div> @enderror
                    </div>
                </div>

                @if ($kendaraan->isEmpty())
                    <div class="flash err" style="margin-top:12px">Saat ini tidak ada kendaraan yang tersedia. Coba lagi nanti.</div>
                @endif

                <div class="row">
                    <div>
                        <label for="kendaraan_id">Kendaraan (yang tersedia)</label>
                        <select id="kendaraan_id" name="kendaraan_id" required>
                            <option value="">- pilih kendaraan -</option>
                            @foreach ($kendaraan as $k)
                                <option value="{{ $k->id }}" @selected($v('kendaraan_id') == $k->id)>{{ $k->plat }} - {{ $k->jenis }}</option>
                            @endforeach
                        </select>
                        @error('kendaraan_id') <div class="ferr">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="pengemudi_id">Pengemudi (yang tersedia)</label>
                        <select id="pengemudi_id" name="pengemudi_id">
                            <option value="">Tanpa pengemudi</option>
                            @foreach ($pengemudi as $s)
                                <option value="{{ $s->id }}" @selected($v('pengemudi_id') == $s->id)>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                        @error('pengemudi_id') <div class="ferr">{{ $message }}</div> @enderror
                    </div>
                </div>

                <label for="catatan">Catatan tambahan (opsional)</label>
                <textarea id="catatan" name="catatan" rows="3" placeholder="Contoh: butuh mobil kapasitas 7 orang">{{ $v('catatan') }}</textarea>
                @error('catatan') <div class="ferr">{{ $message }}</div> @enderror

                <div style="margin-top:16px">
                    <button class="btn" type="submit" @if ($kendaraan->isEmpty()) disabled @endif>Kirim Pengajuan</button>
                    <a class="btn s" href="{{ route('pegawai.beranda') }}">Batal</a>
                </div>
            </form>
        </div>
    @endif
@endsection
