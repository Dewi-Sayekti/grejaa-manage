<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jemaat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email'              => 'required|email|unique:users',
            'password'           => 'required|min:8|confirmed',
            'nama_lengkap'       => 'required|string',
            'jenis_kelamin'      => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir'       => 'required|string',
            'tanggal_lahir'      => 'required|date',
            'alamat'             => 'required|string',
            'nomor_hp'           => 'required|string',
            'status_pernikahan'  => 'required|in:Belum Menikah,Menikah,Duda,Janda',
            'tanggal_baptis'     => 'nullable|date',
        ]);

        $user = User::create([
            'name'        => $validated['nama_lengkap'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'role'        => 'jemaat',
            'is_approved' => false,
            'approved_at' => null,
        ]);

        Jemaat::create([
            'user_id'           => $user->id,
            'nama_lengkap'      => $validated['nama_lengkap'],
            'jenis_kelamin'     => $validated['jenis_kelamin'],
            'tempat_lahir'      => $validated['tempat_lahir'],
            'tanggal_lahir'     => $validated['tanggal_lahir'],
            'alamat'            => $validated['alamat'],
            'nomor_hp'          => $validated['nomor_hp'],
            'status_pernikahan' => $validated['status_pernikahan'],
            'tanggal_baptis'    => $validated['tanggal_baptis'] ?? null,
            'status_aktif'      => 'Aktif',
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan admin.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->role === 'jemaat' && !$user->is_approved) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda sedang menunggu konfirmasi admin. Anda belum dapat login.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout berhasil!');
    }
}