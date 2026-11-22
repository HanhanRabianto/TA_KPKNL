@extends('layouts.app')
@section('judul', 'Data Pengguna')

@section('isi')
    <div class="bar">
        <h2 style="margin:0">Data Pengguna (Seksi)</h2>
        <a class="btn" href="{{ route('admin.pengguna.create') }}">+ Tambah Pengguna</a>
    </div>

    <form method="GET" class="card" style="display:flex; gap:10px; align-items:end">
        <div style="flex:1">
            <label for="q" style="margin-top:0">Cari nama seksi atau username</label>
            <input id="q" name="q" value="{{ $q }}">
        </div>
        <button class="btn" type="submit">Cari</button>
        @if ($q !== '') <a class="btn s" href="{{ route('admin.pengguna.index') }}">Reset</a> @endif
    </form>

    <div class="card">
        <div class="tw">
            <table>
                <tr><th>Nama seksi</th><th>Username</th><th>Peran</th><th>Status akun</th><th>Aksi</th></tr>
                @forelse ($pengguna as $u)
                    <tr>
                        <td><strong>{{ $u->nama_seksi }}</strong>@if ($u->id === auth()->id()) <span class="note">(Anda)</span> @endif</td>
                        <td>{{ $u->username }}</td>
                        <td><span class="b {{ $u->peran === 'admin' ? 'in' : 'ok' }}">{{ ucfirst($u->peran) }}</span></td>
                        <td><span class="b {{ $u->aktif ? 'ok' : 'er' }}">{{ $u->aktif ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td style="white-space:nowrap">
                            <a class="btn s" href="{{ route('admin.pengguna.edit', $u) }}">Ubah</a>
                            @if ($u->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.pengguna.destroy', $u) }}" style="display:inline"
                                      onsubmit="return confirm('Hapus pengguna {{ $u->nama_seksi }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn d" type="submit">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="note" style="text-align:center; padding:24px">Belum ada data pengguna.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
