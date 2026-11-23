<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    
    public $timestamps = false;

    
    public const MAKS_AKTIF = 2;

    
    public const STATUS_AKTIF = ['menunggu', 'disetujui', 'verifikasi'];

    protected $fillable = [
        'pengguna_id', 'kendaraan_id', 'pengemudi_id', 'lokasi_tujuan', 'menginap',
        'tujuan', 'petugas', 'penumpang', 'catatan', 'mulai', 'selesai', 'status',
        'nomor_surat', 'alasan_tolak', 'diputuskan_pada',
    ];

    protected function casts(): array
    {
        return [
            'menginap'        => 'boolean',
            'mulai'           => 'datetime',
            'selesai'         => 'datetime',
            'diajukan_pada'   => 'datetime',
            'diputuskan_pada' => 'datetime',
        ];
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    public function pengemudi(): BelongsTo
    {
        return $this->belongsTo(Pengemudi::class, 'pengemudi_id');
    }

    // ---------- aturan bisnis ----------

    public static function jumlahAktif(int $penggunaId): int
    {
        return static::where('pengguna_id', $penggunaId)->whereIn('status', self::STATUS_AKTIF)->count();
    }

    public static function adaPengembalianDitolak(int $penggunaId): bool
    {
        return static::where('pengguna_id', $penggunaId)->where('status', 'kembali_ditolak')->exists();
    }

    // null = boleh mengajukan; selain itu berisi alasan pemblokiran
    public static function alasanBlokir(int $penggunaId): ?string
    {
        if (static::adaPengembalianDitolak($penggunaId)) {
            return 'Laporan pengembalian Anda ditolak. Perbaiki dan ajukan kembali laporannya sebelum meminjam lagi.';
        }

        if (static::jumlahAktif($penggunaId) >= self::MAKS_AKTIF) {
            return 'Anda sudah meminjam maksimal '.self::MAKS_AKTIF.' kendaraan. Pengajuan baru dibuka setelah salah satu pengembalian diterima.';
        }

        return null;
    }

    // ---------- tampilan status ----------

    public function getLabelStatusAttribute(): string
    {
        return [
            'menunggu'        => 'Menunggu',
            'disetujui'       => 'Disetujui',
            'ditolak'         => 'Ditolak',
            'verifikasi'      => 'Pengembalian diverifikasi',
            'kembali_ditolak' => 'Pengembalian ditolak',
            'selesai'         => 'Selesai',
        ][$this->status] ?? $this->status;
    }

    public function getKelasStatusAttribute(): string
    {
        return [
            'menunggu' => 'wr', 'disetujui' => 'in', 'ditolak' => 'er',
            'verifikasi' => 'wr', 'kembali_ditolak' => 'er', 'selesai' => 'ok',
        ][$this->status] ?? 'in';
    }
}
