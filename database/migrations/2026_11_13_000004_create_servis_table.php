<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tgl_masuk');
            $table->string('jenis', 150);
            $table->string('bengkel', 100)->nullable();
            $table->unsignedInteger('odometer')->nullable();
            $table->decimal('biaya', 14, 2)->default(0);
            $table->date('estimasi_selesai')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['Berjalan', 'Selesai'])->default('Berjalan');
            $table->date('tgl_selesai')->nullable();
            $table->decimal('biaya_akhir', 14, 2)->nullable();
            $table->unsignedInteger('odometer_keluar')->nullable();
            $table->enum('kondisi_setelah', ['Baik', 'Perlu dicek'])->nullable();
            $table->text('catatan_selesai')->nullable();
            $table->timestamps();
            $table->index(['kendaraan_id', 'status']);
            $table->index('tgl_masuk');
        });

        DB::statement('ALTER TABLE servis ADD CONSTRAINT ck_servis_biaya CHECK (biaya >= 0)');
        DB::statement('ALTER TABLE servis ADD CONSTRAINT ck_servis_selesai CHECK (tgl_selesai IS NULL OR tgl_selesai >= tgl_masuk)');
    }

    public function down(): void
    {
        Schema::dropIfExists('servis');
    }
};
