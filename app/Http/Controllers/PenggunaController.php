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
    public const SANDI_RESET = 'sementara123';

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $peran = in_array($request->query('peran'), ['admin', 'pegawai'], true) ? $request->query('peran') : '';

        $pengguna = User::query()
            ->when($q !== '', fn ($w) => $w->where(function ($s) use ($q) {
                $s->where('nama_seksi', 'like', "%{$q}%")->orWhere('username', 'like', "%{$q}%");
            }))
            ->when($peran !== '', fn ($w) => $w->where('peran', $peran))
            ->orderBy('peran')
            ->orderBy('nama_seksi')
            ->get();

        return view('admin.pengguna.index', [
            'pengguna'   => $pengguna,
            'q'          => $q,
            'peran'      => $peran,
            'jumlahSeksi' => User::where('peran', 'pegawai')->count(),
        ]);
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
            'peran'      => ['required', Rule::in(['admin', 'pegawai'])],
            'email'      => ['nullable', 'email', 'max:100'],
            'password'   => ['required', 'string', 'min:6'],
        ], $this->pesan());

        $data['aktif'] = true;

        User::create($data);

        return redirect()->route('admin.pengguna.index')->with('ok', 'Data pengguna tersimpan.');
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
            'peran'      => ['required', Rule::in(['admin', 'pegawai'])],
            'aktif'      => ['required', 'in:0,1'],
        ], $this->pesan());

        $data['aktif'] = $data['aktif'] === '1';

        
        if ($pengguna->id === $request->user()->id) {
            $data['aktif'] = true;
            $data['peran'] = 'admin';
        }

        $pengguna->update($data);

        return redirect()->route('admin.pengguna.index')->with('ok', 'Data pengguna tersimpan.');
    }

    public function reset(User $pengguna): RedirectResponse
    {
        $pengguna->update(['password' => self::SANDI_RESET]);

        return back()->with('ok', 'Kata sandi direset menjadi '.self::SANDI_RESET.'.');
    }

    public function destroy(Request $request, User $pengguna): RedirectResponse
    {
        if ($pengguna->id === $request->user()->id) {
            return back()->with('err', 'Akun yang sedang Anda pakai tidak bisa dihapus.');
        }

        if ($pengguna->peran === 'admin' && User::where('peran', 'admin')->count() <= 1) {
            return back()->with('err', 'Admin terakhir tidak bisa dihapus.');
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
            'username.unique'     => 'Username sudah terdaftar.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip, dan garis bawah.',
            'email.email'         => 'Format email tidak valid.',
            'password.required'   => 'Kata sandi sementara wajib diisi.',
            'password.min'        => 'Kata sandi minimal 6 karakter.',
            'peran.in'            => 'Peran tidak valid.',
        ];
    }
}
