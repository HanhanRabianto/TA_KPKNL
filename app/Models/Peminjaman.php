<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    
    public $timestamps = false;

    
    public const MAKS_AKTIF = 2;

    
    public const STATUS_AKTIF = ['menunggu', 'disetujui', 'verifikasi', 'kembali_ditolak'];

    
    public const STATUS_DIPINJAM = ['disetujui', 'verifikasi', 'kembali_ditolak'];

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

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }

    

    public static function jumlahAktif(int $penggunaId): int
    {
        return static::where('pengguna_id', $penggunaId)->whereIn('status', self::STATUS_AKTIF)->count();
    }

    public function getNamaKendaraanAttribute(): string
    {
        return $this->kendaraan?->nama ?? '-';
    }

    public static function adaPengembalianDitolak(int $penggunaId): bool
    {
        return static::where('pengguna_id', $penggunaId)->where('status', 'kembali_ditolak')->exists();
    }

    
    public static function penghalang(int $penggunaId): ?self
    {
        $aktif = static::where('pengguna_id', $penggunaId)
            ->whereIn('status', self::STATUS_AKTIF)
            ->orderBy('id')
            ->get();

        return $aktif->firstWhere('status', 'kembali_ditolak')
            ?? ($aktif->count() >= self::MAKS_AKTIF ? $aktif->first() : null);
    }

    
    public static function alasanBlokir(int $penggunaId): ?string
    {
        $p = static::penghalang($penggunaId);

        if (! $p) {
            return null;
        }

        return $p->status === 'kembali_ditolak'
            ? 'Laporan pengembalian Anda ditolak. Perbaiki dan ajukan kembali laporannya sebelum meminjam lagi.'
            : 'Anda sudah meminjam maksimal '.self::MAKS_AKTIF.' kendaraan. Pengajuan baru dibuka setelah salah satu pengembalian diterima.';
    }

    
    public function getStatusPengajuanAttribute(): string
    {
        return in_array($this->status, ['menunggu', 'ditolak'], true) ? $this->status : 'disetujui';
    }

    
    public function getStatusPengembalianAttribute(): string
    {
        return match ($this->status) {
            'disetujui'       => 'belum',
            'kembali_ditolak' => 'ditolak',
            default           => $this->status,
        };
    }

    

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
