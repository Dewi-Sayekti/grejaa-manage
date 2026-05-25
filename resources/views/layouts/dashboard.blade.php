<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Gereja YHS</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/Logobaru.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/Logobaru.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logobaru.png') }}">
    <meta property="og:image" content="{{ asset('images/Logobaru.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 260px;
            --topbar-h: 64px;
            --sidebar-bg: #1e1a14;
            --sidebar-hover: #2a2520;
            --sidebar-active: #d4af37;
            --content-bg: #f7f5f0;
            --card-bg: #ffffff;
            --gold: #d4af37;
            --gold-dark: #b8960b;
            --gold-light: #f5e6b8;
            --text-dark: #2c3e50;
            --text-mid: #6b7280;
            --text-light: #9ca3af;
            --border: #e8e4dc;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --radius: 12px;
            --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--content-bg);
            color: var(--text-dark);
            line-height: 1.6;
        }

        a { text-decoration: none; color: inherit; }

        /* ===== LAYOUT ===== */
        .dash-wrapper { display: flex; min-height: 100vh; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 50;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand img { height: 36px; width: auto; }

        .sidebar-brand-text {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--gold);
        }

        .sidebar-nav { flex: 1; padding: 16px 12px; }

        .sidebar-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,0.35);
            padding: 16px 12px 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: rgba(255,255,255,0.65);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 2px;
        }

        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: rgba(255,255,255,0.9);
        }

        .sidebar-link.active {
            background: rgba(212,175,55,0.15);
            color: var(--gold);
        }

        .sidebar-link i { width: 20px; text-align: center; font-size: 15px; }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            border-radius: 8px;
        }

        .sidebar-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--gold);
            color: var(--sidebar-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .sidebar-user-info { flex: 1; min-width: 0; }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 600;
            color: rgba(255,255,255,0.9);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: 11px;
            color: var(--gold);
            text-transform: capitalize;
        }

        /* ===== MAIN ===== */
        .main-area {
            flex: 1;
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }

        .topbar {
            height: var(--topbar-h);
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-left { display: flex; align-items: center; gap: 16px; }

        .topbar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-dark);
            cursor: pointer;
        }

        .topbar-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .topbar-right { display: flex; align-items: center; gap: 16px; }

        .topbar-date { font-size: 13px; color: var(--text-mid); }

        .topbar-btn {
            background: none;
            border: none;
            font-size: 18px;
            color: var(--text-mid);
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            transition: all 0.2s;
            position: relative;
        }

        .topbar-btn:hover { color: var(--gold); background: var(--gold-light); }

        .topbar-logout {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .topbar-logout:hover { opacity: 0.9; transform: translateY(-1px); }

        .content-area { padding: 28px; }

        /* ===== CARDS ===== */
        .card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h3 {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            font-weight: 600;
        }

        .card-body { padding: 22px; }

        /* ===== STATS GRID ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 22px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-icon.gold { background: #fef3c7; color: #d97706; }
        .stat-icon.blue { background: #dbeafe; color: #2563eb; }
        .stat-icon.green { background: #d1fae5; color: #059669; }
        .stat-icon.red { background: #fce4ec; color: #dc2626; }
        .stat-icon.purple { background: #ede9fe; color: #7c3aed; }
        .stat-icon.teal { background: #ccfbf1; color: #0d9488; }

        .stat-info { flex: 1; }

        .stat-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-mid);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
            margin-top: 2px;
        }

        /* ===== TABLE ===== */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-mid);
            background: #faf8f5;
            border-bottom: 1px solid var(--border);
        }

        table td {
            padding: 12px 16px;
            font-size: 14px;
            border-bottom: 1px solid #f3f1ec;
            color: var(--text-dark);
        }

        table tbody tr:hover { background: #fdfcfa; }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fce4ec; color: #991b1b; }
        .badge-info { background: #dbeafe; color: #1e40af; }

        /* ===== GRID LAYOUTS ===== */
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 28px;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-mid);
        }

        .empty-state i { font-size: 40px; color: var(--border); margin-bottom: 12px; }
        .empty-state p { font-size: 14px; }

        /* ===== PROFILE CARD ===== */
        .profile-header {
            background: linear-gradient(135deg, #2c2417 0%, #1e1a14 100%);
            padding: 32px 28px;
            text-align: center;
            color: white;
        }

        .profile-avatar-lg {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: var(--gold);
            color: var(--sidebar-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 700;
            margin: 0 auto 12px;
            border: 3px solid rgba(255,255,255,0.2);
        }

        .profile-name {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
        }

        .profile-role {
            font-size: 13px;
            color: var(--gold);
            margin-top: 4px;
        }

        .profile-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0;
        }

        .profile-detail-item {
            padding: 14px 22px;
            border-bottom: 1px solid var(--border);
        }

        .profile-detail-item:nth-child(odd) {
            border-right: 1px solid var(--border);
        }

        .profile-detail-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-mid);
            letter-spacing: 0.5px;
        }

        .profile-detail-value {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            margin-top: 2px;
        }

        /* ===== NOTIFICATION LIST ===== */
        .notif-item {
            display: flex;
            gap: 14px;
            padding: 14px 22px;
            border-bottom: 1px solid #f3f1ec;
            transition: background 0.2s;
        }

        .notif-item:hover { background: #fdfcfa; }

        .notif-icon {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .notif-content { flex: 1; min-width: 0; }

        .notif-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .notif-text {
            font-size: 13px;
            color: var(--text-mid);
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notif-time {
            font-size: 11px;
            color: var(--text-light);
            margin-top: 4px;
        }

        /* ===== QUICK ACTIONS ===== */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .quick-action-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            border-radius: 10px;
            background: #faf8f5;
            border: 1px solid var(--border);
            font-size: 13px;
            font-weight: 500;
            color: var(--text-dark);
            transition: all 0.2s;
            cursor: pointer;
        }

        .quick-action-btn:hover {
            background: var(--gold-light);
            border-color: var(--gold);
            color: var(--gold-dark);
            transform: translateY(-1px);
        }

        .quick-action-btn i { font-size: 16px; color: var(--gold); }

        /* ===== WELCOME BANNER ===== */
        .welcome-banner {
            background: linear-gradient(135deg, #2c2417 0%, #3d3225 50%, #1e1a14 100%);
            border-radius: var(--radius);
            padding: 32px;
            color: white;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(212,175,55,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .welcome-banner h2 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .welcome-banner p { color: rgba(255,255,255,0.7); font-size: 14px; }

        .welcome-banner .gold-text { color: var(--gold); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .grid-2 { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-area { margin-left: 0; }
            .topbar-toggle { display: block; }
            .stats-grid { grid-template-columns: 1fr; }
            .profile-detail-grid { grid-template-columns: 1fr; }
            .profile-detail-item:nth-child(odd) { border-right: none; }
            .quick-actions { grid-template-columns: 1fr; }
            .content-area { padding: 16px; }
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 45;
        }

        .overlay.show { display: block; }
    </style>
</head>
<body>
    <div class="dash-wrapper">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('images/Logobaru.png') }}" alt="Logo" class="h-10 w-auto" onerror="this.style.display='none'">
                <span class="sidebar-brand-text">Gereja YHS</span>
            </div>

            <nav class="sidebar-nav">
                <div class="sidebar-label">Menu Utama</div>

                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>

                @if(Auth::user()->isAdmin())
                    <div class="sidebar-label">Manajemen</div>

                    <a href="{{ route('admin.jemaat.index') }}" class="sidebar-link {{ request()->routeIs('admin.jemaat.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Data Jemaat
                    </a>
                    <a href="{{ route('admin.keuangan.index') }}" class="sidebar-link {{ request()->routeIs('admin.keuangan.*') ? 'active' : '' }}">
                        <i class="fas fa-wallet"></i> Keuangan
                    </a>
                    <a href="{{ route('admin.galeri.create') }}" class="sidebar-link">
                        <i class="fas fa-images"></i> Galeri
                    </a>
                    <a href="{{ route('admin.notifikasi.index') }}" class="sidebar-link {{ request()->routeIs('admin.notifikasi.*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i> Notifikasi
                    </a>
                    <a href="{{ route('admin.user-approvals.index') }}" class="sidebar-link {{ request()->routeIs('admin.user-approvals.index') ? 'active' : '' }}">
                        <i class="fas fa-user-check"></i> Persetujuan User
                    </a>

                    <div class="sidebar-label">Konten</div>
                    <a href="{{ route('admin.pastors.index') }}" class="sidebar-link {{ request()->routeIs('admin.pastors.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i> Pemimpin
                    </a>
                    <a href="{{ route('admin.services.index') }}" class="sidebar-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <i class="fas fa-hands-praying"></i> Pelayanan
                    </a>
                    <a href="{{ route('admin.schedules.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Jadwal Ibadah
                    </a>
                    <a href="{{ route('admin.news.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i> Berita & Pengumuman
                    </a>
                    <a href="{{ route('admin.absensi.index') }}" class="sidebar-link {{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i> Presensi Jemaat
                    </a>
                    @php $pendingAbs = \App\Models\Absensi::where('approval_status','pending')->count(); @endphp
                    @if($pendingAbs > 0)
                    <span style="margin-left:auto;background:var(--danger);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:10px;">
                    {{ $pendingAbs }}
                    </span>
                    @endif
                    </a>
                    @else
                    <div class="sidebar-label">Informasi</div>

                    <a href="{{ route('profile.edit') }}" class="sidebar-link">
                        <i class="fas fa-user-circle"></i> Profil Saya
                    </a>
                    <a href="{{ route('dashboard') }}" class="sidebar-link">
                        <i class="fas fa-bell"></i> Notifikasi
                    </a>

                    <div class="sidebar-label">Konten Gereja</div>
                    <a href="{{ route('dashboard.features.pastors') }}" class="sidebar-link {{ request()->routeIs('dashboard.features.pastors') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i> Pemimpin
                    </a>
                    <a href="{{ route('dashboard.features.services') }}" class="sidebar-link {{ request()->routeIs('dashboard.features.services') ? 'active' : '' }}">
                        <i class="fas fa-hands-praying"></i> Pelayanan
                    </a>
                    <a href="{{ route('dashboard.features.events') }}" class="sidebar-link {{ request()->routeIs('dashboard.features.events') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> Acara & Berita
                    </a>
                    <a href="{{ route('absensi.index') }}"
                    class="sidebar-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i> Presensi Saya
                    </a>
                    <a href="{{ route('persembahan.index') }}"
                    class="sidebar-link {{ request()->routeIs('persembahan.*') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-heart"></i> Persembahan
                    </a>
                    @endif

                <div class="sidebar-label">Lainnya</div>

                <a href="{{ route('welcome') }}" class="sidebar-link">
                    <i class="fas fa-globe"></i> Lihat Website
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                        <div class="sidebar-user-role">{{ Auth::user()->role }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- OVERLAY (mobile) -->
        <div class="overlay" id="overlay"></div>

        <!-- MAIN CONTENT -->
        <div class="main-area">
            <header class="topbar">
                <div class="topbar-left">
                    <button class="topbar-toggle" id="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="topbar-right">
                    <span class="topbar-date">
                        <i class="far fa-calendar-alt"></i>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="topbar-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </header>

            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const toggleBtn = document.getElementById('toggleSidebar');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            });
        }

        // Pop-up untuk success message
        @if(session('success'))
            const successMessage = "{{ session('success') }}";
            let iconType = 'success';
            let titleText = 'Berhasil!';

            // Customize based on message
            if (successMessage.includes('Jadwal')) {
                titleText = successMessage.includes('diperbarui') ? 'Jadwal Diperbarui!' : 'Jadwal Ditambahkan!';
            } else if (successMessage.includes('dihapus')) {
                titleText = 'Dihapus!';
            }

            Swal.fire({
                title: titleText,
                text: successMessage,
                icon: iconType,
                confirmButtonText: 'Kembali',
                confirmButtonColor: '#d4af37',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dari jadwal, pastikan refresh di index jadwal
                    if (successMessage.includes('Jadwal')) {
                        window.location.href = "{{ route('admin.schedules.index') }}";
                    }
                }
            });
        @endif
    </script>
</body>
</html>


{{-- Letakkan setelah link Notifikasi di bagian @else --}}
<a href="{{ route('absensi.index') }}"
   class="sidebar-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
    <i class="fas fa-clipboard-check"></i> Presensi Saya
</a>

{{--
=================================================================
2. Untuk bagian ADMIN (di dalam @if(Auth::user()->isAdmin()), bagian "Manajemen"):
=================================================================
--}}

{{-- Letakkan setelah link "Persetujuan User" di bagian admin --}}
<a href="{{ route('admin.absensi.index') }}"
   class="sidebar-link {{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}">
    <i class="fas fa-clipboard-list"></i> Presensi
    @php $pendingAbs = \App\Models\Absensi::where('approval_status','pending')->count(); @endphp
    @if($pendingAbs > 0)
        <span style="margin-left:auto;background:var(--danger);color:#fff;font-size:10px;font-weight:700;padding:2px 6px;border-radius:10px;">
            {{ $pendingAbs }}
        </span>
    @endif
</a>
