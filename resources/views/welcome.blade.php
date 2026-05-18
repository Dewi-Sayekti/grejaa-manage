@extends('layouts.landing')

@section('title', 'Beranda - Gereja YHS')

@section('content')

{{-- ===== HERO SECTION ===== --}}
<section class="hero" id="beranda">
    <div class="hero-slider-container">
        @forelse($heroSliders as $index => $slider)
            @php $sliderImage = str_replace(' ', '%20', $slider['image_path']); @endphp
            <div class="hero-slide @if($index === 0) active @endif"
                 style="background-image: url('{{ asset($sliderImage) }}');">
                <img src="{{ asset($sliderImage) }}" alt="{{ $slider['title'] }}" class="hero-slide-image">
                <div class="hero-slide-overlay"></div>
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">{{ $slider['title'] }}</h1>
                        <p class="hero-desc">{{ $slider['description'] }}</p>
                        <div class="hero-buttons">
                            @if($slider['link'] && $slider['button_text'])
                                <a href="{{ $slider['link'] }}" class="btn-primary">
                                    {{ $slider['button_text'] }}
                                    <i class="fas fa-calendar-alt ml-2"></i>
                                </a>
                            @endif
                            <a href="{{ route('layanan') }}" class="btn-outline">
                                Pelajari Lebih Lanjut
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            @php
                $defaultSlides = [
                    ['image' => 'images/hero-1.jpg', 'title' => "Datang.\nBertumbuh.\nBerdampak.", 'desc' => 'Tempat di mana setiap orang dipulihkan, diperlengkapi, dan diutus untuk membawa terang Kristus.'],
                    ['image' => 'images/hero-2.jpg', 'title' => "Bersama\nDalam Iman.", 'desc' => 'Bergabunglah bersama kami dalam perjalanan iman yang penuh makna dan berkat.'],
                    ['image' => 'images/hero-3.jpg', 'title' => "Komunitas\nPenuh Kasih.", 'desc' => 'Menjadi keluarga besar yang saling mendukung, mengasihi, dan bertumbuh bersama.'],
                ];
            @endphp
            @foreach($defaultSlides as $dIndex => $dSlide)
                <div class="hero-slide @if($dIndex === 0) active @endif"
                     style="background-image: url('{{ asset($dSlide['image']) }}');">
                    <img src="{{ asset($dSlide['image']) }}" alt="{{ strip_tags($dSlide['title']) }}" class="hero-slide-image">
                    <div class="hero-slide-overlay"></div>
                    <div class="hero-content">
                        <div class="hero-text">
                            <h1 class="hero-title">{!! nl2br(e($dSlide['title'])) !!}</h1>
                            <p class="hero-desc">{{ $dSlide['desc'] }}</p>
                            <div class="hero-buttons">
                                <a href="{{ route('register') }}" class="btn-primary">
                                    Gabung Ibadah <i class="fas fa-calendar-alt ml-2"></i>
                                </a>
                                <a href="{{ route('layanan') }}" class="btn-outline">
                                    Pelajari Lebih Lanjut
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforelse

        {{-- Slider Controls --}}
        @php $slideCount = ($heroSliders && count($heroSliders) > 0) ? count($heroSliders) : 3; @endphp
        @if($slideCount > 1)
        <div class="hero-slider-controls">
            <button onclick="prevSlide()" class="slider-arrow"><i class="fas fa-chevron-left"></i></button>
            <div class="hero-slider-dots">
                @for($i = 0; $i < $slideCount; $i++)
                    <div class="dot @if($i === 0) active @endif" onclick="currentSlide({{ $i }})"></div>
                @endfor
            </div>
            <button onclick="nextSlide()" class="slider-arrow"><i class="fas fa-chevron-right"></i></button>
        </div>
        @endif
    </div>
</section>

{{-- ===== PILLARS SECTION ===== --}}
<section class="pillars-section">
    <div class="pillars-container">
        <div class="pillar-card">
            <div class="pillar-icon"><i class="fas fa-users"></i></div>
            <h3>Komunitas</h3>
            <p>Kami adalah keluarga yang saling mengasihi dan mendukung.</p>
            <a href="{{ route('history') }}" class="pillar-link">Selengkapnya <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="pillar-card">
            <div class="pillar-icon"><i class="fas fa-book-open"></i></div>
            <h3>Pertumbuhan</h3>
            <p>Kami bertumbuh dalam firman dan dipimpin oleh Roh Kudus.</p>
            <a href="{{ route('vision') }}" class="pillar-link">Selengkapnya <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="pillar-card">
            <div class="pillar-icon"><i class="fas fa-globe"></i></div>
            <h3>Dampak</h3>
            <p>Kami hadir untuk membawa kebaikan dan perubahan bagi dunia.</p>
            <a href="{{ route('layanan') }}" class="pillar-link">Selengkapnya <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

