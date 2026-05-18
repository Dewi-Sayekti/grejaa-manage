<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\HeroSlider;

// Update hero sliders to use actual images from public/images/
$updates = [
    1 => ['image_path' => 'images/hero-1.jpg', 'title' => 'Selamat Datang di Gereja YHS', 'description' => 'Tempat berbagi kasih, iman, dan harapan bersama'],
    2 => ['image_path' => 'images/hero-2.jpg', 'title' => 'Bersama Dalam Iman', 'description' => 'Bergabunglah bersama kami dalam perjalanan iman yang penuh makna'],
    3 => ['image_path' => 'images/hero-3.jpg', 'title' => 'Komunitas Penuh Kasih', 'description' => 'Menjadi keluarga besar yang saling mendukung dan mengasihi'],
];

foreach ($updates as $id => $data) {
    $slider = HeroSlider::find($id);
    if ($slider) {
        $slider->update($data);
        echo "Updated slider #{$id}: {$data['image_path']}\n";
    }
}

echo "\n=== VERIFY ===\n";
foreach (HeroSlider::all() as $s) {
    $path = public_path($s->image_path);
    $exists = file_exists($path) ? 'EXISTS' : 'MISSING';
    echo "  #{$s->id} '{$s->title}' => {$s->image_path} [{$exists}]\n";
}

echo "\nDone!\n";
