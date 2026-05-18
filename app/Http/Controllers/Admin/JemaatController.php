<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jemaat;
use Illuminate\Http\Request;

class JemaatController extends Controller
{
    /**
     * Menampilkan daftar jemaat
     */
    public function index()
    {
        $jemaats = Jemaat::with('user')
            ->orderBy('nama_lengkap')
            ->paginate(15);

        return view('admin.jemaat.index', compact('jemaats'));
    }

    /**
     * Menampilkan detail jemaat
     */
    public function show(Jemaat $jemaat)
    {
        return view('admin.jemaat.show', compact('jemaat'));
    }

    /**
     * Menghapus data jemaat (opsional)
     */
    public function destroy(Jemaat $jemaat)
    {
        $user = $jemaat->user;
        $jemaat->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('admin.jemaat.index')
            ->with('success', 'Data jemaat berhasil dihapus.');
    }
}
