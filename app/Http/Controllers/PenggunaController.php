<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PenggunaController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $pengguna = User::query()
            ->when($q !== '', fn ($w) => $w->where(function ($s) use ($q) {
                $s->where('nama_seksi', 'like', "%{$q}%")->orWhere('username', 'like', "%{$q}%");
            }))
            ->orderBy('peran')
            ->orderBy('nama_seksi')
            ->get();

        return view('admin.pengguna.index', compact('pengguna', 'q'));
    }

    public function create(): View
    {
        return view('admin.pengguna.form', ['pengguna' => new User(['peran' => 'pegawai', 'aktif' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_seksi' => ['required', 'string', 'max:100'],
            'username'   => ['required', 'string', 'max:50', 'alpha_dash', 'unique:pengguna,username'],
            'password'   => ['required', 'string', 'min:6', 'confirmed'],
            'peran'      => ['required', Rule::in(['admin', 'pegawai'])],
        ], $this->pesan());

        $data['aktif'] = $request->boolean('aktif');

        User::create($data);

        return redirect()->route('admin.pengguna.index')->with('ok', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $pengguna): View
    {
        return view('admin.pengguna.form', compact('pengguna'));
    }

    public function update(Request $request, User $pengguna): RedirectResponse
    {
        $data = $request->validate([
            'nama_seksi' => ['required', 'string', 'max:100'],
            'username'   => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('pengguna', 'username')->ignore($pengguna->id)],
            'password'   => ['nullable', 'string', 'min:6', 'confirmed'],
            'peran'      => ['required', Rule::in(['admin', 'pegawai'])],
        ], $this->pesan());

        $data['aktif'] = $request->boolean('aktif');

        
        if ($pengguna->id === $request->user()->id) {
            $data['aktif'] = true;
            $data['peran'] = 'admin';
        }

        
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $pengguna->update($data);

        return redirect()->route('admin.pengguna.index')->with('ok', 'Data pengguna diperbarui.');
    }

    public function destroy(Request $request, User $pengguna): RedirectResponse
    {
        if ($pengguna->id === $request->user()->id) {
            return back()->with('err', 'Anda tidak dapat menghapus akun yang sedang dipakai.');
        }

        if (DB::table('peminjaman')->where('pengguna_id', $pengguna->id)->exists()) {
            return back()->with('err', 'Pengguna ini punya riwayat peminjaman. Nonaktifkan saja akunnya lewat tombol Ubah.');
        }

        $pengguna->delete();

        return redirect()->route('admin.pengguna.index')->with('ok', 'Pengguna dihapus.');
    }

    private function pesan(): array
    {
        return [
            'nama_seksi.required' => 'Nama seksi wajib diisi.',
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username sudah dipakai.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip, dan garis bawah.',
            'password.required'   => 'Kata sandi wajib diisi.',
            'password.min'        => 'Kata sandi minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi kata sandi tidak sama.',
            'peran.in'            => 'Peran tidak valid.',
        ];
    }
}
