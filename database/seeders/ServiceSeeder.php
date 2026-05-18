<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::truncate();

        $services = [
            [
                'title' => 'Ibadah Rutin',
                'description' => 'Bergabunglah dengan kami setiap minggu untuk ibadah yang penuh makna dan pembelajaran firman Tuhan yang mendalam.',
                'icon' => 'fas fa-bible',
                'color' => 'gold',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Kelompok Kecil',
                'description' => 'Komunitas kecil untuk saling berbagi, mendukung, dan tumbuh bersama dalam iman dan persahabatan.',
                'icon' => 'fas fa-people-group',
                'color' => 'blue',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Sekolah Minggu',
                'description' => 'Program khusus untuk anak-anak kami dengan metode pembelajaran kreatif dan menyenangkan tentang kebenaran Tuhan.',
                'icon' => 'fas fa-child',
                'color' => 'green',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Musik & Nyanyian',
                'description' => 'Ekspresikan iman Anda melalui nyanyian yang indah dan musik yang menyentuh hati dalam peribadahan.',
                'icon' => 'fas fa-music',
                'color' => 'gold',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Konseling Rohani',
                'description' => 'Layanan konseling profesional dari pemimpin rohani berpengalaman untuk berbagai kebutuhan spiritual Anda.',
                'icon' => 'fas fa-heart',
                'color' => 'blue',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Pelayanan Sosial',
                'description' => 'Kami berkomitmen untuk membantu masyarakat melalui berbagai program sosial dan kemitraan komunitas.',
                'icon' => 'fas fa-hands-helping',
                'color' => 'green',
                'order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
