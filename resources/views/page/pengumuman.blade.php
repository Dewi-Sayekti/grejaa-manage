@extends('layouts.landing')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-6">
    {{-- Section: Acara Mendatang --}}
    <div class="mb-20">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900" style="font-family:'Playfair Display', serif;">📅 Acara Mendatang</h2>
                <p class="text-gray-500 mt-2">Jangan lewatkan momen kebersamaan dan pertumbuhan iman kita.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($events as $event)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden flex flex-col md:flex-row hover:shadow-xl transition-shadow">
                    @if($event->image_path)
                        <div class="md:w-1/3 h-48 md:h-auto">
                            <img src="{{ Storage::url($event->image_path) }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="p-6 md:w-2/3 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-4 mb-3">
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase">Event</span>
                                <span class="text-gray-400 text-xs"><i class="fas fa-calendar-alt mr-1"></i> {{ $event->tanggal_acara ? $event->tanggal_acara->format('d M Y') : '-' }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $event->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $event->excerpt }}</p>
                        </div>
                        <a href="{{ route('news.detail', $event->id) }}" class="text-blue-600 font-bold text-sm hover:text-blue-700">Detail Acara <i class="fas fa-chevron-right ml-1"></i></a>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 text-gray-400">
                    Belum ada acara mendatang.
                </div>
            @endforelse
        </div>
    </div>

    <hr class="border-gray-100 mb-20">

    {{-- Section: Berita & Pemberitahuan --}}
    <div>
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900" style="font-family:'Playfair Display', serif;">📰 Berita & Pemberitahuan</h2>
            <p class="text-gray-500 mt-2">Kumpulan informasi terbaru seputar aktivitas gereja kita.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($news as $item)
                <div class="group">
                    @if($item->image_path)
                        <div class="rounded-2xl overflow-hidden mb-4 aspect-video shadow-sm">
                            <img src="{{ Storage::url($item->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                    @endif
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-gray-400 text-xs"><i class="fas fa-clock mr-1"></i> {{ $item->created_at->format('d M Y') }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors">{{ $item->title }}</h3>
                    <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $item->excerpt }}</p>
                    <a href="{{ route('news.detail', $item->id) }}" class="text-gray-900 font-bold text-xs border-b-2 border-yellow-500 pb-1 hover:border-gray-900 transition-colors">BACA SELENGKAPNYA</a>
                </div>
            @empty
                <div class="col-span-3 text-center py-10 text-gray-400">
                    Belum ada berita terbaru.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
