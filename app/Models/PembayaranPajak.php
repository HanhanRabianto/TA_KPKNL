<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPajak extends Model
{
    protected $table = 'pembayaran_pajak';

    protected $fillable = ['kendaraan_id', 'jenis', 'tgl_bayar', 'nominal', 'berlaku_sampai', 'keterangan'];

    protected function casts(): array
    {
        return ['tgl_bayar' => 'date', 'berlaku_sampai' => 'date'];
    }

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}