{{-- ===== WORSHIP SECTION ===== --}}
<section class="worship-section" id="ibadah">
    <div class="worship-wrapper">
        <div class="worship-left">
            <p class="section-label">IBADAH KAMI</p>
            <h2 class="section-title">Bergabunglah<br>Bersama Kami</h2>
            <p class="section-body">Kami percaya ibadah bersama dapat mengubahkan hidup dan membawa damai sejahtera.</p>
            <a href="{{ route('layanan') }}" class="btn-gold-outline">Lihat Jadwal Lengkap</a>
        </div>
        <div class="worship-right">
            @if(isset($schedules) && $schedules->count() > 0)
                @foreach($schedules->take(3) as $schedule)
                <div class="worship-card">
                    <div class="worship-card-img-wrapper">
                        @if($schedule->image_path)
                            <img src="{{ asset($schedule->image_path) }}" alt="{{ $schedule->title }}" class="worship-card-img">
                        @else
                            <div class="worship-card-img-placeholder"><i class="fas fa-church"></i></div>
                        @endif
                    </div>
                    <div class="worship-card-body">
                        <h4>{{ $schedule->title }}</h4>
                        <p>{{ $schedule->day ?? '' }} {{ $schedule->time ?? '' }}</p>
                        <span>{{ $schedule->location ?? 'Onsite' }}</span>
                    </div>
                </div>
                @endforeach
            @else
                <div class="worship-card">
                    <div class="worship-card-img-wrapper">
                        <div class="worship-card-img-placeholder"><i class="fas fa-church"></i></div>
                    </div>
                    <div class="worship-card-body">
                        <h4>Ibadah Umum</h4>
                        <p>Minggu | 07.00 · 10.00 · 17.00 WIB</p>
                        <span>Onsite &amp; Online</span>
                    </div>
                </div>
                <div class="worship-card">
                    <div class="worship-card-img-wrapper">
                        <div class="worship-card-img-placeholder"><i class="fas fa-people-group"></i></div>
                    </div>
                    <div class="worship-card-body">
                        <h4>Ibadah Sel</h4>
                        <p>Rabu | 19.00 WIB</p>
                        <span>Onsite</span>
                    </div>
                </div>
                <div class="worship-card">
                    <div class="worship-card-img-wrapper">
                        <div class="worship-card-img-placeholder"><i class="fas fa-child"></i></div>
                    </div>
                    <div class="worship-card-body">
                        <h4>Ibadah Youth</h4>
                        <p>Sabtu | 17.00 WIB</p>
                        <span>Onsite</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ===== ABOUT SECTION ===== --}}
<section class="about-section" id="tentang">
    <div class="about-wrapper">
        <div class="about-left">
            <p class="section-label">TENTANG KAMI</p>
            <h2 class="section-title">Gereja YHS</h2>
            <p class="section-body">Gereja YHS adalah komunitas iman yang berkomitmen untuk memenangkan jiwa, membangun murid, dan mengirimkan tenaga kerja bagi Kerajaan Allah. Kami hadir untuk setiap orang yang mencari makna, pemulihan, dan pertumbuhan rohani.</p>
            <a href="{{ route('history') }}" class="btn-dark">Pelajari Lebih Lanjut</a>
        </div>
        <div class="about-right">
            @if(isset($images) && $images->count() > 0)
                @php $aboutImg = $images->first(); @endphp
                @php $imgUrl = str_starts_with($aboutImg->path, 'http') ? $aboutImg->path : (str_starts_with($aboutImg->path, 'images/') ? asset($aboutImg->path) : asset('storage/' . $aboutImg->path)); @endphp
                <img src="{{ $imgUrl }}" alt="Tentang Gereja" class="about-img">
            @else
                <div class="about-img-placeholder">
                    <i class="fas fa-church"></i>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ===== NEWS SECTION ===== --}}
