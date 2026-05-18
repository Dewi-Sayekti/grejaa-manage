@extends('layouts.dashboard')
@section('title', $news->title)
@section('page-title', 'Detail Acara')

@section('content')
<div class="acara-wrap">

    {{-- Hero --}}
    <div class="acara-hero" style="background: linear-gradient(135deg, {{ $news->gradient_from }}, {{ $news->gradient_to }});">
        @if($news->image_path)
        <img src="{{ asset($news->image_path) }}" alt="{{ $news->title }}" class="acara-hero-img">
        @endif
        <div class="acara-hero-overlay">
            <div class="acara-hero-badge"><i class="fas fa-calendar-star"></i> Acara</div>
            <h1 class="acara-hero-title">{{ $news->title }}</h1>
            <p class="acara-hero-excerpt">{{ $news->excerpt }}</p>
        </div>
    </div>

    <div class="acara-grid">

        {{-- Konten --}}
        <div class="acara-main">
            {{-- Info strip --}}
            <div class="acara-info-strip">
                @if($news->tanggal_acara)
                <div class="acara-info-chip">
                    <i class="fas fa-calendar-day"></i>
                    <div>
                        <div class="acara-info-chip-label">Tanggal</div>
                        <div class="acara-info-chip-val">{{ $news->tanggal_acara->translatedFormat('l, d F Y') }}</div>
                    </div>
                </div>
                <div class="acara-info-chip">
                    <i class="fas fa-clock"></i>
                    <div>
                        <div class="acara-info-chip-label">Waktu</div>
                        <div class="acara-info-chip-val">
                            {{ $news->tanggal_acara->format('H:i') }} WIB
                            @if($news->tanggal_acara_selesai)
                                – {{ $news->tanggal_acara_selesai->format('H:i') }} WIB
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @if($news->lokasi_acara)
                <div class="acara-info-chip">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <div class="acara-info-chip-label">Lokasi</div>
                        <div class="acara-info-chip-val">{{ $news->lokasi_acara }}</div>
                    </div>
                </div>
                @endif
                @if($news->kuota)
                <div class="acara-info-chip">
                    <i class="fas fa-users"></i>
                    <div>
                        <div class="acara-info-chip-label">Kuota</div>
                        <div class="acara-info-chip-val">{{ $totalPeserta }}/{{ $news->kuota }} peserta</div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Deskripsi --}}
            @if($news->content)
            <div class="acara-panel">
                <div class="acara-panel-title"><i class="fas fa-align-left"></i> Detail Acara</div>
                <div class="acara-content">{!! nl2br(e($news->content)) !!}</div>
            </div>
            @endif
        </div>

        {{-- Sidebar pendaftaran --}}
        <div class="acara-sidebar">

            {{-- Notif --}}
            @if(session('success'))
            <div class="acara-alert acara-alert--success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="acara-alert acara-alert--error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif

            {{-- Sudah daftar --}}
            @if($sudahDaftar)
            <div class="acara-registered-card">
                <div class="acara-registered-icon"><i class="fas fa-check-circle"></i></div>
                <div class="acara-registered-title">Kamu Sudah Terdaftar!</div>
                <div class="acara-registered-sub">Tunggu konfirmasi dari admin gereja. Cek email kamu untuk kode registrasi.</div>
                <form action="{{ route('acara.cancel', Auth::user()->registrasis->where('news_id', $news->id)->whereIn('status',['pending'])->first()?->id ?? 0) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="acara-cancel-btn" onclick="return confirm('Batalkan pendaftaran?')">
                        <i class="fas fa-times"></i> Batalkan Pendaftaran
                    </button>
                </form>
            </div>

            {{-- Pendaftaran tertutup --}}
            @elseif(!$news->buka_pendaftaran)
            <div class="acara-closed-card">
                <i class="fas fa-lock" style="font-size:28px;color:#94a3b8;margin-bottom:10px;"></i>
                <div style="font-weight:700;color:var(--text-dark);">Pendaftaran Belum Dibuka</div>
                <div style="font-size:12px;color:var(--text-mid);margin-top:6px;">Admin belum membuka pendaftaran untuk acara ini.</div>
            </div>

            @elseif($news->batas_pendaftaran && now()->isAfter($news->batas_pendaftaran))
            <div class="acara-closed-card">
                <i class="fas fa-calendar-times" style="font-size:28px;color:#94a3b8;margin-bottom:10px;"></i>
                <div style="font-weight:700;color:var(--text-dark);">Pendaftaran Sudah Ditutup</div>
                <div style="font-size:12px;color:var(--text-mid);margin-top:6px;">Batas pendaftaran: {{ $news->batas_pendaftaran->format('d M Y, H:i') }} WIB</div>
            </div>

            @elseif(!is_null($news->kuota) && $news->sisa_kuota <= 0)
            <div class="acara-closed-card">
                <i class="fas fa-users-slash" style="font-size:28px;color:#94a3b8;margin-bottom:10px;"></i>
                <div style="font-weight:700;color:var(--text-dark);">Kuota Penuh</div>
                <div style="font-size:12px;color:var(--text-mid);margin-top:6px;">Semua tempat sudah terisi.</div>
            </div>

            {{-- Form daftar --}}
            @else
            <div class="acara-form-card">
                <div class="acara-form-title"><i class="fas fa-user-plus"></i> Daftar Sekarang</div>
                @if($news->batas_pendaftaran)
                <div class="acara-deadline">
                    <i class="fas fa-hourglass-half"></i>
                    Batas daftar: <strong>{{ $news->batas_pendaftaran->format('d M Y, H:i') }}</strong>
                </div>
                @endif
                @if(!is_null($news->sisa_kuota))
                <div class="acara-kuota-bar">
                    <div class="acara-kuota-info">
                        <span>Sisa kuota</span>
                        <strong>{{ $news->sisa_kuota }} tempat</strong>
                    </div>
                    @php $persen = $news->kuota > 0 ? round(($totalPeserta / $news->kuota) * 100) : 0; @endphp
                    <div class="acara-kuota-track">
                        <div class="acara-kuota-fill" style="width:{{ $persen }}%"></div>
                    </div>
                </div>
                @endif

                @guest
                <div class="acara-login-notice">
                    <i class="fas fa-info-circle"></i>
                    <a href="{{ route('login') }}">Login</a> untuk mendaftar lebih mudah, atau isi form di bawah.
                </div>
                @endguest

                <form action="{{ route('acara.daftar', $news) }}" method="POST" class="acara-form">
                    @csrf
                    <div class="acara-field">
                        <label class="acara-label">Nama Lengkap <span class="req">*</span></label>
                        <input type="text" name="nama_lengkap" class="acara-input"
                               value="{{ old('nama_lengkap', Auth::user()?->name) }}"
                               placeholder="Nama lengkap kamu" required>
                    </div>
                    <div class="acara-field">
                        <label class="acara-label">Email <span class="req">*</span></label>
                        <input type="email" name="email" class="acara-input"
                               value="{{ old('email', Auth::user()?->email) }}"
                               placeholder="email@contoh.com" required>
                    </div>
                    <div class="acara-field">
                        <label class="acara-label">No. HP <span style="color:var(--text-light);font-weight:400;">(opsional)</span></label>
                        <input type="text" name="nomor_hp" class="acara-input" placeholder="08xxxxxxxxxx"
                               value="{{ old('nomor_hp') }}">
                    </div>
                    <div class="acara-field">
                        <label class="acara-label">Jumlah Peserta <span class="req">*</span></label>
                        <select name="jumlah_peserta" class="acara-input">
                            @for($i=1; $i<=min(10, $news->sisa_kuota ?? 10); $i++)
                            <option value="{{ $i }}" {{ old('jumlah_peserta',1)==$i?'selected':'' }}>{{ $i }} orang</option>
                            @endfor
                        </select>
                    </div>
                    <div class="acara-field">
                        <label class="acara-label">Catatan <span style="color:var(--text-light);font-weight:400;">(opsional)</span></label>
                        <textarea name="catatan" class="acara-input acara-textarea"
                                  placeholder="Pertanyaan atau catatan khusus...">{{ old('catatan') }}</textarea>
                    </div>
                    @guest
                    <button type="submit" class="acara-submit-btn">
                        <i class="fas fa-paper-plane"></i> Kirim Pendaftaran
                    </button>
                    @else
                    <button type="submit" class="acara-submit-btn">
                        <i class="fas fa-paper-plane"></i> Daftar Sekarang
                    </button>
                    @endguest
                </form>
            </div>
            @endif

        </div>
    </div>
