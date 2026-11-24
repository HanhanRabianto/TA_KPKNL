@extends('layouts.app')
@section('judul', 'Verifikasi Pengajuan')

@php
    $kendaraanOk = $p->kendaraan && $p->kendaraan->status === 'Tersedia';
    $pengemudiOk = ! $p->pengemudi || $p->pengemudi->status === 'Tersedia';
    $menunggu = $p->status === 'menunggu';
@endphp

@section('isi')
    <h2>Verifikasi Pengajuan</h2>

    <div class="card" style="max-width:640px">
        <div class="flash" style="background:#e9edf3; color:#3d4a5c">
            Seksi memilih kendaraan dan pengemudi saat mengajukan. Periksa pengajuan lalu setujui atau tolak.
            Setelah disetujui, surat tugas terbit dan tampil di Riwayat Saya seksi.
        </div>

        @if ($menunggu && ! $kendaraanOk)
            <div class="flash err">Kendaraan pilihan seksi sudah tidak tersedia ({{ $p->kendaraan?->status ?? 'tidak ditemukan' }}). Pengajuan tidak dapat disetujui, silakan tolak dengan alasan.</div>
        @endif
        @if ($menunggu && ! $pengemudiOk)
            <div class="flash err">Pengemudi pilihan seksi sedang bertugas. Pengajuan tidak dapat disetujui, silakan tolak dengan alasan.</div>
        @endif

        <table style="font-size:14px">
            @foreach ([
                'Seksi'              => $p->pengguna->nama_seksi,
                'Petugas'            => $p->petugas,
                'Tujuan'             => $p->tujuan,
                'Waktu'              => $p->mulai->locale('id')->translatedFormat('d M Y H:i'),
                'Lokasi tujuan'      => $p->lokasi_tujuan,
                'Menginap'           => $p->menginap ? 'Menginap' : 'Tidak menginap',
                'Jumlah penumpang'   => $p->penumpang.' orang',
                'Preferensi'         => $p->catatan ?: 'Tidak ada',
                'Kendaraan dipilih'  => $p->kendaraan ? $p->kendaraan->jenis.', '.$p->kendaraan->plat : '-',
                'Pengemudi dipilih'  => $p->pengemudi?->nama ?? 'Tanpa pengemudi',
            ] as $label => $nilai)
                <tr>
                    <td style="width:190px; color:var(--mu); border:0; padding:6px 8px">{{ $label }}</td>
                    <td style="border:0; padding:6px 8px">{{ $nilai }}</td>
                </tr>
            @endforeach
        </table>

        <hr style="border:0; border-top:1px solid var(--bd); margin:16px 0">

        <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap">
            <a class="btn s" href="{{ route('admin.peminjaman.index') }}">Batal</a>

            @if ($menunggu)
                <div style="display:flex; gap:8px">
                    <a class="btn d" href="{{ route('admin.peminjaman.tolak', $p) }}">Tolak</a>
                    <form method="POST" action="{{ route('admin.peminjaman.setujui', $p) }}">
                        @csrf
                        <button class="btn" type="submit" @if (! $kendaraanOk || ! $pengemudiOk) disabled @endif>Setujui</button>
                    </form>
                </div>
            @else
                <span class="note">Pengajuan ini sudah diproses ({{ $p->label_status }}).</span>
            @endif
        </div>
    </div>
@endsection
