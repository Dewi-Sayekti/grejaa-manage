<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;

    protected $table = 'keuangan';

    protected $fillable = [
        'jemaat_id',
        'tipe',
        'kategori',
        'jumlah',
        'tanggal_transaksi',
        'keterangan',
        'bukti_file',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
    ];

    public function jemaat()
    {
        return $this->belongsTo(Jemaat::class);
    }
}
