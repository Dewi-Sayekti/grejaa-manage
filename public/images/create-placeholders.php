<?php
// Jalankan sekali: http://localhost/Gereja/gereja-management-system/public/images/create-placeholders.php

$images = [
    'hero-1.jpg' => ['#fff1bf', '#ffe587', '🏛️ Gereja'],
    'hero-2.jpg' => ['#ffe587', '#ffdb1f', '📖 Ibadah'],
    'hero-3.jpg' => ['#d4af37', '#b8860b', '❤️ Komunitas'],
];

foreach ($images as $filename => $data) {
    $file = __DIR__ . '/' . $filename;
    if (!file_exists($file)) {
        // Create simple placeholder image
        $image = imagecreate(800, 400);
        $color1 = hextoRGB($data[0]);
        $color2 = hextoRGB($data[1]);

        $bg = imagecolorallocate($image, $color1['r'], $color1['g'], $color1['b']);
        $text_color = imagecolorallocate($image, 44, 62, 80);

        imagefilledrectangle($image, 0, 0, 800, 400, $bg);
        imagestring($image, 5, 300, 180, $data[2], $text_color);

        imagejpeg($image, $file, 90);
        imagedestroy($image);
        echo "✅ Created: $filename<br>";
    }
}

function hextoRGB($hex) {
    $hex = str_replace('#', '', $hex);
    return [
        'r' => hexdec(substr($hex, 0, 2)),
        'g' => hexdec(substr($hex, 2, 2)),
        'b' => hexdec(substr($hex, 4, 2))
    ];
}
?>
