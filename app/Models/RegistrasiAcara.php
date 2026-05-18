<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RegistrasiAcara extends Model
{
    use HasFactory;

    protected $table = 'registrasi_acara';

    protected $fillable = [
        'news_id', 'user_id', 'nama_lengkap', 'email',
        'nomor_hp', 'jumlah_peserta', 'catatan',
        'status', 'kode_registrasi', 'confirmed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'jumlah_peserta' => 'integer',
    ];

    public static function generateKode(): string
    {
        do {
            $kode = 'REG-' . strtoupper(Str::random(8));
        } while (self::where('kode_registrasi', $kode)->exists());
        return $kode;
    }

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'Menunggu Konfirmasi',
            'confirmed' => 'Terkonfirmasi',
            'cancelled' => 'Dibatalkan',
            default     => $this->status,
        };
    }
}