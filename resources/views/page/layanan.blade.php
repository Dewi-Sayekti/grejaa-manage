@extends('layouts.landing')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-6">
    <div class="text-center mb-16">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4" style="font-family:'Playfair Display', serif;">Pelayanan Kami</h1>
        <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Gereja YHS berkomitmen untuk melayani setiap jemaat dan masyarakat melalui berbagai program pelayanan yang memberkati dan membangun rohani.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse($services as $service)
            <div class="group bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100 flex flex-col">
                <div class="p-8 flex-grow">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-yellow-600 mb-6 group-hover:bg-yellow-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-hand-holding-heart text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ $service->title }}</h3>
                    <p class="text-gray-600 leading-relaxed line-clamp-3 mb-6">
                        {{ $service->description }}
                    </p>
                </div>
                <div class="px-8 pb-8">
                    <a href="{{ route('service.detail', $service->id) }}" 
                       class="inline-flex items-center text-yellow-600 font-bold hover:text-yellow-700 transition-colors">
                        Lihat Selengkapnya <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <i class="fas fa-info-circle text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Belum ada data pelayanan yang ditampilkan.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
