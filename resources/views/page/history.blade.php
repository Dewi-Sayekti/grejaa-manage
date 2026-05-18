@extends('layouts.landing')

@section('content')
<div class="relative w-80 h-50 overflow-hidden">
    <picture>
      <source media="(max-width: 767px)"
              srcset="{{ asset('images/hero-2.jpg') }}">
      <source media="(min-width: 768px)"
              srcset="{{ asset('images/hero-2.jpg') }}">
      <img src="{{ asset('images/hero-2.jpg') }}"
            alt="Sejarah Gereja"
            class="w-50 h-50 object-cover">

<div class="max-w-5xl mx-auto py-12 px-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Sejarah Gereja</h1>
    <p class="text-gray-700 leading-relaxed mb-8">
        Gereja kami memulai perjalanan spiritual dengan visi yang jelas untuk melayani komunitas. Dari awal yang sederhana, kami telah berkembang menjadi komunitas gereja yang kuat dan berdampak.
    </p>

    <!-- Timeline Section -->
    <div class="mt-8">
        <h2 class="text-2xl font-semibold mb-8 text-gray-800">Perjalanan Gereja Kami</h2>

        <!-- Timeline Item 1 -->
        <div class="relative pl-8 pb-8 border-l-4 border-indigo-600">
            <div class="absolute -left-4 top-0 w-8 h-8 bg-indigo-600 rounded-full border-4 border-white"></div>
            <div class="bg-indigo-50 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">1989 - Awal Mula Pelayanan</h3>
                <img src="images/hero-3.jpg" alt="Awal pelayanan" 
                    class="w-80 h-auto rounded-lg mb-4 mx-auto">
                <p class="text-gray-700">
                    Gereja dimulai dengan sekelompok anak muda yang memiliki passion untuk berdedikasi dalam pelayanan. Dengan iman yang kuat dan semangat yang membara, mereka memulai ibadah di Peace Centre dengan jumlah jemaat yang masih sangat kecil.
                </p>
            </div>
        </div>


        <!-- Timeline Item 2 -->
        <div class="relative pl-8 pb-8 border-l-4 border-blue-600">
            <div class="absolute -left-4 top-0 w-8 h-8 bg-blue-600 rounded-full border-4 border-white"></div>
            <div class="bg-blue-50 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">1995 - Pertumbuhan Signifikan</h3>
                <p class="text-gray-700">
                    Melalui kerja keras, doa tanpa henti, dan penuntun Roh Kudus, jemaat terus bertumbuh. Pertumbuhan ini mencapai ribuan jiwa yang datang setiap minggu untuk beribadat dan memperdalam iman mereka bersama Tuhan.
                </p>
            </div>
        </div>

        <!-- Timeline Item 3 -->
        <div class="relative pl-8 pb-8 border-l-4 border-green-600">
            <div class="absolute -left-4 top-0 w-8 h-8 bg-green-600 rounded-full border-4 border-white"></div>
            <div class="bg-green-50 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">2000 - Pelayanan Sosial</h3>
                <p class="text-gray-700">
                    Gereja mulai aktif dalam program pelayanan sosial, memperluas misi tidak hanya dalam aspek spiritual tetapi juga dalam pemberdayaan masyarakat. Program-program seperti bantuan sosial, beasiswa pendidikan, dan klinik kesehatan dimulai pada periode ini.
                </p>
            </div>
        </div>

        <!-- Timeline Item 4 -->
        <div class="relative pl-8 pb-8 border-l-4 border-purple-600">
            <div class="absolute -left-4 top-0 w-8 h-8 bg-purple-600 rounded-full border-4 border-white"></div>
            <div class="bg-purple-50 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">2010 - Ekspansi & Modernisasi</h3>
                <p class="text-gray-700">
                    Dengan dukungan jemaat, gereja membangun fasilitas pelayanan yang lebih modern dan menyeluruh. Teknologi digunakan untuk menjangkau lebih banyak orang melalui live streaming dan platform digital lainnya.
                </p>
            </div>
        </div>

        <!-- Timeline Item 5 (Current) -->
        <div class="relative pl-8 pb-8 border-l-4 border-yellow-600">
            <div class="absolute -left-4 top-0 w-8 h-8 bg-yellow-600 rounded-full border-4 border-white"></div>
            <div class="bg-yellow-50 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">2026 - Masa Kini & Masa Depan</h3>
                <p class="text-gray-700">
                    Hingga kini, gereja terus berkembang dengan fokus pada transformasi digital, pembimbingan generasi muda, dan pelayanan yang lebih inklusif. Kami berkomitmen untuk menjadi gereja yang relevan, peduli, dan memberikan dampak positif bagi bangsa dan negara.
                </p>
            </div>
        </div>
    </div>

    <!-- Gallery Section -->
    @if($images && count($images) > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-semibold mb-8 text-gray-800">Galeri Kegiatan Kami</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($images as $image)
            <div class="relative overflow-hidden rounded-lg shadow-lg group cursor-pointer">
                <img src="{{ asset('storage/'.$image->path) }}"
                     alt="{{ $image->title }}"
                     class="w-full h-48 object-cover group-hover:scale-110 transition duration-300">
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                    <p class="text-white font-semibold">{{ $image->title }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Ayat Alkitab -->
        <div class="mt-12 text-center">
        <h2 class="text-2xl font-bold mb-4">Ayat Alkitab</h2>
        <blockquote class="text-lg italic mb-6">
            "Dan Aku pun berkata kepadamu: Engkau adalah Petrus dan di atas batu karang ini Aku akan mendirikan jemaat-Ku dan alam maut tidak akan menguasainya."
        </blockquote>
        <p class="mb-6">- Matius 16:18</p>
    </div>
</div>
@endsection
