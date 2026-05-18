<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('notifikasi.index', compact('notifikasi'));
    }

    public function markAsRead($id)
    {
        $notifikasi = Notifikasi::find($id);
        $notifikasi->update(['dibaca' => true]);

        return redirect()->back()->with('success', 'Notifikasi ditandai sebagai sudah dibaca!');
    }
}
