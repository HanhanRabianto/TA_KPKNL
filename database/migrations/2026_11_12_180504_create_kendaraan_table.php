<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('kendaraan', function (Blueprint $table) {
    $table->id();
    $table->string('plat', 15)->unique();
    $table->string('jenis', 80);
    $table->unsignedInteger('odometer')->default(0);
    $table->enum('status', ['Tersedia', 'Sedang dipakai', 'Sedang diservis'])->default('Tersedia');
    $table->date('stnk_berlaku')->nullable();
    $table->date('bpkb_berlaku')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};
