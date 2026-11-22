@extends('layouts.app')
@php $ubah = $pengemudi->exists; @endphp
@section('judul', $ubah ? 'Ubah Pengemudi' : 'Tambah Pengemudi')

@section('isi')
    <h2>{{ $ubah ? 'Ubah Pengemudi' : 'Tambah Pengemudi' }}</h2>

    <div class="card" style="max-width:620px">
        <form method="POST" action="{{ $ubah ? route('admin.pengemudi.update', $pengemudi) : route('admin.pengemudi.store') }}">
            @csrf
            @if ($ubah) @method('PUT') @endif

            <div class="row">
                <div>
                    <label for="nama">Nama pengemudi</label>
                    <input id="nama" name="nama" value="{{ old('nama', $pengemudi->nama) }}" required>
                    @error('nama') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="kontak">Kontak (No. HP)</label>
                    <input id="kontak" name="kontak" value="{{ old('kontak', $pengemudi->kontak) }}">
                    @error('kontak') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            @if ($ubah)
                <p class="note">Status pengemudi (<strong>{{ $pengemudi->status }}</strong>) berubah otomatis saat ditugaskan dan setelah pengembalian diterima.</p>
            @endif

            <div style="margin-top:16px">
                <button class="btn" type="submit">Simpan</button>
                <a class="btn s" href="{{ route('admin.pengemudi.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
