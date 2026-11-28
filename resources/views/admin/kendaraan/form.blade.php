@extends('layouts.app')
@php
    $ubah = $kendaraan->exists;
    $terkunci = $ubah && ! in_array($kendaraan->status, \App\Models\Kendaraan::STATUS_MANUAL, true);
@endphp
@section('judul', $ubah ? 'Ubah Kendaraan' : 'Tambah Kendaraan')

@section('isi')
    @include('partials.judul', ['judul' => $ubah ? 'Ubah Kendaraan' : 'Tambah Kendaraan', 'sub' => 'Data kendaraan dinas'])

    <div class="card" style="max-width:760px">
        <form method="POST" action="{{ $ubah ? route('admin.kendaraan.update', $kendaraan) : route('admin.kendaraan.store') }}">
            @csrf
            @if ($ubah) @method('PUT') @endif

            <div class="row">
                <div>
                    <label for="plat">Plat nomor</label>
                    <input id="plat" name="plat" value="{{ old('plat', $kendaraan->plat) }}" required>
                    @error('plat') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="jenis">Merek / tipe</label>
                    <input id="jenis" name="jenis" value="{{ old('jenis', $kendaraan->jenis) }}" placeholder="Contoh: Toyota Innova" required>
                    @error('jenis') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="tipe">Jenis kendaraan</label>
                    <select id="tipe" name="tipe">
                        @foreach (\App\Models\Kendaraan::TIPE as $t)
                            <option value="{{ $t }}" @selected(old('tipe', $kendaraan->tipe) === $t)>{{ $t }}</option>
                        @endforeach
                    </select>
                    @error('tipe') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div>
                    <label for="kapasitas">Kapasitas (orang)</label>
                    <input id="kapasitas" type="number" min="1" max="60" name="kapasitas" value="{{ old('kapasitas', $kendaraan->kapasitas ?? 7) }}" required>
                    @error('kapasitas') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="tahun">Tahun</label>
                    <input id="tahun" type="number" min="1980" max="{{ now()->year + 1 }}" name="tahun" value="{{ old('tahun', $kendaraan->tahun ?? now()->year) }}" required>
                    @error('tahun') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="odometer">Odometer (km)</label>
                    <input id="odometer" type="number" min="0" name="odometer" value="{{ old('odometer', $kendaraan->odometer ?? 0) }}" required>
                    @error('odometer') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="status">Status</label>
                    @if ($terkunci)
                        <input id="status" value="{{ $kendaraan->label_status }}" readonly>
                    @else
                        <select id="status" name="status">
                            @foreach (\App\Models\Kendaraan::STATUS_MANUAL as $status)
                                <option value="{{ $status }}" @selected(old('status', $kendaraan->status) === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    @endif
                    @error('status') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div>
                    <label for="stnk_berlaku">STNK berlaku sampai</label>
                    <input id="stnk_berlaku" type="date" name="stnk_berlaku"
                           value="{{ old('stnk_berlaku', optional($kendaraan->stnk_berlaku)->format('Y-m-d')) }}" @if ($ubah) readonly @endif>
                    @error('stnk_berlaku') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="bpkb_berlaku">BPKB berlaku sampai</label>
                    <input id="bpkb_berlaku" type="date" name="bpkb_berlaku"
                           value="{{ old('bpkb_berlaku', optional($kendaraan->bpkb_berlaku)->format('Y-m-d')) }}" @if ($ubah) readonly @endif>
                    @error('bpkb_berlaku') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            @if ($ubah)
                <p class="note">Tanggal STNK dan BPKB tidak dapat diubah di sini. Nilainya berubah otomatis saat pembayaran pajak dicatat.
                    @if ($terkunci) Status <strong>{{ $kendaraan->label_status }}</strong> diatur otomatis oleh peminjaman dan servis. @endif</p>
            @else
                <p class="note">Isi tanggal awal STNK dan BPKB untuk kendaraan baru. Setelah disimpan, tanggal hanya berubah lewat pencatatan pembayaran pajak. Status Dipakai dan Sedang diservis diatur otomatis oleh sistem.</p>
            @endif

            <div style="margin-top:14px">
                <button class="btn" type="submit">Simpan</button>
                <a class="btn s" href="{{ route('admin.kendaraan.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
