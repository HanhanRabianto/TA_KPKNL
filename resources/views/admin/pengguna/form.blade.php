@extends('layouts.app')
@php $ubah = $pengguna->exists; $diriSendiri = $ubah && $pengguna->id === auth()->id(); @endphp
@section('judul', $ubah ? 'Ubah Pengguna' : 'Tambah Pengguna')

@section('isi')
    @include('partials.judul', ['judul' => $ubah ? 'Ubah Pengguna' : 'Tambah Pengguna', 'sub' => 'Akun admin atau seksi'])

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
                    <label for="peran">Peran</label>
                    <select id="peran" name="peran" @if ($diriSendiri) disabled @endif>
                        <option value="pegawai" @selected(old('peran', $pengguna->peran) === 'pegawai')>Seksi</option>
                        <option value="admin" @selected(old('peran', $pengguna->peran) === 'admin')>Admin</option>
                    </select>
                    @if ($diriSendiri) <input type="hidden" name="peran" value="admin"> @endif
                    @error('peran') <div class="ferr">{{ $message }}</div> @enderror
                </div>

                @if ($ubah)
                    <div>
                        <label for="aktif">Status</label>
                        <select id="aktif" name="aktif" @if ($diriSendiri) disabled @endif>
                            <option value="1" @selected(old('aktif', $pengguna->aktif ? '1' : '0') == '1')>Aktif</option>
                            <option value="0" @selected(old('aktif', $pengguna->aktif ? '1' : '0') == '0')>Nonaktif (tidak bisa masuk)</option>
                        </select>
                        @if ($diriSendiri) <input type="hidden" name="aktif" value="1"> @endif
                    </div>
                @else
                    <div>
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}">
                        @error('email') <div class="ferr">{{ $message }}</div> @enderror
                    </div>
                @endif
            </div>

            @unless ($ubah)
                <label for="password">Kata sandi sementara</label>
                <input id="password" type="password" name="password" autocomplete="new-password" required>
                @error('password') <div class="ferr">{{ $message }}</div> @enderror
                <p class="note">Pengguna dapat menggantinya sendiri di halaman Profil.</p>
            @endunless

            @if ($diriSendiri)
                <p class="note">Anda tidak dapat menonaktifkan atau menurunkan peran akun sendiri.</p>
            @endif

            <div style="margin-top:14px">
                <button class="btn" type="submit">Simpan</button>
                <a class="btn s" href="{{ route('admin.pengguna.index') }}">Batal</a>
            </div>
        </form>

        @if ($ubah)
            <hr style="border:0; border-top:1px solid var(--bd); margin:18px 0">
            <form method="POST" action="{{ route('admin.pengguna.reset', $pengguna) }}"
                  onsubmit="return confirm('Reset kata sandi {{ $pengguna->nama_seksi }} menjadi {{ \App\Http\Controllers\PenggunaController::SANDI_RESET }}?')">
                @csrf
                <button class="btn w" type="submit">Reset Kata Sandi</button>
                <span class="note">Kata sandi menjadi <strong>{{ \App\Http\Controllers\PenggunaController::SANDI_RESET }}</strong>. Minta pengguna menggantinya di Profil.</span>
            </form>
        @endif
    </div>
@endsection
