<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->string('tipe', 30)->default('Minibus')->after('jenis');
            $table->unsignedTinyInteger('kapasitas')->default(7)->after('tipe');
            $table->unsignedSmallInteger('tahun')->nullable()->after('kapasitas');
        });

        // tambah status Rusak
        DB::statement("ALTER TABLE kendaraan MODIFY status ENUM('Tersedia','Sedang dipakai','Sedang diservis','Rusak') NOT NULL DEFAULT 'Tersedia'");
    }

    public function down(): void
    {
        DB::statement("UPDATE kendaraan SET status = 'Tersedia' WHERE status = 'Rusak'");
        DB::statement("ALTER TABLE kendaraan MODIFY status ENUM('Tersedia','Sedang dipakai','Sedang diservis') NOT NULL DEFAULT 'Tersedia'");

        Schema::table('kendaraan', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'kapasitas', 'tahun']);
        });
    }
};
