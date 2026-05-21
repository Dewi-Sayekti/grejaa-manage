@extends('layouts.landing')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-6">
    {{-- Section: Pengumuman Jadwal Ibadah Terbaru --}}
    @if($scheduleAnnouncements->isNotEmpty())
    <div class="mb-20">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900" style="font-family:'Playfair Display', serif;">⏰ Pembaruan Jadwal Ibadah</h2>
                <p class="text-gray-500 mt-2">Pengumuman terbaru mengenai jadwal ibadah dari administrator.</p>
            </div>
        </div>

        <div class="space-y-4">
            @foreach($scheduleAnnouncements as $announcement)
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="inline-block bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
                                    <i class="fas fa-bell mr-1"></i> JADWAL
                                </span>
                                <span class="text-gray-500 text-xs">
                                    <i class="fas fa-clock mr-1"></i> {{ $announcement->tanggal_kirim->format('d M Y H:i') }}
                                </span>
                                @if($announcement->tipe === 'penting')
                                    <span class="inline-block bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded">⚠️ PENTING</span>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $announcement->judul }}</h3>
                            <p class="text-gray-700 text-sm leading-relaxed">{{ $announcement->isi }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.schedules.index') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-calendar-check"></i> Lihat di Admin
                                    </a>
                                @else
                                    <a href="{{ route('jadwal-ibadah') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-calendar-check"></i> Lihat Jadwal
                                    </a>
                                @endif
                            @endauth
                            @guest
                                <a href="{{ route('jadwal-ibadah') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-calendar-check"></i> Lihat Jadwal
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <hr class="border-gray-100 mb-20">
    @endif

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
