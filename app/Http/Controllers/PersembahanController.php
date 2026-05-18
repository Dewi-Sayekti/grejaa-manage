<?php

namespace App\Http\Controllers;

use App\Models\Persembahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PersembahanController extends Controller
{
    public function __construct()
    {
        // Setup Midtrans config
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    // ── Halaman form persembahan (publik + login) ──
    public function index()
    {
        $riwayat = collect();
        if (Auth::check()) {
            $riwayat = Persembahan::where('user_id', Auth::id())
                ->latest()
                ->take(5)
                ->get();
        }
        return view('persembahan.index', compact('riwayat'));
    }

    // ── Buat transaksi ke Midtrans ──
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemberi' => 'required|string|max:100',
            'email'        => 'required|email',
            'phone'        => 'nullable|string|max:20',
            'jenis'        => 'required|in:persembahan,perpuluhan,diakonia,misi,lainnya',
            'jumlah'       => 'required|integer|min:5000',
            'catatan'      => 'nullable|string|max:200',
        ]);

        $orderId = 'GRJ-' . strtoupper(Str::random(10)) . '-' . time();

        // Simpan ke DB dulu dengan status pending
        $persembahan = Persembahan::create([
            'user_id'      => Auth::id(),
            'nama_pemberi' => $request->nama_pemberi,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'jenis'        => $request->jenis,
            'jumlah'       => $request->jumlah,
            'catatan'      => $request->catatan,
            'order_id'     => $orderId,
            'status'       => 'pending',
        ]);

        // Kirim ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $request->jumlah,
            ],
            'customer_details' => [
                'first_name' => $request->nama_pemberi,
                'email'      => $request->email,
                'phone'      => $request->phone,
            ],
            'item_details' => [[
                'id'       => $request->jenis,
                'price'    => (int) $request->jumlah,
                'quantity' => 1,
                'name'     => 'Persembahan - ' . ucfirst($request->jenis),
            ]],
            // Hanya aktifkan QRIS, GoPay, OVO, Dana
            'enabled_payments' => ['gopay', 'shopeepay', 'other_qris'],
            'gopay' => ['enable_callback' => true],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $persembahan->update(['snap_token' => $snapToken]);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id'   => $orderId,
            ]);
        } catch (\Exception $e) {
            $persembahan->delete();
            return response()->json(['error' => 'Gagal membuat transaksi: ' . $e->getMessage()], 500);
        }
    }

    // ── Webhook dari Midtrans (dipanggil otomatis oleh Midtrans) ──
    public function webhook(Request $request)
    {
        try {
            $notif = new Notification();

            $orderId       = $notif->order_id;
            $status        = $notif->transaction_status;
            $paymentType   = $notif->payment_type;
            $transactionId = $notif->transaction_id;
            $fraudStatus   = $notif->fraud_status ?? null;

            $persembahan = Persembahan::where('order_id', $orderId)->firstOrFail();

            // Tentukan status final
            if ($status === 'capture') {
                $finalStatus = ($fraudStatus === 'challenge') ? 'pending' : 'settlement';
            } elseif ($status === 'settlement') {
                $finalStatus = 'settlement';
            } elseif (in_array($status, ['cancel', 'deny', 'expire'])) {
                $finalStatus = $status;
            } else {
                $finalStatus = 'pending';
            }

            $persembahan->update([
                'status'         => $finalStatus,
                'payment_type'   => $paymentType,
                'transaction_id' => $transactionId,
                'paid_at'        => $finalStatus === 'settlement' ? now() : null,
            ]);

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ── Halaman sukses setelah bayar ──
    public function finish(Request $request)
    {
        $persembahan = Persembahan::where('order_id', $request->order_id)->first();
        return view('persembahan.finish', compact('persembahan'));
    }

    // ── Admin: lihat semua persembahan ──
    public function adminIndex(Request $request)
    {
        $keuangan = \App\Models\Keuangan::with('jemaat')
            ->orderBy('tanggal_transaksi', 'desc')
            ->paginate(15);

        $monthlyData = \App\Models\Keuangan::selectRaw('MONTH(tanggal_transaksi) as month')
            ->selectRaw("SUM(CASE WHEN tipe = 'pemasukan' THEN jumlah ELSE 0 END) as pemasukan")
            ->selectRaw("SUM(CASE WHEN tipe = 'pengeluaran' THEN jumlah ELSE 0 END) as pengeluaran")
            ->whereYear('tanggal_transaksi', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date('F', mktime(0, 0, 0, $item->month, 10)),
                    'pemasukan' => (float) $item->pemasukan,
                    'pengeluaran' => (float) $item->pengeluaran,
                ];
            })
            ->toArray();

        return view('admin.persembahan.index', compact('keuangan', 'monthlyData'));
    }
}