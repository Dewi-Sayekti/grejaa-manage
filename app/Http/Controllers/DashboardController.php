<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Jemaat;
use App\Models\Keuangan;
use App\Models\News;
use App\Models\Notifikasi;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->jemaatDashboard();
    }

    private function adminDashboard()
    {
        $totalJemaat        = Jemaat::count();
        $totalUser          = User::count();
        $userPending        = User::where('is_approved', false)->count();
        $totalPemasukan     = Keuangan::where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran   = Keuangan::where('tipe', 'pengeluaran')->sum('jumlah');
        $totalGambar        = Image::count();
        $notifikasiTerbaru  = Notifikasi::latest('tanggal_kirim')->take(5)->get();
        $transaksiTerbaru   = Keuangan::with('jemaat')->latest('tanggal_transaksi')->take(5)->get();
        $jemaatTerbaru      = Jemaat::latest()->take(5)->get();
        $absensiPending     = Absensi::where('approval_status', 'pending')->count();

        return view('dashboard.admin', compact(
            'totalJemaat', 'totalUser', 'userPending',
            'totalPemasukan', 'totalPengeluaran', 'totalGambar',
            'notifikasiTerbaru', 'transaksiTerbaru', 'jemaatTerbaru',
            'absensiPending'
        ));
    }

    private function jemaatDashboard()
    {
        $user   = Auth::user();
        $jemaat = $user->jemaat;

        $notifikasi = collect();
        $absensiStats = ['hadir' => 0, 'izin' => 0, 'pending' => 0, 'total' => 0];

        if ($jemaat) {
            $notifikasi = Notifikasi::where('jemaat_id', $jemaat->id)
                ->latest('tanggal_kirim')
                ->take(10)
                ->get();

            // Stats absensi bulan ini
            $absensiStats = [
                'hadir'   => Absensi::where('jemaat_id', $jemaat->id)
                                ->where('status', 'hadir')
                                ->where('approval_status', 'approved')
                                ->whereMonth('tanggal', now()->month)
                                ->count(),
                'izin'    => Absensi::where('jemaat_id', $jemaat->id)
                                ->where('status', 'izin')
                                ->where('approval_status', 'approved')
                                ->whereMonth('tanggal', now()->month)
                                ->count(),
                'pending' => Absensi::where('jemaat_id', $jemaat->id)
                                ->where('approval_status', 'pending')
                                ->count(),
                'total'   => Absensi::where('jemaat_id', $jemaat->id)
                                ->where('approval_status', 'approved')
                                ->count(),
            ];
        }

        // Jadwal ibadah berikutnya
        $schedules    = Schedule::where('is_active', true)->orderBy('order')->orderBy('day')->get();
        $nextSchedule = Schedule::where('is_active', true)->orderBy('order')->first();

        // Absensi jemaat yang sudah ada (untuk cek duplikat di popup)
        $existingAbsensi = collect();
        if ($jemaat) {
            $existingAbsensi = Absensi::where('jemaat_id', $jemaat->id)
                ->whereMonth('tanggal', now()->month)
                ->get()
                ->keyBy('schedule_id');
        }

        // Berita/pengumuman terbaru
        $news = class_exists(\App\Models\News::class)
            ? \App\Models\News::latest()->take(3)->get()
            : collect();

        return view('dashboard.jemaat', compact(
            'jemaat', 'notifikasi', 'absensiStats',
            'schedules', 'nextSchedule', 'news', 'existingAbsensi'
        ));
    }
}