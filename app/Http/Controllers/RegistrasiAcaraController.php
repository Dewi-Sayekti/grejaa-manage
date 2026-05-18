<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\RegistrasiAcara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrasiAcaraController extends Controller
{
    // Halaman detail acara + form daftar (jemaat)
    public function show(News $news)
    {
        abort_unless($news->is_published && $news->is_event, 404);

        $sudahDaftar = false;
        if (Auth::check()) {
            $sudahDaftar = RegistrasiAcara::where('news_id', $news->id)
                ->where('user_id', Auth::id())
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();
        }

        $totalPeserta = RegistrasiAcara::where('news_id', $news->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('jumlah_peserta');

        return view('acara.show', compact('news', 'sudahDaftar', 'totalPeserta'));
    }

    // Submit form pendaftaran (jemaat)
    public function store(Request $request, News $news)
    {
        abort_unless($news->is_event && $news->pendaftaran_terbuka_terisi, 403);

        $request->validate([
            'nama_lengkap'   => 'required|string|max:100',
            'email'          => 'required|email',
            'nomor_hp'       => 'nullable|string|max:20',
            'jumlah_peserta' => 'required|integer|min:1|max:10',
            'catatan'        => 'nullable|string|max:300',
        ]);

        // Cek sudah daftar
        if (Auth::check()) {
            $exists = RegistrasiAcara::where('news_id', $news->id)
                ->where('user_id', Auth::id())
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();
            if ($exists) {
                return back()->with('error', 'Kamu sudah mendaftar untuk acara ini.');
            }
        }

        // Cek kuota
        if (!is_null($news->kuota)) {
            $terisi = RegistrasiAcara::where('news_id', $news->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->sum('jumlah_peserta');
            if (($terisi + $request->jumlah_peserta) > $news->kuota) {
                return back()->with('error', 'Maaf, kuota tidak mencukupi.');
            }
        }

        RegistrasiAcara::create([
            'news_id'          => $news->id,
            'user_id'          => Auth::id(),
            'nama_lengkap'     => $request->nama_lengkap,
            'email'            => $request->email,
            'nomor_hp'         => $request->nomor_hp,
            'jumlah_peserta'   => $request->jumlah_peserta,
            'catatan'          => $request->catatan,
            'status'           => 'pending',
            'kode_registrasi'  => RegistrasiAcara::generateKode(),
        ]);

        return redirect()->route('acara.show', $news)
            ->with('success', 'Pendaftaran berhasil! Kode registrasi kamu sudah dikirim. Tunggu konfirmasi dari admin.');
    }

    // Batalkan pendaftaran (jemaat)
    public function cancel(RegistrasiAcara $registrasi)
    {
        abort_unless(Auth::id() === $registrasi->user_id, 403);
        abort_unless($registrasi->status === 'pending', 403);

        $registrasi->update(['status' => 'cancelled']);
        return back()->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    // ===== ADMIN =====

    // Admin lihat semua pendaftar per acara
    public function adminIndex(News $news)
    {
        $registrasis = RegistrasiAcara::where('news_id', $news->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        $stats = [
            'total'     => RegistrasiAcara::where('news_id', $news->id)->count(),
            'pending'   => RegistrasiAcara::where('news_id', $news->id)->where('status', 'pending')->count(),
            'confirmed' => RegistrasiAcara::where('news_id', $news->id)->where('status', 'confirmed')->count(),
            'cancelled' => RegistrasiAcara::where('news_id', $news->id)->where('status', 'cancelled')->count(),
            'peserta'   => RegistrasiAcara::where('news_id', $news->id)->whereIn('status', ['pending','confirmed'])->sum('jumlah_peserta'),
        ];

        return view('admin.acara.registrasi', compact('news', 'registrasis', 'stats'));
    }

    // Admin konfirmasi pendaftaran
    public function confirm(RegistrasiAcara $registrasi)
    {
        $registrasi->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
        ]);
        return back()->with('success', 'Pendaftaran dikonfirmasi.');
    }

    // Admin tolak/batalkan pendaftaran
    public function reject(RegistrasiAcara $registrasi)
    {
        $registrasi->update(['status' => 'cancelled']);
        return back()->with('success', 'Pendaftaran dibatalkan.');
    }
}