</div>

<style>
.acara-wrap { max-width: 1000px; }
.acara-hero { border-radius: 16px; overflow: hidden; position: relative; min-height: 220px; display: flex; align-items: flex-end; margin-bottom: 24px; }
.acara-hero-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.35; }
.acara-hero-overlay { position: relative; z-index: 1; padding: 28px; }
.acara-hero-badge { display: inline-flex; align-items: center; gap: 6px; background: var(--gold); color: #fff; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: .5px; }
.acara-hero-title { font-family: 'Playfair Display', serif; font-size: 26px; font-weight: 800; color: var(--text-dark); margin: 0 0 8px; line-height: 1.3; }
.acara-hero-excerpt { font-size: 14px; color: var(--text-mid); margin: 0; line-height: 1.6; }

.acara-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start; }
@media(max-width: 768px) { .acara-grid { grid-template-columns: 1fr; } }

.acara-info-strip { display: flex; gap: 14px; flex-wrap: wrap; background: #fdfaf4; border: 1px solid #f0ebe0; border-radius: 12px; padding: 16px 18px; margin-bottom: 18px; }
.acara-info-chip { display: flex; align-items: flex-start; gap: 10px; }
.acara-info-chip > i { color: var(--gold); font-size: 16px; margin-top: 2px; flex-shrink: 0; }
.acara-info-chip-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; color: var(--text-light); }
.acara-info-chip-val { font-size: 13px; font-weight: 600; color: var(--text-dark); margin-top: 2px; }

