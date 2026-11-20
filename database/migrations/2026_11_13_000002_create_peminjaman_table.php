<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('kendaraan_id')->nullable()->constrained('kendaraan')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('pengemudi_id')->nullable()->constrained('pengemudi')->cascadeOnUpdate()->nullOnDelete();
            $table->enum('lokasi_tujuan', ['Dalam kota', 'Luar kota'])->default('Dalam kota');
            $table->boolean('menginap')->default(false);
            $table->string('tujuan', 255);
            $table->string('petugas', 100);
            $table->unsignedTinyInteger('penumpang')->default(1);
            $table->text('catatan')->nullable();
            $table->dateTime('mulai');
            $table->dateTime('selesai')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'verifikasi', 'kembali_ditolak', 'selesai'])->default('menunggu');
            $table->string('nomor_surat', 60)->nullable();
            $table->text('alasan_tolak')->nullable();
            $table->timestamp('diajukan_pada')->useCurrent();
            $table->dateTime('diputuskan_pada')->nullable();
            $table->index(['pengguna_id', 'status']);
            $table->index(['kendaraan_id', 'status']);
            $table->index('status');
            $table->index('mulai');
        });

        DB::statement('ALTER TABLE peminjaman ADD CONSTRAINT ck_pinjam_penumpang CHECK (penumpang >= 1)');
        DB::statement('ALTER TABLE peminjaman ADD CONSTRAINT ck_pinjam_waktu CHECK (selesai IS NULL OR selesai >= mulai)');
        DB::statement("ALTER TABLE peminjaman ADD CONSTRAINT ck_pinjam_tolak CHECK (status <> 'ditolak' OR (alasan_tolak IS NOT NULL AND alasan_tolak <> ''))");
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
