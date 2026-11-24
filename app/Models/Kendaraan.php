<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $table = 'kendaraan';

    public const TIPE = ['Minibus', 'Sedan', 'SUV', 'Sepeda motor'];

    protected $fillable = [
        'plat', 'jenis', 'tipe', 'kapasitas', 'tahun', 'odometer', 'status', 'stnk_berlaku', 'bpkb_berlaku',
    ];

    protected function casts(): array
    {
        return [
            'stnk_berlaku' => 'date',
            'bpkb_berlaku' => 'date',
        ];
    }

    
    public function getNamaAttribute(): string
    {
        return $this->jenis.', '.$this->plat;
    }

    
    public function getLabelStatusAttribute(): string
    {
        return $this->status === 'Sedang dipakai' ? 'Dipakai' : $this->status;
    }

    public function getKelasStatusAttribute(): string
    {
        return ['Tersedia' => 'ok', 'Sedang dipakai' => 'in', 'Rusak' => 'er'][$this->status] ?? 'wr';
    }

    
    public static function jumlahJatuhTempo(string $kolom, int $hari = 30): int
    {
        return static::whereNotNull($kolom)
            ->where($kolom, '<=', now()->addDays($hari)->toDateString())
            ->count();
    }

    
    public static function jumlahHampirHabis(int $hari = 90): int
    {
        $batas = now()->addDays($hari)->toDateString();

        return static::where(function ($q) use ($batas) {
            $q->where(fn ($s) => $s->whereNotNull('stnk_berlaku')->where('stnk_berlaku', '<=', $batas))
              ->orWhere(fn ($b) => $b->whereNotNull('bpkb_berlaku')->where('bpkb_berlaku', '<=', $batas));
        })->count();
    }

    
    public function sisaHari(string $kolom): ?int
    {
        $tgl = $this->{$kolom};

        return $tgl ? (int) now()->startOfDay()->diffInDays($tgl->copy()->startOfDay(), false) : null;
    }
}
