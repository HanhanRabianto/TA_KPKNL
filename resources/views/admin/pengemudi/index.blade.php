@extends('layouts.app')
@section('judul', 'Data Pengemudi')

@section('isi')
    <div class="bar">
        <h2 style="margin:0">Data Pengemudi</h2>
        <a class="btn" href="{{ route('admin.pengemudi.create') }}">+ Tambah Pengemudi</a>
    </div>

    <form method="GET" class="card" style="display:flex; gap:10px; align-items:end">
        <div style="flex:1">
            <label for="q" style="margin-top:0">Cari nama atau kontak</label>
            <input id="q" name="q" value="{{ $q }}">
        </div>
        <button class="btn" type="submit">Cari</button>
        @if ($q !== '') <a class="btn s" href="{{ route('admin.pengemudi.index') }}">Reset</a> @endif
    </form>

    <div class="card">
        <div class="tw">
            <table>
                <tr><th>Nama</th><th>Kontak</th><th>Status</th><th>Aksi</th></tr>
                @forelse ($pengemudi as $p)
                    <tr>
                        <td><strong>{{ $p->nama }}</strong></td>
                        <td>{{ $p->kontak ?: '-' }}</td>
                        <td><span class="b {{ $p->status === 'Tersedia' ? 'ok' : 'wr' }}">{{ $p->status }}</span></td>
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
                    <tr><td colspan="4" class="note" style="text-align:center; padding:24px">Belum ada data pengemudi.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
