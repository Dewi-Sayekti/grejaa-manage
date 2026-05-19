# 📖 Analisis Projek: Gereja Management System (Gereja YHS)

## Ringkasan Umum

Ini adalah **Sistem Manajemen Gereja** berbasis web untuk **Gereja YHS**, dibangun menggunakan **Laravel 10** dengan **TailwindCSS** dan **Vite**. Aplikasi ini sekarang mencakup:
1. **Website publik** (landing page, halaman statis, galeri, berita, layanan)
2. **Sistem administrasi** untuk mengelola jemaat, keuangan, notifikasi, absensi, event, dan registrasi acara
3. **Fitur donasi/persembahan online** dengan integrasi Midtrans

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 10 (PHP 8.1+) |
| Database | MySQL (`gereja_management_system`) |
| CSS | TailwindCSS + Custom CSS |
| Build Tool | Vite |
| Auth | Laravel Breeze + custom `AuthController` |
| Font | Playfair Display, Figtree (Google Fonts) |
| Icons | Font Awesome 6.4 |
| Server | XAMPP (local) + ngrok (tunnel) |

---

## 📁 Struktur Proyek

```
gereja-management-system/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php          ← Login, Register, Logout custom
│   │   ├── ImageController.php         ← Landing page + CRUD galeri
│   │   ├── PageController.php          ← Halaman statis, layanan, berita, pastors
│   │   ├── ProfileController.php       ← Edit profil user
│   │   ├── DashboardController.php     ← Dashboard utama
│   │   ├── DashboardFeatureController.php ← API fitur dashboard jemaat
│   │   ├── AbsensiController.php       ← Absensi jemaat + approval admin
│   │   ├── PersembahanController.php   ← Persembahan online + Midtrans webhook
│   │   ├── RegistrasiAcaraController.php ← Event registrasi user/admin
│   │   ├── UserApprovalController.php  ← Approval user baru
│   │   ├── JemaatController.php        ← Manajemen data jemaat (admin)
│   │   ├── KeuanganController.php      ← Manajemen keuangan admin
│   │   ├── NotifikasiController.php    ← Manajemen notifikasi admin
│   │   ├── LayananController.php       ← Kosong / malformatted saat ini
│   │   └── HomeController.php
│   ├── Http/Controllers/Admin/
│   │   ├── PastorController.php
│   │   ├── ServiceContentController.php
│   │   ├── NewsScheduleController.php
│   │   ├── ScheduleController.php
│   │   ├── JemaatController.php
│   │   ├── NotifikasiController.php
│   │   ├── KeuanganController.php
│   │   └── AdminDashboardController.php
│   ├── Models/
│   │   ├── User.php          ← User (role: admin/pendeta/jemaat)
│   │   ├── Jemaat.php        ← Data anggota jemaat (soft delete)
│   │   ├── Keuangan.php      ← Transaksi keuangan
│   │   ├── Notifikasi.php    ← Notifikasi untuk jemaat
│   │   ├── Absensi.php       ← Absensi jemaat
│   │   ├── HeroSlider.php    ← Slider gambar landing page
│   │   ├── Image.php         ← Galeri gambar
│   │   ├── News.php          ← Berita & pengumuman
│   │   ├── Pastor.php        ← Data pastor
│   │   ├── Persembahan.php   ← Rekaman persembahan online
│   │   ├── RegistrasiAcara.php ← Registrasi acara
│   │   ├── Schedule.php      ← Jadwal ibadah
│   │   ├── Service.php       ← Layanan gereja
│   │   ├── MenuItem.php      ← Menu navigasi (hierarki parent-child)
│   │   └── ...
│   └── View/Components/
│       └── AppLayout.php
├── database/migrations/       ← Migration untuk tabel utama dan fitur baru
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
│   │   ├── auth/                          ← Login, Register, password reset
│   │   ├── admin/                         ← Panel admin (sebagian di-comment)
│   │   │   ├── dashboard.blade.php
│   │   │   ├── galeri/
│   │   │   ├── keuangan/
│   │   │   └── notifikasi/
│   │   ├── image/                         ← Gallery & detail gambar
│   │   ├── profile/                       ← Edit profil
│   │   └── dashboard/                     ← Dashboard feature views
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
| `pendeta` | Sama dengan admin (`isAdmin()`) |
| `jemaat` | Akses dashboard terbatas |

### Alur Registrasi:
1. User mengisi form registrasi (email, password, data diri lengkap)
2. Terbuat **User** + **Jemaat** profile
3. `is_approved` kemungkinan auto-approve pada controller custom
4. Langsung login dan redirect ke dashboard

### Catatan Auth Saat Ini:
- `routes/web.php` menggunakan `AuthController` custom untuk login/register/logout
- `routes/auth.php` tetap menyimpan route default Breeze untuk email verification, password reset, dan logout
- Kedua sistem ini berjalan berdampingan, yang dapat menyebabkan duplikasi atau konflik dalam otentikasi

### Field Registrasi Jemaat:
- Nama lengkap, jenis kelamin, tempat/tanggal lahir
- Alamat, nomor HP, status pernikahan
- No. identitas (KTP), tanggal baptis (opsional)

---

## 🗺️ Route Map

### Public Routes (tanpa login):
| Route | Controller | Fungsi |
|-------|------------|--------|
| `/` | `ImageController@index` | Landing page utama |
| `/history` | `PageController@history` | Halaman sejarah |
| `/vision` | `PageController@vision` | Halaman visi |
| `/struktur` | `PageController@struktur` | Halaman struktur organisasi |
| `/layanan` | `PageController@layanan` | Daftar layanan |
| `/layanan/{id}` | `PageController@serviceDetail` | Detail layanan |
| `/pengumuman` | `PageController@pengumuman` | List berita/pengumuman |
| `/pengumuman/{id}` | `PageController@newsDetail` | Detail berita |
| `/pastors` | `PageController@pastors` | Daftar pastor |
| `/gallery` | `ImageController@gallery` | Galeri publik |
| `/image/{id}` | `ImageController@show` | Detail gambar |
| `/image/{id}/download` | `ImageController@download` | Unduh gambar |
| `/acara/{news}` | `RegistrasiAcaraController@show` | Detail acara publik |
| `/persembahan` | `PersembahanController@index` | Form persembahan online |
| `/persembahan/finish` | `PersembahanController@finish` | Halaman selesai persembahan |
| `/webhook/midtrans` | `PersembahanController@webhook` | Midtrans callback |

### Protected Routes (perlu login):
| Route | Controller | Fungsi |
|-------|------------|--------|
| `/dashboard` | `DashboardController@index` | Dashboard user/admin |
| `/admin/galeri/*` | `ImageController` | CRUD galeri |
| `/profile/edit` | `ProfileController@edit` | Edit profil user |
| `/logout` | `AuthController@logout` | Logout |
| `/absensi` | `AbsensiController@index/store/destroy` | Absensi jemaat |
| `/dashboard/features/*` | `DashboardFeatureController` | API fitur dashboard |

### Admin Routes (admin middleware):
| Route | Controller | Fungsi |
|-------|------------|--------|
| `/admin/jemaat` | `Admin
eource Jemaat` | Manage data jemaat |
| `/admin/pastors` | `Admin
eource Pastor` | CRUD pastor |
| `/admin/services` | `Admin
eource ServiceContent` | CRUD layanan |
| `/admin/news` | `Admin
eource NewsSchedule` | CRUD berita dan event |
| `/admin/schedules` | `Admin
eource Schedule` | CRUD jadwal ibadah |
| `/admin/schedules/{schedule}/toggle` | `ScheduleController@toggleActive` | Aktif/nonaktif jadwal |
| `/admin/notifikasi` | `Admin
eource Notifikasi` | CRUD notifikasi |
| `/admin/notifikasi/send-to-all` | `AdminNotifikasiController@sendToAll` | Kirim notifikasi ke semua |
| `/admin/keuangan` | `Admin
eource Keuangan` | CRUD transaksi |
| `/admin/keuangan-report` | `AdminKeuanganController@report` | Laporan keuangan |
| `/admin/user-approvals` | `UserApprovalController` | Approve/reject registrasi user |
| `/admin/absensi` | `AbsensiController@adminIndex` | Dashboard absensi admin |
| `/admin/absensi/{absensi}/approve` | `AbsensiController@approve` | Approve absensi |
| `/admin/absensi/{absensi}/reject` | `AbsensiController@reject` | Reject absensi |
| `/admin/absensi/bulk-approve` | `AbsensiController@bulkApprove` | Bulk approve absensi |
| `/admin/acara/{news}/registrasi` | `RegistrasiAcaraController@adminIndex` | Daftar registrasi acara |
| `/admin/acara/registrasi/{registrasi}/confirm` | `RegistrasiAcaraController@confirm` | Konfirmasi registrasi acara |
| `/admin/acara/registrasi/{registrasi}/reject` | `RegistrasiAcaraController@reject` | Reject registrasi acara |

---

## 📊 Database Schema (13+ Tabel Utama)

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

### `absensis`
- jemaat_id (FK), tanggal, status, keterangan, approved_by, status_approval

### `persembahans`
- user_id (FK), jumlah, metode, status, transaction_id, bukti, created_at

### `registrasi_acaras`
- user_id (FK), news_id (FK), status, jumlah_peserta, nama, kontak

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

### `menu_items`
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

### 1. Fitur dan route baru yang aktif
- Persembahan online sudah ada dengan dukungan Midtrans callback
- Event/acara sekarang mendukung pendaftaran pengguna dan manajemen admin
- Absensi jemaat mendukung approval/reject serta bulk approval
- Jadwal ibadah disediakan sebagai CRUD admin dengan toggle active
- Notifikasi dapat dikirim ke semua pengguna dari admin

### 2. Auth routes ganda
- `routes/web.php` dan `routes/auth.php` sekarang keduanya terdaftar
- Ini membawa risiko duplikasi route, nama route sama, dan inkonsistensi

### 3. Bagian yang perlu perbaikan
- `app/Http/Controllers/LayananController.php` kosong/malformatted
- Beberapa view admin masih tampak partial atau belum diaktifkan sepenuhnya
- Struktur route admin cukup kompleks; perlu audit untuk memastikan middleware dan akses benar

---

## 📊 Diagram Relasi Model

```
User (1) ──── (1) Jemaat
                    │
                    ├── (N) Keuangan
                    ├── (N) Notifikasi
                    ├── (N) Absensi
                    ├── (N) Persembahan
                    └── (N) RegistrasiAcara

HeroSlider (standalone)
Image (standalone)
News (standalone)
Schedule (standalone)
Service (standalone)
Pastor (standalone)
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
