@extends('layouts.landing')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Misi & Keyakinan Kami</h1>

    <div class="space-y-8">
        <!-- Misi Section -->
        <div class="bg-indigo-50 rounded-lg p-8">
            <h2 class="text-2xl font-semibold text-indigo-900 mb-4">Misi</h2>
            <p class="text-gray-700 leading-relaxed">
                Kami berkomitmen untuk melayani jemaat dengan sepenuh hati, menyebarkan kasih Kristus, dan memberdayakan komunitas melalui pelayanan sosial yang nyata dan berkelanjutan.
            </p>
        </div>

        <!-- Visi Section -->
        <div class="bg-blue-50 rounded-lg p-8">
            <h2 class="text-2xl font-semibold text-blue-900 mb-4">Visi</h2>
            <p class="text-gray-700 leading-relaxed">
                Menjadi gereja yang hidup, berkembang, dan menjadi berkat bagi seluruh masyarakat dalam menghadirkan perubahan positif melalui iman, harapan, dan kasih yang sejati.
            </p>
        </div>

        <!-- Keyakinan Section -->
        <div class="bg-green-50 rounded-lg p-8">
            <h2 class="text-2xl font-semibold text-green-900 mb-4">Nilai-Nilai Inti Kami</h2>
            <ul class="space-y-3 text-gray-700">
                <li class="flex items-start">
                    <span class="text-green-600 mr-3">✓</span>
                    <span>Integritas dan Kesetiaan terhadap Injil Kristus</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-600 mr-3">✓</span>
                    <span>Pelayanan dengan kerendahan hati dan cinta kasih</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-600 mr-3">✓</span>
                    <span>Transparansi dan akuntabilitas dalam setiap keputusan</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-600 mr-3">✓</span>
                    <span>Pemberdayaan anggota jemaat untuk pertumbuhan rohani</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-600 mr-3">✓</span>
                    <span>Komitmen pada keadilan sosial dan pembangunan berkelanjutan</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
