@extends('layouts.app')
@section('judul', 'Catat BBM')

@section('isi')
    <h2>Catat BBM</h2>

    <div class="card" style="max-width:720px">
        <form method="POST" action="{{ route('admin.bbm.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div>
                    <label for="tgl_pemberian">Tanggal pemberian kupon</label>
                    <input id="tgl_pemberian" type="date" name="tgl_pemberian" value="{{ old('tgl_pemberian', now()->format('Y-m-d')) }}" required>
                    @error('tgl_pemberian') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="tgl_pengembalian">Tanggal pengembalian kupon</label>
                    <input id="tgl_pengembalian" type="date" name="tgl_pengembalian" value="{{ old('tgl_pengembalian', now()->format('Y-m-d')) }}" required>
                    @error('tgl_pengembalian') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div>
                    <label for="petugas">Petugas</label>
                    <input id="petugas" name="petugas" value="{{ old('petugas') }}" required>
                    @error('petugas') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="kendaraan_id">Mobil</label>
                    <select id="kendaraan_id" name="kendaraan_id" required>
                        <option value="">- pilih mobil -</option>
                        @foreach ($kendaraan as $k)
                            <option value="{{ $k->id }}" @selected(old('kendaraan_id') == $k->id)>{{ $k->plat }} - {{ $k->jenis }}</option>
                        @endforeach
                    </select>
                    @error('kendaraan_id') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div>
                    <label for="jumlah_kupon">Jumlah kupon</label>
                    <input id="jumlah_kupon" type="number" min="1" name="jumlah_kupon" value="{{ old('jumlah_kupon') }}" required>
                    @error('jumlah_kupon') <div class="ferr">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="total">Total (Rp) - diisi manual</label>
                    <input id="total" type="number" min="0" name="total" value="{{ old('total') }}" required>
                    @error('total') <div class="ferr">{{ $message }}</div> @enderror
                </div>
            </div>

            <label for="foto_struk">Foto struk SPBU (JPG atau PNG, maksimal 4 MB)</label>
            <input id="foto_struk" type="file" name="foto_struk" accept="image/png,image/jpeg" required>
            @error('foto_struk') <div class="ferr">{{ $message }}</div> @enderror

            <div style="margin-top:16px">
                <button class="btn" type="submit">Simpan</button>
                <a class="btn s" href="{{ route('admin.bbm.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
