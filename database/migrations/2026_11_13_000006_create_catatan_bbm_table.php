<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_bbm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tgl_pemberian');
            $table->date('tgl_pengembalian');
            $table->string('petugas', 100);
            $table->unsignedSmallInteger('jumlah_kupon');
            $table->decimal('total', 14, 2);
            $table->string('foto_struk', 255);
            $table->timestamps();
            $table->index('kendaraan_id');
            $table->index('tgl_pengembalian');
        });

        DB::statement('ALTER TABLE catatan_bbm ADD CONSTRAINT ck_bbm_kupon CHECK (jumlah_kupon >= 1)');
        DB::statement('ALTER TABLE catatan_bbm ADD CONSTRAINT ck_bbm_total CHECK (total >= 0)');
        DB::statement('ALTER TABLE catatan_bbm ADD CONSTRAINT ck_bbm_tanggal CHECK (tgl_pengembalian >= tgl_pemberian)');
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_bbm');
    }
};
