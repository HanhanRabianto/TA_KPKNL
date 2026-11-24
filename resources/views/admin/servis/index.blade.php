@extends('layouts.app')
@section('judul', 'Servis Kendaraan')

@section('isi')
    @include('partials.judul', [
        'judul' => 'Servis Kendaraan',
        'sub'   => 'Jadwal servis dan riwayat servis kendaraan dinas',
        'aksi'  => '<a class="btn" href="'.route('admin.servis.create').'">+ Catat Servis</a>',
    ])

    @include('partials.kartu', ['kartu' => [
        ['Jumlah kendaraan', $jumlahKendaraan, '#0b3d91'],
        ['Sedang di servis', $berjalan->count(), '#a76400'],
        ['Selesai di servis', $riwayat->count(), '#127a3d'],
    ]])

    <div class="card">
        <h3 style="margin-top:0">Sedang Diservis ({{ $berjalan->count() }})</h3>
        <div class="tw">
            <table>
                <tr><th>Kendaraan</th><th>Jenis servis</th><th>Bengkel</th><th>Masuk</th><th>Estimasi selesai</th><th>Aksi</th></tr>
                @forelse ($berjalan as $s)
                    <tr>
                        <td>{{ $s->kendaraan->nama }}</td>
                        <td>{{ $s->jenis }}@if ($s->catatan)<br><span class="note">{{ $s->catatan }}</span>@endif</td>
                        <td>{{ $s->bengkel ?: '-' }}</td>
                        <td>{{ $s->tgl_masuk->format('d/m/Y') }}</td>
                        <td>{{ $s->estimasi_selesai?->format('d/m/Y') ?? '-' }}</td>
                        <td><a class="btn" href="{{ route('admin.servis.selesai', $s) }}">Tandai Selesai</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="note" style="text-align:center; padding:20px">Tidak ada kendaraan yang sedang diservis.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Riwayat Servis</h3>
        <div class="tw">
            <table>
                <tr><th>Tanggal</th><th>Kendaraan</th><th>Jenis servis</th><th>Bengkel</th><th>Biaya</th></tr>
                @forelse ($riwayat as $s)
                    <tr>
                        <td>{{ $s->tgl_masuk->format('d/m/Y') }}@if ($s->tgl_selesai)<br><span class="note">selesai {{ $s->tgl_selesai->format('d/m/Y') }}</span>@endif</td>
                        <td>{{ $s->kendaraan->nama }}</td>
                        <td>{{ $s->jenis }}</td>
                        <td>{{ $s->bengkel ?: '-' }}</td>
                        <td>Rp {{ number_format($s->biaya_akhir ?? $s->biaya, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="note" style="text-align:center; padding:20px">Belum ada riwayat servis.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection
