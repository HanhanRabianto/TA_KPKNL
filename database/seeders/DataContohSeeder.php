<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DataContohSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('pengguna')->insertOrIgnore([
            ['nama_seksi' => 'Seksi Piutang Negara', 'username' => 'piutang', 'password' => Hash::make('pegawai123'), 'peran' => 'pegawai', 'aktif' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nama_seksi' => 'Seksi Pelayanan Lelang', 'username' => 'lelang', 'password' => Hash::make('pegawai123'), 'peran' => 'pegawai', 'aktif' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('kendaraan')->insertOrIgnore([
            ['plat' => 'D 1001 KN', 'jenis' => 'Toyota Innova', 'odometer' => 42150, 'status' => 'Tersedia', 'stnk_berlaku' => '2027-03-15', 'bpkb_berlaku' => '2030-03-15', 'created_at' => $now, 'updated_at' => $now],
            ['plat' => 'D 1002 KN', 'jenis' => 'Toyota Avanza', 'odometer' => 28430, 'status' => 'Tersedia', 'stnk_berlaku' => '2026-12-01', 'bpkb_berlaku' => '2029-12-01', 'created_at' => $now, 'updated_at' => $now],
            ['plat' => 'D 1003 KN', 'jenis' => 'Mitsubishi Xpander', 'odometer' => 15200, 'status' => 'Tersedia', 'stnk_berlaku' => '2027-08-20', 'bpkb_berlaku' => '2030-08-20', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('pengemudi')->insert([
            ['nama' => 'Asep Sunandar', 'kontak' => '081234567890', 'status' => 'Tersedia', 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Dedi Kurniawan', 'kontak' => '081298765432', 'status' => 'Tersedia', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
