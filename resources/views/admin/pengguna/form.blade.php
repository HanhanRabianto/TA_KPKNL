@extends('layouts.app')
@php $ubah = $pengguna->exists; $diriSendiri = $ubah && $pengguna->id === auth()->id(); @endphp
@section('judul', $ubah ? 'Ubah Pengguna' : 'Tambah Pengguna')

@section('isi')
    <h2>{{ $ubah ? 'Ubah Pengguna' : 'Tambah Pengguna' }}</h2>

    <div class="card" style="max-width:720px">
        <form method="POST" action="{{ $ubah ? route('admin.pengguna.update', $pengguna) : route('admin.pengguna.store') }}">
            @csrf
            @if ($ubah) @method('PUT') @endif

            <div class="row">
                <div>
                    <label for="nama_seksi">Nama seksi</label>
                    <input id="nama_seksi" name="nama_seksi" value="{{ old('nama_seksi', $pengguna->nama_seksi) }}" required>
                    @error('nama_seksi') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="username">Username</label>
                    <input id="username" name="username" value="{{ old('username', $pengguna->username) }}" required autocomplete="off">
                    @error('username') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div>
                    <label for="password">Kata sandi @if ($ubah) <span class="note">(kosongkan jika tidak diubah)</span> @endif</label>
                    <input id="password" type="password" name="password" autocomplete="new-password" @if (! $ubah) required @endif>
                    @error('password') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="password_confirmation">Ulangi kata sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
                </div>
            </div>

            <div class="row">
                <div>
                    <label for="peran">Peran</label>
                    <select id="peran" name="peran" @if ($diriSendiri) disabled @endif>
                        <option value="pegawai" @selected(old('peran', $pengguna->peran) === 'pegawai')>Pegawai (seksi)</option>
                        <option value="admin" @selected(old('peran', $pengguna->peran) === 'admin')>Admin</option>
                    </select>
                    @if ($diriSendiri) <input type="hidden" name="peran" value="admin"> @endif
                    @error('peran') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="aktif">Status akun</label>
                    <select id="aktif" name="aktif" @if ($diriSendiri) disabled @endif>
                        <option value="1" @selected(old('aktif', $pengguna->aktif ? '1' : '0') == '1')>Aktif</option>
                        <option value="0" @selected(old('aktif', $pengguna->aktif ? '1' : '0') == '0')>Nonaktif (tidak bisa masuk)</option>
                    </select>
                    @if ($diriSendiri) <input type="hidden" name="aktif" value="1"> @endif
                </div>
            </div>

            @if ($diriSendiri)
                <p class="note">Anda tidak dapat menonaktifkan atau menurunkan peran akun sendiri.</p>
            @endif

            <div style="margin-top:16px">
                <button class="btn" type="submit">Simpan</button>
                <a class="btn s" href="{{ route('admin.pengguna.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
