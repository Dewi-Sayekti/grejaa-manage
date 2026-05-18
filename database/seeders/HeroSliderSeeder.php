<?php

namespace Database\Seeders;

use App\Models\HeroSlider;
use Illuminate\Database\Seeder;

class HeroSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HeroSlider::truncate();

        $sliders = [
            [
                'title' => '🏛️ Keluarga Gereja YHS',
                'image_path' => 'images/Keluarga YHS.jpg',
                'description' => 'Bergabunglah dengan komunitas yang penuh kasih sayang dan saling mendukung dalam perjalanan iman kami.',
                'link' => '#tentang',
                'button_text' => 'Pelajari Lebih Lanjut',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => '📖 Pelayanan Ibadah',
                'image_path' => 'images/Pelayanan.jpg',
                'description' => 'Nikmati ibadah yang bermakna dengan pengajaran firman yang mendalam setiap minggu.',
                'link' => '#ibadah',
                'button_text' => 'Lihat Jadwal',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => '❤️ Komunitas & Persahabatan',
                'image_path' => 'images/Pastor.jpg',
                'description' => 'Bangun persahabatan yang langgeng dan saling memperkuat dalam kelompok-kelompok kecil kami.',
                'link' => '#group',
                'button_text' => 'Bergabung Sekarang',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slider) {
            HeroSlider::create($slider);
        }
    }
}
