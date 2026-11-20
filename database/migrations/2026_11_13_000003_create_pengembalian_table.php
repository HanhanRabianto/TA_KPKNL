<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->unique()->constrained('peminjaman')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedInteger('odometer_awal');
            $table->unsignedInteger('odometer_akhir');
            $table->dateTime('kembali_pada');
            $table->enum('kondisi', ['Baik', 'Perlu dicek', 'Rusak'])->default('Baik');
            $table->text('catatan_kondisi')->nullable();
            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('alasan_tolak')->nullable();
            $table->timestamp('diajukan_pada')->useCurrent();
            $table->dateTime('diputuskan_pada')->nullable();
            $table->index('status');
        });

        DB::statement('ALTER TABLE pengembalian ADD CONSTRAINT ck_kembali_odometer CHECK (odometer_akhir >= odometer_awal)');
        DB::statement("ALTER TABLE pengembalian ADD CONSTRAINT ck_kembali_rusak CHECK (kondisi <> 'Rusak' OR (catatan_kondisi IS NOT NULL AND catatan_kondisi <> ''))");
        DB::statement("ALTER TABLE pengembalian ADD CONSTRAINT ck_kembali_tolak CHECK (status <> 'ditolak' OR (alasan_tolak IS NOT NULL AND alasan_tolak <> ''))");
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
