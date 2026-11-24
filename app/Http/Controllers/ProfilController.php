<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function show(Request $request): View
    {
        return view('profil', ['u' => $request->user()]);
    }

    
    public function simpan(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['nullable', 'email', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:30'],
        ], ['email.email' => 'Format email tidak valid.']);

        $request->user()->update($data);

        return back()->with('ok', 'Profil tersimpan.');
    }

    public function sandi(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'sandi_lama' => ['required', 'string'],
            'sandi_baru' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'sandi_lama.required'  => 'Kata sandi lama wajib diisi.',
            'sandi_baru.required'  => 'Kata sandi baru wajib diisi.',
            'sandi_baru.min'       => 'Kata sandi baru minimal 8 karakter.',
            'sandi_baru.confirmed' => 'Pengulangan kata sandi baru tidak sama.',
        ]);

        if (! Hash::check($data['sandi_lama'], $request->user()->password)) {
            return back()->withErrors(['sandi_lama' => 'Kata sandi lama tidak sesuai.']);
        }

        $request->user()->update(['password' => $data['sandi_baru']]);

        return back()->with('ok', 'Kata sandi diperbarui.');
    }
}
