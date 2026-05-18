@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')

<!-- Welcome Banner -->
<div class="welcome-banner">
    <h2>Selamat Datang, <span class="gold-text">{{ Auth::user()->name }}</span> 👋</h2>
    <p>Kelola data gereja dengan mudah dan efisien melalui dashboard ini.</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <div class="stat-label">Total Jemaat</div>
            <div class="stat-value">{{ $totalJemaat }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-user-shield"></i></div>
        <div class="stat-info">
            <div class="stat-label">Total User</div>
            <div class="stat-value">{{ $totalUser }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-user-clock"></i></div>
        <div class="stat-info">
            <div class="stat-label">Menunggu Persetujuan</div>
            <div class="stat-value">{{ $userPending }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-arrow-up"></i></div>
        <div class="stat-info">
            <div class="stat-label">Pemasukan</div>
            <div class="stat-value" style="font-size:18px;color:var(--success);">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-arrow-down"></i></div>
        <div class="stat-info">
            <div class="stat-label">Pengeluaran</div>
            <div class="stat-value" style="font-size:18px;color:var(--danger);">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon teal"><i class="fas fa-images"></i></div>
        <div class="stat-info">
            <div class="stat-label">Total Gambar</div>
            <div class="stat-value">{{ $totalGambar }}</div>
        </div>
    </div>
</div>

<!-- Saldo Card -->
<div class="card" style="margin-bottom:28px;">
    <div class="card-body" style="display:flex;align-items:center;justify-content:space-between;">
        <div>
            <div style="font-size:13px;color:var(--text-mid);font-weight:500;">SALDO GEREJA</div>
            <div style="font-size:28px;font-weight:700;font-family:'Playfair Display',serif;color:{{ ($totalPemasukan - $totalPengeluaran) >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
            </div>
        </div>
        <div style="font-size:40px;color:var(--border);"><i class="fas fa-church"></i></div>
    </div>
</div>

<!-- Two Column Grid -->
<div class="grid-2">
    <!-- Jemaat Terbaru -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-plus" style="color:var(--gold);margin-right:8px;"></i>Jemaat Terbaru</h3>
        </div>
        @if($jemaatTerbaru->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jemaatTerbaru as $j)
                            <tr>
                                <td style="font-weight:500;">{{ $j->nama_lengkap }}</td>
                                <td>{{ $j->jenis_kelamin }}</td>
                                <td><span class="badge badge-success">{{ $j->status_aktif }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <p>Belum ada data jemaat.</p>
            </div>
        @endif
    </div>

    <!-- Transaksi Terbaru -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-receipt" style="color:var(--gold);margin-right:8px;"></i>Transaksi Terbaru</h3>
        </div>
        @if($transaksiTerbaru->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksiTerbaru as $t)
                            <tr>
                                <td>{{ $t->tanggal_transaksi ? $t->tanggal_transaksi->format('d M Y') : '-' }}</td>
                                <td>
                                    <span class="badge {{ $t->tipe == 'pemasukan' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($t->tipe) }}
                                    </span>
                                </td>
                                <td style="font-weight:600;color:{{ $t->tipe == 'pemasukan' ? 'var(--success)' : 'var(--danger)' }};">
                                    Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-receipt"></i>
                <p>Belum ada transaksi.</p>
            </div>
        @endif
    </div>
</div>

<!-- Notifikasi Terbaru -->
<div class="card" style="margin-bottom:28px;">
    <div class="card-header">
        <h3><i class="fas fa-bell" style="color:var(--gold);margin-right:8px;"></i>Notifikasi Terbaru</h3>
    </div>
    @if($notifikasiTerbaru->count() > 0)
        @foreach($notifikasiTerbaru as $n)
            <div class="notif-item">
                <div class="notif-icon" style="background:#fef3c7;color:#d97706;">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="notif-content">
                    <div class="notif-title">{{ $n->judul }}</div>
                    <div class="notif-text">{{ $n->isi }}</div>
                    <div class="notif-time">
                        {{ $n->tanggal_kirim ? $n->tanggal_kirim->diffForHumans() : '-' }}
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="fas fa-bell-slash"></i>
            <p>Tidak ada notifikasi.</p>
        </div>
    @endif
</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-bolt" style="color:var(--gold);margin-right:8px;"></i>Aksi Cepat</h3>
    </div>
    <div class="card-body">
        <div class="quick-actions">
            <a href="{{ route('admin.galeri.create') }}" class="quick-action-btn">
                <i class="fas fa-plus-circle"></i> Tambah Gambar
            </a>
            <a href="{{ route('profile.edit') }}" class="quick-action-btn">
                <i class="fas fa-user-edit"></i> Edit Profil
            </a>
            <a href="{{ route('welcome') }}" class="quick-action-btn">
                <i class="fas fa-globe"></i> Lihat Website
            </a>
            <a href="{{ route('gallery') }}" class="quick-action-btn">
                <i class="fas fa-images"></i> Lihat Galeri
            </a>
        </div>
    </div>
</div>

@endsection
