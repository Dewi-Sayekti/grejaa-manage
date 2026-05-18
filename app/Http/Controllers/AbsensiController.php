<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Jemaat;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    // ============================================================
    //  JEMAAT: Lihat & buat absensi sendiri
    // ============================================================

    public function index(Request $request)
    {
        $user   = Auth::user();
        $jemaat = $user->jemaat;

        if (! $jemaat) {
            return redirect()->route('dashboard')->with('error', 'Profil jemaat tidak ditemukan.');
        }

        $schedules = Schedule::where('is_active', true)
                        ->orderBy('order')
                        ->orderBy('day')
                        ->get();

        // Jadwal yang dipilih (dari URL ?schedule=id atau session)
        $selectedSchedule = null;
        if ($request->filled('schedule')) {
            $selectedSchedule = Schedule::find($request->schedule);
        }

        $absensis = Absensi::where('jemaat_id', $jemaat->id)
                        ->with('schedule')
                        ->latest('tanggal')
                        ->paginate(10);

        $stats = [
            'total'   => Absensi::where('jemaat_id', $jemaat->id)->where('approval_status', 'approved')->count(),
            'hadir'   => Absensi::where('jemaat_id', $jemaat->id)->where('status', 'hadir')->where('approval_status', 'approved')->whereMonth('tanggal', now()->month)->count(),
            'izin'    => Absensi::where('jemaat_id', $jemaat->id)->where('status', 'izin')->where('approval_status', 'approved')->whereMonth('tanggal', now()->month)->count(),
            'pending' => Absensi::where('jemaat_id', $jemaat->id)->where('approval_status', 'pending')->count(),
        ];

        return view('dashboard.absensi.index', compact('absensis', 'schedules', 'selectedSchedule', 'jemaat', 'stats'));
    }

    public function store(Request $request)
    {
        $user   = Auth::user();
        $jemaat = $user->jemaat;

        if (! $jemaat) {
            return back()->with('error', 'Profil jemaat tidak ditemukan.');
        }

        $request->validate([
            'tanggal'      => 'required|date|before_or_equal:today',
            'schedule_id'  => 'required|exists:schedules,id',
            'jenis_ibadah' => 'required|string|max:100',
            'status'       => 'required|in:hadir,tidak_hadir,izin',
            'alasan_izin'  => 'nullable|string|max:500',
            'keterangan'   => 'nullable|string|max:500',
        ]);

        // Cek duplikat
        $exists = Absensi::where('jemaat_id', $jemaat->id)
                    ->where('tanggal', $request->tanggal)
                    ->where('schedule_id', $request->schedule_id)
                    ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah mencatat absensi untuk jadwal ini pada tanggal tersebut.');
        }

        Absensi::create([
            'jemaat_id'       => $jemaat->id,
            'schedule_id'     => $request->schedule_id,
            'tanggal'         => $request->tanggal,
            'jenis_ibadah'    => $request->jenis_ibadah,
            'status'          => $request->status,
            'approval_status' => 'pending',
            'alasan_izin'     => $request->alasan_izin,
            'keterangan'      => $request->keterangan,
        ]);

        return back()->with('success', 'Absensi berhasil dikirim dan menunggu persetujuan admin.');
    }

    public function destroy(Absensi $absensi)
    {
        $jemaat = Auth::user()->jemaat;

        if (! $jemaat || $absensi->jemaat_id !== $jemaat->id) {
            abort(403);
        }

        if ($absensi->approval_status !== 'pending') {
            return back()->with('error', 'Hanya absensi berstatus Menunggu yang bisa dihapus.');
        }

        $absensi->delete();
        return back()->with('success', 'Absensi berhasil dihapus.');
    }

    // ============================================================
    //  ADMIN: Kelola semua absensi
    // ============================================================

    public function adminIndex(Request $request)
    {
        $query = Absensi::with(['jemaat', 'schedule', 'approvedBy'])->latest('tanggal');

        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }
        if ($request->filled('jemaat_id')) {
            $query->where('jemaat_id', $request->jemaat_id);
        }
        if ($request->filled('schedule_id')) {
            $query->where('schedule_id', $request->schedule_id);
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $absensis  = $query->paginate(15);
        $jemaats   = Jemaat::orderBy('nama_lengkap')->get();
        $schedules = Schedule::orderBy('order')->get();

        $stats = [
            'pending'  => Absensi::where('approval_status', 'pending')->count(),
            'approved' => Absensi::where('approval_status', 'approved')->count(),
            'rejected' => Absensi::where('approval_status', 'rejected')->count(),
            'total'    => Absensi::count(),
        ];

        return view('admin.absensi.index', compact('absensis', 'jemaats', 'schedules', 'stats'));
    }

    public function approve(Absensi $absensi)
    {
        $absensi->update([
            'approval_status' => 'approved',
            'approved_by'     => Auth::id(),
            'approved_at'     => now(),
        ]);

        return back()->with('success', 'Absensi disetujui.');
    }

    public function reject(Request $request, Absensi $absensi)
    {
        $request->validate(['catatan_admin' => 'nullable|string|max:500']);

        $absensi->update([
            'approval_status' => 'rejected',
            'approved_by'     => Auth::id(),
            'approved_at'     => now(),
            'catatan_admin'   => $request->catatan_admin,
        ]);

        return back()->with('success', 'Absensi ditolak.');
    }

    public function bulkApprove(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:absensis,id']);

        Absensi::whereIn('id', $request->ids)
            ->where('approval_status', 'pending')
            ->update([
                'approval_status' => 'approved',
                'approved_by'     => Auth::id(),
                'approved_at'     => now(),
            ]);

        return back()->with('success', count($request->ids) . ' absensi disetujui.');
    }
}