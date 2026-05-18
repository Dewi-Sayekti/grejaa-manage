@extends('layouts.landing')

@section('content')
<div class="bg-white min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-6">
        <nav class="flex mb-8 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('welcome') }}" class="hover:text-yellow-600">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-300 text-xs mx-2"></i>
                        <a href="{{ route('pengumuman') }}" class="hover:text-yellow-600">Pengumuman</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-300 text-xs mx-2"></i>
                        <span class="text-gray-400 truncate max-w-xs">{{ $news->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <article>
            <header class="mb-10">
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                        {{ $news->is_event ? 'Event' : 'Berita' }}
                    </span>
                    <span class="text-gray-400 text-sm">
                        <i class="fas fa-clock mr-1"></i> {{ $news->created_at->format('d M Y') }}
                    </span>
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6" style="font-family:'Playfair Display', serif; line-height: 1.2;">
                    {{ $news->title }}
                </h1>
                
                @if($news->is_event && $news->tanggal_acara)
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 flex flex-wrap gap-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center text-yellow-600">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400 font-bold uppercase">Tanggal</div>
                                <div class="text-gray-800 font-bold">{{ $news->tanggal_acara->format('d M Y') }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center text-yellow-600">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400 font-bold uppercase">Waktu</div>
                                <div class="text-gray-800 font-bold">{{ $news->tanggal_acara->format('H:i') }} WIB</div>
                            </div>
                        </div>
                        @if($news->lokasi_acara)
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center text-yellow-600">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400 font-bold uppercase">Lokasi</div>
                                    <div class="text-gray-800 font-bold">{{ $news->lokasi_acara }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </header>

            @if($news->image_path)
                <div class="rounded-3xl overflow-hidden shadow-lg mb-12">
                    <img src="{{ Storage::url($news->image_path) }}" class="w-full h-auto" alt="{{ $news->title }}">
                </div>
            @endif

            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                {!! nl2br(e($news->content)) !!}
            </div>

            @if($news->is_event && $news->kuota)
                 <div class="mt-12 p-8 bg-yellow-50 rounded-3xl border border-yellow-100 text-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tertarik mengikuti acara ini?</h3>
                    <p class="text-gray-600 mb-6">Kuota terbatas! Tersisa kursi untuk {{ $news->kuota }} orang.</p>
                    <a href="{{ route('login') }}" class="inline-block px-10 py-4 bg-yellow-500 text-white font-extrabold rounded-2xl hover:bg-yellow-600 transition-all shadow-lg shadow-yellow-200">
                        Daftar Sekarang
                    </a>
                 </div>
            @endif
        </article>

        <footer class="mt-20 pt-10 border-t border-gray-100 flex justify-between items-center">
            <div class="text-gray-400 text-sm">
                Bagikan: 
                <a href="#" class="ml-2 hover:text-blue-600"><i class="fab fa-facebook"></i></a>
                <a href="#" class="ml-2 hover:text-green-600"><i class="fab fa-whatsapp"></i></a>
                <a href="#" class="ml-2 hover:text-blue-400"><i class="fab fa-twitter"></i></a>
            </div>
            <a href="{{ route('pengumuman') }}" class="text-yellow-600 font-bold hover:underline">
                Lihat Berita Lainnya <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </footer>
    </div>
</div>
@endsection
