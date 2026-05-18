<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserApprovalController extends Controller
{
    // Tampilkan list pending registrations
    public function pendingRegistrations()
    {
        $pendingUsers = User::where('is_approved', false)->whereNull('rejection_reason')->get();
        $approvedUsers = User::where('is_approved', true)->get();
        $rejectedUsers = User::where('is_approved', false)->whereNotNull('rejection_reason')->get();

        return view('admin.user-approvals', [
            'pendingUsers' => $pendingUsers,
            'approvedUsers' => $approvedUsers,
            'rejectedUsers' => $rejectedUsers,
        ]);
    }

    // Approve user
    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'is_approved' => true,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', "User {$user->name} telah disetujui.");
    }

    // Reject user
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'is_approved' => false,
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', "Registrasi {$user->name} telah ditolak.");
    }
}
