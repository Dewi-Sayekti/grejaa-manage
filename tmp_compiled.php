<?php $__env->startSection('title', 'Beranda - Gereja YHS'); ?>

<?php $__env->startSection('content'); ?>

<!-- ===== HERO SLIDER SECTION ===== -->
<section class="hero">
    <div class="hero-slider-container">
        <?php $__empty_1 = true; $__currentLoopData = $heroSliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="hero-slide <?php if($index === 0): ?> active <?php endif; ?>"
                 style="background-image: url('<?php echo e(asset($slider['image_path'])); ?>');">
                <img src="<?php echo e(asset($slider['image_path'])); ?>" alt="<?php echo e($slider['title']); ?>"
                     class="hero-slide-image">
                <div class="hero-slide-overlay"></div>
                <div class="hero-content">
                    <div class="hero-text">
                        <h1>
                            <?php echo e($slider['title']); ?>

                        </h1>
                        <p><?php echo e($slider['description']); ?></p>
                        <?php if($slider['link'] && $slider['button_text']): ?>
                            <a href="<?php echo e($slider['link']); ?>" class="btn-primary">
                                <?php echo e($slider['button_text']); ?>

                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="hero-slide active"
                 style="background: linear-gradient(135deg, #fff1bf 0%, #ffe587 100%);">
                <div class="hero-slide-overlay"></div>
                <div class="hero-content">
                    <div class="hero-text">
                        <h1>Selamat Datang di <span class="highlight">Gereja YHS</span></h1>
                        <p>Tempat berbagi kasih, iman, dan harapan bersama</p>
                        <a href="#tentang" class="btn-primary">
                            Pelajari Lebih Lanjut
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Slider Controls -->
        <?php if($heroSliders && count($heroSliders) > 1): ?>
            <div class="hero-slider-controls">
                <button onclick="prevSlide()" class="slider-arrow" title="Sebelumnya">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <div class="hero-slider-dots">
                    <?php $__currentLoopData = $heroSliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="dot <?php if($index === 0): ?> active <?php endif; ?>"
                             onclick="currentSlide(<?php echo e($index); ?>)"
                             title="Slide <?php echo e($index + 1); ?>"></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <button onclick="nextSlide()" class="slider-arrow" title="Berikutnya">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ===== FEATURED SECTION (Quick Links) ===== -->
<section class="features">
    <div class="features-content">
        <div class="features-header">
            <h2 class="features-title">Layanan Kami</h2>
            <p class="features-subtitle">Nikmati berbagai layanan rohani untuk pertumbuhan iman Anda</p>
        </div>

        <div class="features-grid">
            <!-- Card 1 -->
            <div class="feature-card gold">
                <div class="feature-icon">
                    <i class="fas fa-bible text-gold-600"></i>
                </div>
                <h3>Ibadah Rutin</h3>
                <p>Bergabunglah dengan kami setiap minggu untuk ibadah yang penuh makna dan pembelajaran firman Tuhan yang mendalam.</p>
            </div>

            <!-- Card 2 -->
            <div class="feature-card blue">
                <div class="feature-icon">
                    <i class="fas fa-people-group text-blue-600"></i>
                </div>
                <h3>Kelompok Kecil</h3>
                <p>Komunitas kecil untuk saling berbagi, mendukung, dan tumbuh bersama dalam iman dan persahabatan.</p>
            </div>

            <!-- Card 3 -->
            <div class="feature-card green">
                <div class="feature-icon">
                    <i class="fas fa-child text-green-600"></i>
                </div>
                <h3>Sekolah Minggu</h3>
                <p>Program khusus untuk anak-anak kami dengan metode pembelajaran kreatif dan menyenangkan tentang kebenaran Tuhan.</p>
            </div>

            <!-- Card 4 -->
            <div class="feature-card gold">
                <div class="feature-icon">
                    <i class="fas fa-music text-gold-600"></i>
                </div>
                <h3>Musik & Nyanyian</h3>
                <p>Ekspresikan iman Anda melalui nyanyian yang indah dan musik yang menyentuh hati dalam peribadahan.</p>
            </div>

            <!-- Card 5 -->
            <div class="feature-card blue">
                <div class="feature-icon">
                    <i class="fas fa-heart text-blue-600"></i>
                </div>
                <h3>Konseling Rohani</h3>
                <p>Layanan konseling profesional dari pemimpin rohani berpengalaman untuk berbagai kebutuhan spiritual Anda.</p>
            </div>

            <!-- Card 6 -->
            <div class="feature-card green">
                <div class="feature-icon">
                    <i class="fas fa-hands-helping text-green-600"></i>
                </div>
                <h3>Pelayanan Sosial</h3>
                <p>Kami berkomitmen untuk membantu masyarakat melalui berbagai program sosial dan kemitraan komunitas.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===== SCHEDULE/JADWAL IBADAH SECTION ===== -->
