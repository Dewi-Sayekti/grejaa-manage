@extends('layouts.dashboard')

@section('title', 'Dashboard Jemaat')
@section('page-title', 'Dashboard')

@section('content')

{{-- ===== WELCOME AREA ===== --}}
<div class="db-welcome">
    <div class="db-welcome-text">
        <div class="db-welcome-greeting">Selamat datang,</div>
        <h2 class="db-welcome-name">{{ Auth::user()->name }} 👋</h2>
        @php
            $ayats = [
                ['teks' => '"Sebab Aku ini mengetahui rancangan-rancangan yang ada pada-Ku mengenai kamu, demikianlah firman TUHAN, yaitu rancangan damai sejahtera dan bukan rancangan kecelakaan, untuk memberikan kepadamu hari depan yang penuh harapan."', 'ref' => 'Yeremia 29:11'],
                ['teks' => '"Serahkanlah perbuatanmu kepada TUHAN, maka terlaksanalah segala rencanamu."', 'ref' => 'Amsal 16:3'],
                ['teks' => '"Kuatkan dan teguhkanlah hatimu, janganlah takut dan jangan gemetar karena mereka, sebab TUHAN, Allahmu, Dialah yang berjalan menyertai engkau."', 'ref' => 'Ulangan 31:6'],
            ];
            $ayat = $ayats[date('j') % count($ayats)];
        @endphp
        <p class="db-welcome-verse">{{ $ayat['teks'] }}</p>
        <span class="db-welcome-ref">— {{ $ayat['ref'] }}</span>
    </div>
    
</div>

{{-- ===== INFO CARDS ===== --}}
<div class="db-info-cards">

    {{-- Ibadah Berikutnya --}}
    <div class="db-info-card db-info-card--purple">
        <div class="db-info-card-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="db-info-card-body">
            <div class="db-info-card-label">Ibadah Berikutnya</div>
            @if(isset($nextSchedule) && $nextSchedule)
                <div class="db-info-card-value">{{ \Carbon\Carbon::parse($nextSchedule->date ?? now())->format('d M Y') }}</div>
                <div class="db-info-card-sub">{{ $nextSchedule->title }}</div>
            @else
                <div class="db-info-card-value">Minggu Ini</div>
                <div class="db-info-card-sub">07.00 & 10.00 WIB</div>
            @endif
        </div>
        <a href="{{ route('dashboard.features.events') }}" class="db-info-card-arrow"><i class="fas fa-chevron-right"></i></a>
    </div>

    {{-- Kehadiran --}}
    <div class="db-info-card db-info-card--blue">
        <div class="db-info-card-icon"><i class="fas fa-clipboard-check"></i></div>
        <div class="db-info-card-body">
            <div class="db-info-card-label">Kehadiran Saya</div>
            <div class="db-info-card-value">{{ $absensiStats['hadir'] ?? 0 }}x</div>
            <div class="db-info-card-sub">Bulan ini tercatat hadir</div>
        </div>
        <a href="{{ route('absensi.index') }}" class="db-info-card-arrow"><i class="fas fa-chevron-right"></i></a>
    </div>

    {{-- Absensi Pending --}}
    <div class="db-info-card db-info-card--gold">
        <div class="db-info-card-icon"><i class="fas fa-hourglass-half"></i></div>
        <div class="db-info-card-body">
            <div class="db-info-card-label">Absensi Pending</div>
            <div class="db-info-card-value">{{ $absensiStats['pending'] ?? 0 }}</div>
            <div class="db-info-card-sub">Menunggu persetujuan admin</div>
        </div>
        <a href="{{ route('absensi.index') }}" class="db-info-card-arrow"><i class="fas fa-chevron-right"></i></a>
    </div>

    {{-- Renungan --}}
    <div class="db-info-card db-info-card--green">
        <div class="db-info-card-icon"><i class="fas fa-book-open"></i></div>
        <div class="db-info-card-body">
            <div class="db-info-card-label">Renungan Hari Ini</div>
            <div class="db-info-card-value" style="font-size:14px;">{{ $ayat['ref'] }}</div>
            <div class="db-info-card-sub">Baca firman hari ini</div>
        </div>
        <a href="{{ route('dashboard.features.services') }}" class="db-info-card-arrow"><i class="fas fa-chevron-right"></i></a>
    </div>

</div>

