<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'jemaat_id',
        'judul',
        'isi',
        'tipe',
        'tanggal_kirim',
        'sudah_dibaca',
    ];

    protected $casts = [
        'tanggal_kirim' => 'datetime',
    ];

    public function jemaat()
    {
        return $this->belongsTo(Jemaat::class);
    }
}
