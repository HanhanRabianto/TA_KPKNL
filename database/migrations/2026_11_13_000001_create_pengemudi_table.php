<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengemudi', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('kontak', 30)->nullable();
            $table->enum('status', ['Tersedia', 'Bertugas'])->default('Tersedia');
            $table->timestamps();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengemudi');
    }
};
