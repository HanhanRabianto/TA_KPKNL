<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    \App\Models\User::create([
    'nama_seksi' => 'Admin Kendaraan',
    'username'   => 'admin',
    'password'   => 'admin123',
    'peran'      => 'admin',
    ]);
    $this->call(DataContohSeeder::class);
    }
}
