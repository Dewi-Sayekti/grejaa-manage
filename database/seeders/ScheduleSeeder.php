<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schedule::truncate();

        $schedules = [
            [
                'title' => 'Ibadah Pagi',
                'emoji' => '🌅',
                'description' => "Minggu Pagi: Pukul 07:00 - 08:30\nRabu Pagi: Pukul 06:00 - 07:00",
                'day' => 'Minggu & Rabu',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Ibadah Sore',
                'emoji' => '🌤️',
                'description' => "Minggu Sore: Pukul 16:00 - 17:30\nJumat Sore: Pukul 18:00 - 19:30",
                'day' => 'Minggu & Jumat',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Doa Malam',
                'emoji' => '🌙',
                'description' => "Setiap Malam: Pukul 19:30 - 20:30\nKhusus Jumat: Pemberkatan Khusus",
                'day' => 'Setiap Hari',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Sekolah Minggu',
                'emoji' => '👶',
                'description' => "Setiap Minggu: Pukul 08:00 - 09:00\nUsia: 4 - 12 Tahun",
                'day' => 'Minggu Pagi',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }
    }
}
