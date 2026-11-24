<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengemudi extends Model
{
    protected $table = 'pengemudi';

    public const JENIS_SIM = ['A', 'B1', 'B2'];

    protected $fillable = ['nama', 'kontak', 'no_sim', 'jenis_sim', 'sim_berlaku', 'status'];

    protected function casts(): array
    {
        return ['sim_berlaku' => 'date'];
    }

    public function getKelasStatusAttribute(): string
    {
        return ['Tersedia' => 'ok', 'Cuti' => 'wr'][$this->status] ?? 'in';
    }

    
    public function getSimSegeraHabisAttribute(): bool
    {
        return $this->sim_berlaku !== null && $this->sim_berlaku->lte(now()->addDays(210));
    }
}
