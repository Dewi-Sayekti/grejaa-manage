# Struktur Landing Page - Dokumentasi Lengkap

## 📁 Lokasi File

```
resources/
├── views/
│   ├── layouts/
│   │   └── landing.blade.php          ← Layout utama (Header & Footer)
│   └── welcome.blade.php              ← Konten halaman welcome
└── css/
    ├── app.css                        ← Main CSS (include landing-custom.css)
    └── landing-custom.css             ← Custom styles untuk landing page
```

---

## 🎨 Struktur CSS

Semua styling diorganisir dalam `resources/css/landing-custom.css` dengan section:

### Section CSS yang Tersedia:

1. **HEADER** - Navigation bar styling
   - `.header` - Header container
   - `.navbar` - Navigation wrapper
   - `.logo` - Logo styling
   - `.nav-link` - Menu links
   - `.btn-sign-in` - Sign in button
   - `.btn-dashboard` - Dashboard button

2. **HERO SECTION** - Landing hero area
   - `.hero` - Hero container
   - `.hero-text` - Text content
   - `.btn-primary` - Primary CTA button
   - `.btn-secondary` - Secondary CTA button
   - `.hero-image` - Image area
   - `.quote-box` - Inspirational quote

3. **FEATURES SECTION** - Feature cards
   - `.features-grid` - Grid layout
   - `.feature-card` - Card styling
   - `.feature-card.blue` - Blue variant
   - `.feature-card.green` - Green variant

4. **CTA SECTION** - Call to action
   - `.cta` - CTA container
   - `.btn-white` - White button
   - `.btn-white-outline` - Outlined button

5. **SCHEDULE SECTION** - Jadwal ibadah
   - `.schedule-grid` - Grid layout
   - `.schedule-card` - Card styling
   - `.schedule-items` - Item list

6. **FOOTER** - Footer area
   - `.footer` - Footer container
   - `.footer-content` - Content wrapper

---

## 🔧 Cara Mengedit Tampilan

### 1️⃣ Mengubah Warna

Edit file `resources/css/landing-custom.css`:

```css
/* Contoh: Ubah warna header dari yellow ke blue */
.header {
    @apply bg-blue-50 shadow-sm;  /* Ubah bg-yellow-50 menjadi bg-blue-50 */
}

/* Ubah warna logo */
.logo {
    @apply text-3xl font-bold text-blue-700;  /* Ubah text-yellow-700 */
}

/* Ubah warna button primary */
.btn-primary {
    @apply px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold;  /* Ubah bg-blue-600 */
}
```

**Warna yang tersedia di Tailwind:**
- `yellow`, `orange`, `red`, `blue`, `green`, `purple`, `pink`, `indigo`, dll.

### 2️⃣ Mengubah Ukuran Font & Spacing

```css
/* Ubah ukuran judul hero */
.hero-text h1 {
    @apply text-4xl md:text-6xl font-bold text-gray-900 mb-8 leading-tight;
    /* text-4xl → ukuran lebih besar */
    /* mb-8 → margin bottom (spacing) */
}
```

### 3️⃣ Mengubah Layout

Edit file `resources/views/welcome.blade.php` atau `resources/views/layouts/landing.blade.php`:

```blade
<!-- Contoh: Ubah grid layout dari 2 kolom menjadi 3 -->
<div class="hero-grid">  <!-- Ubah md:grid-cols-2 menjadi md:grid-cols-3 -->
    <!-- Konten -->
</div>
```

### 4️⃣ Mengubah Text & Konten

Edit file `resources/views/welcome.blade.php`:

```blade
<!-- Ubah judul -->
<h1>
    Selamat Datang di <span class="highlight">Gereja</span>  <!-- Ubah teks di sini -->
</h1>

<!-- Ubah deskripsi -->
<p>
    Sistem manajemen jemaat...  <!-- Ubah teks deskripsi -->
</p>

<!-- Ubah tombol -->
<a href="{{ route('login') }}" class="btn-primary">Masuk</a>  <!-- Ubah Label Tombol -->
```

