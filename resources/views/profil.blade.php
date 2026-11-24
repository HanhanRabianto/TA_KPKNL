@extends('layouts.app')
@section('judul', 'Profil')

@section('isi')
    @include('partials.judul', ['judul' => 'Profil saya', 'sub' => 'Data akun Anda di SIMOVDIN'])

    <div class="card" style="max-width:640px">
        <div style="display:flex; gap:14px; align-items:center; margin-bottom:6px">
            <span style="width:52px; height:52px; border-radius:50%; background:var(--pr2); color:var(--pr); display:grid; place-items:center; font-weight:700; font-size:20px">
                {{ strtoupper(mb_substr($u->nama_seksi, 0, 1)) }}
            </span>
            <div>
                <strong style="font-size:16px">{{ $u->nama_seksi }}</strong><br>
                <span class="b {{ $u->peran === 'admin' ? 'in' : 'ok' }}">{{ $u->label_peran }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('profil.simpan') }}">
            @csrf
            <label for="username">Username</label>
            <input id="username" value="{{ $u->username }}" readonly>

            <div class="row">
                <div>
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $u->email) }}">
                    @error('email') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="no_hp">No. HP</label>
                    <input id="no_hp" name="no_hp" value="{{ old('no_hp', $u->no_hp) }}">
                    @error('no_hp') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>
            <p class="note">Nama seksi dan username hanya dapat diubah oleh admin.</p>
            <button class="btn" type="submit">Simpan Profil</button>
        </form>
    </div>

    <div class="card" style="max-width:640px">
        <h3 style="margin-top:0">Ubah Kata Sandi</h3>
        <form method="POST" action="{{ route('profil.sandi') }}">
            @csrf
            <label for="sandi_lama">Kata sandi lama</label>
            <input id="sandi_lama" type="password" name="sandi_lama" autocomplete="current-password" required>
            @error('sandi_lama') <div class="ferr">{{ $message }}</div> @enderror

            <div class="row">
                <div>
                    <label for="sandi_baru">Kata sandi baru (minimal 8 karakter)</label>
                    <input id="sandi_baru" type="password" name="sandi_baru" autocomplete="new-password" required>
                    @error('sandi_baru') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="sandi_baru_confirmation">Ulangi kata sandi baru</label>
                    <input id="sandi_baru_confirmation" type="password" name="sandi_baru_confirmation" autocomplete="new-password" required>
                </div>
            </div>

            <div style="margin-top:14px"><button class="btn" type="submit">Perbarui Kata Sandi</button></div>
        </form>
    </div>
@endsection
