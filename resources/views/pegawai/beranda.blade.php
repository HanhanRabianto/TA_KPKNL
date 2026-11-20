@extends('layouts.app')
@section('judul', 'Beranda')

@section('isi')
    <h2>Beranda</h2>
    <div class="card">
        <p>Selamat datang, <strong>{{ auth()->user()->nama_seksi }}</strong>.</p>
        <p>Fitur pengajuan peminjaman akan ditambahkan pada tahap berikutnya.</p>
    </div>
@endsection