### 5️⃣ Mengubah Jadwal Ibadah

Edit di dalam section `<!-- Schedule Section -->`:

```blade
<p class="schedule-item"><strong>Pukul 08:00</strong> - Ibadah Pagi Reguler</p>
<!-- Ubah jam dan nama kegiatan -->
```

---

## 📱 Class Tailwind yang Sering Digunakan

### Ukuran Teks:
- `text-xs` - Extra small
- `text-sm` - Small
- `text-base` - Normal
- `text-lg` - Large
- `text-xl` - Extra large
- `text-2xl` - 2XL
- `text-3xl` - 3XL
- `text-4xl` - 4XL
- `text-5xl` - 5XL

### Warna Background:
- `bg-white`, `bg-gray-50`, `bg-yellow-50`, `bg-blue-50`, etc.

### Warna Text:
- `text-gray-900`, `text-yellow-700`, `text-blue-600`, etc.

### Margin & Padding:
- `p-6` - Padding 6 (1.5rem)
- `px-6` - Padding horizontal
- `py-4` - Padding vertical
- `m-4` - Margin
- `mb-8` - Margin bottom

### Responsive:
- `md:` - Medium screens (tablet)
- `lg:` - Large screens (desktop)

---

## 🎯 Custom Class Prefixes yang Saya Buat

### Buttons:
- `.btn-primary` - Tombol biru utama
- `.btn-secondary` - Tombol yellow border
- `.btn-white` - Tombol putih
- `.btn-white-outline` - Tombol outline putih

### Sections:
- `.hero` - Hero section
- `.features` - Features section
- `.cta` - Call to action section
- `.schedule` - Jadwal section

### Cards:
- `.feature-card` - Feature card (yellow)
- `.feature-card.blue` - Feature card (blue)
- `.feature-card.green` - Feature card (green)
- `.schedule-card` - Schedule card

---

## 📝 Contoh Lengkap: Mengubah Warna Header

### Step 1: Buka `resources/css/landing-custom.css`
### Step 2: Cari section HEADER
### Step 3: Update class:

```css
/* SEBELUM */
.header {
    @apply bg-yellow-50 shadow-sm;
}

.logo {
    @apply text-3xl font-bold text-yellow-700;
}

.btn-dashboard {
    @apply px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition;
}

/* SESUDAH */
.header {
    @apply bg-blue-50 shadow-sm;
}

.logo {
    @apply text-3xl font-bold text-blue-700;
}

.btn-dashboard {
    @apply px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition;
}
```

### Step 4: Refresh browser
### Step 5: Lihat perubahan!

---

## ✨ Tips & Trik

1. **Jangan Edit Tailwind Classes Langsung di Blade**
   - Selalu gunakan custom CSS classes
   - Lebih mudah diubah di satu tempat

2. **Gunakan Keyboard Shortcut**
   - `Ctrl+H` untuk Find & Replace di VS Code
   - Cocok untuk mengganti warna theme sekali jalan

3. **Responsive Design**
   - Selalu test di mobile, tablet, dan desktop
   - Gunakan `md:` dan `lg:` untuk responsive

4. **Konsistensi Warna**
   - Pilih 3-4 warna utama untuk theme
   - Gunakan shade yang sama (600, 700 untuk hover)

---

## 🚀 File yang Penting untuk Diingat

| File | Fungsi | Cara Mengubah |
|------|--------|---------------|
| `landing-custom.css` | Styling utama | Edit class CSS |
| `welcome.blade.php` | Konten halaman | Edit text, layout |
| `landing.blade.php` | Header & Footer layout | Edit navigation links |

---

## 💡 Hasil Akhir

Dengan struktur ini, Anda bisa dengan mudah:
✅ Mengubah warna theme
✅ Menambah/menghapus section
✅ Mengubah text dan konten
✅ Memodifikasi layout
✅ Menambah fitur baru

**Semua tanpa perlu mengerti Tailwind mendalam!**
