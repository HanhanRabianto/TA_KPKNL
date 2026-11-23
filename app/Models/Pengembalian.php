<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';

    
    public $timestamps = false;

    protected $fillable = [
        'peminjaman_id', 'odometer_awal', 'odometer_akhir', 'kembali_pada', 'kondisi',
        'catatan_kondisi', 'status', 'alasan_tolak', 'diajukan_pada', 'diputuskan_pada',
    ];

    protected function casts(): array
    {
        return [
            'kembali_pada'    => 'datetime',
            'diajukan_pada'   => 'datetime',
            'diputuskan_pada' => 'datetime',
        ];
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function getJarakAttribute(): int
    {
        return max(0, $this->odometer_akhir - $this->odometer_awal);
    }

    
    public function getLabelStatusAttribute(): string
    {
        return ['menunggu' => 'Menunggu', 'diterima' => 'Selesai', 'ditolak' => 'Ditolak'][$this->status] ?? $this->status;
    }

    public function getKelasStatusAttribute(): string
    {
        return ['menunggu' => 'wr', 'diterima' => 'ok', 'ditolak' => 'er'][$this->status] ?? 'in';
    }
}
