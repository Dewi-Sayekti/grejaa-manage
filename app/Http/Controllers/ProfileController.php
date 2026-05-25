<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Jemaat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update profil akun atau data jemaat.
     */
    public function update(Request $request): RedirectResponse
    {
        // --- Update password ---
        if ($request->boolean('update_password')) {
            return $this->updatePassword($request);
        }

        // --- Update data jemaat ---
        if ($request->boolean('update_jemaat')) {
            return $this->updateJemaat($request);
        }

        // --- Update foto profil saja ---
        if ($request->hasFile('foto') && ! $request->filled('name')) {
            return $this->updateFoto($request);
        }

        // --- Update akun (nama + email) ---
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255',
                        \Illuminate\Validation\Rule::unique('users')->ignore($request->user()->id)],
        ]);

        $user = $request->user();
        $user->fill($request->only('name', 'email'));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Simpan foto jika ada
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('profile-photos', 'public');
            if ($user->jemaat) {
                if ($user->jemaat->foto) {
                    Storage::disk('public')->delete($user->jemaat->foto);
                }
                $user->jemaat->update(['foto' => $path]);
            }
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated')
            ->with('active_tab', 'profil');
    }

    /**
     * Update password.
     */
    private function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.min'                      => 'Password baru minimal 8 karakter.',
            'password.confirmed'                => 'Konfirmasi password tidak cocok.',
        ]);

        $request->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return Redirect::route('profile.edit')
            ->with('success', 'Password berhasil diperbarui.')
            ->with('active_tab', 'password');
    }

    /**
     * Update data jemaat.
     */
    private function updateJemaat(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_lengkap'      => ['required', 'string', 'max:255'],
            'jenis_kelamin'     => ['nullable', 'in:Laki-laki,Perempuan'],
            'tempat_lahir'      => ['nullable', 'string', 'max:100'],
            'tanggal_lahir'     => ['nullable', 'date', 'before:today'],
            'nomor_hp'          => ['nullable', 'string', 'max:20'],
            'golongan_darah'    => ['nullable', 'string', 'max:5'],
            'alamat'            => ['nullable', 'string', 'max:500'],
            'status_pernikahan' => ['nullable', 'string', 'in:Belum Menikah,Menikah,Janda,Duda'],
            'tanggal_baptis'    => ['nullable', 'date'],
        ]);

        $user   = $request->user();
        $jemaat = $user->jemaat;

        if (! $jemaat) {
            return Redirect::route('profile.edit')
                ->with('error', 'Data jemaat tidak ditemukan.')
                ->with('active_tab', 'jemaat');
        }

        $jemaat->update($request->only([
            'nama_lengkap', 'jenis_kelamin', 'tempat_lahir',
            'tanggal_lahir', 'nomor_hp', 'golongan_darah',
            'alamat', 'status_pernikahan', 'tanggal_baptis',
        ]));

        return Redirect::route('profile.edit')
            ->with('success', 'Data jemaat berhasil diperbarui.')
            ->with('active_tab', 'jemaat');
    }

    /**
     * Update foto profil.
     */
    private function updateFoto(Request $request): RedirectResponse
    {
        $request->validate([
            'foto' => ['required', 'image', 'max:2048'],
        ]);

        $user   = $request->user();
        $jemaat = $user->jemaat;

        if ($jemaat) {
            if ($jemaat->foto) {
                Storage::disk('public')->delete($jemaat->foto);
            }
            $path = $request->file('foto')->store('profile-photos', 'public');
            $jemaat->update(['foto' => $path]);
        }

        return Redirect::route('profile.edit')
            ->with('success', 'Foto profil berhasil diperbarui.')
            ->with('active_tab', 'profil');
    }

    /**
     * Hapus akun.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}