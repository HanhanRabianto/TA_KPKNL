@extends('layouts.app')
@section('judul', 'Data Kendaraan')

@php
    // kelas badge status kendaraan
    $kelasStatus = ['Tersedia' => 'ok', 'Sedang dipakai' => 'in', 'Sedang diservis' => 'wr'];
@endphp

@section('isi')
    <div class="bar">
        <h2 style="margin:0">Data Kendaraan</h2>
        <a class="btn" href="{{ route('admin.kendaraan.create') }}">+ Tambah Kendaraan</a>
    </div>

    <form method="GET" class="card" style="display:flex; gap:10px; align-items:end">
        <div style="flex:1">
            <label for="q" style="margin-top:0">Cari nomor polisi atau jenis</label>
            <input id="q" name="q" value="{{ $q }}" placeholder="Contoh: D 1001 atau Innova">
        </div>
        <button class="btn" type="submit">Cari</button>
        @if ($q !== '') <a class="btn s" href="{{ route('admin.kendaraan.index') }}">Reset</a> @endif
    </form>

    <div class="card">
        <div class="tw">
            <table>
                <tr>
                    <th>No. Polisi</th><th>Jenis</th><th>Odometer</th><th>Status</th>
                    <th>STNK berlaku sampai</th><th>BPKB berlaku sampai</th><th>Aksi</th>
                </tr>
                @forelse ($kendaraan as $k)
                    <tr>
                        <td><strong>{{ $k->plat }}</strong></td>
                        <td>{{ $k->jenis }}</td>
                        <td>{{ number_format($k->odometer, 0, ',', '.') }} km</td>
                        <td><span class="b {{ $kelasStatus[$k->status] ?? 'in' }}">{{ $k->status }}</span></td>
                        @foreach (['stnk_berlaku', 'bpkb_berlaku'] as $kolom)
                            @php $sisa = $k->sisaHari($kolom); @endphp
                            <td>
                                @if ($k->{$kolom})
                                    {{ $k->{$kolom}->format('d/m/Y') }}
                                    @if ($sisa < 0)
                                        <br><span class="b er">Lewat {{ abs($sisa) }} hari</span>
                                    @elseif ($sisa <= 30)
                                        <br><span class="b wr">{{ $sisa }} hari lagi</span>
                                    @endif
                                @else
                                    <span class="note">Belum diisi</span>
                                @endif
                            </td>
                        @endforeach
                        <td style="white-space:nowrap">
                            <a class="btn s" href="{{ route('admin.kendaraan.edit', $k) }}">Ubah</a>
                            <form method="POST" action="{{ route('admin.kendaraan.destroy', $k) }}" style="display:inline"
                                  onsubmit="return confirm('Hapus kendaraan {{ $k->plat }}?')">
                                @csrf @method('DELETE')
                                <button class="btn d" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="note" style="text-align:center; padding:24px">Belum ada data kendaraan.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
