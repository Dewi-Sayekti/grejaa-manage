<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::truncate();

        $news = [
            [
                'title' => 'Perayaan Paskah 2026',
                'excerpt' => 'Bergabunglah dengan kami dalam perayaan Paskah yang penuh makna dan berkah. Acara spesial akan diadakan di seluruh minggu ini.',
                'content' => 'Kami dengan senang hati mengundang Anda untuk merayakan Paskah bersama gereja kami. Acara-acara spesial telah dipersiapkan untuk membuat perayaan ini bermakna dan berkesan...',
                'image_path' => 'images/hero-3.jpg',
                'gradient_from' => '#fff1bf',
                'gradient_to' => '#ffe587',
                'published_at' => now()->format('Y-m-d'),
                'is_published' => true,
                'order' => 1,
            ],
            [
                'title' => 'Kelas Pembinaan Iman',
                'excerpt' => 'Program pembinaan iman baru dimulai bulan depan dengan materi yang mendalam dan interaktif untuk pertumbuhan rohani.',
                'content' => 'Kami membuka kelas pembinaan iman baru yang dirancang untuk membantu setiap anggota gereja tumbuh dalam pemahaman akan Alkitab dan hubungan personal mereka dengan Tuhan...',
                'image_path' => 'images/news-2.jpg',
                'gradient_from' => '#d4af37',
                'gradient_to' => '#b8860b',
                'published_at' => now()->subDay()->format('Y-m-d'),
                'is_published' => true,
                'order' => 2,
            ],
            [
                'title' => 'Pelayanan Sosial Komunitas',
                'excerpt' => 'Kami mengadakan kegiatan sosial untuk membantu keluarga yang membutuhkan. Undangan terbuka untuk semua jemaat yang ingin berkontribusi.',
                'content' => 'Dalam semangat melayani, gereja kami akan mengadakan acara penggalangan dana dan pemberian bantuan kepada keluarga-keluarga yang mengalami kesulitan...',
                'image_path' => 'images/news-3.jpg',
                'gradient_from' => '#3b82f6',
                'gradient_to' => '#2563eb',
                'published_at' => now()->subDays(2)->format('Y-m-d'),
                'is_published' => true,
                'order' => 3,
            ],
        ];

        foreach ($news as $item) {
            News::create($item);
        }
    }
}
