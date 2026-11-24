@extends('layouts.app')
@section('judul', 'Verifikasi Pengembalian')

@php $p = $r->peminjaman; $menunggu = $r->status === 'menunggu'; @endphp

@section('isi')
    @include('partials.judul', ['judul' => 'Verifikasi Pengembalian', 'sub' => 'Periksa laporan pemakaian lalu terima atau tolak'])

    <div class="card" style="max-width:700px">
        <div class="flash info">Periksa laporan pemakaian dari seksi. Jika diterima, kendaraan kembali tersedia dan data masuk ke Laporan Pemakaian.</div>

        <dl class="dl">
            <dt>Seksi</dt><dd>{{ $p->pengguna->nama_seksi }}</dd>
            <dt>Petugas</dt><dd>{{ $p->petugas }}</dd>
            <dt>Tujuan</dt><dd>{{ $p->tujuan }}</dd>
            <dt>Kendaraan</dt><dd>{{ $p->kendaraan->nama }}</dd>
            <dt>Waktu pinjam</dt><dd>{{ $p->mulai->format('d/m/Y H:i') }}</dd>
            <dt>Waktu kembali</dt><dd>{{ $r->kembali_pada->format('d/m/Y H:i') }}</dd>
            <dt>Odometer awal</dt><dd>{{ number_format($r->odometer_awal, 0, ',', '.') }} km</dd>
            <dt>Odometer akhir</dt><dd>{{ number_format($r->odometer_akhir, 0, ',', '.') }} km</dd>
            <dt>Jarak tempuh</dt><dd>{{ number_format($r->jarak, 0, ',', '.') }} km</dd>
            <dt>Kondisi kendaraan</dt><dd>{{ $r->kondisi }}</dd>
            <dt>Catatan kondisi</dt><dd>{{ $r->catatan_kondisi ?: '-' }}</dd>
        </dl>

        <hr style="border:0; border-top:1px solid var(--bd); margin:14px 0">

        @if ($menunggu)
            <form method="POST">
                @csrf
                <label for="alasan_tolak">Alasan penolakan (diisi hanya jika laporan ditolak)</label>
                <textarea id="alasan_tolak" name="alasan_tolak" rows="3">{{ old('alasan_tolak') }}</textarea>
                @error('alasan_tolak') <div class="ferr">{{ $message }}</div> @enderror

                <div style="margin-top:14px; display:flex; justify-content:space-between; gap:8px; flex-wrap:wrap">
                    <a class="btn w" href="{{ route('admin.pengembalian.index') }}">Batal</a>
                    <div style="display:flex; gap:8px">
                        <button class="btn d" type="submit" formaction="{{ route('admin.pengembalian.tolak', $r) }}">Tolak Pengembalian</button>
                        <button class="btn" type="submit" formaction="{{ route('admin.pengembalian.terima', $r) }}">Terima Pengembalian</button>
                    </div>
                </div>
            </form>
        @else
            <a class="btn w" href="{{ route('admin.pengembalian.index') }}">Kembali</a>
            <span class="note">Laporan ini sudah diproses ({{ $r->label_status }}).</span>
        @endif
    </div>
@endsection
