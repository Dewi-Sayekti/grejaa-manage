# Hero Slider Setup Guide

## Apa yang Sudah Saya Buat

Saya telah membuat sistem **Hero Slider Interaktif** untuk halaman utama dengan fitur:
- ✅ Multiple slides dengan gambar dan konten berbeda
- ✅ Navigation arrows (prev/next)
- ✅ Dots indicator untuk navigasi
- ✅ Autoplay dengan pause on hover
- ✅ Keyboard navigation (arrow keys)
- ✅ Database table untuk manageable content

## File-File yang Dibuat/Diubah

### 1. Database Migration
- **File**: `database/migrations/2026_04_05_000000_create_hero_sliders_table.php`
- **Struktur**: id, title, image_path, description, link, button_text, order, is_active, timestamps

### 2. Model
- **File**: `app/Models/HeroSlider.php`
- **Method**: `getActive()` - untuk mendapatkan slider yang aktif

### 3. Controller
- **File**: `app/Http/Controllers/ImageController.php`
- **Update**: Menambah data `heroSliders` ke view

### 4. View
- **File**: `resources/views/welcome.blade.php`
- **Update**: Hero section sekarang menampilkan slider interaktif

### 5. Seeder  
- **File**: `database/seeders/HeroSliderSeeder.php`
- **Fungsi**: Contoh data untuk testing

### 6. Folder
- **Folder**: `public/images/` (baru dibuat)
- **Fungsi**: Tempat menyimpan gambar hero slider

## Langkah-Langkah Setup

### Step 1: Run Migration
```bash
php artisan migrate
```

### Step 2: Run Seeder (Optional)
```bash
php artisan db:seed --class=HeroSliderSeeder
```

### Step 3: Pindahkan Gambar
Letakkan file gambar Anda di:
```
public/images/
  ├── Keluarga YHS.jpg
  ├── gambar-lain-1.jpg
  └── gambar-lain-2.jpg
```

### Step 4: Tambahkan Data via Database
Periksa data di table `hero_sliders`:
```
id | title | image_path | description | link | button_text | order | is_active
```

## Cara Menambahkan Slider Baru

### Via Database (Manual)
```sql
INSERT INTO hero_sliders (title, image_path, description, link, button_text, order, is_active, created_at, updated_at)
VALUES (
  'Judul Slider',
  'images/nama-gambar.jpg',
  'Deskripsi konten slider',
  'https://link-tujuan.com',
  'Teks Tombol',
  4,
  1,
  NOW(),
  NOW()
);
```

### Via Artisan Tinker
```bash
php artisan tinker

APP\Models\HeroSlider::create([
  'title' => 'Judul Baru',
  'image_path' => 'images/gambar.jpg',
  'description' => 'Deskripsi',
  'link' => '#section',
  'button_text' => 'Klik Di Sini',
  'order' => 2,
  'is_active' => true
]);
```

## Features Slider

✅ **Autoplay**: Otomatis berganti slide setiap 5 detik  
✅ **Pause on Hover**: Berhenti saat mouse over  
✅ **Navigation Buttons**: Tombol prev/next  
✅ **Dot Indicators**: Titik aktif untuk slide  
✅ **Keyboard Support**: Gunakan arrow keys untuk navigasi  
✅ **Smooth Transition**: Fade transition 700ms  
✅ **Clickable**: Setiap slide bisa diklik sesuai link yang ditetapkan  

## Customize Durasi Autoplay

Edit di `welcome.blade.php` line dengan `setInterval(nextSlide, 5000)`:
- 5000 = 5 detik
- 3000 = 3 detik
- 10000 = 10 detik

## Selesai! 🎉

Sekarang halaman utama Anda memiliki hero slider interaktif yang siap untuk konten yang berbeda-beda.