{{-- ===== 3-COLUMN CONTENT ===== --}}
<div class="db-content-grid">

    {{-- COL 1: Jadwal Ibadah --}}
    <div class="db-panel">
        <div class="db-panel-header">
            <span class="db-panel-title">Jadwal Ibadah</span>
            <a href="{{ route('dashboard.features.events') }}" class="db-panel-link">Lihat semua</a>
        </div>
        <div class="db-panel-body">
            @if(isset($schedules) && $schedules->count() > 0)
                @foreach($schedules->take(4) as $sc)
                @php
                    $sudahAbsen = isset($existingAbsensi) && $existingAbsensi->has($sc->id);
                    $absensiData = $sudahAbsen ? $existingAbsensi->get($sc->id) : null;
                    $scTime = $sc->start_time ? \Carbon\Carbon::parse($sc->start_time)->format('H:i').' WIB' : '';
                @endphp
                <div class="db-schedule-item db-schedule-item--clickable"
                     onclick="openAbsensiPopup({{ $sc->id }}, '{{ e($sc->title) }}', '{{ e($sc->day ?? "") }}', '{{ $scTime }}', '{{ e($sc->location ?? "Gereja") }}', {{ $sudahAbsen ? 'true' : 'false' }}, '{{ $absensiData ? $absensiData->approval_status : "" }}')"
                     title="Klik untuk catat absensi">
                    <div class="db-schedule-img">
                        @if($sc->image_path ?? false)
                            <img src="{{ asset($sc->image_path) }}" alt="{{ $sc->title }}">
                        @else
                            <div class="db-schedule-img-ph"><i class="fas fa-church"></i></div>
                        @endif
                    </div>
                    <div class="db-schedule-info">
                        <div class="db-schedule-dot db-dot--gold"></div>
                        <div>
                            <div class="db-schedule-name">{{ $sc->title }}</div>
                            <div class="db-schedule-time">
                                {{ $sc->day ?? '' }}
                                @if($sc->start_time) &middot; {{ \Carbon\Carbon::parse($sc->start_time)->format('H:i') }} WIB @endif
                            </div>
                            <div class="db-schedule-loc">
                                <i class="fas fa-map-marker-alt" style="font-size:10px;margin-right:3px;"></i>{{ $sc->location ?? 'Gereja' }}
                            </div>
                        </div>
                    </div>
                    @if($sudahAbsen)
                        @if($absensiData->approval_status === 'approved')
                            <span class="db-badge db-badge--green"><i class="fas fa-check" style="margin-right:3px;"></i>Hadir</span>
                        @elseif($absensiData->approval_status === 'pending')
                            <span class="db-badge db-badge--gold"><i class="fas fa-clock" style="margin-right:3px;"></i>Pending</span>
                        @else
                            <span class="db-badge" style="background:#fee2e2;color:#991b1b;"><i class="fas fa-times" style="margin-right:3px;"></i>Ditolak</span>
                        @endif
                    @else
                        <span class="db-badge db-badge--blue"><i class="fas fa-plus" style="margin-right:3px;"></i>Absen</span>
                    @endif
                </div>
                @endforeach
            @else
                <div class="db-schedule-item">
                    <div class="db-schedule-img"><div class="db-schedule-img-ph"><i class="fas fa-church"></i></div></div>
                    <div class="db-schedule-info">
                        <div class="db-schedule-dot db-dot--gold"></div>
                        <div>
                            <div class="db-schedule-name">Ibadah Raya Minggu</div>
                            <div class="db-schedule-time">Minggu, 07.00 & 10.00 WIB</div>
                            <div class="db-schedule-loc">Gedung Utama</div>
                        </div>
                    </div>
                    <span class="db-badge db-badge--blue">Akan datang</span>
                </div>
                <div class="db-schedule-item">
                    <div class="db-schedule-img"><div class="db-schedule-img-ph"><i class="fas fa-moon"></i></div></div>
                    <div class="db-schedule-info">
                        <div class="db-schedule-dot db-dot--purple"></div>
                        <div>
                            <div class="db-schedule-name">Doa Malam</div>
                            <div class="db-schedule-time">Setiap hari, 19.30 WIB</div>
                            <div class="db-schedule-loc">Ruang Doa</div>
                        </div>
                    </div>
                    <span class="db-badge db-badge--blue">Akan datang</span>
                </div>
                <div class="db-schedule-item">
                    <div class="db-schedule-img"><div class="db-schedule-img-ph"><i class="fas fa-guitar"></i></div></div>
                    <div class="db-schedule-info">
                        <div class="db-schedule-dot db-dot--green"></div>
                        <div>
                            <div class="db-schedule-name">Youth Worship</div>
                            <div class="db-schedule-time">Sabtu, 17.00 WIB</div>
                            <div class="db-schedule-loc">Aula Muda Mudi</div>
                        </div>
                    </div>
                    <span class="db-badge db-badge--blue">Akan datang</span>
                </div>
            @endif
        </div>
        <div class="db-panel-footer">
            <a href="{{ route('dashboard.features.events') }}" class="db-panel-footer-link">
                Lihat semua jadwal ibadah <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>

    {{-- COL 2: Pengumuman --}}
    <div class="db-panel">
        <div class="db-panel-header">
            <span class="db-panel-title">Pengumuman Terbaru</span>
            <a href="{{ route('pengumuman') }}" class="db-panel-link">Lihat semua</a>
        </div>
        <div class="db-panel-body">
            @if(isset($news) && $news->count() > 0)
                @foreach($news->take(3) as $n)
                @php
                    $nImg = $n->image_path ?? null;
                    $nImgUrl = $nImg ? (str_starts_with($nImg,'http') ? $nImg : asset('storage/'.$nImg)) : null;
                @endphp
                <div class="db-news-item">
                    <div class="db-news-img">
                        @if($nImgUrl)
                            <img src="{{ $nImgUrl }}" alt="{{ $n->title }}">
                        @else
                            <div class="db-news-img-ph"><i class="fas fa-newspaper"></i></div>
                        @endif
                    </div>
                    <div class="db-news-info">
                        <div class="db-news-title">{{ $n->title }}</div>
                        <div class="db-news-excerpt">{{ Str::limit($n->content ?? $n->excerpt ?? '', 80) }}</div>
                        <div class="db-news-date">{{ \Carbon\Carbon::parse($n->created_at)->format('d M Y') }}</div>
                    </div>
                </div>
                @endforeach
            @else
                @foreach([['Retreat Pemuda 2024','Retreat Pemuda akan diadakan pada 7-9 Juni 2024 di Villa Shalom.','13 Mei 2024'],['Seminar Keluarga Kristen','Seminar dengan tema "Keluarga yang Berkenan di Hadapan Tuhan".','10 Mei 2024'],['Baptisan Air','Pendaftaran baptisan air dibuka hingga 30 Mei 2024.','8 Mei 2024']] as $dummy)
                <div class="db-news-item">
                    <div class="db-news-img"><div class="db-news-img-ph"><i class="fas fa-newspaper"></i></div></div>
                    <div class="db-news-info">
                        <div class="db-news-title">{{ $dummy[0] }}</div>
                        <div class="db-news-excerpt">{{ $dummy[1] }}</div>
                        <div class="db-news-date">{{ $dummy[2] }}</div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
        <div class="db-panel-footer">
            <a href="{{ route('pengumuman') }}" class="db-panel-footer-link">
                Lihat semua pengumuman <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>

    {{-- COL 3: Doa & Absensi Recent --}}
    <div>
        {{-- Notifikasi --}}
        <div class="db-panel" style="margin-bottom:20px;">
            <div class="db-panel-header">
                <span class="db-panel-title">Doa & Permohonan</span>
                <a href="#" class="db-panel-link">Buat baru</a>
            </div>
            <div class="db-panel-body" style="padding:0;">
                @if($notifikasi->count() > 0)
                    @foreach($notifikasi->take(3) as $n)
                    <div class="db-prayer-item">
                        <div class="db-prayer-icon" style="background:{{ $n->tipe == 'penting' ? '#fce4ec' : '#ede9fe' }};color:{{ $n->tipe == 'penting' ? '#dc2626' : '#7c3aed' }};">
                            <i class="fas fa-praying-hands"></i>
                        </div>
                        <div class="db-prayer-info">
                            <div class="db-prayer-title">{{ $n->judul }}</div>
                            <div class="db-prayer-text">{{ Str::limit($n->isi, 60) }}</div>
                            <div class="db-prayer-stat">Disampaikan {{ $n->tanggal_kirim ? $n->tanggal_kirim->diffForHumans() : '' }}</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="db-prayer-item">
                        <div class="db-prayer-icon" style="background:#ede9fe;color:#7c3aed;"><i class="fas fa-praying-hands"></i></div>
                        <div class="db-prayer-info">
                            <div class="db-prayer-title">Kesembuhan</div>
                            <div class="db-prayer-text">Mohon doa untuk pemulihan kesehatan yang sedang dirawat.</div>
                            <div class="db-prayer-stat">Didoakan oleh jemaat</div>
                        </div>
                    </div>
                    <div class="db-prayer-item">
                        <div class="db-prayer-icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-praying-hands"></i></div>
                        <div class="db-prayer-info">
                            <div class="db-prayer-title">Pekerjaan & Hikmat</div>
                            <div class="db-prayer-text">Mohon doa untuk pekerjaan baru dan hikmat dalam setiap keputusan.</div>
                            <div class="db-prayer-stat">Didoakan oleh jemaat</div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="db-panel-footer">
                <a href="#" class="db-panel-footer-link">Lihat semua permohonan <i class="fas fa-chevron-right"></i></a>
            </div>
        </div>

        {{-- Ayat Alkitab --}}
        <div class="db-panel db-bible-card">
            <div class="db-bible-icon"><i class="fas fa-sun"></i></div>
            <div class="db-bible-title">Ayat Alkitab Hari Ini</div>
            <p class="db-bible-verse">{{ $ayat['teks'] }}</p>
            <div class="db-bible-ref">{{ $ayat['ref'] }}</div>
        </div>
    </div>

