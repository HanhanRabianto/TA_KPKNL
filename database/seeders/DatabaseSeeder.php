<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        
        User::create([
            'nama_seksi' => 'Admin Kendaraan',
            'username'   => 'admin',
            'password'   => 'admin123',
            'peran'      => 'admin',
        ]);

        $this->call(DataContohSeeder::class);
    }
}
