@extends('layouts.landing')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-6">
    {{-- Header Section --}}
    <div class="mb-12">
        <div class="flex items-center gap-4 mb-4">
            <h1 class="text-4xl font-extrabold text-gray-900" style="font-family:'Playfair Display', serif;">⏰ Jadwal Ibadah</h1>
            <span class="bg-blue-100 text-blue-700 text-sm font-bold px-4 py-1 rounded-full">Pembaruan Terbaru</span>
        </div>
        <p class="text-gray-600 text-lg">Berikut adalah jadwal ibadah reguler di Gereja kami. Pastikan Anda tidak ketinggalan moment penting bersama jemaat.</p>
    </div>

    {{-- Schedules List --}}
    <div class="space-y-6">
        @forelse($schedules as $schedule)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow">
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        {{-- Schedule Icon & Title --}}
                        <div class="md:col-span-2">
                            <div class="flex items-start gap-4">
                                <div class="text-4xl">{{ $schedule->emoji ?? '📅' }}</div>
                                <div class="flex-1">
                                    <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $schedule->title }}</h3>
                                    @if($schedule->description)
                                        <p class="text-gray-600 text-sm leading-relaxed">{{ $schedule->description }}</p>
                                    @endif
                                    @if($schedule->location)
                                        <div class="flex items-center gap-2 text-gray-500 text-sm mt-3">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $schedule->location }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Schedule Details --}}
                        <div class="space-y-3">
                            @if($schedule->day)
                                <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                                    <p class="text-xs text-blue-600 uppercase font-semibold mb-1"><i class="fas fa-calendar-alt mr-1"></i> Hari</p>
                                    <p class="text-sm font-bold text-gray-800">{{ ucfirst($schedule->day) }}</p>
                                </div>
                            @endif

                            @if($schedule->start_time)
                                <div class="bg-purple-50 rounded-lg p-3 border border-purple-100">
                                    <p class="text-xs text-purple-600 uppercase font-semibold mb-1"><i class="fas fa-clock mr-1"></i> Jam</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $schedule->waktu_lengkap }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Status Badge --}}
                        <div class="flex flex-col items-end justify-between">
                            @if($schedule->is_recurring)
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-2 rounded-full inline-block mb-2">
                                    <i class="fas fa-repeat mr-1"></i> Rutin
                                </span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-2 rounded-full inline-block mb-2">
                                    <i class="fas fa-calendar-check mr-1"></i> Satu Kali
                                </span>
                            @endif

                            @if($schedule->tanggal)
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 mb-1">Tanggal:</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $schedule->tanggal->format('d M Y') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <div class="text-5xl mb-4">📭</div>
                <p class="text-gray-500 text-lg">Jadwal ibadah belum tersedia saat ini.</p>
                <p class="text-gray-400 text-sm mt-2">Silakan kembali lagi untuk pembaruan terbaru.</p>
            </div>
        @endforelse
    </div>

    {{-- Info Box --}}
    <div class="mt-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200 p-8">
        <div class="flex items-start gap-4">
            <div class="text-2xl">ℹ️</div>
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Informasi Penting</h3>
                <ul class="text-gray-700 text-sm space-y-2">
                    <li><i class="fas fa-check-circle text-green-600 mr-2"></i> Jadwal di atas adalah jadwal reguler ibadah kami.</li>
                    <li><i class="fas fa-check-circle text-green-600 mr-2"></i> Perubahan jadwal akan diumumkan melalui halaman <a href="{{ route('pengumuman') }}" class="text-blue-600 font-bold hover:underline">Pengumuman</a>.</li>
                    <li><i class="fas fa-check-circle text-green-600 mr-2"></i> Jika Anda adalah anggota jemaat, silakan login untuk dapat melakukan absensi.</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Back Button --}}
    <div class="mt-8 flex gap-4">
        <a href="{{ route('pengumuman') }}" class="inline-flex items-center gap-2 bg-gray-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-gray-700 transition-colors">
            <i class="fas fa-chevron-left"></i> Kembali ke Pengumuman
        </a>
    </div>
</div>
@endsection
