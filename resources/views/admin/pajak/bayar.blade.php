@extends('layouts.app')
@section('judul', 'Catat Pembayaran '.$nama)

@section('isi')
    <h2>Catat Pembayaran {{ $nama }}</h2>

    <div class="card" style="max-width:720px">
        <p>
            <strong>{{ $k->plat }} - {{ $k->jenis }}</strong><br>
            Berlaku saat ini: {{ $k->{$kolom}?->format('d/m/Y') ?? 'belum ada data' }}
        </p>

        <form method="POST" action="{{ route('admin.pajak.simpan', [$slug, $k]) }}">
            @csrf

            <div class="row">
                <div>
                    <label for="tgl_bayar">Tanggal bayar</label>
                    <input id="tgl_bayar" type="date" name="tgl_bayar" value="{{ old('tgl_bayar', now()->format('Y-m-d')) }}" required>
                    @error('tgl_bayar') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="nominal">Nominal (Rp)</label>
                    <input id="nominal" type="number" min="0" name="nominal" value="{{ old('nominal') }}" required>
                    @error('nominal') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="berlaku_sampai">Berlaku sampai (baru)</label>
                    <input id="berlaku_sampai" type="date" name="berlaku_sampai" value="{{ old('berlaku_sampai', $saran) }}" required>
                    @error('berlaku_sampai') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <label for="keterangan">Keterangan</label>
            <input id="keterangan" name="keterangan" value="{{ old('keterangan') }}">
            @error('keterangan') <div class="ferr">{{ $message }}</div> @enderror

            <p class="note">Setelah disimpan, tanggal berlaku {{ $nama }} pada data kendaraan berubah otomatis ke tanggal di atas.</p>

            <div style="margin-top:12px">
                <button class="btn" type="submit">Simpan Pembayaran</button>
                <a class="btn s" href="{{ route('admin.pajak.index', $slug) }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
