@extends('layouts.app')
@php $ubah = $pengemudi->exists; $bertugas = $ubah && $pengemudi->status === 'Bertugas'; @endphp
@section('judul', $ubah ? 'Ubah Pengemudi' : 'Tambah Pengemudi')

@section('isi')
    @include('partials.judul', ['judul' => $ubah ? 'Ubah Pengemudi' : 'Tambah Pengemudi', 'sub' => 'Data pengemudi dinas'])

    <div class="card" style="max-width:720px">
        <form method="POST" action="{{ $ubah ? route('admin.pengemudi.update', $pengemudi) : route('admin.pengemudi.store') }}">
            @csrf
            @if ($ubah) @method('PUT') @endif

            <div class="row">
                <div>
                    <label for="nama">Nama</label>
                    <input id="nama" name="nama" value="{{ old('nama', $pengemudi->nama) }}" required>
                    @error('nama') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="kontak">No. HP</label>
                    <input id="kontak" name="kontak" value="{{ old('kontak', $pengemudi->kontak) }}">
                    @error('kontak') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div>
                    <label for="no_sim">Nomor SIM</label>
                    <input id="no_sim" name="no_sim" value="{{ old('no_sim', $pengemudi->no_sim) }}">
                    @error('no_sim') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="jenis_sim">Jenis SIM</label>
                    <select id="jenis_sim" name="jenis_sim">
                        @foreach (\App\Models\Pengemudi::JENIS_SIM as $j)
                            <option value="{{ $j }}" @selected(old('jenis_sim', $pengemudi->jenis_sim) === $j)>{{ $j }}</option>
                        @endforeach
                    </select>
                    @error('jenis_sim') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="sim_berlaku">Masa berlaku SIM</label>
                    <input id="sim_berlaku" type="date" name="sim_berlaku" value="{{ old('sim_berlaku', optional($pengemudi->sim_berlaku)->format('Y-m-d')) }}">
                    @error('sim_berlaku') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <label for="status">Status</label>
            @if ($bertugas)
                <input id="status" value="Bertugas" readonly>
                <p class="note">Status Bertugas diatur otomatis oleh penugasan peminjaman dan kembali Tersedia setelah pengembalian diterima.</p>
            @else
                <select id="status" name="status">
                    @foreach (['Tersedia', 'Cuti'] as $s)
                        <option value="{{ $s }}" @selected(old('status', $pengemudi->status) === $s)>{{ $s }}</option>
                    @endforeach
                </select>
            @endif

            <div style="margin-top:14px">
                <button class="btn" type="submit">Simpan</button>
                <a class="btn s" href="{{ route('admin.pengemudi.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
