@extends('layouts.app')
@section('judul', 'Data Pengemudi')

@section('isi')
    @include('partials.judul', [
        'judul' => 'Data Pengemudi',
        'sub'   => 'Kelola pengemudi dinas dan masa berlaku SIM',
        'aksi'  => '<a class="btn" href="'.route('admin.pengemudi.create').'">+ Tambah Pengemudi</a>',
    ])

    <form method="GET" class="card no-print" style="display:flex; gap:10px; align-items:end; flex-wrap:wrap">
        <div style="flex:1; min-width:220px">
            <label for="q" style="margin-top:0">Cari nama atau No. HP</label>
            <input id="q" name="q" value="{{ $q }}">
        </div>
        <div style="min-width:180px">
            <label for="status" style="margin-top:0">Status</label>
            <select id="status" name="status">
                @foreach (['' => 'Semua status', 'Tersedia' => 'Tersedia', 'Bertugas' => 'Bertugas', 'Cuti' => 'Cuti'] as $nilai => $nama)
                    <option value="{{ $nilai }}" @selected($status === (string) $nilai)>{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn" type="submit">Terapkan</button>
        @if ($q !== '' || $status !== '') <a class="btn s" href="{{ route('admin.pengemudi.index') }}">Reset</a> @endif
    </form>

    <div class="card">
        <div class="tw">
            <table>
                <tr><th>Nama</th><th>No. HP</th><th>Jenis SIM</th><th>Masa berlaku SIM</th><th>Perjalanan bulan ini</th><th>Status</th><th>Aksi</th></tr>
                @forelse ($pengemudi as $p)
                    <tr>
                        <td><strong>{{ $p->nama }}</strong>@if ($p->no_sim)<br><span class="note">SIM {{ $p->no_sim }}</span>@endif</td>
                        <td>{{ $p->kontak ?: '-' }}</td>
                        <td>{{ $p->jenis_sim }}</td>
                        <td>
                            @if ($p->sim_berlaku)
                                {{ $p->sim_berlaku->locale('id')->translatedFormat('M Y') }}
                                @if ($p->sim_segera_habis) <br><span class="b wr">Segera habis</span> @endif
                            @else
                                <span class="note">Belum diisi</span>
                            @endif
                        </td>
                        <td>{{ $perjalanan[$p->id] ?? 0 }}</td>
                        <td><span class="b {{ $p->kelas_status }}">{{ $p->status }}</span></td>
                        <td style="white-space:nowrap">
                            <a class="btn s" href="{{ route('admin.pengemudi.edit', $p) }}">Ubah</a>
                            <form method="POST" action="{{ route('admin.pengemudi.destroy', $p) }}" style="display:inline"
                                  onsubmit="return confirm('Hapus pengemudi {{ $p->nama }}?')">
                                @csrf @method('DELETE')
                                <button class="btn d" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="note" style="text-align:center; padding:24px">Tidak ada pengemudi{{ ($q !== '' || $status !== '') ? ' pada filter ini' : '' }}.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
