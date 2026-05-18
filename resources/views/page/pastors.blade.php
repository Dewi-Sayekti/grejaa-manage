@extends('layouts.landing')

@section('content')
<div class="max-w-6xl mx-auto py-16 px-6">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Tim Pastoral Kami</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Mengenal lebih dekat para pemimpin rohani dan pendeta yang melayani dengan sepenuh hati di Gereja YHS.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @forelse($pastors as $pastor)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 border border-gray-100 flex flex-col items-center text-center p-8 group">
                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-yellow-100 shadow-md group-hover:scale-105 transition duration-300">
                    @if($pastor->image_path)
                        <img src="{{ asset($pastor->image_path) }}" alt="{{ $pastor->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400 text-5xl">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $pastor->name }}</h3>
                <div class="bg-yellow-100 text-yellow-800 px-4 py-1.5 rounded-full text-sm font-semibold mb-4 inline-block">
                    {{ $pastor->role }}
                </div>
                
                <p class="text-gray-600 leading-relaxed">
                    {{ $pastor->description ?: 'Melayani dengan sepenuh hati untuk jemaat dan kemuliaan Tuhan.' }}
                </p>
            </div>
        @empty
            <div class="col-span-full text-center py-16 text-gray-500 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                <i class="fas fa-user-slash text-5xl mb-4 text-gray-300"></i>
                <p class="text-lg font-medium">Belum ada data pemimpin yang ditambahkan.</p>
            </div>
        @endforelse
    </div>

    <!-- Ajakan -->
    <div class="mt-16 bg-gradient-to-r from-blue-700 to-indigo-800 rounded-3xl p-10 text-white text-center shadow-xl relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-5"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 rounded-full bg-white opacity-10"></div>
        
        <div class="relative z-10">
            <h2 class="text-3xl font-extrabold mb-4">Bergabunglah Dengan Kami</h2>
            <p class="mb-8 text-blue-100 max-w-2xl mx-auto text-lg">Kami senantiasa terbuka untuk melayani Anda dan keluarga. Temukan kehangatan keluarga dalam Kristus bersama kami.</p>
            <a href="/#kontak" class="inline-block bg-white text-indigo-700 font-bold px-10 py-4 rounded-full hover:bg-yellow-400 hover:text-yellow-900 transition duration-300 shadow-lg transform hover:-translate-y-1">
                Hubungi Kami Sekarang
            </a>
        </div>
    </div>
</div>
@endsection
