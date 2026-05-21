@extends('layouts.dashboard')

@section('title', 'Presensi QR')
@section('page-title', 'Presensi via QR Code')

@section('content')

<style>
.qr-wrap {
    max-width: 480px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.qr-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    overflow: hidden;
    text-align: center;
}

.qr-header {
    background: linear-gradient(135deg, #1e1a14, #2a2520);
    padding: 2rem 1.5rem 1.5rem;
    color: #fff;
}

.qr-header .schedule-emoji { font-size: 2.5rem; display: block; margin-bottom: .5rem; }
.qr-header h2 { font-size: 1.3rem; font-weight: 700; margin-bottom: .3rem; }
.qr-header .schedule-meta { font-size: .82rem; color: #d4af37; opacity: .9; }

.qr-body { padding: 2rem 1.5rem; }

.qr-jemaat-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: var(--gold-light);
    padding: 1rem 1.2rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    text-align: left;
}

.qr-jemaat-info .avatar {
    width: 48px; height: 48px;
    background: var(--gold);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: #1e1a14;
    font-weight: 700;
    flex-shrink: 0;
}

.qr-jemaat-info .nama { font-weight: 700; color: var(--text-dark); }
.qr-jemaat-info .sub  { font-size: .78rem; color: var(--text-mid); margin-top: .1rem; }

/* Status: sudah absen */
.already-badge {
    background: #d1fae5;
    border: 2px solid #10b981;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.already-badge .icon { font-size: 2.5rem; color: #10b981; display: block; margin-bottom: .5rem; }
.already-badge h3 { font-size: 1.1rem; font-weight: 700; color: #065f46; }
.already-badge p  { font-size: .85rem; color: #047857; margin-top: .3rem; }

/* Form Konfirmasi */
.confirm-box {
    background: #f9f8f6;
    border-radius: 10px;
    padding: 1.5rem;
    border: 1px solid var(--border);
    margin-bottom: 1.5rem;
    text-align: left;
}

.confirm-box h3 { font-size: .95rem; font-weight: 700; color: var(--text-dark); margin-bottom: 1rem; }

.confirm-detail { display: flex; justify-content: space-between; padding: .5rem 0; border-bottom: 1px solid var(--border); font-size: .85rem; }
.confirm-detail:last-child { border-bottom: none; }
.confirm-label  { color: var(--text-mid); }
.confirm-value  { font-weight: 600; color: var(--text-dark); }

.btn-scan-confirm {
    width: 100%;
    padding: 1rem;
    background: var(--gold);
    color: #1e1a14;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
}

.btn-scan-confirm:hover { background: var(--gold-dark); }

.btn-back {
    display: block;
    text-align: center;
    margin-top: 1rem;
    font-size: .85rem;
    color: var(--text-mid);
    text-decoration: none;
}

.btn-back:hover { color: var(--gold); }

.expires-info {
    font-size: .78rem;
    color: var(--text-light);
    margin-top: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .3rem;
}
</style>

<div class="qr-wrap">
    <div class="qr-card">
        {{-- Header jadwal --}}
        <div class="qr-header">
            <span class="schedule-emoji">{{ $schedule->emoji ?? '⛪' }}</span>
            <h2>{{ $schedule->title }}</h2>
            <div class="schedule-meta">
                📅 {{ now()->translatedFormat('l, d F Y') }}
                @if($schedule->start_time)
                    &bull; 🕐 {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} WIB
                @endif
                @if($schedule->location)
                    &bull; 📍 {{ $schedule->location }}
                @endif
            </div>
        </div>

        <div class="qr-body">
            {{-- Info Jemaat --}}
            <div class="qr-jemaat-info">
                <div class="avatar">{{ strtoupper(substr($jemaat->nama_lengkap, 0, 1)) }}</div>
                <div>
                    <div class="nama">{{ $jemaat->nama_lengkap }}</div>
                    <div class="sub">Jemaat Aktif &bull; {{ $jemaat->nomor_hp ?? '-' }}</div>
                </div>
            </div>

            @if($sudahAbsen)
                {{-- Sudah absen hari ini --}}
                <div class="already-badge">
                    <span class="icon">✅</span>
                    <h3>Anda Sudah Presensi!</h3>
                    <p>Kehadiran Anda di ibadah ini hari ini sudah tercatat.</p>
                </div>
                <a href="{{ route('absensi.index') }}" class="btn-scan-confirm" style="background:var(--success);color:#fff;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Presensi
                </a>
            @else
                {{-- Konfirmasi absen --}}
                <div class="confirm-box">
                    <h3><i class="fas fa-clipboard-check" style="color:var(--gold)"></i> Konfirmasi Kehadiran</h3>
                    <div class="confirm-detail">
                        <span class="confirm-label">Jadwal</span>
                        <span class="confirm-value">{{ $schedule->title }}</span>
                    </div>
                    <div class="confirm-detail">
                        <span class="confirm-label">Tanggal</span>
                        <span class="confirm-value">{{ now()->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="confirm-detail">
                        <span class="confirm-label">Status</span>
                        <span class="confirm-value" style="color:var(--success)">✓ Hadir</span>
                    </div>
                    <div class="confirm-detail">
                        <span class="confirm-label">Metode</span>
                        <span class="confirm-value">📱 QR Code</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('absensi.scan.store', $schedule->qr_token) }}">
                    @csrf
                    <button type="submit" class="btn-scan-confirm">
                        <i class="fas fa-check-circle"></i> Konfirmasi Saya Hadir
                    </button>
                </form>

                <div class="expires-info">
                    <i class="fas fa-clock"></i>
                    QR berlaku hingga {{ $schedule->qr_expires_at?->format('H:i') }} WIB
                </div>
            @endif

            <a href="{{ route('absensi.index') }}" class="btn-back">
                ← Kembali ke halaman presensi
            </a>
        </div>
    </div>
</div>

@endsection
