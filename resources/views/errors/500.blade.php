@extends('layouts.landing')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="text-center">
        <div class="mb-8">
            <h1 class="text-6xl font-bold text-orange-600 mb-2">500</h1>
            <h2 class="text-3xl font-semibold text-gray-800">Kesalahan Server Internal</h2>
        </div>

        <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
            Maaf, terjadi kesalahan pada server kami. Tim teknis kami sudah diberitahu dan sedang menangani masalah ini. Silakan coba lagi nanti.
        </p>

        <div class="space-x-4">
            <a href="{{ route('home') }}" class="inline-block px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                Kembali ke Beranda
            </a>
            <a href="javascript:history.back()" class="inline-block px-8 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-semibold">
                Kembali
            </a>
        </div>

        <div class="mt-12">
            <svg class="mx-auto w-48 h-48 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
</div>
@endsection
