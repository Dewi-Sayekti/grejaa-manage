# 📖 Analisis Projek: Gereja Management System (Gereja YHS)

## Ringkasan Umum

Ini adalah **Sistem Manajemen Gereja** berbasis web untuk **Gereja YHS**, dibangun menggunakan **Laravel 10** dengan **TailwindCSS** dan **Vite**. Aplikasi ini berfungsi sebagai:
1. **Website publik** (landing page) untuk pengunjung
2. **Sistem administrasi** untuk mengelola jemaat, keuangan, galeri, dan notifikasi

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 10 (PHP 8.1+) |
| Database | MySQL (`gereja_management_system`) |
| CSS | TailwindCSS + Custom CSS |
| Build Tool | Vite |
| Auth | Laravel Breeze (custom AuthController) |
| Font | Playfair Display, Figtree (Google Fonts) |
| Icons | Font Awesome 6.4 |
| Server | XAMPP (local) + ngrok (tunnel) |

---

## 📁 Struktur Proyek

```
gereja-management-system/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php          ← Login, Register, Logout
│   │   ├── ImageController.php         ← Landing page + CRUD galeri
│   │   ├── PageController.php          ← Halaman statis (sejarah, visi, dll)
│   │   ├── ProfileController.php       ← Edit profil user
│   │   ├── JemaatController.php        ← Manajemen data jemaat
│   │   ├── KeuanganController.php      ← Manajemen keuangan
│   │   ├── NotifikasiController.php    ← Manajemen notifikasi
│   │   ├── UserApprovalController.php  ← Approval user baru
│   │   └── DashboardController.php     ← Dashboard
│   ├── Models/
│   │   ├── User.php          ← User (role: admin/pendeta/jemaat)
│   │   ├── Jemaat.php        ← Data anggota jemaat (soft delete)
│   │   ├── Keuangan.php      ← Transaksi keuangan
│   │   ├── Notifikasi.php    ← Notifikasi untuk jemaat
│   │   ├── HeroSlider.php    ← Slider gambar di landing page
│   │   ├── Image.php         ← Galeri gambar
│   │   ├── News.php          ← Berita & pengumuman
│   │   ├── Schedule.php      ← Jadwal ibadah
│   │   ├── Service.php       ← Layanan gereja
│   │   └── MenuItem.php      ← Menu navigasi (hierarki parent-child)
│   └── View/Components/
│       └── AppLayout.php
├── database/migrations/       ← 15 migration files
├── resources/
│   ├── views/
│   │   ├── welcome.blade.php              ← Landing page (hero slider + CTA)
│   │   ├── dashboard.blade.php            ← Dashboard sederhana
│   │   ├── layouts/
│   │   │   ├── landing.blade.php          ← Layout publik (header + footer)
│   │   │   ├── app.blade.php              ← Layout admin
│   │   │   ├── navigation.blade.php       ← Navigasi Breeze
│   │   │   └── footer.blade.php           ← Footer
│   │   ├── page/                          ← Halaman statis publik
│   │   │   ├── history.blade.php
│   │   │   ├── vision.blade.php
│   │   │   ├── struktur.blade.php
│   │   │   ├── layanan.blade.php
│   │   │   ├── pengumuman.blade.php
│   │   │   └── pastors.blade.php
│   │   ├── auth/                          ← Login, Register, dll
│   │   ├── admin/                         ← Panel admin (sebagian di-comment)
│   │   │   ├── dashboard.blade.php
│   │   │   ├── galeri/
│   │   │   ├── keuangan/
│   │   │   └── notifikasi/
│   │   ├── image/                         ← Gallery & detail gambar
│   │   └── profile/                       ← Edit profil
│   └── css/
│       ├── app.css                        ← Entry point CSS
│       ├── landing-custom.css             ← Custom styles landing page
│       └── landing.css                    ← Additional landing styles
├── routes/
│   ├── web.php                            ← Route utama
│   └── auth.php                           ← Route auth (Breeze)
└── public/
    └── images/                            ← Gambar statis (Logo, dll)
```

---

## 🔐 Sistem Autentikasi & Roles

### Roles:
| Role | Akses |
|------|-------|
| `admin` | Full admin panel access |
| `pendeta` | Same as admin (`isAdmin()`) |
| `jemaat` | Limited dashboard access |

### Alur Registrasi:
1. User mengisi form registrasi (email, password, data diri lengkap)
2. Otomatis membuat **User** + **Jemaat** profile
3. `is_approved` langsung `true` (auto-approve)
4. Langsung login dan redirect ke dashboard

