<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\Jemaat;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Menampilkan list notifikasi
     */
    public function index()
    {
        $notifikasi = Notifikasi::with('jemaat')
            ->orderBy('tanggal_kirim', 'desc')
            ->paginate(15);

        return view('admin.notifikasi.index', compact('notifikasi'));
    }

    /**
     * Menampilkan form tambah notifikasi
     */
    public function create()
    {
        $jemaat = Jemaat::orderBy('nama_lengkap')->get();
        return view('admin.notifikasi.create', compact('jemaat'));
    }

    /**
     * Menyimpan notifikasi baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jemaat_id' => 'nullable|exists:jemaats,id',
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tipe' => 'required|in:pengumuman,penting,event',
            'tanggal_kirim' => 'required|date',
        ]);

        $validated['sudah_dibaca'] = false;
        Notifikasi::create($validated);

        return redirect()->route('admin.notifikasi.index')
            ->with('success', 'Notifikasi berhasil dikirim');
    }

    /**
     * Menampilkan form edit notifikasi
     */
    public function edit(Notifikasi $notifikasi)
    {
        $jemaat = Jemaat::orderBy('nama_lengkap')->get();
        return view('admin.notifikasi.edit', compact('notifikasi', 'jemaat'));
    }

    /**
     * Update notifikasi
     */
    public function update(Request $request, Notifikasi $notifikasi)
    {
        $validated = $request->validate([
            'jemaat_id' => 'nullable|exists:jemaats,id',
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tipe' => 'required|in:pengumuman,penting,event',
            'tanggal_kirim' => 'required|date',
        ]);

        $notifikasi->update($validated);

        return redirect()->route('admin.notifikasi.index')
            ->with('success', 'Notifikasi berhasil diperbarui');
    }

    /**
     * Menghapus notifikasi
     */
    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();

        return redirect()->route('admin.notifikasi.index')
            ->with('success', 'Notifikasi berhasil dihapus');
    }

    /**
     * Kirim notifikasi ke semua jemaat
     */
    public function sendToAll(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tipe' => 'required|in:pengumuman,penting,event',
        ]);

        $jemaat = Jemaat::all();
        $count = 0;

        foreach ($jemaat as $j) {
            Notifikasi::create([
                'jemaat_id' => $j->id,
                'judul' => $validated['judul'],
                'isi' => $validated['isi'],
                'tipe' => $validated['tipe'],
                'tanggal_kirim' => now(),
                'sudah_dibaca' => false,
            ]);
            $count++;
        }

        return redirect()->route('admin.notifikasi.index')
            ->with('success', "Notifikasi berhasil dikirim ke $count jemaat");
    }
}
