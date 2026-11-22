<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengemudi extends Model
{
    protected $table = 'pengemudi';

    protected $fillable = ['nama', 'kontak', 'status'];
}
