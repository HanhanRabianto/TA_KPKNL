@extends('layouts.app')
@section('judul', 'Data Kendaraan')

@php
    $opsiStatus = ['' => 'Semua status', 'Tersedia' => 'Tersedia', 'Sedang dipakai' => 'Dipakai', 'Sedang diservis' => 'Sedang diservis', 'Rusak' => 'Rusak'];
@endphp

@section('isi')
    @include('partials.judul', [
        'judul' => 'Data Kendaraan',
        'sub'   => 'Kelola armada kendaraan dinas',
        'aksi'  => '<a class="btn" href="'.route('admin.kendaraan.create').'">+ Tambah Kendaraan</a>',
    ])

    <form method="GET" class="card no-print" style="display:flex; gap:10px; align-items:end; flex-wrap:wrap">
        <div style="flex:1; min-width:220px">
            <label for="q" style="margin-top:0">Cari plat, merek, atau jenis</label>
            <input id="q" name="q" value="{{ $q }}" placeholder="Contoh: D 1001 atau Innova">
        </div>
        <div style="min-width:180px">
            <label for="status" style="margin-top:0">Status</label>
            <select id="status" name="status">
                @foreach ($opsiStatus as $nilai => $nama)
                    <option value="{{ $nilai }}" @selected($status === (string) $nilai)>{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn" type="submit">Terapkan</button>
        @if ($q !== '' || $status !== '') <a class="btn s" href="{{ route('admin.kendaraan.index') }}">Reset</a> @endif
    </form>

    <div class="card">
        <div class="tw">
            <table>
                <tr>
                    <th>Merek/Tipe</th><th>Plat</th><th>Jenis</th><th>Kapasitas</th><th>Tahun</th><th>Odometer</th>
                    <th>STNK s.d.</th><th>BPKB s.d.</th><th>Status</th><th>Aksi</th>
                </tr>
                @forelse ($kendaraan as $k)
                    <tr>
                        <td><strong>{{ $k->jenis }}</strong></td>
                        <td>{{ $k->plat }}</td>
                        <td>{{ $k->tipe }}</td>
                        <td>{{ $k->kapasitas }} orang</td>
                        <td>{{ $k->tahun ?? '-' }}</td>
                        <td>{{ number_format($k->odometer, 0, ',', '.') }} km</td>
                        @foreach (['stnk_berlaku', 'bpkb_berlaku'] as $kolom)
                            @php $sisa = $k->sisaHari($kolom); @endphp
                            <td>
                                @if ($k->{$kolom})
                                    {{ $k->{$kolom}->locale('id')->translatedFormat('M Y') }}
                                    @if ($sisa < 0) <br><span class="b er">Kadaluarsa</span>
                                    @elseif ($sisa <= 90) <br><span class="b wr">Segera habis</span> @endif
                                @else
                                    <span class="note">Belum diisi</span>
                                @endif
                            </td>
                        @endforeach
                        <td><span class="b {{ $k->kelas_status }}">{{ $k->label_status }}</span></td>
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
                    <tr><td colspan="10" class="note" style="text-align:center; padding:24px">Tidak ada kendaraan{{ ($q !== '' || $status !== '') ? ' pada filter ini' : '' }}.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
