<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\HeroSlider;
use App\Models\Image;
use Illuminate\Support\Facades\Hash;

// Create Admin account
$admin = User::updateOrCreate(
    ['email' => 'admin@gereja.com'],
    [
        'name' => 'Admin Gereja',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'is_approved' => true,
        'approved_at' => now(),
    ]
);
echo "Admin created: admin@gereja.com / password123\n";

// Create Jemaat account
$jemaat = User::updateOrCreate(
    ['email' => 'jemaat@gereja.com'],
    [
        'name' => 'Jemaat Test',
        'password' => Hash::make('password123'),
        'role' => 'jemaat',
        'is_approved' => true,
        'approved_at' => now(),
    ]
);
echo "Jemaat created: jemaat@gereja.com / password123\n";

// Check Hero Sliders
echo "\n=== HERO SLIDERS ===\n";
echo "Total: " . HeroSlider::count() . "\n";
echo "Active: " . HeroSlider::where('is_active', true)->count() . "\n";
foreach (HeroSlider::all() as $s) {
    echo "  #{$s->id} '{$s->title}' path={$s->image_path} active={$s->is_active}\n";
}

// Check Images
echo "\n=== IMAGES ===\n";
echo "Total: " . Image::count() . "\n";
foreach (Image::all() as $img) {
    echo "  #{$img->id} '{$img->title}' path={$img->path}\n";
}

// Check Users
echo "\n=== ALL USERS ===\n";
foreach (User::all() as $u) {
    echo "  #{$u->id} {$u->name} ({$u->email}) role={$u->role} approved={$u->is_approved}\n";
}

echo "\nDone!\n";