<section id="ibadah" class="schedule">
    <div class="schedule-content">
        <div class="schedule-header">
            <h2 class="schedule-title">Jadwal Ibadah</h2>
            <p class="text-lg text-text-light">Kunjungi kami dan jadilah bagian dari keluarga besar gereja kami</p>
        </div>

        <div class="schedule-grid">
            <!-- Ibadah Pagi -->
            <div class="schedule-card">
                <div class="schedule-emoji">🌅</div>
                <h3>Ibadah Pagi</h3>
                <div class="schedule-items">
                    <div class="schedule-item">
                        <strong>Minggu Pagi</strong><br>
                        Pukul 07:00 - 08:30
                    </div>
                    <div class="schedule-item">
                        <strong>Rabu Pagi</strong><br>
                        Pukul 06:00 - 07:00
                    </div>
                </div>
            </div>

            <!-- Ibadah Sore -->
            <div class="schedule-card">
                <div class="schedule-emoji">🌤️</div>
                <h3>Ibadah Sore</h3>
                <div class="schedule-items">
                    <div class="schedule-item">
                        <strong>Minggu Sore</strong><br>
                        Pukul 16:00 - 17:30
                    </div>
                    <div class="schedule-item">
                        <strong>Jumat Sore</strong><br>
                        Pukul 18:00 - 19:30
                    </div>
                </div>
            </div>

            <!-- Doa Malam -->
            <div class="schedule-card">
                <div class="schedule-emoji">🌙</div>
                <h3>Doa Malam</h3>
                <div class="schedule-items">
                    <div class="schedule-item">
                        <strong>Setiap Malam</strong><br>
                        Pukul 19:30 - 20:30
                    </div>
                    <div class="schedule-item">
                        <strong>Khusus Jumat</strong><br>
                        Pemberkatan Khusus
                    </div>
                </div>
            </div>

            <!-- Sekolah Minggu -->
            <div class="schedule-card">
                <div class="schedule-emoji">👶</div>
                <h3>Sekolah Minggu</h3>
                <div class="schedule-items">
                    <div class="schedule-item">
                        <strong>Setiap Minggu</strong><br>
                        Pukul 08:00 - 09:00
                    </div>
                    <div class="schedule-item">
                        <strong>Untuk Usia</strong><br>
                        4 - 12 Tahun
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== NEWS/BERITA SECTION ===== -->
<section id="berita" class="news-section">
    <div class="news-content">
        <div class="news-header">
            <h2 class="news-title">Berita & Pengumuman</h2>
            <p class="text-lg text-text-light">Informasi terbaru dari komunitas gereja kami</p>
        </div>

        <div class="news-grid">
            <!-- News Card 1 -->
            <div class="news-card">
                <div class="news-card-image" style="background: linear-gradient(135deg, #fff1bf 0%, #ffe587 100%);"></div>
                <div class="news-card-content">
                    <div class="news-card-date">5 April 2026</div>
                    <h3 class="news-card-title">Perayaan Paskah 2026</h3>
                    <p class="news-card-excerpt">Bergabunglah dengan kami dalam perayaan Paskah yang penuh makna dan berkah. Acara spesial akan diadakan di seluruh minggu ini.</p>
                    <a href="#" class="news-card-link">
                        Baca Selengkapnya
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- News Card 2 -->
            <div class="news-card">
                <div class="news-card-image" style="background: linear-gradient(135deg, #d4af37 0%, #b8860b 100%);"></div>
                <div class="news-card-content">
                    <div class="news-card-date">1 April 2026</div>
                    <h3 class="news-card-title">Kelas Pembinaan Iman</h3>
                    <p class="news-card-excerpt">Program pembinaan iman baru dimulai bulan depan dengan materi yang mendalam dan interaktif untuk pertumbuhan rohani.</p>
                    <a href="#" class="news-card-link">
                        Baca Selengkapnya
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- News Card 3 -->
            <div class="news-card">
                <div class="news-card-image" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);"></div>
                <div class="news-card-content">
                    <div class="news-card-date">25 Maret 2026</div>
                    <h3 class="news-card-title">Pelayanan Sosial Komunitas</h3>
                    <p class="news-card-excerpt">Kami mengadakan kegiatan sosial untuk membantu keluarga yang membutuhkan. Undangan terbuka untuk semua jemaat yang ingin berkontribusi.</p>
                    <a href="#" class="news-card-link">
                        Baca Selengkapnya
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CTA SECTION ===== -->
<section class="cta">
    <div class="cta-content">
        <?php if(auth()->guard()->check()): ?>
            <h2 class="cta-title">Selamat Datang Kembali!</h2>
            <p class="cta-subtitle">Akses dashboard Anda untuk informasi lengkap dan update terbaru dari gereja.</p>
            <div class="cta-buttons">
                <a href="<?php echo e(route('dashboard')); ?>" class="btn-primary">
                    <i class="fas fa-tachometer-alt mr-2"></i> Ke Dashboard
                </a>
                <a href="#kontak" class="btn-secondary">
                    <i class="fas fa-phone mr-2"></i> Hubungi Kami
                </a>
            </div>
        <?php else: ?>
            <h2 class="cta-title">Bergabunglah Dengan Kami</h2>
            <p class="cta-subtitle">Menjadi bagian dari komunitas gereja kami dan rasakan berkah serta dukungan spiritual yang luar biasa.</p>
            <div class="cta-buttons">
                <a href="<?php echo e(route('register')); ?>" class="btn-primary">
                    <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                </a>
                <a href="<?php echo e(route('login')); ?>" class="btn-secondary">
                    <i class="fas fa-sign-in-alt mr-2"></i> Sudah Punya Akun?
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ===== HERO SLIDER SCRIPT ===== -->
<script>
    let currentSlideIndex = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const totalSlides = slides.length;
    let autoplayInterval;

    function showSlide(n) {
        if (n >= totalSlides) {
            currentSlideIndex = 0;
        }
        if (n < 0) {
            currentSlideIndex = totalSlides - 1;
        }

        // Hide all slides
        slides.forEach(slide => slide.classList.remove('active'));

        // Update dots
        document.querySelectorAll('.dot').forEach(dot => dot.classList.remove('active'));

        // Show current slide
        if (slides[currentSlideIndex]) {
            slides[currentSlideIndex].classList.add('active');
        }

        // Update dot
        const dots = document.querySelectorAll('.dot');
        if (dots[currentSlideIndex]) {
            dots[currentSlideIndex].classList.add('active');
        }
    }

    function nextSlide() {
        clearInterval(autoplayInterval);
        currentSlideIndex++;
        showSlide(currentSlideIndex);
        startAutoplay();
    }

    function prevSlide() {
        clearInterval(autoplayInterval);
        currentSlideIndex--;
        showSlide(currentSlideIndex);
        startAutoplay();
    }

    function currentSlide(n) {
        clearInterval(autoplayInterval);
        currentSlideIndex = n;
        showSlide(currentSlideIndex);
        startAutoplay();
    }

    function startAutoplay() {
        if (totalSlides > 1) {
            autoplayInterval = setInterval(() => {
                currentSlideIndex++;
                showSlide(currentSlideIndex);
            }, 4000); // Change slide every 4 seconds
        }
    }

    // Pause autoplay on hover
    const sliderContainer = document.querySelector('.hero-slider-container');
    if (sliderContainer) {
        sliderContainer.addEventListener('mouseenter', () => clearInterval(autoplayInterval));
        sliderContainer.addEventListener('mouseleave', startAutoplay);
    }

    // Start autoplay when page loads
    document.addEventListener('DOMContentLoaded', () => {
        showSlide(currentSlideIndex);
        startAutoplay();
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>