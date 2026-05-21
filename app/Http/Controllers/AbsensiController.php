<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Jemaat;
use App\Models\Notifikasi;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\AbsensiExport;

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

        $selectedSchedule = null;
        if ($request->filled('schedule')) {
            $selectedSchedule = Schedule::find($request->schedule);
        }

        $absensis = Absensi::where('jemaat_id', $jemaat->id)
                        ->with('schedule')
                        ->latest('tanggal')
                        ->paginate(10);

        // Statistik bulan ini
        $stats = [
            'total'   => Absensi::where('jemaat_id', $jemaat->id)->where('approval_status', 'approved')->count(),
            'hadir'   => Absensi::where('jemaat_id', $jemaat->id)->where('status', 'hadir')->where('approval_status', 'approved')->whereMonth('tanggal', now()->month)->count(),
            'izin'    => Absensi::where('jemaat_id', $jemaat->id)->where('status', 'izin')->where('approval_status', 'approved')->whereMonth('tanggal', now()->month)->count(),
            'pending' => Absensi::where('jemaat_id', $jemaat->id)->where('approval_status', 'pending')->count(),
        ];

        // Data grafik 6 bulan terakhir (per jemaat)
        $grafikData = $this->getGrafikJemaat($jemaat->id);

        // Persentase kehadiran bulan ini
        $totalBulanIni   = $stats['hadir'] + $stats['izin'];
        $persentaseHadir = $totalBulanIni > 0 ? round(($stats['hadir'] / $totalBulanIni) * 100) : 0;

        return view('absensi.index', compact(
            'absensis', 'schedules', 'selectedSchedule',
            'jemaat', 'stats', 'grafikData', 'persentaseHadir'
        ));
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

        $exists = Absensi::where('jemaat_id', $jemaat->id)
                    ->where('tanggal', $request->tanggal)
                    ->where('schedule_id', $request->schedule_id)
                    ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah mencatat presensi untuk jadwal ini pada tanggal tersebut.');
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
            'input_method'    => 'self',
        ]);

        return back()->with('success', 'Presensi berhasil dikirim dan menunggu persetujuan admin.');
    }

    public function destroy(Absensi $absensi)
    {
        $jemaat = Auth::user()->jemaat;

        if (! $jemaat || $absensi->jemaat_id !== $jemaat->id) {
            abort(403);
        }

        if ($absensi->approval_status !== 'pending') {
            return back()->with('error', 'Hanya presensi berstatus Menunggu yang bisa dihapus.');
        }

        $absensi->delete();
        return back()->with('success', 'Presensi berhasil dihapus.');
    }

    // ============================================================
    //  QR CODE: Generate & Scan
    // ============================================================

    /**
     * Generate QR token untuk schedule tertentu (admin)
     */
    public function generateQr(Schedule $schedule)
    {
        $token = Str::random(32);
        $schedule->update([
            'qr_token'      => $token,
            'qr_expires_at' => now()->addHours(3), // berlaku 3 jam
        ]);

        return response()->json([
            'token'      => $token,
            'url'        => route('absensi.scan', $token),
            'expires_at' => $schedule->qr_expires_at->format('H:i'),
        ]);
    }

    /**
     * Halaman scan QR (jemaat)
     */
    public function scanQr(string $token)
    {
        $schedule = Schedule::where('qr_token', $token)
                        ->where('qr_expires_at', '>=', now())
                        ->where('is_active', true)
                        ->firstOrFail();

        $user   = Auth::user();
        $jemaat = $user->jemaat;

        if (! $jemaat) {
            return redirect()->route('dashboard')->with('error', 'Profil jemaat tidak ditemukan.');
        }

        // Cek sudah absen?
        $sudahAbsen = Absensi::where('jemaat_id', $jemaat->id)
                        ->where('schedule_id', $schedule->id)
                        ->whereDate('tanggal', today())
                        ->exists();

        if ($sudahAbsen) {
            return view('absensi.qr-scan', compact('schedule', 'jemaat'))
                ->with('sudahAbsen', true);
        }

        return view('absensi.qr-scan', compact('schedule', 'jemaat'))->with('sudahAbsen', false);
    }

    /**
     * Simpan absensi dari QR scan
     */
    public function storeQr(Request $request, string $token)
    {
        $schedule = Schedule::where('qr_token', $token)
                        ->where('qr_expires_at', '>=', now())
                        ->firstOrFail();

        $jemaat = Auth::user()->jemaat;
        if (! $jemaat) abort(403);

        $exists = Absensi::where('jemaat_id', $jemaat->id)
                    ->where('schedule_id', $schedule->id)
                    ->whereDate('tanggal', today())
                    ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah melakukan presensi hari ini.');
        }

        Absensi::create([
            'jemaat_id'       => $jemaat->id,
            'schedule_id'     => $schedule->id,
            'tanggal'         => today(),
            'jenis_ibadah'    => $schedule->title,
            'status'          => 'hadir',
            'approval_status' => 'approved', // QR langsung approved
            'input_method'    => 'qr',
            'approved_by'     => null,
            'approved_at'     => now(),
        ]);

        // Kirim notifikasi konfirmasi
        Notifikasi::create([
            'jemaat_id' => $jemaat->id,
            'judul'     => 'Presensi Berhasil Dicatat',
            'pesan'     => "Kehadiran Anda di {$schedule->title} pada " . today()->translatedFormat('l, d F Y') . ' telah dicatat secara otomatis.',
            'tipe'      => 'konfirmasi_absensi',
            'data'      => ['schedule_id' => $schedule->id],
        ]);

        return redirect()->route('absensi.index')->with('success', 'Presensi QR berhasil! Kehadiran Anda telah dicatat.');
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
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', Carbon::parse($request->bulan)->month)
                  ->whereYear('tanggal', Carbon::parse($request->bulan)->year);
        }

        $absensis  = $query->paginate(15)->withQueryString();
        $jemaats   = Jemaat::orderBy('nama_lengkap')->get();
        $schedules = Schedule::orderBy('order')->get();

        $stats = [
            'pending'  => Absensi::where('approval_status', 'pending')->count(),
            'approved' => Absensi::where('approval_status', 'approved')->count(),
            'rejected' => Absensi::where('approval_status', 'rejected')->count(),
            'total'    => Absensi::count(),
        ];

        // Statistik per schedule untuk grafik admin
        $grafikPerSchedule = $this->getGrafikPerSchedule();

        return view('admin.absensi.index', compact(
            'absensis', 'jemaats', 'schedules', 'stats', 'grafikPerSchedule'
        ));
    }

    public function approve(Absensi $absensi)
    {
        $absensi->update([
            'approval_status' => 'approved',
            'approved_by'     => Auth::id(),
            'approved_at'     => now(),
        ]);

        // Notifikasi ke jemaat
        Notifikasi::create([
            'jemaat_id' => $absensi->jemaat_id,
            'judul'     => 'Presensi Disetujui',
            'pesan'     => "Presensi Anda pada " . $absensi->tanggal->translatedFormat('d F Y') . " ({$absensi->jenis_ibadah}) telah disetujui.",
            'tipe'      => 'konfirmasi_absensi',
            'data'      => ['absensi_id' => $absensi->id],
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

        // Notifikasi ke jemaat
        Notifikasi::create([
            'jemaat_id' => $absensi->jemaat_id,
            'judul'     => 'Presensi Ditolak',
            'pesan'     => "Presensi Anda pada " . $absensi->tanggal->translatedFormat('d F Y') . " ({$absensi->jenis_ibadah}) ditolak." . ($request->catatan_admin ? " Catatan: {$request->catatan_admin}" : ''),
            'tipe'      => 'konfirmasi_absensi',
            'data'      => ['absensi_id' => $absensi->id],
        ]);

        return back()->with('success', 'Absensi ditolak.');
    }

    public function bulkApprove(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:absensis,id']);

        $absensis = Absensi::whereIn('id', $request->ids)
                        ->where('approval_status', 'pending')
                        ->get();

        foreach ($absensis as $absensi) {
            $absensi->update([
                'approval_status' => 'approved',
                'approved_by'     => Auth::id(),
                'approved_at'     => now(),
            ]);

            Notifikasi::create([
                'jemaat_id' => $absensi->jemaat_id,
                'judul'     => 'Presensi Disetujui',
                'pesan'     => "Presensi Anda pada " . $absensi->tanggal->translatedFormat('d F Y') . " ({$absensi->jenis_ibadah}) telah disetujui.",
                'tipe'      => 'konfirmasi_absensi',
                'data'      => ['absensi_id' => $absensi->id],
            ]);
        }

        return back()->with('success', count($request->ids) . ' absensi disetujui.');
    }

    // ============================================================
    //  ADMIN: Input Absensi Massal
    // ============================================================

    public function massal(Request $request)
    {
        $schedules = Schedule::where('is_active', true)->orderBy('order')->get();
        $jemaats   = Jemaat::where('status_aktif', true)->orderBy('nama_lengkap')->get();

        $selectedSchedule = null;
        $selectedTanggal  = $request->tanggal ?? today()->toDateString();

        if ($request->filled('schedule_id')) {
            $selectedSchedule = Schedule::find($request->schedule_id);

            // Ambil status absensi yg sudah ada untuk tanggal + schedule ini
            $existingAbsensis = Absensi::where('schedule_id', $request->schedule_id)
                                    ->whereDate('tanggal', $selectedTanggal)
                                    ->pluck('status', 'jemaat_id');

            return view('admin.absensi.massal', compact(
                'schedules', 'jemaats', 'selectedSchedule',
                'selectedTanggal', 'existingAbsensis'
            ));
        }

        return view('admin.absensi.massal', compact(
            'schedules', 'jemaats', 'selectedSchedule', 'selectedTanggal'
        ));
    }

    public function storeMassal(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'tanggal'     => 'required|date',
            'absensis'    => 'required|array',
            'absensis.*'  => 'in:hadir,tidak_hadir,izin',
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);
        $adminId  = Auth::id();
        $count    = 0;

        DB::transaction(function () use ($request, $schedule, $adminId, &$count) {
            foreach ($request->absensis as $jemaatId => $status) {
                $absensi = Absensi::updateOrCreate(
                    [
                        'jemaat_id'   => $jemaatId,
                        'schedule_id' => $request->schedule_id,
                        'tanggal'     => $request->tanggal,
                    ],
                    [
                        'jenis_ibadah'    => $schedule->title,
                        'status'          => $status,
                        'approval_status' => 'approved',
                        'approved_by'     => $adminId,
                        'approved_at'     => now(),
                        'input_by'        => $adminId,
                        'input_method'    => 'admin',
                    ]
                );

                // Notifikasi ke jemaat
                Notifikasi::updateOrCreate(
                    [
                        'jemaat_id' => $jemaatId,
                        'tipe'      => 'konfirmasi_absensi',
                        'data->absensi_id' => $absensi->id, // hindari duplikat
                    ],
                    [
                        'judul' => 'Presensi Dicatat Admin',
                        'pesan' => "Presensi Anda di {$schedule->title} pada " . Carbon::parse($request->tanggal)->translatedFormat('d F Y') . " dicatat sebagai: " . ucfirst(str_replace('_', ' ', $status)) . ".",
                        'tipe'  => 'konfirmasi_absensi',
                        'data'  => ['absensi_id' => $absensi->id],
                    ]
                );

                $count++;
            }
        });

        return redirect()->route('admin.absensi.index')
            ->with('success', "Absensi massal berhasil disimpan untuk {$count} jemaat.");
    }

    // ============================================================
    //  REKAP & GRAFIK
    // ============================================================

    public function rekap(Request $request)
    {
        $schedules = Schedule::orderBy('order')->get();
        $jemaats   = Jemaat::orderBy('nama_lengkap')->get();

        $bulan     = $request->bulan ?? now()->format('Y-m');
        $jemaatId  = $request->jemaat_id;

        // Rekap per jemaat untuk bulan dipilih
        $query = Absensi::with(['jemaat', 'schedule'])
                    ->where('approval_status', 'approved')
                    ->whereYear('tanggal', Carbon::parse($bulan)->year)
                    ->whereMonth('tanggal', Carbon::parse($bulan)->month);

        if ($jemaatId) {
            $query->where('jemaat_id', $jemaatId);
        }

        // Summary rekap per jemaat
        $rekapJemaat = Absensi::with('jemaat')
            ->where('approval_status', 'approved')
            ->whereYear('tanggal', Carbon::parse($bulan)->year)
            ->whereMonth('tanggal', Carbon::parse($bulan)->month)
            ->when($jemaatId, fn($q) => $q->where('jemaat_id', $jemaatId))
            ->select('jemaat_id',
                DB::raw("SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as total_hadir"),
                DB::raw("SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as total_izin"),
                DB::raw("SUM(CASE WHEN status = 'tidak_hadir' THEN 1 ELSE 0 END) as total_tidak_hadir"),
                DB::raw("COUNT(*) as total")
            )
            ->groupBy('jemaat_id')
            ->with('jemaat')
            ->get()
            ->map(function ($item) {
                $item->persentase = $item->total > 0
                    ? round(($item->total_hadir / $item->total) * 100)
                    : 0;
                return $item;
            })
            ->sortByDesc('persentase');

        // Grafik tren per schedule bulan ini
        $grafikSchedule = $this->getGrafikPerSchedule($bulan);

        return view('admin.absensi.rekap', compact(
            'schedules', 'jemaats', 'bulan', 'jemaatId',
            'rekapJemaat', 'grafikSchedule'
        ));
    }

    // ============================================================
    //  EXPORT: Excel & PDF
    // ============================================================

    public function exportExcel(Request $request)
    {
        $bulan    = $request->bulan ?? now()->format('Y-m');
        $filename = 'rekap-presensi-' . $bulan . '.xlsx';

        return Excel::download(new AbsensiExport($request->all()), $filename);
    }

    public function exportPdf(Request $request)
    {
        $bulan    = $request->bulan ?? now()->format('Y-m');
        $jemaatId = $request->jemaat_id;

        $rekapJemaat = Absensi::with('jemaat')
            ->where('approval_status', 'approved')
            ->whereYear('tanggal', Carbon::parse($bulan)->year)
            ->whereMonth('tanggal', Carbon::parse($bulan)->month)
            ->when($jemaatId, fn($q) => $q->where('jemaat_id', $jemaatId))
            ->select('jemaat_id',
                DB::raw("SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as total_hadir"),
                DB::raw("SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as total_izin"),
                DB::raw("SUM(CASE WHEN status = 'tidak_hadir' THEN 1 ELSE 0 END) as total_tidak_hadir"),
                DB::raw("COUNT(*) as total")
            )
            ->groupBy('jemaat_id')
            ->with('jemaat')
            ->get()
            ->map(function ($item) {
                $item->persentase = $item->total > 0
                    ? round(($item->total_hadir / $item->total) * 100) : 0;
                return $item;
            })
            ->sortByDesc('persentase');

        $judul    = 'Rekap Presensi ' . Carbon::parse($bulan)->translatedFormat('F Y');
        $filename = 'rekap-presensi-' . $bulan . '.pdf';

        $pdf = Pdf::loadView('admin.absensi.pdf', compact('rekapJemaat', 'judul', 'bulan'))
                ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    // ============================================================
    //  NOTIFIKASI: Reminder Ibadah
    // ============================================================

    /**
     * Dipanggil via scheduler / artisan command
     * Kirim reminder ke semua jemaat aktif sebelum ibadah
     */
    public function sendReminder(Schedule $schedule): int
    {
        $jemaats = Jemaat::where('status_aktif', true)->get();
        $count   = 0;

        foreach ($jemaats as $jemaat) {
            // Jangan kirim kalau sudah ada notif hari ini untuk schedule ini
            $sudahAda = Notifikasi::where('jemaat_id', $jemaat->id)
                            ->where('tipe', 'reminder_ibadah')
                            ->whereDate('created_at', today())
                            ->whereJsonContains('data->schedule_id', $schedule->id)
                            ->exists();

            if ($sudahAda) continue;

            Notifikasi::create([
                'jemaat_id' => $jemaat->id,
                'judul'     => "Reminder: {$schedule->title} Hari Ini",
                'pesan'     => "Ibadah {$schedule->title} akan berlangsung hari ini" . ($schedule->start_time ? ' pukul ' . Carbon::parse($schedule->start_time)->format('H:i') . ' WIB' : '') . ($schedule->location ? " di {$schedule->location}" : '') . '. Jangan lupa presensi ya!',
                'tipe'      => 'reminder_ibadah',
                'data'      => ['schedule_id' => $schedule->id],
            ]);
            $count++;
        }

        return $count;
    }

    // ============================================================
    //  NOTIFIKASI: API untuk jemaat
    // ============================================================

    public function getNotifikasi(Request $request)
    {
        $jemaat = Auth::user()->jemaat;
        if (! $jemaat) return response()->json([]);

        $notifikasis = Notifikasi::where('jemaat_id', $jemaat->id)
                            ->latest()
                            ->limit(20)
                            ->get();

        return response()->json($notifikasis);
    }

    public function readNotifikasi(Notifikasi $notifikasi)
    {
        if (Auth::user()->jemaat?->id !== $notifikasi->jemaat_id) abort(403);
        $notifikasi->markAsRead();
        return response()->json(['success' => true]);
    }

    public function readAllNotifikasi()
    {
        $jemaat = Auth::user()->jemaat;
        if (! $jemaat) return back();

        Notifikasi::where('jemaat_id', $jemaat->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'Semua notifikasi telah dibaca.');
    }

    // ============================================================
    //  HELPER: Data Grafik
    // ============================================================

    private function getGrafikJemaat(int $jemaatId): array
    {
        $bulanList = collect(range(5, 0))->map(fn($i) => now()->subMonths($i));

        $data = [];
        foreach ($bulanList as $bulan) {
            $row = Absensi::where('jemaat_id', $jemaatId)
                        ->where('approval_status', 'approved')
                        ->whereYear('tanggal', $bulan->year)
                        ->whereMonth('tanggal', $bulan->month)
                        ->select(
                            DB::raw("SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as hadir"),
                            DB::raw("SUM(CASE WHEN status='izin' THEN 1 ELSE 0 END) as izin"),
                            DB::raw("SUM(CASE WHEN status='tidak_hadir' THEN 1 ELSE 0 END) as tidak_hadir")
                        )->first();

            $data[] = [
                'bulan'        => $bulan->translatedFormat('M Y'),
                'hadir'        => (int) ($row->hadir ?? 0),
                'izin'         => (int) ($row->izin ?? 0),
                'tidak_hadir'  => (int) ($row->tidak_hadir ?? 0),
            ];
        }

        return $data;
    }

    private function getGrafikPerSchedule(?string $bulan = null): array
    {
        $bulan     = $bulan ?? now()->format('Y-m');
        $schedules = Schedule::where('is_active', true)->orderBy('order')->get();
        $result    = [];

        foreach ($schedules as $schedule) {
            $row = Absensi::where('schedule_id', $schedule->id)
                        ->where('approval_status', 'approved')
                        ->whereYear('tanggal', Carbon::parse($bulan)->year)
                        ->whereMonth('tanggal', Carbon::parse($bulan)->month)
                        ->select(
                            DB::raw("SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as hadir"),
                            DB::raw("SUM(CASE WHEN status='izin' THEN 1 ELSE 0 END) as izin"),
                            DB::raw("SUM(CASE WHEN status='tidak_hadir' THEN 1 ELSE 0 END) as tidak_hadir")
                        )->first();

            $result[] = [
                'schedule' => $schedule->title,
                'hadir'    => (int) ($row->hadir ?? 0),
                'izin'     => (int) ($row->izin ?? 0),
                'tidak_hadir' => (int) ($row->tidak_hadir ?? 0),
            ];
        }

        return $result;
    }
}