### Field Registrasi Jemaat:
- Nama lengkap, jenis kelamin, tempat/tanggal lahir
- Alamat, nomor HP, status pernikahan
- No. identitas (KTP), tanggal baptis (opsional)

---

## 🗺️ Route Map

### Public Routes (tanpa login):
| Route | Controller | View |
|-------|-----------|------|
| `/` | `ImageController@index` | `welcome` (landing page) |
| `/history` | `PageController@history` | `page.history` |
| `/vision` | `PageController@vision` | `page.vision` |
| `/struktur` | `PageController@struktur` | `page.struktur` |
| `/layanan` | `PageController@layanan` | `page.layanan` |
| `/pengumuman` | `PageController@pengumuman` | `page.pengumuman` |
| `/pastors` | `PageController@pastors` | `page.pastors` |
| `/gallery` | `ImageController@gallery` | `image.gallery` |
| `/image/{id}` | `ImageController@show` | `image.detail` |

### Protected Routes (perlu login):
| Route | Fungsi |
|-------|--------|
| `/dashboard` | Dashboard (render `welcome` view with heroSliders) |
| `/admin/galeri/*` | CRUD galeri (create, store, edit, update, delete) |
| `/profile/edit` | Edit profil user |
| `POST /logout` | Logout |

---

## 📊 Database Schema (10 Tabel Utama)

### `users`
- name, email, password, role, is_approved, approved_at, rejection_reason

### `jemaats` (soft delete)
- user_id (FK), nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir
- alamat, nomor_hp, status_pernikahan, tanggal_baptis, no_identitas, foto, status_aktif

### `keuangan`
- jemaat_id (FK), tipe (pemasukan/pengeluaran), kategori, jumlah
- tanggal_transaksi, keterangan, bukti_file

### `notifikasi`
- jemaat_id (FK), judul, isi, tipe, tanggal_kirim, sudah_dibaca

### `hero_sliders`
- title, image_path, description, link, button_text, order, is_active

### `images`
- title, path

### `news`
- title, excerpt, content, image_path, gradient_from/to, published_at, is_published, order

### `schedules`
- title, emoji, description, day, start_time, end_time, order, is_active

### `services`
- title, description, icon, color, order, is_active

### `menu_items` (hierarki parent-child)
- name, slug, icon, url, parent_id, order, is_active, target

---

## 🎨 Design System

### Color Palette (Custom Tailwind):
- **Cream**: `#fffef9` → `#ffe587` (background tones)
- **Gold**: `#fffbf0`, `#d4af37`, `#b8860b`, `#8b6914` (accent/brand)
- **Text**: Dark `#2c3e50`, Light `#555555`

### Typography:
- **Sans**: Figtree, Inter (body text)
- **Serif**: Playfair Display (headings)

### Layout:
- Landing page: Custom `landing.blade.php` layout
- Admin/Dashboard: `app.blade.php` layout (Breeze)

---

## ⚠️ Status & Catatan Penting

### 1. Banyak Section di-Comment Out
Sebagian besar konten di `welcome.blade.php` di-comment (`{{-- --}}`):
- ❌ Features/Layanan section
- ❌ Schedule/Jadwal Ibadah section
- ❌ Gallery section
- ❌ News/Berita section
- ✅ Hero Slider (aktif)
- ✅ CTA Section (aktif)

### 2. Admin Dashboard di-Comment
File `admin/dashboard.blade.php` seluruhnya di-comment. Dashboard menampilkan statistik (total jemaat, user, pemasukan, pengeluaran, saldo) dan tabel notifikasi + transaksi terbaru.

### 3. Dashboard Route Bermasalah
Route `/dashboard` me-render view `welcome` (landing page) bukan view dashboard admin. Ini berarti user yang login dan akses dashboard akan melihat landing page lagi.

### 4. Controller Kosong
- `LayananController.php` — hanya 8 bytes (kosong)

### 5. ImageController Tidak Lengkap
- Method `edit()`, `update()`, `destroy()` belum ada, tapi sudah didaftarkan di routes

### 6. Ngrok URL di .env
`APP_URL` mengarah ke ngrok tunnel: `https://surfer-cage-occupier.ngrok-free.dev`

---

## 📊 Diagram Relasi Model

```
User (1) ──── (1) Jemaat
                    │
                    ├── (N) Keuangan
                    └── (N) Notifikasi

HeroSlider (standalone)
Image (standalone)
News (standalone)
Schedule (standalone)
Service (standalone)
MenuItem (self-referencing: parent_id)
```

---

## 🚀 Cara Menjalankan

```bash
# Server sudah berjalan via:
php artisan serve

# Untuk frontend build (jika perlu):
npm run dev
```

Server lokal: `http://127.0.0.1:8000`
