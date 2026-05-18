@extends('layouts.landing')

@section('title', 'Galeri Kegiatan - Gereja YHS')

@section('content')
<div class="container mx-auto py-12 px-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-2 text-center">Galeri Kegiatan Gereja</h1>
    <p class="text-gray-700 leading-relaxed mb-8 text-center">
        Lihat berbagai kegiatan dan momen berharga dari perjalanan gereja kami.
    </p>

    {{-- Tombol Tambah Gambar hanya untuk Admin --}}
    @auth
        @if(Auth::user()->isAdmin())
            <div class="flex justify-end mb-6">
                <a href="{{ route('admin.galeri.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Tambah Gambar
                </a>
            </div>
        @endif
    @endauth

    @if($images && count($images) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($images as $image)
            @php
                $imageUrl = str_starts_with($image->path, 'http')
                    ? $image->path
                    : (str_starts_with($image->path, 'images/')
                        ? asset($image->path)
                        : asset('storage/' . $image->path));
            @endphp
            <div class="relative overflow-hidden rounded-lg shadow-lg group">

                <img src="{{ $imageUrl }}"
                     alt="{{ $image->title }}"
                     class="w-full h-48 object-cover">

                {{-- Info Bar: Judul + Tanggal Upload --}}
                <div class="bg-white px-4 py-3 border-t">
                    <h3 class="font-semibold text-gray-800 text-sm truncate">{{ $image->title }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Diunggah: {{ $image->created_at ? $image->created_at->format('d M Y, H:i') : '-' }}
                    </p>
                </div>

                {{-- Overlay on Hover --}}
                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex flex-col items-center justify-center gap-2">

                    <p class="text-white font-semibold text-center px-2">{{ $image->title }}</p>

                    {{-- Detail --}}
                    <a href="{{ route('image.detail', $image->id) }}"
                       class="bg-white text-gray-800 px-3 py-1 rounded text-sm hover:bg-gray-100 transition">
                        <i class="fas fa-eye mr-1"></i> Detail
                    </a>

                    {{-- Download (semua user) --}}
                    <a href="{{ route('image.download', $image->id) }}"
                       class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition">
                        <i class="fas fa-download mr-1"></i> Download
                    </a>

                    {{-- Edit & Hapus hanya untuk Admin --}}
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.galeri.edit', $image->id) }}"
                               class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 transition">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>

                            <form action="{{ route('admin.galeri.delete', $image->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin mau hapus gambar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        @endif
                    @endauth

                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <div class="text-gray-400 text-6xl mb-4"><i class="fas fa-images"></i></div>
        <p class="text-gray-500 text-lg">Belum ada gambar yang di-upload.</p>
    </div>
    @endif
</div>
@endsection
