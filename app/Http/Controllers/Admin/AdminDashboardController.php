<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use App\Models\Notifikasi;
use App\Models\User;
use App\Models\Jemaat;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan statistik
     */
    public function index()
    {
        // Total user (jemaat)
        $totalJemaat = Jemaat::count();

        // Total user terregistrasi
        $totalUser = User::where('role', 'jemaat')->count();

        // User pending approval
        $userPending = User::where('role', 'jemaat')
            ->where('is_approved', false)
            ->count();

        // Total keuangan tahun ini
        $totalKeuangan = Keuangan::whereYear('tanggal_transaksi', now()->year)->sum('jumlah');

        // Pemasukan tahun ini
        $totalPemasukan = Keuangan::where('tipe', 'pemasukan')
            ->whereYear('tanggal_transaksi', now()->year)
            ->sum('jumlah');

        // Pengeluaran tahun ini
        $totalPengeluaran = Keuangan::where('tipe', 'pengeluaran')
            ->whereYear('tanggal_transaksi', now()->year)
            ->sum('jumlah');

        // Notifikasi terbaru
        $notifikasiTerbaru = Notifikasi::orderBy('tanggal_kirim', 'desc')
            ->limit(5)
            ->get();

        // Transaksi keuangan terbaru
        $transaksiTerbaru = Keuangan::with('jemaat')
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'totalJemaat' => $totalJemaat,
            'totalUser' => $totalUser,
            'userPending' => $userPending,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalKeuangan' => $totalKeuangan,
            'notifikasiTerbaru' => $notifikasiTerbaru,
            'transaksiTerbaru' => $transaksiTerbaru,
        ]);
    }
}
