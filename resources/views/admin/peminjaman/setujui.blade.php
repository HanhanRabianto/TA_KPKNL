@extends('layouts.app')
@section('judul', 'Setujui Pinjaman')

@section('isi')
    <h2>Setujui Pengajuan</h2>

    <div class="card" style="max-width:720px">
        <p>
            <strong>{{ $p->pengguna->nama_seksi }}</strong> (petugas: {{ $p->petugas }})<br>
            {{ $p->tujuan }} - {{ $p->lokasi_tujuan }}, {{ $p->menginap ? 'menginap' : 'tidak menginap' }}<br>
            {{ $p->mulai->format('d/m/Y H:i') }} | {{ $p->penumpang }} penumpang<br>
            <span class="note">Catatan: {{ $p->catatan ?: '-' }}</span>
        </p>
        <p>
            <strong>Pilihan seksi:</strong> {{ $p->kendaraan?->plat }} - {{ $p->kendaraan?->jenis }},
            pengemudi {{ $p->pengemudi?->nama ?? 'tanpa pengemudi' }}
        </p>
        @if ($p->kendaraan && $p->kendaraan->status !== 'Tersedia')
            <div class="flash err">Kendaraan pilihan seksi sudah tidak tersedia ({{ $p->kendaraan->status }}). Pilih kendaraan lain di bawah atau tolak pengajuan.</div>
        @endif
        @if ($p->pengemudi && $p->pengemudi->status !== 'Tersedia')
            <div class="flash err">Pengemudi pilihan seksi sedang bertugas. Pilih pengemudi lain atau tanpa pengemudi.</div>
        @endif
    </div>

    <div class="card" style="max-width:720px">
        @if ($kendaraan->isEmpty())
            <div class="flash err">Tidak ada kendaraan berstatus Tersedia saat ini. Pengajuan belum bisa disetujui.</div>
        @endif

        <form method="POST" action="{{ route('admin.peminjaman.setujui', $p) }}">
            @csrf

            <label for="kendaraan_id">Kendaraan yang ditugaskan (default: pilihan seksi, bisa diganti)</label>
            <select id="kendaraan_id" name="kendaraan_id" required>
                <option value="">- pilih kendaraan -</option>
                @foreach ($kendaraan as $k)
                    <option value="{{ $k->id }}" @selected(old('kendaraan_id', $p->kendaraan_id) == $k->id)>{{ $k->plat }} - {{ $k->jenis }}</option>
                @endforeach
            </select>
            @error('kendaraan_id') <div class="ferr">{{ $message }}</div> @enderror

            <label for="pengemudi_id">Pengemudi (opsional, hanya yang tersedia)</label>
            <select id="pengemudi_id" name="pengemudi_id">
                <option value="">Tanpa pengemudi</option>
                @foreach ($pengemudi as $s)
                    <option value="{{ $s->id }}" @selected(old('pengemudi_id', $p->pengemudi_id) == $s->id)>{{ $s->nama }}</option>
                @endforeach
            </select>
            @error('pengemudi_id') <div class="ferr">{{ $message }}</div> @enderror

            <label for="nomor_surat">Nomor surat tugas</label>
            <input id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $nomorSaran) }}" required>
            @error('nomor_surat') <div class="ferr">{{ $message }}</div> @enderror

            <div style="margin-top:16px">
                <button class="btn" type="submit" @if ($kendaraan->isEmpty()) disabled @endif>Setujui</button>
                <a class="btn s" href="{{ route('admin.peminjaman.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
