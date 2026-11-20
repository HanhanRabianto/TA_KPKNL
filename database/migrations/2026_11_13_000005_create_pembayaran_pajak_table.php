<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_pajak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('jenis', ['STNK', 'BPKB']);
            $table->date('tgl_bayar');
            $table->decimal('nominal', 14, 2)->default(0);
            $table->date('berlaku_sampai');
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
            $table->index(['kendaraan_id', 'jenis']);
            $table->index('tgl_bayar');
        });

        DB::statement('ALTER TABLE pembayaran_pajak ADD CONSTRAINT ck_pajak_nominal CHECK (nominal >= 0)');
        DB::statement('ALTER TABLE pembayaran_pajak ADD CONSTRAINT ck_pajak_berlaku CHECK (berlaku_sampai >= tgl_bayar)');
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_pajak');
    }
};
