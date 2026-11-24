@extends('layouts.app')
@section('judul', 'Catat Servis')

@section('isi')
    <h2>Catat Servis Kendaraan</h2>

    <div class="card" style="max-width:760px">
        @if ($kendaraan->isEmpty())
            <div class="flash err">Tidak ada kendaraan berstatus Tersedia. Kendaraan yang sedang dipakai atau diservis tidak dapat dicatat servisnya.</div>
        @endif

        <form method="POST" action="{{ route('admin.servis.store') }}">
            @csrf

            <div class="row">
                <div>
                    <label for="kendaraan_id">Kendaraan (yang tersedia)</label>
                    <select id="kendaraan_id" name="kendaraan_id" required>
                        <option value="">- pilih kendaraan -</option>
                        @foreach ($kendaraan as $k)
                            <option value="{{ $k->id }}" @selected(old('kendaraan_id') == $k->id)>{{ $k->plat }} - {{ $k->jenis }}</option>
                        @endforeach
                    </select>
                    @error('kendaraan_id') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="tgl_masuk">Tanggal masuk servis</label>
                    <input id="tgl_masuk" type="date" name="tgl_masuk" value="{{ old('tgl_masuk', now()->format('Y-m-d')) }}" required>
                    @error('tgl_masuk') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <label for="jenis">Jenis servis</label>
            <input id="jenis" name="jenis" value="{{ old('jenis') }}" placeholder="Contoh: ganti oli, servis berkala" required>
            @error('jenis') <div class="ferr">{{ $message }}</div> @enderror

            <div class="row">
                <div>
                    <label for="bengkel">Bengkel</label>
                    <input id="bengkel" name="bengkel" value="{{ old('bengkel') }}">
                    @error('bengkel') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="odometer">Odometer saat masuk (km)</label>
                    <input id="odometer" type="number" min="0" name="odometer" value="{{ old('odometer') }}">
                    @error('odometer') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="biaya">Estimasi biaya (Rp)</label>
                    <input id="biaya" type="number" min="0" name="biaya" value="{{ old('biaya') }}">
                    @error('biaya') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="estimasi_selesai">Estimasi selesai</label>
                    <input id="estimasi_selesai" type="date" name="estimasi_selesai" value="{{ old('estimasi_selesai') }}">
                    @error('estimasi_selesai') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <label for="catatan">Catatan</label>
            <textarea id="catatan" name="catatan" rows="3">{{ old('catatan') }}</textarea>
            @error('catatan') <div class="ferr">{{ $message }}</div> @enderror

            <p class="note">Setelah disimpan, status kendaraan menjadi <strong>Sedang diservis</strong> dan tidak bisa dipinjam sampai servis diselesaikan.</p>

            <div style="margin-top:12px">
                <button class="btn" type="submit" @if ($kendaraan->isEmpty()) disabled @endif>Simpan</button>
                <a class="btn s" href="{{ route('admin.servis.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
