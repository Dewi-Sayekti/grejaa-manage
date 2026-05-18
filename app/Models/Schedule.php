<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'emoji',
        'description',
        'day',
        'location',
        'tanggal',
        'is_recurring',
        'start_time',
        'end_time',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_recurring' => 'boolean',
        'order'        => 'integer',
        'start_time'   => 'datetime:H:i',
        'end_time'     => 'datetime:H:i',
        'tanggal'      => 'date',
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function getWaktuLengkapAttribute(): string
    {
        $time = '';
        if ($this->start_time) {
            $time = \Carbon\Carbon::parse($this->start_time)->format('H:i');
            if ($this->end_time) {
                $time .= ' - ' . \Carbon\Carbon::parse($this->end_time)->format('H:i');
            }
            $time .= ' WIB';
        }
        return $time;
    }

    public static function getActive()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->get();
    }
}