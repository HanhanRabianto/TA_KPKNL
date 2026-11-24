<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengemudi', function (Blueprint $table) {
            $table->string('no_sim', 30)->nullable()->after('kontak');
            $table->enum('jenis_sim', ['A', 'B1', 'B2'])->default('A')->after('no_sim');
            $table->date('sim_berlaku')->nullable()->after('jenis_sim');
        });

        // tambah status Cuti
        DB::statement("ALTER TABLE pengemudi MODIFY status ENUM('Tersedia','Bertugas','Cuti') NOT NULL DEFAULT 'Tersedia'");
    }

    public function down(): void
    {
        DB::statement("UPDATE pengemudi SET status = 'Tersedia' WHERE status = 'Cuti'");
        DB::statement("ALTER TABLE pengemudi MODIFY status ENUM('Tersedia','Bertugas') NOT NULL DEFAULT 'Tersedia'");

        Schema::table('pengemudi', function (Blueprint $table) {
            $table->dropColumn(['no_sim', 'jenis_sim', 'sim_berlaku']);
        });
    }
};
