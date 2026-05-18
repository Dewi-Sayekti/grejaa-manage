@extends('layouts.landing')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="text-center">
        <div class="mb-8">
            <h1 class="text-6xl font-bold text-red-600 mb-2">404</h1>
            <h2 class="text-3xl font-semibold text-gray-800">Halaman Tidak Ditemukan</h2>
        </div>

        <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
            Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin telah dihapus atau alamatnya berubah.
        </p>

        <div class="space-x-4">
            <a href="{{ route('home') }}" class="inline-block px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                Kembali ke Beranda
            </a>
            <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-semibold">
                Login
            </a>
        </div>

        <div class="mt-12">
            <svg class="mx-auto w-48 h-48 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zm-2-9a9 9 0 00-8.942 7.053.75.75 0 101.503.148A7.5 7.5 0 0116 8a.75.75 0 00.75-.75V6a.75.75 0 00-1.5 0v1.25A7.5 7.5 0 0116 8a10 10 0 01-10 10 .75.75 0 010-1.5A8.5 8.5 0 0016 8V6a9 9 0 00-9-9z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
</div>
@endsection
