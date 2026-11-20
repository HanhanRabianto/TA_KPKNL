<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function form(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // hanya akun aktif yang boleh masuk
        $ok = Auth::attempt(
            ['username' => $data['username'], 'password' => $data['password'], 'aktif' => 1],
            $request->boolean('ingat')
        );

        if (! $ok) {
            return back()
                ->withErrors(['username' => 'Username atau kata sandi salah, atau akun tidak aktif.'])
                ->onlyInput('username');
        }

        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function arah(): RedirectResponse
    {
        return Auth::user()->peran === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('pegawai.beranda');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