</div>



{{-- ===== STYLES ===== --}}
<style>
/* ============ WELCOME ============ */
.db-welcome {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 28px;
    flex-wrap: wrap;
}
.db-welcome-greeting {
    font-size: 15px;
    color: var(--text-mid);
    margin-bottom: 2px;
}
.db-welcome-name {
    font-family: 'Playfair Display', serif;
    font-size: 30px;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 10px;
}
.db-welcome-verse {
    font-size: 13px;
    color: var(--text-mid);
    font-style: italic;
    line-height: 1.6;
    max-width: 580px;
    margin-bottom: 4px;
}
.db-welcome-ref {
    font-size: 12px;
    font-weight: 600;
    color: var(--gold);
}



/* ============ INFO CARDS ============ */
.db-info-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.db-info-card {
    background: #fff;
    border-radius: 12px;
    padding: 18px 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: var(--shadow);
    border-left: 4px solid transparent;
    transition: transform 0.2s, box-shadow 0.2s;
    position: relative;
    overflow: hidden;
}
.db-info-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
.db-info-card--purple { border-left-color: #7c3aed; }
.db-info-card--blue   { border-left-color: #2563eb; }
.db-info-card--gold   { border-left-color: var(--gold); }
.db-info-card--green  { border-left-color: #059669; }
.db-info-card-icon {
    width: 44px; height: 44px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.db-info-card--purple .db-info-card-icon { background: #ede9fe; color: #7c3aed; }
.db-info-card--blue   .db-info-card-icon { background: #dbeafe; color: #2563eb; }
.db-info-card--gold   .db-info-card-icon { background: var(--gold-light); color: var(--gold-dark); }
.db-info-card--green  .db-info-card-icon { background: #d1fae5; color: #059669; }
.db-info-card-body { flex: 1; min-width: 0; }
.db-info-card-label { font-size: 11px; font-weight: 600; color: var(--text-mid); text-transform: uppercase; letter-spacing: 0.5px; }
.db-info-card-value { font-size: 17px; font-weight: 700; color: var(--text-dark); margin-top: 2px; }
.db-info-card-sub { font-size: 11px; color: var(--text-mid); margin-top: 2px; }
.db-info-card-arrow { color: var(--text-light); font-size: 12px; flex-shrink: 0; text-decoration: none; }
.db-info-card-arrow:hover { color: var(--gold); }

/* ============ 3-COL GRID ============ */
.db-content-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

/* ============ PANEL ============ */
.db-panel { background: #fff; border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; }
.db-panel-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-bottom: 1px solid var(--border);
}
.db-panel-title { font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 700; color: var(--text-dark); }
.db-panel-link { font-size: 12px; font-weight: 600; color: var(--gold-dark); text-decoration: none; }
.db-panel-link:hover { color: var(--gold); }
.db-panel-body { padding: 0; }
.db-panel-footer {
    padding: 12px 20px; border-top: 1px solid var(--border); text-align: center;
}
.db-panel-footer-link { font-size: 13px; font-weight: 600; color: var(--gold-dark); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
.db-panel-footer-link:hover { color: var(--gold); }

/* ============ SCHEDULE ITEMS ============ */
.db-schedule-item {
    display: flex; align-items: center; gap: 12px;
    padding: 13px 20px; border-bottom: 1px solid #f3f1ec;
    transition: background 0.2s;
}
.db-schedule-item:hover { background: #fdfcfa; }
.db-schedule-item:last-child { border-bottom: none; }
.db-schedule-img { width: 56px; height: 48px; border-radius: 8px; overflow: hidden; flex-shrink: 0; background: var(--gold-light); }
.db-schedule-img img { width: 100%; height: 100%; object-fit: cover; }
.db-schedule-img-ph { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--gold-dark); font-size: 18px; }
.db-schedule-info { display: flex; align-items: flex-start; gap: 8px; flex: 1; }
.db-schedule-dot { width: 8px; height: 8px; border-radius: 50%; margin-top: 5px; flex-shrink: 0; }
.db-dot--gold { background: var(--gold); }
.db-dot--purple { background: #7c3aed; }
.db-dot--green { background: #059669; }
.db-schedule-name { font-size: 13px; font-weight: 600; color: var(--text-dark); }
.db-schedule-time { font-size: 11.5px; color: var(--text-mid); margin-top: 2px; }
.db-schedule-loc { font-size: 11px; color: var(--text-light); margin-top: 1px; }
.db-badge { padding: 3px 9px; border-radius: 20px; font-size: 10.5px; font-weight: 600; white-space: nowrap; flex-shrink: 0; }
.db-badge--blue { background: #dbeafe; color: #1e40af; }
.db-badge--gold { background: var(--gold-light); color: var(--gold-dark); }
.db-badge--green { background: #d1fae5; color: #065f46; }

/* ============ NEWS ITEMS ============ */
.db-news-item {
    display: flex; gap: 12px; padding: 13px 20px;
    border-bottom: 1px solid #f3f1ec; transition: background 0.2s;
}
.db-news-item:hover { background: #fdfcfa; }
.db-news-item:last-child { border-bottom: none; }
.db-news-img { width: 72px; height: 60px; border-radius: 8px; overflow: hidden; flex-shrink: 0; background: #f3f1ec; }
.db-news-img img { width: 100%; height: 100%; object-fit: cover; }
.db-news-img-ph { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--gold-dark); font-size: 18px; }
.db-news-info { flex: 1; min-width: 0; }
.db-news-title { font-size: 13px; font-weight: 600; color: var(--text-dark); line-height: 1.4; }
.db-news-excerpt { font-size: 11.5px; color: var(--text-mid); margin-top: 4px; line-height: 1.5; }
.db-news-date { font-size: 11px; color: var(--text-light); margin-top: 4px; }

/* ============ PRAYER ============ */
.db-prayer-item {
    display: flex; gap: 12px; padding: 14px 20px;
    border-bottom: 1px solid #f3f1ec; transition: background 0.2s;
}
.db-prayer-item:hover { background: #fdfcfa; }
.db-prayer-item:last-child { border-bottom: none; }
.db-prayer-icon { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
.db-prayer-info { flex: 1; min-width: 0; }
.db-prayer-title { font-size: 13px; font-weight: 600; color: var(--text-dark); }
.db-prayer-text { font-size: 12px; color: var(--text-mid); margin-top: 2px; line-height: 1.5; }
.db-prayer-stat { font-size: 11px; color: var(--gold-dark); margin-top: 4px; font-weight: 600; }

/* ============ BIBLE CARD ============ */
.db-bible-card { padding: 22px 20px; background: linear-gradient(135deg, #2c2417, #1e1a14); }
.db-bible-icon { font-size: 22px; color: var(--gold); margin-bottom: 10px; }
.db-bible-title { font-size: 14px; font-weight: 700; color: rgba(255,255,255,0.9); margin-bottom: 10px; }
.db-bible-verse { font-size: 12.5px; font-style: italic; color: rgba(255,255,255,0.7); line-height: 1.6; margin-bottom: 10px; }
.db-bible-ref { font-size: 12px; font-weight: 700; color: var(--gold); }

/* ============ CTA PRAY ============ */
.db-cta-pray {
    background: linear-gradient(135deg, #2c2417 0%, #3d3225 50%, var(--sidebar-bg) 100%);
    border-radius: 12px;
    padding: 24px 32px;
    display: flex;
    align-items: center;
    gap: 20px;
    position: relative;
    overflow: hidden;
}
.db-cta-pray::after {
    content: '';
    position: absolute;
    right: 200px;
    top: -50%;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(212,175,55,0.12), transparent 70%);
    border-radius: 50%;
}
.db-cta-pray-icon { width: 48px; height: 48px; background: rgba(212,175,55,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--gold); flex-shrink: 0; }
.db-cta-pay-text { flex: 1; }
.db-cta-pray-title { font-size: 17px; font-weight: 700; color: #fff; }
.db-cta-pray-sub { font-size: 13px; color: rgba(255,255,255,0.6); margin-top: 2px; }
.db-cta-pray-btn { display: inline-flex; align-items: center; padding: 11px 22px; background: var(--gold); color: var(--sidebar-bg); border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; transition: all 0.2s; white-space: nowrap; flex-shrink: 0; }
.db-cta-pray-btn:hover { background: var(--gold-light); color: var(--sidebar-bg); }

/* ============ RESPONSIVE ============ */
@media (max-width: 1280px) {
    .db-info-cards { grid-template-columns: repeat(2, 1fr); }
    .db-content-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 900px) {
    .db-content-grid { grid-template-columns: 1fr; }
    .db-info-cards { grid-template-columns: 1fr 1fr; }
    .db-welcome { flex-direction: column; }
}
@media (max-width: 560px) {
    .db-info-cards { grid-template-columns: 1fr; }
}
</style>


{{-- ===== MODAL POPUP ABSENSI ===== --}}
<div id="absensiModal" class="abs-modal-overlay" onclick="closeAbsensiModal(event)">
    <div class="abs-modal-box">
        {{-- Header --}}
        <div class="abs-modal-header">
            <div class="abs-modal-header-icon"><i class="fas fa-clipboard-check"></i></div>
            <div>
                <h3 class="abs-modal-title" id="absModalTitle">Catat Absensi</h3>
                <p class="abs-modal-subtitle" id="absModalSubtitle">Jadwal Ibadah</p>
            </div>
            <button class="abs-modal-close" onclick="closeAbsensiModal()"><i class="fas fa-times"></i></button>
        </div>

        {{-- Info jadwal --}}
        <div class="abs-modal-schedule-info" id="absModalInfo"></div>

        {{-- Status sudah absen --}}
        <div id="absAlreadyDone" class="abs-already-card" style="display:none;">
            <div class="abs-already-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="abs-already-title">Absensi Sudah Dikirim</div>
                <div class="abs-already-sub" id="absAlreadyStatus"></div>
            </div>
        </div>

        {{-- Form absensi --}}
        <form id="absForm" method="POST" action="{{ route('absensi.store') }}">
            @csrf
            <input type="hidden" name="schedule_id" id="absScheduleId">
            <input type="hidden" name="jenis_ibadah" id="absJenisIbadah">

            <div class="abs-form-body" id="absFormBody">
                {{-- Tanggal --}}
                <div class="abs-field">
                    <label class="abs-label"><i class="fas fa-calendar-day"></i> Tanggal Ibadah</label>
                    <input type="date" name="tanggal" id="absTanggal" class="abs-input"
                           max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                </div>

                {{-- Status kehadiran --}}
                <div class="abs-field">
                    <label class="abs-label"><i class="fas fa-user-check"></i> Status Kehadiran</label>
                    <div class="abs-status-grid">
                        <label class="abs-status-option">
                            <input type="radio" name="status" value="hadir" required>
                            <div class="abs-status-card abs-status-card--green">
                                <i class="fas fa-check-circle"></i>
                                <span>Hadir</span>
                            </div>
                        </label>
                        <label class="abs-status-option">
                            <input type="radio" name="status" value="izin">
                            <div class="abs-status-card abs-status-card--gold">
                                <i class="fas fa-comment-alt"></i>
                                <span>Izin</span>
                            </div>
                        </label>
                        <label class="abs-status-option">
                            <input type="radio" name="status" value="tidak_hadir">
                            <div class="abs-status-card abs-status-card--red">
                                <i class="fas fa-times-circle"></i>
                                <span>Tidak Hadir</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Alasan izin --}}
                <div class="abs-field" id="absAlasanWrap" style="display:none;">
                    <label class="abs-label"><i class="fas fa-pen"></i> Alasan Izin</label>
                    <textarea name="alasan_izin" id="absAlasan" class="abs-input abs-textarea"
                              placeholder="Tulis alasan izin Anda..." maxlength="500"></textarea>
                </div>

                {{-- Keterangan --}}
                <div class="abs-field">
                    <label class="abs-label"><i class="fas fa-sticky-note"></i> Keterangan <span style="color:var(--text-light);font-weight:400;">(opsional)</span></label>
                    <textarea name="keterangan" class="abs-input abs-textarea"
                              placeholder="Catatan tambahan..." maxlength="500" rows="2"></textarea>
                </div>
            </div>

            {{-- Notice pending --}}
            <div class="abs-pending-notice">
                <i class="fas fa-info-circle"></i>
                Absensi akan menunggu <strong>persetujuan admin</strong> sebelum tercatat resmi.
            </div>

            {{-- Actions --}}
            <div class="abs-modal-actions" id="absFormActions">
                <button type="button" class="abs-btn abs-btn--cancel" onclick="closeAbsensiModal()">Batal</button>
                <button type="submit" class="abs-btn abs-btn--submit">
                    <i class="fas fa-paper-plane"></i> Kirim Absensi
                </button>
            </div>
        </form>

        {{-- Already-done close button --}}
        <div class="abs-modal-actions" id="absAlreadyActions" style="display:none;">
            <a href="{{ route('absensi.index') }}" class="abs-btn abs-btn--submit">
                <i class="fas fa-list"></i> Lihat Riwayat Absensi
            </a>
            <button type="button" class="abs-btn abs-btn--cancel" onclick="closeAbsensiModal()">Tutup</button>
        </div>
    </div>
</div>

@if(session('success'))
<div class="abs-toast abs-toast--success" id="absToast">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
<script>setTimeout(() => { const t = document.getElementById('absToast'); if(t) t.classList.add('abs-toast--hide'); }, 4000);</script>
@endif

@if(session('error'))
<div class="abs-toast abs-toast--error" id="absToast">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
<script>setTimeout(() => { const t = document.getElementById('absToast'); if(t) t.classList.add('abs-toast--hide'); }, 5000);</script>
@endif

{{-- ===== STYLES MODAL ===== --}}
<style>
/* Clickable schedule item */
.db-schedule-item--clickable { cursor: pointer; }
.db-schedule-item--clickable:hover { background: #fdf8ee !important; transform: translateX(2px); transition: all 0.18s; }

/* Modal overlay */
.abs-modal-overlay {
    display: none; position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,0.52); backdrop-filter: blur(3px);
    align-items: center; justify-content: center; padding: 16px;
}
.abs-modal-overlay.abs-open { display: flex; animation: absOverlayIn 0.2s ease; }
@keyframes absOverlayIn { from { opacity: 0; } to { opacity: 1; } }

/* Modal box */
.abs-modal-box {
    background: #fff; border-radius: 18px; width: 100%; max-width: 480px;
    box-shadow: 0 24px 60px rgba(0,0,0,0.22);
    animation: absBoxIn 0.25s cubic-bezier(0.34,1.56,0.64,1);
    max-height: 92vh; overflow-y: auto;
}
@keyframes absBoxIn { from { transform: scale(0.88) translateY(20px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }

/* Header */
.abs-modal-header {
    display: flex; align-items: center; gap: 14px;
    padding: 20px 22px; border-bottom: 1px solid #f0ebe0;
    background: linear-gradient(135deg, #fdfaf4, #fff);
    border-radius: 18px 18px 0 0;
}
.abs-modal-header-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark, #b8860b));
    color: #fff; display: flex; align-items: center; justify-content: center; font-size: 18px;
    flex-shrink: 0;
}
.abs-modal-title { font-size: 17px; font-weight: 700; color: var(--text-dark, #1a1a1a); margin: 0; }
.abs-modal-subtitle { font-size: 12px; color: var(--text-mid, #666); margin: 2px 0 0; }
.abs-modal-close {
    margin-left: auto; background: #f5f0e8; border: none; border-radius: 8px;
    width: 32px; height: 32px; cursor: pointer; color: #888; font-size: 14px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    transition: all 0.15s;
}
.abs-modal-close:hover { background: #fee2e2; color: #dc2626; }

/* Schedule info bar */
.abs-modal-schedule-info {
    display: flex; gap: 18px; padding: 14px 22px;
    background: #fdfaf4; border-bottom: 1px solid #f0ebe0; flex-wrap: wrap;
}
.abs-info-chip {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; color: var(--text-mid, #666);
}
.abs-info-chip i { color: var(--gold, #d4af37); font-size: 11px; }

/* Already done card */
.abs-already-card {
    margin: 16px 22px; padding: 16px; border-radius: 12px;
    background: #f0fdf4; border: 1px solid #bbf7d0;
    display: flex; align-items: center; gap: 14px;
}
.abs-already-icon { font-size: 28px; color: #16a34a; flex-shrink: 0; }
.abs-already-title { font-size: 14px; font-weight: 700; color: #15803d; }
.abs-already-sub { font-size: 12px; color: #16a34a; margin-top: 2px; }

/* Form body */
.abs-form-body { padding: 18px 22px 0; display: flex; flex-direction: column; gap: 16px; }
.abs-field { display: flex; flex-direction: column; gap: 7px; }
.abs-label { font-size: 12px; font-weight: 600; color: var(--text-dark, #1a1a1a); display: flex; align-items: center; gap: 6px; }
.abs-label i { color: var(--gold, #d4af37); }
.abs-input {
    border: 1.5px solid #e5e0d5; border-radius: 9px; padding: 10px 13px;
    font-size: 13.5px; color: var(--text-dark, #1a1a1a); outline: none;
    transition: border-color 0.15s; background: #fff; font-family: inherit; width: 100%; box-sizing: border-box;
}
.abs-input:focus { border-color: var(--gold, #d4af37); box-shadow: 0 0 0 3px rgba(212,175,55,0.12); }
.abs-textarea { resize: vertical; min-height: 72px; }

/* Status grid */
.abs-status-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
.abs-status-option { cursor: pointer; }
.abs-status-option input[type=radio] { display: none; }
.abs-status-card {
    border: 2px solid #e5e0d5; border-radius: 10px; padding: 11px 8px;
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 600; color: #888;
    transition: all 0.15s; text-align: center; background: #fafafa;
}
.abs-status-card i { font-size: 20px; }
.abs-status-card--green i { color: #22c55e; }
.abs-status-card--gold i { color: #f59e0b; }
.abs-status-card--red i { color: #ef4444; }
.abs-status-option input:checked + .abs-status-card--green {
    border-color: #22c55e; background: #f0fdf4; color: #15803d; box-shadow: 0 0 0 3px rgba(34,197,94,0.12);
}
.abs-status-option input:checked + .abs-status-card--gold {
    border-color: #f59e0b; background: #fffbeb; color: #92400e; box-shadow: 0 0 0 3px rgba(245,158,11,0.12);
}
.abs-status-option input:checked + .abs-status-card--red {
    border-color: #ef4444; background: #fef2f2; color: #991b1b; box-shadow: 0 0 0 3px rgba(239,68,68,0.12);
}

/* Pending notice */
.abs-pending-notice {
    margin: 16px 22px 0; padding: 10px 14px; background: #eff6ff;
    border: 1px solid #bfdbfe; border-radius: 9px;
    font-size: 12px; color: #1d4ed8; display: flex; align-items: center; gap: 8px; line-height: 1.5;
}
.abs-pending-notice i { flex-shrink: 0; }

/* Actions */
.abs-modal-actions {
    display: flex; gap: 10px; padding: 18px 22px;
    justify-content: flex-end;
}
.abs-btn {
    padding: 10px 20px; border-radius: 9px; font-size: 13px; font-weight: 600;
    cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 7px;
    transition: all 0.15s; text-decoration: none;
}
.abs-btn--cancel { background: #f5f0e8; color: #888; }
.abs-btn--cancel:hover { background: #ede8de; color: #555; }
.abs-btn--submit {
    background: linear-gradient(135deg, var(--gold, #d4af37), var(--gold-dark, #b8860b));
    color: #fff;
}
.abs-btn--submit:hover { opacity: 0.9; transform: translateY(-1px); color: #fff; }

/* Toast */
.abs-toast {
    position: fixed; bottom: 24px; right: 24px; z-index: 99999;
    padding: 13px 20px; border-radius: 10px;
    display: flex; align-items: center; gap: 10px;
    font-size: 13.5px; font-weight: 600;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    animation: absToastIn 0.3s ease;
    transition: opacity 0.4s, transform 0.4s;
}
.abs-toast--success { background: #16a34a; color: #fff; }
.abs-toast--error { background: #dc2626; color: #fff; }
.abs-toast--hide { opacity: 0; transform: translateY(20px); }
@keyframes absToastIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

{{-- ===== JAVASCRIPT MODAL ===== --}}
<script>
function openAbsensiPopup(scheduleId, title, day, time, location, sudahAbsen, approvalStatus) {
    // Set header info
    document.getElementById('absModalTitle').textContent = 'Absensi: ' + title;
    document.getElementById('absModalSubtitle').textContent = day + (time ? ' · ' + time : '');

    // Set schedule info bar
    document.getElementById('absModalInfo').innerHTML =
        '<div class="abs-info-chip"><i class="fas fa-calendar"></i> ' + (day || 'Jadwal Reguler') + '</div>' +
        (time ? '<div class="abs-info-chip"><i class="fas fa-clock"></i> ' + time + '</div>' : '') +
        '<div class="abs-info-chip"><i class="fas fa-map-marker-alt"></i> ' + location + '</div>';

    // Set hidden fields
    document.getElementById('absScheduleId').value = scheduleId;
    document.getElementById('absJenisIbadah').value = title;

    // Toggle form vs already-done
    var formBody = document.getElementById('absFormBody');
    var formActions = document.getElementById('absFormActions');
    var alreadyDone = document.getElementById('absAlreadyDone');
    var alreadyActions = document.getElementById('absAlreadyActions');
    var pendingNotice = document.querySelector('.abs-pending-notice');

    if (sudahAbsen) {
        formBody.style.display = 'none';
        formActions.style.display = 'none';
        if (pendingNotice) pendingNotice.style.display = 'none';
        alreadyDone.style.display = 'flex';
        alreadyActions.style.display = 'flex';
        var statusMap = { pending: '⏳ Menunggu persetujuan admin', approved: '✅ Sudah disetujui admin', rejected: '❌ Ditolak oleh admin' };
        document.getElementById('absAlreadyStatus').textContent = statusMap[approvalStatus] || 'Status tidak diketahui';

        // Change background color based on status
        var card = document.getElementById('absAlreadyDone');
        if (approvalStatus === 'rejected') {
            card.style.background = '#fef2f2'; card.style.borderColor = '#fecaca';
            card.querySelector('.abs-already-icon').style.color = '#dc2626';
            card.querySelector('.abs-already-title').style.color = '#991b1b';
            card.querySelector('.abs-already-sub').style.color = '#dc2626';
        } else if (approvalStatus === 'pending') {
            card.style.background = '#fffbeb'; card.style.borderColor = '#fde68a';
            card.querySelector('.abs-already-icon').innerHTML = '<i class="fas fa-hourglass-half"></i>';
            card.querySelector('.abs-already-icon').style.color = '#d97706';
            card.querySelector('.abs-already-title').style.color = '#92400e';
            card.querySelector('.abs-already-sub').style.color = '#d97706';
        }
    } else {
        formBody.style.display = 'flex';
        formActions.style.display = 'flex';
        if (pendingNotice) pendingNotice.style.display = 'flex';
        alreadyDone.style.display = 'none';
        alreadyActions.style.display = 'none';

        // Reset form
        document.getElementById('absForm').reset();
        document.getElementById('absTanggal').value = new Date().toISOString().split('T')[0];
        document.getElementById('absAlasanWrap').style.display = 'none';
        document.getElementById('absScheduleId').value = scheduleId;
        document.getElementById('absJenisIbadah').value = title;
    }

    document.getElementById('absensiModal').classList.add('abs-open');
    document.body.style.overflow = 'hidden';
}

function closeAbsensiModal(event) {
    if (event && event.target !== document.getElementById('absensiModal')) return;
    document.getElementById('absensiModal').classList.remove('abs-open');
    document.body.style.overflow = '';
}

// Show/hide alasan izin field
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name="status"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var wrap = document.getElementById('absAlasanWrap');
            wrap.style.display = this.value === 'izin' ? 'flex' : 'none';
            if (this.value !== 'izin') {
                document.getElementById('absAlasan').value = '';
            }
        });
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeAbsensiModal();
    });
});
</script>

@endsection