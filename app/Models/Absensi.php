<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';

    protected $fillable = [
        'jemaat_id',
        'schedule_id',
        'tanggal',
        'jenis_ibadah',
        'status',
        'approval_status',
        'keterangan',
        'alasan_izin',
        'approved_by',
        'approved_at',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'approved_at' => 'datetime',
    ];

    public function jemaat()
    {
        return $this->belongsTo(Jemaat::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'hadir'        => 'Hadir',
            'tidak_hadir'  => 'Tidak Hadir',
            'izin'         => 'Izin',
            default        => $this->status,
        };
    }

    public function getApprovalLabelAttribute()
    {
        return match($this->approval_status) {
            'pending'  => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => $this->approval_status,
        };
    }
}