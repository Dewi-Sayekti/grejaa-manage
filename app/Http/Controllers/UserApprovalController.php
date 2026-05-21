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
        $approvedUsers = User::with('approvedBy')->where('is_approved', true)->get();
        $rejectedUsers = User::with('rejectedBy')->where('is_approved', false)->whereNotNull('rejection_reason')->get();

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
            'approved_by' => auth()->id(),
            'rejection_reason' => null,
            'rejected_by' => null,
            'rejected_at' => null,
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
            'rejected_by' => auth()->id(),
            'rejected_at' => now(),
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return back()->with('success', "Registrasi {$user->name} telah ditolak.");
    }

    // Update user role
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:jemaat,admin',
        ]);

        $user = User::findOrFail($id);
        $oldRole = $user->role;
        $user->update(['role' => $request->role]);

        return response()->json([
            'success' => true,
            'message' => "Role user {$user->name} berhasil diubah dari " . ucfirst($oldRole) . " menjadi " . ucfirst($request->role),
        ]);
    }
}
