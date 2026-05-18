@extends('layouts.landing')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-6">
        <a href="{{ route('layanan') }}" class="inline-flex items-center text-gray-500 hover:text-yellow-600 mb-8 transition-colors">
            <i class="fas fa-chevron-left mr-2"></i> Kembali ke Semua Pelayanan
        </a>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            {{-- Video or Header Image Placeholder --}}
            @if($service->video_link)
                <div class="aspect-video w-full">
                    @php
                        $videoId = '';
                        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $service->video_link, $match)) {
                            $videoId = $match[1];
                        }
                    @endphp
                    @if($videoId)
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <a href="{{ $service->video_link }}" target="_blank" class="text-blue-600 hover:underline">Tonton Video Pelayanan</a>
                        </div>
                    @endif
                </div>
            @endif

            <div class="p-10 md:p-16">
                <span class="inline-block px-4 py-1.5 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold uppercase tracking-widest mb-6">
                    {{ $service->category ?? 'Pelayanan' }}
                </span>
                
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-8" style="font-family:'Playfair Display', serif; line-height: 1.2;">
                    {{ $service->title }}
                </h1>

                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($service->description)) !!}
                </div>

                @if($service->video_link && !$videoId)
                    <div class="mt-12 p-6 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-gray-800">Tonton Video Terkait</h4>
                            <p class="text-sm text-gray-500">Lihat dokumentasi atau profil pelayanan ini.</p>
                        </div>
                        <a href="{{ $service->video_link }}" target="_blank" class="px-6 py-3 bg-yellow-500 text-white font-bold rounded-xl hover:bg-yellow-600 transition-colors">
                            Buka Link <i class="fas fa-external-link-alt ml-2"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-12 text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Ingin bergabung atau butuh info lebih lanjut?</h3>
            <p class="text-gray-600 mb-8">Silakan hubungi sekretariat gereja atau kunjungi kami pada jam ibadah.</p>
            <a href="/#contact" class="inline-block px-8 py-4 bg-gray-900 text-white font-bold rounded-2xl hover:bg-gray-800 transition-all transform hover:-translate-y-1">
                Hubungi Kami
            </a>
        </div>
    </div>
</div>
@endsection
