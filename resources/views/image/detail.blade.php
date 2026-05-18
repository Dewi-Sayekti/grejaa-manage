@extends('layouts.landing')

@section('title', $image->title . ' - Galeri Gereja YHS')

@section('content')
    <div class="container mx-auto py-12 px-6">
        <a href="{{ route('gallery') }}" class="inline-flex items-center gap-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition mb-6">
            <i class="fas fa-arrow-left"></i> Kembali ke Galeri
        </a>

        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            @php
                $imageUrl = str_starts_with($image->path, 'http')
                    ? $image->path
                    : (str_starts_with($image->path, 'images/')
                        ? asset($image->path)
                        : asset('storage/' . $image->path));
            @endphp
            <img src="{{ $imageUrl }}" class="w-full h-auto" alt="{{ $image->title }}">

            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ $image->title }}</h2>

                {{-- Tanggal Upload --}}
                <p class="text-sm text-gray-500 mt-2">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Diunggah: {{ $image->created_at ? $image->created_at->format('d M Y, H:i') : '-' }}
                </p>

                @if($image->description)
                    <p class="mt-4 text-gray-600 leading-relaxed">{{ $image->description }}</p>
                @endif

                <div class="mt-6 flex flex-wrap gap-3">
                    {{-- Download Button (semua user) --}}
                    <a href="{{ route('image.download', $image->id) }}"
                       class="inline-flex items-center gap-2 bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 transition">
                        <i class="fas fa-download"></i> Download Gambar
                    </a>

                    {{-- Edit & Hapus hanya untuk Admin --}}
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.galeri.edit', $image->id) }}"
                               class="inline-flex items-center gap-2 bg-yellow-500 text-white px-5 py-2 rounded hover:bg-yellow-600 transition">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.galeri.delete', $image->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin mau hapus gambar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-2 bg-red-600 text-white px-5 py-2 rounded hover:bg-red-700 transition">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection
