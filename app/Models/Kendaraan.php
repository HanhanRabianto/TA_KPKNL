<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $table = 'kendaraan';

    protected $fillable = [
        'plat', 'jenis', 'odometer', 'status', 'stnk_berlaku', 'bpkb_berlaku',
    ];

    protected function casts(): array
    {
        return [
            'stnk_berlaku' => 'date',
            'bpkb_berlaku' => 'date',
        ];
    }

    
    public static function jumlahJatuhTempo(string $kolom, int $hari = 30): int
    {
        return static::whereNotNull($kolom)
            ->where($kolom, '<=', now()->addDays($hari)->toDateString())
            ->count();
    }

    
    public function sisaHari(string $kolom): ?int
    {
        $tgl = $this->{$kolom};

        return $tgl ? (int) now()->startOfDay()->diffInDays($tgl->copy()->startOfDay(), false) : null;
    }
}
