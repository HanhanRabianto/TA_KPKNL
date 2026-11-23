@extends('layouts.app')
@section('judul', 'Laporan Pengembalian')

@php
    $ulang = $pengembalian && $pengembalian->status === 'ditolak';
    $v = fn ($k, $default = null) => old($k, $pengembalian?->{$k} ?? $default);
    $kembali = old('kembali_pada', $pengembalian?->kembali_pada?->format('Y-m-d\TH:i'));
@endphp

@section('isi')
    <h2>{{ $ulang ? 'Ajukan Kembali Laporan Pengembalian' : 'Laporan Pengembalian Kendaraan' }}</h2>

    @if ($ulang)
        <div class="flash err"><strong>Laporan sebelumnya ditolak.</strong><br>Alasan: {{ $pengembalian->alasan_tolak }}</div>
    @endif

    <div class="card" style="max-width:760px">
        <p>
            <strong>{{ $p->kendaraan->plat }} - {{ $p->kendaraan->jenis }}</strong><br>
            {{ $p->tujuan }} | mulai {{ $p->mulai->format('d/m/Y H:i') }}
        </p>

        <form method="POST" action="{{ route('pegawai.pengembalian.store', $p) }}">
            @csrf

            <div class="row">
                <div>
                    <label for="odometer_awal">Odometer awal (otomatis)</label>
                    <input id="odometer_awal" value="{{ $p->kendaraan->odometer }}" readonly>
                </div>
                <div>
                    <label for="odometer_akhir">Odometer akhir (km)</label>
                    <input id="odometer_akhir" type="number" min="{{ $p->kendaraan->odometer }}" name="odometer_akhir"
                           value="{{ $v('odometer_akhir') }}" required>
                    @error('odometer_akhir') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="kembali_pada">Tanggal dan jam kembali</label>
                    <input id="kembali_pada" type="datetime-local" name="kembali_pada"
                           min="{{ $p->mulai->format('Y-m-d\TH:i') }}"
                           value="{{ $kembali }}" required>
                    @error('kembali_pada') <div class="ferr">{{ $message }}</div> @enderror
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
                <button class="btn" type="submit">{{ $ulang ? 'Kirim Ulang' : 'Kirim Laporan' }}</button>
                <a class="btn s" href="{{ route('pegawai.peminjaman.index', ['tab' => 'diterima']) }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