@if(isset($news) && $news->count() > 0)
<section class="news-section" id="artikel">
    <div class="news-wrapper">
        <div class="news-header">
            <p class="section-label">ARTIKEL TERBARU</p>
        </div>
        <div class="news-grid">
            @foreach($news->take(4) as $article)
            @php
                $articleImg = $article->image_path ?? null;
                $articleImgUrl = $articleImg
                    ? (str_starts_with($articleImg, 'http') ? $articleImg : (str_starts_with($articleImg, 'images/') ? asset($articleImg) : asset('storage/' . $articleImg)))
                    : null;
            @endphp
            <div class="news-card">
                <div class="news-card-img">
                    @if($articleImgUrl)
                        <img src="{{ $articleImgUrl }}" alt="{{ $article->title }}">
                    @else
                        <div class="news-card-img-placeholder"><i class="fas fa-newspaper"></i></div>
                    @endif
                </div>
                <div class="news-card-body">
                    <div class="news-card-meta">
                        <span class="news-category">{{ $article->category ?? 'BERITA' }}</span>
                        <span class="news-date">{{ \Carbon\Carbon::parse($article->created_at)->format('d M Y') }}</span>
                    </div>
                    <h4 class="news-card-title">{{ $article->title }}</h4>
                    <a href="#" class="news-card-link">Baca selengkapnya <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== CTA SECTION ===== --}}
<section class="cta-section" id="kontak">
    <div class="cta-wrapper">
        @auth
            <h2>Selamat Datang Kembali,<br><span>{{ Auth::user()->name }}!</span></h2>
            <p>Akses dashboard Anda untuk informasi lengkap dan update terbaru dari gereja.</p>
            <div class="cta-buttons">
                <a href="{{ route('dashboard') }}" class="btn-primary">
                    <i class="fas fa-tachometer-alt mr-2"></i> Ke Dashboard
                </a>
                <a href="#kontak" class="btn-outline">
                    <i class="fas fa-phone mr-2"></i> Hubungi Kami
                </a>
            </div>
        @else
            <h2>Bergabunglah<br><span>Bersama Kami</span></h2>
            <p>Menjadi bagian dari komunitas gereja kami dan rasakan berkah serta dukungan spiritual yang luar biasa.</p>
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="btn-primary">
                    <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="btn-outline">
                    <i class="fas fa-sign-in-alt mr-2"></i> Sudah Punya Akun?
                </a>
            </div>
        @endauth
    </div>
</section>

{{-- ===== STYLES ===== --}}
<style>
:root {
    --gold: #c8a84b;
    --gold-light: #e8c96a;
    --gold-dark: #a08030;
    --cream: #fff8e7;
    --cream-dark: #f5edd0;
    --dark: #1a1a1a;
    --dark-navy: #1c1f2e;
    --text: #333333;
    --text-light: #666666;
    --white: #ffffff;
}

/* ========== HERO ========== */
.hero {
    position: relative;
    height: 100vh;
    min-height: 620px;
    overflow: hidden;
}
.hero-slider-container { position: relative; width: 100%; height: 100%; }
.hero-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.8s ease-in-out;
    background-size: cover;
    background-position: center;
}
.hero-slide.active { opacity: 1; }
.hero-slide-image { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.hero-slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, rgba(0,0,0,0.72) 40%, rgba(0,0,0,0.2) 100%);
    z-index: 2;
}
.hero-content {
    position: absolute; inset: 0; z-index: 3;
    display: flex; align-items: center; padding: 0 8%;
}
.hero-text { max-width: 560px; }
.hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.8rem, 5.5vw, 5rem);
    font-weight: 800;
    color: var(--white);
    line-height: 1.08;
    margin-bottom: 1.2rem;
}
.hero-title .highlight { color: var(--gold-light); display: block; }
.hero-desc {
    font-size: 1.05rem;
    color: rgba(255,255,255,0.85);
    margin-bottom: 2rem;
    line-height: 1.7;
    max-width: 420px;
}
.hero-buttons { display: flex; gap: 1rem; flex-wrap: wrap; }

.btn-primary {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 13px 26px;
    background: var(--gold); color: var(--dark);
    border: 2px solid var(--gold);
    border-radius: 6px; font-weight: 700; font-size: 0.9rem;
    text-decoration: none; transition: all 0.3s;
}
.btn-primary:hover {
    background: var(--gold-light); border-color: var(--gold-light);
    transform: translateY(-2px); box-shadow: 0 8px 24px rgba(200,168,75,0.4);
    color: var(--dark);
}
.btn-outline {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 13px 26px;
    background: transparent; color: var(--white);
    border: 2px solid rgba(255,255,255,0.65);
    border-radius: 6px; font-weight: 600; font-size: 0.9rem;
    text-decoration: none; transition: all 0.3s;
}
.btn-outline:hover { background: rgba(255,255,255,0.12); border-color: var(--white); }
.btn-dark {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 13px 26px;
    background: var(--dark); color: var(--white);
    border: 2px solid var(--dark);
    border-radius: 6px; font-weight: 600; font-size: 0.9rem;
    text-decoration: none; transition: all 0.3s;
}
.btn-dark:hover { background: var(--gold); border-color: var(--gold); color: var(--dark); }
.btn-gold-outline {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 13px 26px;
    background: var(--gold); color: var(--dark);
    border: 2px solid var(--gold);
    border-radius: 6px; font-weight: 700; font-size: 0.9rem;
    text-decoration: none; transition: all 0.3s;
}
.btn-gold-outline:hover { background: var(--gold-light); border-color: var(--gold-light); }

