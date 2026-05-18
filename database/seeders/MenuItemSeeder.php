<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuItem::truncate();

        // Main Menu Items
        $menuItems = [
            [
                'name' => 'Beranda',
                'slug' => 'home',
                'icon' => 'fas fa-home',
                'url' => '/',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Tentang Kami',
                'slug' => 'about',
                'icon' => 'fas fa-info-circle',
                'url' => null,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Pelayanan',
                'slug' => 'services',
                'icon' => 'fas fa-hands-praying',
                'url' => null,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Acara',
                'slug' => 'events',
                'icon' => 'fas fa-calendar',
                'url' => null,
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Kontak',
                'slug' => 'contact',
                'icon' => 'fas fa-phone',
                'url' => '#kontak',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($menuItems as $item) {
            MenuItem::create($item);
        }

        // Sub menu items (children)
        $aboutMenu = MenuItem::where('slug', 'about')->first();
        if ($aboutMenu) {
            $subItems = [
                ['name' => 'Sejarah Gereja', 'slug' => 'history', 'url' => '#sejarah'],
                ['name' => 'Visi & Misi', 'slug' => 'vision', 'url' => '#visi-misi'],
                ['name' => 'Struktur Organisasi', 'slug' => 'structure', 'url' => '#struktur'],
                ['name' => 'Para Pemimpin', 'slug' => 'leaders', 'url' => '#pemimpin'],
            ];

            foreach ($subItems as $index => $item) {
                MenuItem::create([
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                    'icon' => 'fas fa-circle',
                    'url' => $item['url'],
                    'parent_id' => $aboutMenu->id,
                    'order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }

        $servicesMenu = MenuItem::where('slug', 'services')->first();
        if ($servicesMenu) {
            $subItems = [
                ['name' => 'Jadwal Ibadah', 'slug' => 'schedule', 'url' => '#ibadah'],
                ['name' => 'Kelompok Kecil', 'slug' => 'groups', 'url' => '#group'],
                ['name' => 'Sekolah Minggu', 'slug' => 'sunday_school', 'url' => '#anak'],
                ['name' => 'Musik & Nyanyian', 'slug' => 'music', 'url' => '#musik'],
            ];

            foreach ($subItems as $index => $item) {
                MenuItem::create([
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                    'icon' => 'fas fa-circle',
                    'url' => $item['url'],
                    'parent_id' => $servicesMenu->id,
                    'order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }

        $eventsMenu = MenuItem::where('slug', 'events')->first();
        if ($eventsMenu) {
            $subItems = [
                ['name' => 'Acara Mendatang', 'slug' => 'upcoming', 'url' => '#upcoming'],
                ['name' => 'Galeri', 'slug' => 'admin.galeri', 'url' => '#galeri'],
                ['name' => 'Berita & Pengumuman', 'slug' => 'news', 'url' => '#berita'],
            ];

            foreach ($subItems as $index => $item) {
                MenuItem::create([
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                    'icon' => 'fas fa-circle',
                    'url' => $item['url'],
                    'parent_id' => $eventsMenu->id,
                    'order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}
