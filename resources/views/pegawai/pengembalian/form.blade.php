@extends('layouts.app')
@section('judul', 'Laporan Pemakaian')

@php
    $ulang = $pengembalian && $pengembalian->status === 'ditolak';
    $v = fn ($k, $default = null) => old($k, $pengembalian?->{$k} ?? $default);
    $tanggal = old('tanggal_kembali', $pengembalian?->kembali_pada?->format('Y-m-d') ?? now()->format('Y-m-d'));
    $jam = old('jam_kembali', $pengembalian?->kembali_pada?->format('H:i') ?? now()->format('H:i'));
@endphp

@section('isi')
    @include('partials.judul', [
        'judul' => $ulang ? 'Perbaiki Laporan Pemakaian' : 'Lapor Pemakaian',
        'sub'   => 'Isi data setelah kendaraan dikembalikan',
    ])

    <div class="card" style="max-width:720px">
        <div class="flash info">
            Perjalanan: {{ $p->mulai->format('d/m/Y') }} &middot; {{ $p->tujuan }} ({{ $p->kendaraan->plat }})
        </div>

        @if ($ulang)
            <div class="flash err"><strong>Laporan sebelumnya ditolak.</strong> Alasan: {{ $pengembalian->alasan_tolak }}</div>
        @endif

        <form method="POST" action="{{ route('pegawai.pengembalian.store', $p) }}">
            @csrf

            <div class="row">
                <div>
                    <label for="tanggal_kembali">Tanggal kembali</label>
                    <input id="tanggal_kembali" type="date" name="tanggal_kembali" min="{{ $p->mulai->format('Y-m-d') }}" value="{{ $tanggal }}" required>
                    @error('tanggal_kembali') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="jam_kembali">Jam kembali</label>
                    <input id="jam_kembali" type="time" name="jam_kembali" value="{{ $jam }}" required>
                    @error('jam_kembali') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div>
                    <label for="odometer_awal">Odometer awal (otomatis dari sistem)</label>
                    <input id="odometer_awal" value="{{ $p->kendaraan->odometer }}" readonly>
                </div>
                <div>
                    <label for="odometer_akhir">Odometer akhir (km)</label>
                    <input id="odometer_akhir" type="number" min="{{ $p->kendaraan->odometer }}" name="odometer_akhir" value="{{ $v('odometer_akhir') }}" required>
                    @error('odometer_akhir') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <label for="kondisi">Kondisi kendaraan</label>
            <select id="kondisi" name="kondisi" onchange="document.getElementById('kotak-catatan').style.display = this.value === 'Baik' ? 'none' : ''">
                @foreach (['Baik', 'Perlu dicek', 'Rusak'] as $o)
                    <option value="{{ $o }}" @selected($v('kondisi', 'Baik') === $o)>{{ $o }}</option>
                @endforeach
            </select>
            @error('kondisi') <div class="ferr">{{ $message }}</div> @enderror

            <div id="kotak-catatan" style="{{ $v('kondisi', 'Baik') === 'Baik' ? 'display:none' : '' }}">
                <label for="catatan_kondisi">Catatan kerusakan / kondisi kendaraan (wajib jika Rusak)</label>
                <textarea id="catatan_kondisi" name="catatan_kondisi" rows="3" placeholder="Jelaskan bagian yang rusak atau perlu dicek">{{ $v('catatan_kondisi') }}</textarea>
                @error('catatan_kondisi') <div class="ferr">{{ $message }}</div> @enderror
            </div>

            <div style="margin-top:16px">
                <button class="btn" type="submit">Kirim Laporan dan Ajukan Pengembalian</button>
                <a class="btn s" href="{{ route('pegawai.peminjaman.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