/* Slider controls */
.hero-slider-controls {
    position: absolute; bottom: 36px; left: 50%; transform: translateX(-50%);
    z-index: 10; display: flex; align-items: center; gap: 24px;
}
.slider-arrow {
    width: 42px; height: 42px;
    background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.4);
    border-radius: 50%; color: white; font-size: 15px;
    cursor: pointer; transition: all 0.3s;
    display: flex; align-items: center; justify-content: center;
    backdrop-filter: blur(4px);
}
.slider-arrow:hover { background: var(--gold); border-color: var(--gold); color: var(--dark); }
.hero-slider-dots { display: flex; gap: 8px; }
.dot {
    width: 8px; height: 8px;
    background: rgba(255,255,255,0.4); border-radius: 50%;
    cursor: pointer; transition: all 0.3s;
}
.dot.active { background: var(--gold); width: 24px; border-radius: 4px; }

/* ========== PILLARS ========== */
.pillars-section { background: var(--white); padding: 60px 5%; }
.pillars-container {
    max-width: 1100px; margin: 0 auto;
    display: grid; grid-template-columns: repeat(3, 1fr);
}
.pillar-card {
    padding: 40px 36px;
    border-right: 1px solid #eedea0;
}
.pillar-card:last-child { border-right: none; }
.pillar-icon {
    width: 52px; height: 52px;
    background: var(--cream); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1.2rem; color: var(--gold-dark); font-size: 1.3rem;
    border: 2px solid #e8d89a;
}
.pillar-card h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem; font-weight: 700; color: var(--dark); margin-bottom: 0.7rem;
}
.pillar-card p { color: var(--text-light); font-size: 0.93rem; line-height: 1.65; margin-bottom: 1.2rem; }
.pillar-link {
    color: var(--gold-dark); font-weight: 600; font-size: 0.88rem;
    text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: gap 0.2s;
}
.pillar-link:hover { gap: 10px; }

/* ========== SECTION HELPERS ========== */
.section-label {
    font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em;
    color: var(--gold); margin-bottom: 0.8rem; text-transform: uppercase;
}
.section-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.9rem, 3.5vw, 2.7rem);
    font-weight: 700; color: var(--dark); line-height: 1.2; margin-bottom: 1rem;
}
.section-body { color: var(--text-light); font-size: 0.96rem; line-height: 1.75; margin-bottom: 2rem; }

/* ========== WORSHIP ========== */
.worship-section { background: var(--dark-navy); padding: 80px 5%; }
.worship-wrapper {
    max-width: 1100px; margin: 0 auto;
    display: grid; grid-template-columns: 1fr 2fr; gap: 64px; align-items: center;
}
.worship-section .section-title { color: var(--white); }
.worship-section .section-body { color: rgba(255,255,255,0.6); }
.worship-right { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
.worship-card {
    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px; overflow: hidden; transition: transform 0.3s;
}
.worship-card:hover { transform: translateY(-4px); }
.worship-card-img-wrapper { height: 120px; overflow: hidden; background: rgba(200,168,75,0.12); }
.worship-card-img { width: 100%; height: 100%; object-fit: cover; }
.worship-card-img-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; color: var(--gold);
}
.worship-card-body { padding: 16px; }
.worship-card-body h4 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; font-weight: 700; color: var(--white); margin-bottom: 6px;
}
.worship-card-body p { font-size: 0.81rem; color: rgba(255,255,255,0.55); margin-bottom: 6px; }
.worship-card-body span { font-size: 0.77rem; color: var(--gold); font-weight: 600; }

