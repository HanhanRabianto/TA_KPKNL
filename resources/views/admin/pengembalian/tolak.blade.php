@extends('layouts.app')
@section('judul', 'Tolak Pengembalian')

@section('isi')
    <h2>Tolak Laporan Pengembalian</h2>

    <div class="card" style="max-width:720px">
        <p>
            <strong>{{ $r->peminjaman->pengguna->nama_seksi }}</strong> | {{ $r->peminjaman->kendaraan->plat }} - {{ $r->peminjaman->kendaraan->jenis }}<br>
            Odometer {{ $r->odometer_awal }} &rarr; {{ $r->odometer_akhir }} ({{ $r->jarak }} km), kondisi {{ $r->kondisi }}
        </p>

        <form method="POST" action="{{ route('admin.pengembalian.tolak', $r) }}">
            @csrf
            <label for="alasan_tolak">Alasan penolakan (akan dilihat oleh pelapor)</label>
            <textarea id="alasan_tolak" name="alasan_tolak" rows="4" required>{{ old('alasan_tolak') }}</textarea>
            @error('alasan_tolak') <div class="ferr">{{ $message }}</div> @enderror

            <div style="margin-top:16px">
                <button class="btn d" type="submit">Tolak Laporan</button>
                <a class="btn s" href="{{ route('admin.pengembalian.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
