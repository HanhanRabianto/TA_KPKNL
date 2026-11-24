<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanBbm extends Model
{
    protected $table = 'catatan_bbm';

    protected $fillable = [
        'kendaraan_id', 'tgl_pemberian', 'tgl_pengembalian', 'petugas', 'jumlah_kupon', 'total', 'foto_struk',
    ];

    protected function casts(): array
    {
        return ['tgl_pemberian' => 'date', 'tgl_pengembalian' => 'date'];
    }

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}
