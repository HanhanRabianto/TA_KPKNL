@extends('layouts.app')
@section('judul', 'Tolak Pinjaman')

@section('isi')
    <h2>Tolak Pengajuan</h2>

    <div class="card" style="max-width:720px">
        <p>
            <strong>{{ $p->pengguna->nama_seksi }}</strong> (petugas: {{ $p->petugas }})<br>
            {{ $p->tujuan }} - {{ $p->lokasi_tujuan }}<br>
            {{ $p->mulai->format('d/m/Y H:i') }} | {{ $p->penumpang }} penumpang
        </p>

        <form method="POST" action="{{ route('admin.peminjaman.tolak', $p) }}">
            @csrf
            <label for="alasan_tolak">Alasan penolakan (akan dilihat oleh pemohon)</label>
            <textarea id="alasan_tolak" name="alasan_tolak" rows="4" required>{{ old('alasan_tolak') }}</textarea>
            @error('alasan_tolak') <div class="ferr">{{ $message }}</div> @enderror

            <div style="margin-top:16px">
                <button class="btn d" type="submit">Tolak Pengajuan</button>
                <a class="btn s" href="{{ route('admin.peminjaman.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