.acara-panel { background: #fff; border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; }
.acara-panel-title { padding: 14px 20px; font-size: 14px; font-weight: 700; color: var(--text-dark); border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 8px; }
.acara-panel-title i { color: var(--gold); }
.acara-content { padding: 18px 20px; font-size: 14px; color: var(--text-mid); line-height: 1.8; }

.acara-alert { padding: 12px 16px; border-radius: 9px; font-size: 13px; display: flex; align-items: center; gap: 8px; margin-bottom: 14px; }
.acara-alert--success { background: #d1fae5; color: #065f46; }
.acara-alert--error   { background: #fee2e2; color: #991b1b; }

.acara-registered-card { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px; text-align: center; }
.acara-registered-icon { font-size: 36px; color: #16a34a; margin-bottom: 10px; }
.acara-registered-title { font-size: 15px; font-weight: 700; color: #15803d; margin-bottom: 6px; }
.acara-registered-sub { font-size: 12px; color: #16a34a; line-height: 1.6; margin-bottom: 16px; }
.acara-cancel-btn { background: #fff; border: 1.5px solid #fecaca; color: #dc2626; padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }

.acara-closed-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 28px; text-align: center; }

.acara-form-card { background: #fff; border-radius: 14px; box-shadow: var(--shadow); overflow: hidden; }
.acara-form-title { padding: 16px 20px; font-size: 15px; font-weight: 700; color: var(--text-dark); border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 9px; font-family: 'Playfair Display', serif; }
.acara-form-title i { color: var(--gold); }
.acara-deadline { margin: 12px 20px 0; padding: 9px 13px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; font-size: 12px; color: #92400e; display: flex; align-items: center; gap: 7px; }
.acara-kuota-bar { margin: 12px 20px 0; }
.acara-kuota-info { display: flex; justify-content: space-between; font-size: 12px; color: var(--text-mid); margin-bottom: 6px; }
.acara-kuota-track { height: 6px; background: #f0ebe0; border-radius: 99px; overflow: hidden; }
.acara-kuota-fill { height: 100%; background: linear-gradient(90deg, var(--gold), var(--gold-dark)); border-radius: 99px; transition: width .3s; }
.acara-login-notice { margin: 12px 20px 0; padding: 9px 13px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; font-size: 12px; color: #1d4ed8; display: flex; align-items: center; gap: 7px; }
.acara-login-notice a { font-weight: 700; color: #2563eb; }

.acara-form { padding: 16px 20px; display: flex; flex-direction: column; gap: 14px; }
.acara-field { display: flex; flex-direction: column; gap: 5px; }
.acara-label { font-size: 11.5px; font-weight: 600; color: var(--text-dark); }
.req { color: #dc2626; }
.acara-input { border: 1.5px solid #e5e0d5; border-radius: 8px; padding: 9px 12px; font-size: 13px; outline: none; transition: border-color .15s; font-family: inherit; width: 100%; box-sizing: border-box; }
.acara-input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(212,175,55,.12); }
.acara-textarea { resize: vertical; min-height: 70px; }
.acara-submit-btn { width: 100%; padding: 13px; border: none; border-radius: 9px; background: linear-gradient(135deg, var(--gold), var(--gold-dark)); color: #fff; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: opacity .2s; }
.acara-submit-btn:hover { opacity: .9; }
</style>
@endsection