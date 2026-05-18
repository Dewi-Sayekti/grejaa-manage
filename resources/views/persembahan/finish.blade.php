@extends('layouts.dashboard')
@section('title', 'Terima Kasih')

@section('content')
<div style="max-width:480px;margin:60px auto;text-align:center;">
    <div style="font-size:64px;margin-bottom:16px;">
        {{ $persembahan && $persembahan->isSettled() ? '🙏' : '⏳' }}
    </div>
    <h2 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:800;color:var(--text-dark);margin-bottom:8px;">
        {{ $persembahan && $persembahan->isSettled() ? 'Puji Tuhan, Terima Kasih!' : 'Menunggu Konfirmasi Pembayaran' }}
    </h2>
    <p style="color:var(--text-mid);font-size:14px;line-height:1.7;margin-bottom:24px;">
        @if($persembahan && $persembahan->isSettled())
            Persembahan sebesar <strong>Rp {{ number_format($persembahan->jumlah, 0, ',', '.') }}</strong>
            telah berhasil diterima. Tuhan memberkati setiap langkah iman Anda.
        @else
            Pembayaran Anda sedang diproses. Jika sudah berhasil, status akan diperbarui otomatis.
        @endif
    </p>
    <a href="{{ route('persembahan.index') }}"
       style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:linear-gradient(135deg,var(--gold),var(--gold-dark));color:#fff;border-radius:10px;font-weight:700;text-decoration:none;">
        <i class="fas fa-heart"></i> Kembali
    </a>
</div>
@endsection