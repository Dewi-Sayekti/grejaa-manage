<?php

namespace App\Console\Commands;

use App\Http\Controllers\AbsensiController;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class KirimReminderIbadah extends Command
{
    protected $signature   = 'absensi:reminder {--schedule_id= : ID jadwal spesifik}';
    protected $description = 'Kirim notifikasi reminder ibadah kepada seluruh jemaat aktif';

    public function handle(AbsensiController $controller): int
    {
        $hari = now()->locale('id')->dayName; // Senin, Selasa, dst.

        // Ambil schedule yang hari ini sesuai
        $query = Schedule::where('is_active', true);

        if ($this->option('schedule_id')) {
            $query->where('id', $this->option('schedule_id'));
        } else {
            // Jadwal recurring yang hari ini
            $query->where(function ($q) use ($hari) {
                $q->where('is_recurring', true)->where('day', $hari);
            })->orWhere(function ($q) {
                // Jadwal satu kali yang tanggalnya hari ini
                $q->where('is_recurring', false)->whereDate('tanggal', today());
            });
        }

        $schedules = $query->get();

        if ($schedules->isEmpty()) {
            $this->info('Tidak ada jadwal ibadah hari ini.');
            return self::SUCCESS;
        }

        $total = 0;
        foreach ($schedules as $schedule) {
            $count = $controller->sendReminder($schedule);
            $total += $count;
            $this->info("Reminder '{$schedule->title}': {$count} notifikasi dikirim.");
        }

        $this->info("Total {$total} notifikasi reminder berhasil dikirim.");
        return self::SUCCESS;
    }
}
