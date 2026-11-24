@extends('layouts.app')
@section('judul', 'Data Pengguna')

@section('isi')
    @include('partials.judul', [
        'judul' => 'Data Pengguna',
        'sub'   => 'Kelola akun admin dan seksi',
        'aksi'  => '<a class="btn" href="'.route('admin.pengguna.create').'">+ Tambah Pengguna</a>',
    ])

    <form method="GET" class="card no-print" style="display:flex; gap:10px; align-items:end; flex-wrap:wrap">
        <div style="flex:1; min-width:220px">
            <label for="q" style="margin-top:0">Cari nama seksi atau username</label>
            <input id="q" name="q" value="{{ $q }}">
        </div>
        <div style="min-width:180px">
            <label for="peran" style="margin-top:0">Peran</label>
            <select id="peran" name="peran">
                @foreach (['' => 'Semua peran', 'admin' => 'Admin', 'pegawai' => 'Seksi'] as $nilai => $nama)
                    <option value="{{ $nilai }}" @selected($peran === (string) $nilai)>{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn" type="submit">Terapkan</button>
        @if ($q !== '' || $peran !== '') <a class="btn s" href="{{ route('admin.pengguna.index') }}">Reset</a> @endif
    </form>

    <div class="card">
        <div class="tw">
            <table>
                <tr><th>Nama seksi</th><th>Username</th><th>Email</th><th>Peran</th><th>Status</th><th>Aksi</th></tr>
                @forelse ($pengguna as $u)
                    <tr>
                        <td><strong>{{ $u->nama_seksi }}</strong>@if ($u->id === auth()->id()) <span class="note">(Anda)</span> @endif</td>
                        <td>{{ $u->username }}</td>
                        <td>{{ $u->email ?: '-' }}</td>
                        <td><span class="b {{ $u->peran === 'admin' ? 'in' : 'ok' }}">{{ $u->label_peran }}</span></td>
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
                    <tr><td colspan="6" class="note" style="text-align:center; padding:24px">Tidak ada pengguna{{ ($q !== '' || $peran !== '') ? ' pada filter ini' : '' }}.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
