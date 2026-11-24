<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'pengguna';

    protected $fillable = ['nama_seksi', 'username', 'email', 'no_hp', 'password', 'peran', 'aktif'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'aktif'    => 'boolean',
        ];
    }

    // di tampilan, peran "pegawai" ditulis "Seksi"
    public function getLabelPeranAttribute(): string
    {
        return $this->peran === 'admin' ? 'Admin' : 'Seksi';
    }
}
