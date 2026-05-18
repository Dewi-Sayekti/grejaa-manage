@extends('layouts.landing')

@section('title', 'Struktur Organisasi - Gereja YHS')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Struktur Organisasi Gereja</h1>
    <p class="text-gray-700 leading-relaxed mb-8">
        Struktur organisasi kami dirancang untuk mendukung pelayanan yang transparan, efektif, dan penuh kasih.
        Setiap tim bekerja sama untuk melayani jemaat dan masyarakat dengan dedikasi dan integritas.
    </p>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="rounded-xl bg-white shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-3">Tim Pelayanan</h2>
            <p class="text-gray-700 mb-4">Bertanggung jawab atas perencanaan ibadah, musik, doa, dan pelayanan rohani setiap minggu.</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2">
                <li>Pelayanan Ibadah</li>
                <li>Then Sin Ministry</li>
                <li>Tim Doa</li>
                <li>Pengurus Jemaat</li>
            </ul>
        </div>

        <div class="rounded-xl bg-white shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-3">Tim Administrasi</h2>
            <p class="text-gray-700 mb-4">Mendukung semua kebutuhan operasional gereja, termasuk keuangan, komunikasi, dan manajemen acara.</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2">
                <li>Keuangan</li>
                <li>Pengurus Data</li>
                <li>Hubungan Jemaat</li>
                <li>IT & Media</li>
            </ul>
        </div>
    </div>

    <div class="mt-10 rounded-xl bg-indigo-50 p-6">
        <h2 class="text-2xl font-semibold text-gray-900 mb-3">Visi Struktur Kami</h2>
        <p class="text-gray-700">Kami percaya bahwa struktur yang jelas membantu setiap anggota untuk melayani dengan maksimal, berkolaborasi dalam kasih, dan membawa dampak positif bagi komunitas.</p>
    </div>
</div>
@endsection
