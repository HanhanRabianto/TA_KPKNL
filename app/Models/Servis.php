<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Servis extends Model
{
    protected $table = 'servis';

    protected $fillable = [
        'kendaraan_id', 'tgl_masuk', 'jenis', 'bengkel', 'odometer', 'biaya', 'estimasi_selesai',
        'catatan', 'status', 'tgl_selesai', 'biaya_akhir', 'odometer_keluar', 'kondisi_setelah', 'catatan_selesai',
    ];

    protected function casts(): array
    {
        return [
            'tgl_masuk'        => 'date',
            'estimasi_selesai' => 'date',
            'tgl_selesai'      => 'date',
        ];
    }

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}
