@extends('layouts.app')
@php $ubah = $kendaraan->exists; @endphp
@section('judul', $ubah ? 'Ubah Kendaraan' : 'Tambah Kendaraan')

@section('isi')
    <h2>{{ $ubah ? 'Ubah Kendaraan' : 'Tambah Kendaraan' }}</h2>

    <div class="card" style="max-width:720px">
        <form method="POST" action="{{ $ubah ? route('admin.kendaraan.update', $kendaraan) : route('admin.kendaraan.store') }}">
            @csrf
            @if ($ubah) @method('PUT') @endif

            <div class="row">
                <div>
                    <label for="plat">Nomor polisi</label>
                    <input id="plat" name="plat" value="{{ old('plat', $kendaraan->plat) }}" required>
                    @error('plat') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="jenis">Jenis / merk kendaraan</label>
                    <input id="jenis" name="jenis" value="{{ old('jenis', $kendaraan->jenis) }}" required>
                    @error('jenis') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="odometer">Odometer (km)</label>
                    <input id="odometer" type="number" min="0" name="odometer" value="{{ old('odometer', $kendaraan->odometer ?? 0) }}" required>
                    @error('odometer') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div>
                    <label for="stnk_berlaku">STNK berlaku sampai</label>
                    <input id="stnk_berlaku" type="date" name="stnk_berlaku"
                           value="{{ old('stnk_berlaku', optional($kendaraan->stnk_berlaku)->format('Y-m-d')) }}"
                           @if ($ubah) readonly @endif>
                    @error('stnk_berlaku') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="bpkb_berlaku">BPKB berlaku sampai</label>
                    <input id="bpkb_berlaku" type="date" name="bpkb_berlaku"
                           value="{{ old('bpkb_berlaku', optional($kendaraan->bpkb_berlaku)->format('Y-m-d')) }}"
                           @if ($ubah) readonly @endif>
                    @error('bpkb_berlaku') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            @if ($ubah)
                <p class="note">Tanggal STNK dan BPKB tidak dapat diubah di sini. Nilainya berubah otomatis saat pembayaran pajak dicatat.
                   Status kendaraan (<strong>{{ $kendaraan->status }}</strong>) juga berubah otomatis oleh peminjaman dan servis.</p>
            @else
                <p class="note">Isi tanggal awal STNK dan BPKB untuk kendaraan baru. Setelah disimpan, tanggal hanya berubah lewat pencatatan pembayaran pajak.</p>
            @endif

            <div style="margin-top:16px">
                <button class="btn" type="submit">Simpan</button>
                <a class="btn s" href="{{ route('admin.kendaraan.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
