<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jemaat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
     * Menampilkan form tambah jemaat
     */
    public function create()
    {
        return view('admin.jemaat.create');
    }

    /**
     * Menyimpan data jemaat baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'nomor_hp' => 'nullable|string|max:20',
            'status_pernikahan' => 'nullable|in:Belum Menikah,Menikah,Duda,Janda',
            'tanggal_baptis' => 'nullable|date',
            'status_aktif' => 'nullable|in:Aktif,Tidak Aktif',
            'golongan_darah' => 'nullable|string|max:5',
        ]);

        $user = User::create([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'jemaat',
        ]);

        Jemaat::create([
            'user_id' => $user->id,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'nomor_hp' => $request->nomor_hp,
            'status_pernikahan' => $request->status_pernikahan,
            'tanggal_baptis' => $request->tanggal_baptis,
            'status_aktif' => $request->status_aktif ?? 'Aktif',
            'golongan_darah' => $request->golongan_darah,
        ]);

        return redirect()->route('admin.jemaat.index')
            ->with('success', 'Data jemaat berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit jemaat
     */
    public function edit(Jemaat $jemaat)
    {
        return view('admin.jemaat.edit', compact('jemaat'));
    }

    /**
     * Menyimpan perubahan data jemaat
     */
    public function update(Request $request, Jemaat $jemaat)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$jemaat->user_id,
            'password' => 'nullable|string|min:8|confirmed',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nomor_hp' => 'required|string|max:20',
            'status_pernikahan' => 'required|in:Belum Menikah,Menikah,Duda,Janda',
            'golongan_darah' => 'nullable|string|max:5',
            'tanggal_baptis' => 'nullable|date',
            'status_aktif' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $user = $jemaat->user;
        $user->name = $request->nama_lengkap;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $jemaat->update([
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'nomor_hp' => $request->nomor_hp,
            'status_pernikahan' => $request->status_pernikahan,
            'golongan_darah' => $request->golongan_darah,
            'tanggal_baptis' => $request->tanggal_baptis,
            'status_aktif' => $request->status_aktif,
        ]);

        return redirect()->route('admin.jemaat.index')
            ->with('success', 'Data jemaat berhasil diperbarui.');
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
