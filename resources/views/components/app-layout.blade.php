<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="font-sans text-gray-900 antialiased">
    <header class="header">
        <nav x-data="{ open: false }" class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('home') }}">🕊️ Gereja</a>
                <button class="navbar-toggler" type="button" @click="open = ! open"
                        aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarMain" :class="{'show': open}">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="homeDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">Home</a>
                            <ul class="dropdown-menu" aria-labelledby="homeDropdown">
                                <li><a class="dropdown-item" href="{{ route('history') }}">Gereja Historys</a></li>
                                <li><a class="dropdown-item" href="{{ route('vision') }}">Misi & Keyakinan</a></li>
                                <li><a class="dropdown-item" href="{{ route('layanan') }}">Layanan YHS</a></li>
                                <li><a class="dropdown-item" href="{{ route('pengumuman') }}">Pengumuman Terbaru</a></li>
                                <li><a class="dropdown-item" href="{{ route('pastors') }}">Pelayanan</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="ibadahDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">Ibadah</a>
                            <ul class="dropdown-menu" aria-labelledby="ibadahDropdown">
                                <li><a class="dropdown-item" href="#">Jadwal</a></li>
                                <li><a class="dropdown-item" href="#">Lokasi</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="mediaDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">Media</a>
                            <ul class="dropdown-menu" aria-labelledby="mediaDropdown">
                                <li><a class="dropdown-item" href="#">Khotbah</a></li>
                                <li><a class="dropdown-item" href="#">Video</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Get In Touch</a></li>
                        <li class="nav-item"><a class="nav-link" href="#give">Give</a></li>
                    </ul>

                    <div class="d-flex align-items-center gap-2">
                        @auth
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">SIGN IN</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-200">
            <div class="pt-2 pb-3 space-y-1">
                <div class="px-4 py-2">
                    <a class="block text-gray-700 hover:text-blue-600" href="{{ route('home') }}">Home</a>
                </div>
                <div class="px-4 py-2">
                    <a class="block text-gray-700 hover:text-blue-600" href="#contact">Get In Touch</a>
                </div>
                <div class="px-4 py-2">
                    <a class="block text-gray-700 hover:text-blue-600" href="#give">Give</a>
                </div>
            </div>

            @auth
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <div class="px-4 py-2">
                            <a class="block text-gray-700 hover:text-blue-600" href="{{ route('profile.edit') }}">Profile</a>
                        </div>
                        <div class="px-4 py-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left text-gray-700 hover:text-red-600">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4 py-2">
                        <a class="block text-gray-700 hover:text-blue-600" href="{{ route('login') }}">Sign In</a>
                    </div>
                </div>
            @endauth
        </div>
    </header>

    <main class="container mt-4">
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        {{ $slot }}
    </main>

    <footer class="footer bg-light py-3 mt-5">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} Gereja. Sistem Manajemen Jemaat. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
