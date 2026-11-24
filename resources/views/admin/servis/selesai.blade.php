@extends('layouts.app')
@section('judul', 'Selesaikan Servis')

@section('isi')
    <h2>Selesaikan Servis</h2>

    <div class="card" style="max-width:760px">
        <p>
            <strong>{{ $s->kendaraan->plat }} - {{ $s->kendaraan->jenis }}</strong><br>
            {{ $s->jenis }} | masuk {{ $s->tgl_masuk->format('d/m/Y') }}
            @if ($s->bengkel) | {{ $s->bengkel }} @endif
        </p>

        <form method="POST" action="{{ route('admin.servis.selesai', $s) }}">
            @csrf

            <div class="row">
                <div>
                    <label for="tgl_selesai">Tanggal selesai</label>
                    <input id="tgl_selesai" type="date" name="tgl_selesai" min="{{ $s->tgl_masuk->format('Y-m-d') }}"
                           value="{{ old('tgl_selesai', now()->format('Y-m-d')) }}" required>
                    @error('tgl_selesai') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="biaya_akhir">Biaya akhir (Rp)</label>
                    <input id="biaya_akhir" type="number" min="0" name="biaya_akhir" value="{{ old('biaya_akhir', (int) $s->biaya) }}" required>
                    @error('biaya_akhir') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="odometer_keluar">Odometer saat keluar (km)</label>
                    <input id="odometer_keluar" type="number" min="0" name="odometer_keluar" value="{{ old('odometer_keluar', $s->kendaraan->odometer) }}">
                    @error('odometer_keluar') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="kondisi_setelah">Kondisi setelah servis</label>
                    <select id="kondisi_setelah" name="kondisi_setelah">
                        @foreach (['Baik', 'Perlu dicek'] as $o)
                            <option value="{{ $o }}" @selected(old('kondisi_setelah', 'Baik') === $o)>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('kondisi_setelah') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <label for="catatan_selesai">Catatan penyelesaian</label>
            <textarea id="catatan_selesai" name="catatan_selesai" rows="3">{{ old('catatan_selesai') }}</textarea>

            <p class="note">Setelah disimpan, kendaraan kembali berstatus <strong>Tersedia</strong>.</p>

            <div style="margin-top:12px">
                <button class="btn" type="submit">Simpan &amp; Selesaikan</button>
                <a class="btn s" href="{{ route('admin.servis.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
