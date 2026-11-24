<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function index(Request $request): View
    {
        $uid = $request->user()->id;
        $milik = fn () => Peminjaman::where('pengguna_id', $uid);

        return view('pegawai.beranda', [
            'menunggu'     => $milik()->where('status', 'menunggu')->count(),
            'disetujui'    => $milik()->where('status', 'disetujui')->count(),
            'selesaiBulan' => $milik()->where('status', 'selesai')
                ->whereYear('selesai', now()->year)->whereMonth('selesai', now()->month)->count(),
            'terbaru'      => Peminjaman::with('kendaraan')->where('pengguna_id', $uid)->orderByDesc('id')->limit(5)->get(),
        ]);
    }
}
