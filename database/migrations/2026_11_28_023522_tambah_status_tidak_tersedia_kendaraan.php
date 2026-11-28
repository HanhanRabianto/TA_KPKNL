<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE kendaraan MODIFY status ENUM('Tersedia','Tidak tersedia','Sedang dipakai','Sedang diservis','Rusak') NOT NULL DEFAULT 'Tersedia'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE kendaraan SET status = 'Tersedia' WHERE status = 'Tidak tersedia'");
            DB::statement("ALTER TABLE kendaraan MODIFY status ENUM('Tersedia','Sedang dipakai','Sedang diservis','Rusak') NOT NULL DEFAULT 'Tersedia'");
        }
    }
};