/* ========== ABOUT ========== */
.about-section { background: var(--cream); padding: 80px 5%; }
.about-wrapper {
    max-width: 1100px; margin: 0 auto;
    display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center;
}
.about-img { width: 100%; height: 380px; object-fit: cover; border-radius: 12px; box-shadow: 0 20px 48px rgba(0,0,0,0.1); }
.about-img-placeholder {
    width: 100%; height: 380px;
    background: linear-gradient(135deg, #eedea0, #d4af37);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 5rem; color: rgba(255,255,255,0.6);
}

/* ========== NEWS ========== */
.news-section { background: var(--white); padding: 80px 5%; }
.news-wrapper { max-width: 1100px; margin: 0 auto; }
.news-header { margin-bottom: 36px; }
.news-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
.news-card {
    background: var(--white); border: 1px solid #eedea0;
    border-radius: 10px; overflow: hidden; transition: box-shadow 0.3s, transform 0.3s;
}
.news-card:hover { box-shadow: 0 8px 32px rgba(200,168,75,0.18); transform: translateY(-4px); }
.news-card-img { height: 160px; overflow: hidden; background: var(--cream-dark); }
.news-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
.news-card:hover .news-card-img img { transform: scale(1.06); }
.news-card-img-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; color: var(--gold);
}
.news-card-body { padding: 16px; }
.news-card-meta { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; flex-wrap: wrap; }
.news-category { font-size: 0.68rem; font-weight: 700; color: var(--gold-dark); letter-spacing: 0.08em; text-transform: uppercase; }
.news-date { font-size: 0.73rem; color: var(--text-light); }
.news-card-title {
    font-family: 'Playfair Display', serif;
    font-size: 0.95rem; font-weight: 700; color: var(--dark); line-height: 1.4; margin-bottom: 12px;
}
.news-card-link {
    font-size: 0.81rem; font-weight: 600; color: var(--gold-dark);
    text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: gap 0.2s;
}
.news-card-link:hover { gap: 10px; }

/* ========== CTA ========== */
.cta-section { background: var(--dark); padding: 100px 5%; text-align: center; }
.cta-wrapper { max-width: 600px; margin: 0 auto; }
.cta-section h2 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 3.2rem);
    font-weight: 800; color: var(--white); line-height: 1.2; margin-bottom: 1rem;
}
.cta-section h2 span { color: var(--gold-light); }
.cta-section p { color: rgba(255,255,255,0.6); font-size: 1rem; line-height: 1.7; margin-bottom: 2.5rem; }
.cta-buttons { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.cta-section .btn-outline { border-color: rgba(255,255,255,0.4); }

/* ========== RESPONSIVE ========== */
@media (max-width: 1024px) {
    .worship-wrapper { grid-template-columns: 1fr; gap: 40px; }
    .about-wrapper { grid-template-columns: 1fr; gap: 40px; }
    .about-right { order: -1; }
    .news-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .pillars-container { grid-template-columns: 1fr; }
    .pillar-card { border-right: none; border-bottom: 1px solid #eedea0; }
    .pillar-card:last-child { border-bottom: none; }
    .worship-right { grid-template-columns: 1fr; }
    .news-grid { grid-template-columns: 1fr; }
    .hero-content { padding: 0 6%; }
    .hero-title { font-size: 2.4rem; }
}
</style>

{{-- ===== SLIDER SCRIPT ===== --}}
<script>
    let currentSlideIndex = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const totalSlides = slides.length;
    let autoplayInterval;

    function showSlide(n) {
        if (n >= totalSlides) currentSlideIndex = 0;
        if (n < 0) currentSlideIndex = totalSlides - 1;
        slides.forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.dot').forEach(d => d.classList.remove('active'));
        if (slides[currentSlideIndex]) slides[currentSlideIndex].classList.add('active');
        const dots = document.querySelectorAll('.dot');
        if (dots[currentSlideIndex]) dots[currentSlideIndex].classList.add('active');
    }

    function nextSlide() { clearInterval(autoplayInterval); currentSlideIndex++; showSlide(currentSlideIndex); startAutoplay(); }
    function prevSlide() { clearInterval(autoplayInterval); currentSlideIndex--; showSlide(currentSlideIndex); startAutoplay(); }
    function currentSlide(n) { clearInterval(autoplayInterval); currentSlideIndex = n; showSlide(currentSlideIndex); startAutoplay(); }

    function startAutoplay() {
        if (totalSlides > 1) autoplayInterval = setInterval(() => { currentSlideIndex++; showSlide(currentSlideIndex); }, 5000);
    }

    const sliderContainer = document.querySelector('.hero-slider-container');
    if (sliderContainer) {
        sliderContainer.addEventListener('mouseenter', () => clearInterval(autoplayInterval));
        sliderContainer.addEventListener('mouseleave', startAutoplay);
    }

    document.addEventListener('DOMContentLoaded', () => { showSlide(currentSlideIndex); startAutoplay(); });
</script>

@endsection