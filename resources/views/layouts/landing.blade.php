<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Gereja Management System')</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/Logobaru.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/Logobaru.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logobaru.png') }}">
    <meta property="og:image" content="{{ asset('images/Logobaru.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Figtree:wght@400;500;600;700&display=swap"
    rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    <!-- HEADER & NAVIGATION -->
    <header class="header">
        <div class="header-container">
            <!-- Logo -->
            <div class="logo">
<img src="{{ asset('images/Logobaru.png') }}"
             alt="Logo Gereja"
             class="h-20 w-auto">
                             <div>
                    {{-- <div>Gereja YHS</div>
                    <span class="logo-sub">Jemaat & Manajemen</span> --}}
                </div>
            </div>

            <!-- Desktop Navigation -->
            <nav class="navbar hidden md:flex">
                <!-- Home -->
                <a href="{{ route('welcome') }}" class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}">
                    <i class="fas fa-home mr-1"></i> Beranda
                </a>

                <!-- Tentang Kami (Dropdown) -->
                <div class="nav-dropdown">
                    <button class="nav-link flex items-center">
                        <i class="fas fa-info-circle mr-1"></i> Tentang Kami
                        <i class="fas fa-chevron-down text-xs ml-1"></i>
                    </button>
                    <div class="nav-dropdown-content">
                        <a href="{{ route('history') }}" class="nav-dropdown-item">
                            <i class="fas fa-book mr-2"></i> Sejarah Gereja
                        </a>
                        <a href="{{ route('vision') }}" class="nav-dropdown-item">
                            <i class="fas fa-bullseye mr-2"></i> Visi & Misi
                        </a>
                        <a href="{{ route('struktur') }}" class="nav-dropdown-item">
                            <i class="fas fa-sitemap mr-2"></i> Struktur Organisasi
                        </a>
                        <a href="{{ route('pastors') }}" class="nav-dropdown-item">
                            <i class="fas fa-users mr-2"></i> Para Pemimpin
                        </a>
                    </div>
                </div>

                <!-- Pelayanan (Dropdown) -->
                <div class="nav-dropdown">
                    <button class="nav-link flex items-center">
                        <i class="fas fa-hands-praying mr-1"></i> Pelayanan
                        <i class="fas fa-chevron-down text-xs ml-1"></i>
                    </button>
                    <div class="nav-dropdown-content">
                        <a href="{{ route('layanan') }}" class="nav-dropdown-item">
                            <i class="fas fa-bible mr-2"></i> Jadwal Ibadah
                        </a>
                        <a href="{{ route('layanan') }}" class="nav-dropdown-item">
                            <i class="fas fa-people-group mr-2"></i> Kelompok Kecil
                        </a>
                        <a href="{{ route('layanan') }}" class="nav-dropdown-item">
                            <i class="fas fa-child mr-2"></i> Sekolah Minggu
                        </a>
                        <a href="{{ route('layanan') }}" class="nav-dropdown-item">
                            <i class="fas fa-music mr-2"></i> Musik & Nyanyian
                        </a>
                    </div>
                </div>

                <!-- Acara (Dropdown) -->
                <div class="nav-dropdown">
                    <button class="nav-link flex items-center">
                        <i class="fas fa-calendar mr-1"></i> Acara
                        <i class="fas fa-chevron-down text-xs ml-1"></i>
                    </button>
                    <div class="nav-dropdown-content">
                        <a href="{{ route('pengumuman') }}" class="nav-dropdown-item">
                            <i class="fas fa-clock mr-2"></i> Acara Mendatang
                        </a>
                        <a href="{{ route('gallery') }}" class="nav-dropdown-item">
                            <i class="fas fa-image mr-2"></i> Galeri
                        </a>
                        <a href="{{ route('pengumuman') }}" class="nav-dropdown-item">
                            <i class="fas fa-newspaper mr-2"></i> Berita & Pengumuman
                        </a>
                    </div>
                </div>

                <!-- Kontak -->
                <a href="{{ route('dashboard') }}#kontak" class="nav-link">
                    <i class="fas fa-phone mr-1"></i> Kontak
                </a>
            </nav>

            <!-- Auth Links -->
            <div class="auth-container">
                @auth
                    <div class="nav-dropdown">
                        <button class="btn-dashboard flex items-center">
                            <i class="fas fa-user-circle mr-2"></i>
                            {{ Auth::user()->name }}
                            <i class="fas fa-chevron-down text-xs ml-1"></i>
                        </button>
                        <div class="nav-dropdown-content">
                            {{-- <a href="{{ route('dashboard') }}" class="nav-dropdown-item">
                                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                            </a> --}}
                            <a href="{{ route('profile.edit') }}" class="nav-dropdown-item">
                                <i class="fas fa-user-edit mr-2"></i> Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="nav-dropdown-item p-0">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-cream-400">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-sign-in">
                        <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn-dashboard">
                        <i class="fas fa-user-plus mr-1"></i> Daftar
                    </a>
                @endauth

                <!-- Mobile Menu Toggle -->
                <button id="mobile-menu-btn" class="md:hidden text-gold-600 text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-cream-300 border-t-2 border-gold-400">
            <nav class="flex flex-col p-4 space-y-2">
                <a href="{{ route('welcome') }}" class="nav-link block">
                    <i class="fas fa-home mr-2"></i> Beranda
                </a>
                <a href="{{ route('history') }}" class="nav-link block">
                    <i class="fas fa-info-circle mr-2"></i> Sejarah Gereja
                </a>
                <a href="{{ route('vision') }}" class="nav-link block">
                    <i class="fas fa-bullseye mr-2"></i> Visi & Misi
                </a>
                <a href="{{ route('layanan') }}" class="nav-link block">
                    <i class="fas fa-hands-praying mr-2"></i> Pelayanan
                </a>
                <a href="{{ route('pengumuman') }}" class="nav-link block">
                    <i class="fas fa-calendar mr-2"></i> Acara
                </a>
                <a href="{{ route('gallery') }}" class="nav-link block">
                    <i class="fas fa-image mr-2"></i> Galeri
                </a>
                <a href="{{ route('welcome') }}#kontak" class="nav-link block">
                    <i class="fas fa-phone mr-2"></i> Kontak
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- FOOTER -->
    @include('layouts.footer')

    <!-- Mobile Menu Script -->
    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>


    <!-- Dropdown Hover Script with Delay -->
    <script>
        (function() {
            const dropdowns = document.querySelectorAll('.nav-dropdown');
            dropdowns.forEach(function(dropdown) {
                let hideTimer = null;
                function showDropdown() {
                    clearTimeout(hideTimer);
                    dropdowns.forEach(function(d) { if (d !== dropdown) d.classList.remove('open'); });
                    dropdown.classList.add('open');
                }
                function hideDropdown() {
                    hideTimer = setTimeout(function() { dropdown.classList.remove('open'); }, 200);
                }
                dropdown.addEventListener('mouseenter', showDropdown);
                dropdown.addEventListener('mouseleave', hideDropdown);
                const content = dropdown.querySelector('.nav-dropdown-content');
                if (content) {
                    content.addEventListener('mouseenter', function() { clearTimeout(hideTimer); });
                    content.addEventListener('mouseleave', hideDropdown);
                }
            });
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.nav-dropdown')) {
                    dropdowns.forEach(function(d) { d.classList.remove('open'); });
                }
            });
        })();
    </script>
</body>
</html>