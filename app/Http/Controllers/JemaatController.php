<?php

namespace App\Http\Controllers;

use App\Models\Jemaat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JemaatController extends Controller
{
    public function show()
    {
        $jemaat = Jemaat::where('user_id', Auth::id())->first();
        return view('profile.show', compact('jemaat'));
    }

    public function edit()
    {
        $jemaat = Jemaat::where('user_id', Auth::id())->first();
        return view('profile.edit', compact('jemaat'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nomor_hp' => 'required|string',
            'status_pernikahan' => 'required|in:Belum Menikah,Menikah,Duda,Janda',
        ]);

        $jemaat = Jemaat::where('user_id', Auth::id())->first();
        $jemaat->update($validated);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }
}
