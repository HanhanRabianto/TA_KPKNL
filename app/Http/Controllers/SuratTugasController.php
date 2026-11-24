<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuratTugasController extends Controller
{
    
    public function show(Request $request, Peminjaman $peminjaman): View
    {
        $user = $request->user();
        abort_unless($user->peran === 'admin' || $peminjaman->pengguna_id === $user->id, 403);
        abort_unless(in_array($peminjaman->status, ['disetujui', 'verifikasi', 'kembali_ditolak', 'selesai'], true), 404);

        return view('surat.tugas', ['p' => $peminjaman->load(['pengguna', 'kendaraan', 'pengemudi'])]);
    }
}
