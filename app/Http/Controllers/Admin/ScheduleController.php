<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Schedule;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::withCount('absensis')->orderBy('order')->orderBy('day')->get();

        $stats = [
            'total'   => $schedules->count(),
            'aktif'   => $schedules->where('is_active', true)->count(),
            'rutin'   => $schedules->where('is_recurring', true)->count(),
            'absensi' => Absensi::count(),
        ];

        return view('admin.schedules.index', compact('schedules', 'stats'));
    }

    public function create()
    {
        return view('admin.schedules.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:150',
            'emoji'        => 'nullable|string|max:10',
            'description'  => 'nullable|string',
            'day'          => 'nullable|string|max:50',
            'location'     => 'nullable|string|max:150',
            'tanggal'      => 'nullable|date',
            'is_recurring' => 'nullable|boolean',
            'start_time'   => 'nullable|date_format:H:i',
            'end_time'     => 'nullable|date_format:H:i',
            'order'        => 'nullable|integer|min:0',
            'is_active'    => 'nullable|boolean',
        ]);

        $data['is_recurring'] = $request->boolean('is_recurring');
        $data['is_active']    = $request->boolean('is_active', true);
        $data['emoji']        = $data['emoji'] ?? '📅';
        $data['order']        = $data['order'] ?? 0;

        $schedule = Schedule::create($data);

        // Kirim notifikasi ke jemaat
        Notifikasi::create([
            'judul' => 'Jadwal Baru: ' . $schedule->title,
            'isi' => "Halo Jemaat, ada jadwal ibadah baru: {$schedule->title} pada hari {$schedule->day}. Silakan cek dashboard untuk detailnya.",
            'tipe' => 'pengumuman',
            'tanggal_kirim' => now(),
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function show(Schedule $schedule)
    {
        $absensis = $schedule->absensis()
            ->with(['jemaat', 'approvedBy'])
            ->latest('tanggal')
            ->paginate(15);

        $stats = [
            'total'    => $schedule->absensis()->count(),
            'pending'  => $schedule->absensis()->where('approval_status', 'pending')->count(),
            'approved' => $schedule->absensis()->where('approval_status', 'approved')->count(),
            'rejected' => $schedule->absensis()->where('approval_status', 'rejected')->count(),
        ];

        return view('admin.schedules.show', compact('schedule', 'absensis', 'stats'));
    }

    public function edit(Schedule $schedule)
    {
        return view('admin.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:150',
            'emoji'        => 'nullable|string|max:10',
            'description'  => 'nullable|string',
            'day'          => 'nullable|string|max:50',
            'location'     => 'nullable|string|max:150',
            'tanggal'      => 'nullable|date',
            'is_recurring' => 'nullable|boolean',
            'start_time'   => 'nullable|date_format:H:i',
            'end_time'     => 'nullable|date_format:H:i',
            'order'        => 'nullable|integer|min:0',
            'is_active'    => 'nullable|boolean',
        ]);

        $data['is_recurring'] = $request->boolean('is_recurring');
        $data['is_active']    = $request->boolean('is_active', true);

        $schedule->update($data);

        // Kirim notifikasi update
        Notifikasi::create([
            'judul' => 'Pembaruan Jadwal: ' . $schedule->title,
            'isi' => "Jadwal {$schedule->title} telah diperbarui. Silakan cek detail terbaru di laman Jadwal Ibadah.",
            'tipe' => 'penting',
            'tanggal_kirim' => now(),
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    public function toggleActive(Schedule $schedule)
    {
        $schedule->update(['is_active' => ! $schedule->is_active]);
        $label = $schedule->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Jadwal berhasil {$label}.");
    }
}