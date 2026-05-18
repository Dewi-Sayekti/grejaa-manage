<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persembahan extends Model
{
    use HasFactory;

    protected $table = 'persembahans';

    protected $fillable = [
        'user_id', 'nama_pemberi', 'email', 'phone',
        'jenis', 'jumlah', 'catatan', 'order_id',
        'payment_type', 'transaction_id', 'status',
        'snap_token', 'paid_at',
    ];

    protected $casts = [
        'snap_token' => 'array',
        'paid_at'    => 'datetime',
        'jumlah'     => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isSettled(): bool
    {
        return $this->status === 'settlement';
    }

    public function getJenisLabelAttribute(): string
    {
        return match($this->jenis) {
            'persembahan' => 'Persembahan Umum',
            'perpuluhan'  => 'Perpuluhan',
            'diakonia'    => 'Diakonia',
            'misi'        => 'Dana Misi',
            'lainnya'     => 'Lainnya',
            default       => $this->jenis,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'settlement' => 'Lunas',
            'pending'    => 'Menunggu Pembayaran',
            'expire'     => 'Kedaluwarsa',
            'cancel'     => 'Dibatalkan',
            'deny'       => 'Ditolak',
            default      => $this->status,
        };
    }
}