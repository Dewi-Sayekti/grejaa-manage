<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'title', 'excerpt', 'content', 'image_path',
        'gradient_from', 'gradient_to', 'published_at',
        'is_published', 'order',
        // kolom baru
        'is_event', 'lokasi_acara', 'tanggal_acara',
        'tanggal_acara_selesai', 'kuota',
        'buka_pendaftaran', 'batas_pendaftaran',
    ];

    protected $casts = [
        'is_published'         => 'boolean',
        'is_event'             => 'boolean',
        'buka_pendaftaran'     => 'boolean',
        'order'                => 'integer',
        'kuota'                => 'integer',
        'published_at'         => 'date',
        'tanggal_acara'        => 'datetime',
        'tanggal_acara_selesai'=> 'datetime',
        'batas_pendaftaran'    => 'datetime',
    ];

    public function registrasis()
    {
        return $this->hasMany(RegistrasiAcara::class, 'news_id');
    }

    public function registrasisConfirmed()
    {
        return $this->hasMany(RegistrasiAcara::class, 'news_id')
                    ->where('status', 'confirmed');
    }

    public function getSisaKuotaAttribute(): ?int
    {
        if (is_null($this->kuota)) return null;
        $terisi = $this->registrasis()
                       ->whereIn('status', ['pending', 'confirmed'])
                       ->sum('jumlah_peserta');
        return max(0, $this->kuota - $terisi);
    }

    public function getPendaftaranTerbukaTerisiAttribute(): bool
    {
        if (!$this->buka_pendaftaran) return false;
        if ($this->batas_pendaftaran && now()->isAfter($this->batas_pendaftaran)) return false;
        if (!is_null($this->kuota) && $this->sisa_kuota <= 0) return false;
        return true;
    }

    public static function getPublished()
    {
        return self::where('is_published', true)
                   ->orderBy('published_at', 'desc')
                   ->orderBy('order')
                   ->get();
    }
}