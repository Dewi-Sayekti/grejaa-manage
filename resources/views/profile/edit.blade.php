@extends('layouts.dashboard')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')

@php
    $jemaat = Auth::user()->jemaat;
    $initials = strtoupper(substr(Auth::user()->name, 0, 1));
    $activeTab = session('active_tab', old('active_tab', 'profil'));
@endphp

{{-- ===== PROFILE HERO ===== --}}
<div class="pf-hero">
    <div class="pf-hero-bg"></div>
    <div class="pf-hero-inner">
        <div class="pf-avatar-wrap">
            @if($jemaat && $jemaat->foto)
                <img src="{{ asset('storage/' . $jemaat->foto) }}" alt="Foto Profil" class="pf-avatar-img">
            @else
                <div class="pf-avatar-init">{{ $initials }}</div>
            @endif
            <label for="fotoInput" class="pf-avatar-edit" title="Ganti foto">
                <i class="fas fa-camera"></i>
            </label>
        </div>
        <div class="pf-hero-info">
            <h2 class="pf-hero-name">{{ Auth::user()->name }}</h2>
            <div class="pf-hero-email">{{ Auth::user()->email }}</div>
            <div class="pf-hero-meta">
                <span class="pf-badge pf-badge--role">
                    <i class="fas fa-shield-alt"></i>
                    {{ ucfirst(Auth::user()->role ?? 'Jemaat') }}
                </span>
                @if($jemaat)
                <span class="pf-badge pf-badge--status">
                    <i class="fas fa-circle"></i>
                    {{ $jemaat->status_aktif ?? 'Aktif' }}
                </span>
                @endif
                @if($jemaat && $jemaat->tanggal_baptis)
                <span class="pf-badge pf-badge--info">
                    <i class="fas fa-water"></i>
                    Baptis {{ $jemaat->tanggal_baptis->format('d M Y') }}
                </span>
                @endif
            </div>
        </div>
        <div class="pf-hero-stats">
            <div class="pf-hero-stat">
                <div class="pf-hero-stat-val">{{ \App\Models\Absensi::where('jemaat_id', optional($jemaat)->id)->where('approval_status','approved')->count() }}</div>
                <div class="pf-hero-stat-lbl">Total Hadir</div>
            </div>
            <div class="pf-hero-stat-div"></div>
            <div class="pf-hero-stat">
                <div class="pf-hero-stat-val">{{ \App\Models\Absensi::where('jemaat_id', optional($jemaat)->id)->where('approval_status','pending')->count() }}</div>
                <div class="pf-hero-stat-lbl">Pending</div>
            </div>
            <div class="pf-hero-stat-div"></div>
            <div class="pf-hero-stat">
                <div class="pf-hero-stat-val">{{ Auth::user()->created_at->format('Y') }}</div>
                <div class="pf-hero-stat-lbl">Bergabung</div>
            </div>
        </div>
    </div>
</div>

{{-- ===== ALERTS ===== --}}
@if(session('success') || session('status') === 'profile-updated' || session('status') === 'password-updated')
<div class="pf-alert pf-alert--success">
    <i class="fas fa-check-circle"></i>
    {{ session('success') ?? (session('status') === 'password-updated' ? 'Password berhasil diperbarui.' : 'Profil berhasil disimpan.') }}
</div>
@endif

@if($errors->any())
<div class="pf-alert pf-alert--error">
    <i class="fas fa-exclamation-circle"></i>
    <div>
        @foreach($errors->all() as $err)
            <div>{{ $err }}</div>
        @endforeach
    </div>
</div>
@endif

{{-- ===== TABS ===== --}}
<div class="pf-tabs" id="pfTabs">
    <button class="pf-tab {{ $activeTab === 'profil' ? 'active' : '' }}" onclick="switchTab('profil')">
        <i class="fas fa-user"></i> Akun
    </button>
    <button class="pf-tab {{ $activeTab === 'jemaat' ? 'active' : '' }}" onclick="switchTab('jemaat')">
        <i class="fas fa-id-card"></i> Data Jemaat
    </button>
    <button class="pf-tab {{ $activeTab === 'password' ? 'active' : '' }}" onclick="switchTab('password')">
        <i class="fas fa-lock"></i> Password
    </button>
</div>

{{-- ===== TAB: AKUN ===== --}}
<div class="pf-panel" id="tab-profil" style="{{ $activeTab !== 'profil' ? 'display:none;' : '' }}">
    <div class="pf-panel-header">
        <div>
            <div class="pf-panel-title">Informasi Akun</div>
            <div class="pf-panel-sub">Perbarui nama dan email akun Anda</div>
        </div>
    </div>
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="pf-form">
        @csrf
        @method('PATCH')
        <input type="hidden" name="active_tab" value="profil">
        <input type="file" id="fotoInput" name="foto" accept="image/*" style="display:none;" onchange="previewFoto(this)">

        <div class="pf-form-grid">
            <div class="pf-form-group pf-span-2">
                <label class="pf-label">Nama Lengkap <span class="pf-req">*</span></label>
                <div class="pf-input-wrap">
                    <i class="fas fa-user pf-input-icon"></i>
                    <input type="text" name="name" class="pf-input pf-input--icon" value="{{ old('name', Auth::user()->name) }}" required placeholder="Nama lengkap Anda">
                </div>
                @error('name')<div class="pf-error">{{ $message }}</div>@enderror
            </div>

            <div class="pf-form-group pf-span-2">
                <label class="pf-label">Alamat Email <span class="pf-req">*</span></label>
                <div class="pf-input-wrap">
                    <i class="fas fa-envelope pf-input-icon"></i>
                    <input type="email" name="email" class="pf-input pf-input--icon" value="{{ old('email', Auth::user()->email) }}" required placeholder="email@contoh.com">
                </div>
                @error('email')<div class="pf-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="pf-form-footer">
            <button type="submit" class="pf-btn-save">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

{{-- ===== TAB: DATA JEMAAT ===== --}}
<div class="pf-panel" id="tab-jemaat" style="{{ $activeTab !== 'jemaat' ? 'display:none;' : '' }}">
    <div class="pf-panel-header">
        <div>
            <div class="pf-panel-title">Data Jemaat</div>
            <div class="pf-panel-sub">Informasi keanggotaan dan data pribadi jemaat</div>
        </div>
    </div>

    @if($jemaat)
    {{-- READ VIEW --}}
    <div id="jemaatReadView">
        <div class="pf-data-grid">
            <div class="pf-data-section">
                <div class="pf-data-section-title"><i class="fas fa-user-circle"></i> Data Pribadi</div>
                <div class="pf-data-rows">
                    <div class="pf-data-row">
                        <div class="pf-data-label">Nama Lengkap</div>
                        <div class="pf-data-value">{{ $jemaat->nama_lengkap ?? '-' }}</div>
                    </div>
                    <div class="pf-data-row">
                        <div class="pf-data-label">Jenis Kelamin</div>
                        <div class="pf-data-value">{{ $jemaat->jenis_kelamin ?? '-' }}</div>
                    </div>
                    <div class="pf-data-row">
                        <div class="pf-data-label">Tempat Lahir</div>
                        <div class="pf-data-value">{{ $jemaat->tempat_lahir ?? '-' }}</div>
                    </div>
                    <div class="pf-data-row">
                        <div class="pf-data-label">Tanggal Lahir</div>
                        <div class="pf-data-value">
                            {{ $jemaat->tanggal_lahir ? $jemaat->tanggal_lahir->format('d F Y') : '-' }}
                            @if($jemaat->tanggal_lahir)
                                <span class="pf-data-note">({{ $jemaat->tanggal_lahir->age }} tahun)</span>
                            @endif
                        </div>
                    </div>
                    <div class="pf-data-row">
                        <div class="pf-data-label">Status Pernikahan</div>
                        <div class="pf-data-value">{{ $jemaat->status_pernikahan ?? '-' }}</div>
                    </div>
                    <div class="pf-data-row">
                        <div class="pf-data-label">Golongan Darah</div>
                        <div class="pf-data-value">{{ $jemaat->golongan_darah ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="pf-data-section">
                <div class="pf-data-section-title"><i class="fas fa-phone-alt"></i> Kontak & Alamat</div>
                <div class="pf-data-rows">
                    <div class="pf-data-row">
                        <div class="pf-data-label">Nomor HP</div>
                        <div class="pf-data-value">
                            @if($jemaat->nomor_hp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$jemaat->nomor_hp) }}" target="_blank" class="pf-link">
                                    <i class="fab fa-whatsapp" style="color:#25d366;"></i> {{ $jemaat->nomor_hp }}
                                </a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="pf-data-row">
                        <div class="pf-data-label">Alamat</div>
                        <div class="pf-data-value">{{ $jemaat->alamat ?? '-' }}</div>
                    </div>
                </div>

                <div class="pf-data-section-title" style="margin-top:24px;"><i class="fas fa-church"></i> Data Gerejawi</div>
                <div class="pf-data-rows">
                    <div class="pf-data-row">
                        <div class="pf-data-label">Tanggal Baptis</div>
                        <div class="pf-data-value">{{ $jemaat->tanggal_baptis ? $jemaat->tanggal_baptis->format('d F Y') : '-' }}</div>
                    </div>
                    <div class="pf-data-row">
                        <div class="pf-data-label">Status Keanggotaan</div>
                        <div class="pf-data-value">
                            <span class="pf-status-badge pf-status--{{ strtolower($jemaat->status_aktif ?? 'aktif') === 'aktif' ? 'aktif' : 'nonaktif' }}">
                                <i class="fas fa-circle"></i>
                                {{ $jemaat->status_aktif ?? 'Aktif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pf-form-footer" style="border-top:1px solid var(--border);">
            <button type="button" onclick="showJemaatEdit()" class="pf-btn-save">
                <i class="fas fa-edit"></i> Edit Data Jemaat
            </button>
        </div>
    </div>

    {{-- EDIT FORM --}}
    <div id="jemaatEditView" style="display:none;">
        <form method="POST" action="{{ route('profile.update') }}" class="pf-form">
            @csrf
            @method('PATCH')
            <input type="hidden" name="active_tab" value="jemaat">
            <input type="hidden" name="update_jemaat" value="1">

            <div class="pf-form-grid">
                <div class="pf-form-group pf-span-2">
                    <label class="pf-label">Nama Lengkap <span class="pf-req">*</span></label>
                    <div class="pf-input-wrap">
                        <i class="fas fa-user pf-input-icon"></i>
                        <input type="text" name="nama_lengkap" class="pf-input pf-input--icon" value="{{ old('nama_lengkap', $jemaat->nama_lengkap) }}" required>
                    </div>
                </div>

                <div class="pf-form-group">
                    <label class="pf-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="pf-input">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $jemaat->jenis_kelamin) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $jemaat->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="pf-form-group">
                    <label class="pf-label">Status Pernikahan</label>
                    <select name="status_pernikahan" class="pf-input">
                        <option value="">-- Pilih --</option>
                        @foreach(['Belum Menikah','Menikah','Janda','Duda'] as $s)
                            <option value="{{ $s }}" {{ old('status_pernikahan', $jemaat->status_pernikahan) === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pf-form-group">
                    <label class="pf-label">Tempat Lahir</label>
                    <div class="pf-input-wrap">
                        <i class="fas fa-map-marker-alt pf-input-icon"></i>
                        <input type="text" name="tempat_lahir" class="pf-input pf-input--icon" value="{{ old('tempat_lahir', $jemaat->tempat_lahir) }}" placeholder="Kota/kabupaten">
                    </div>
                </div>

                <div class="pf-form-group">
                    <label class="pf-label">Tanggal Lahir</label>
                    <div class="pf-input-wrap">
                        <i class="fas fa-birthday-cake pf-input-icon"></i>
                        <input type="date" name="tanggal_lahir" class="pf-input pf-input--icon" value="{{ old('tanggal_lahir', optional($jemaat->tanggal_lahir)->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="pf-form-group">
                    <label class="pf-label">Nomor HP / WhatsApp</label>
                    <div class="pf-input-wrap">
                        <i class="fas fa-phone pf-input-icon"></i>
                        <input type="text" name="nomor_hp" class="pf-input pf-input--icon" value="{{ old('nomor_hp', $jemaat->nomor_hp) }}" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div class="pf-form-group">
                    <label class="pf-label">Golongan Darah</label>
                    <div class="pf-input-wrap">
                        <i class="fas fa-tint pf-input-icon"></i>
                        <input type="text" name="golongan_darah" class="pf-input pf-input--icon" value="{{ old('golongan_darah', $jemaat->golongan_darah) }}" placeholder="A, B, AB, O" maxlength="5">
                    </div>
                </div>

                <div class="pf-form-group pf-span-2">
                    <label class="pf-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="pf-input pf-textarea" rows="3" placeholder="Jalan, nomor, RT/RW, kelurahan, kecamatan, kota...">{{ old('alamat', $jemaat->alamat) }}</textarea>
                </div>

                <div class="pf-form-group">
                    <label class="pf-label">Tanggal Baptis</label>
                    <div class="pf-input-wrap">
                        <i class="fas fa-water pf-input-icon"></i>
                        <input type="date" name="tanggal_baptis" class="pf-input pf-input--icon" value="{{ old('tanggal_baptis', optional($jemaat->tanggal_baptis)->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>

            <div class="pf-form-footer" style="border-top:1px solid var(--border);">
                <button type="button" onclick="hideJemaatEdit()" class="pf-btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="pf-btn-save">
                    <i class="fas fa-save"></i> Simpan Data Jemaat
                </button>
            </div>
        </form>
    </div>

    @else
    {{-- NO JEMAAT DATA --}}
    <div class="pf-empty-jemaat">
        <div class="pf-empty-icon"><i class="fas fa-id-card"></i></div>
        <h3>Data Jemaat Belum Tersedia</h3>
        <p>Data keanggotaan Anda belum terdaftar. Hubungi admin gereja untuk mendaftarkan data jemaat Anda.</p>
        <a href="{{ route('welcome') }}" class="pf-btn-save" style="display:inline-flex;margin-top:16px;">
            <i class="fas fa-phone"></i> Hubungi Admin
        </a>
    </div>
    @endif
</div>

{{-- ===== TAB: PASSWORD ===== --}}
<div class="pf-panel" id="tab-password" style="{{ $activeTab !== 'password' ? 'display:none;' : '' }}">
    <div class="pf-panel-header">
        <div>
            <div class="pf-panel-title">Ganti Password</div>
            <div class="pf-panel-sub">Gunakan password yang kuat dan unik untuk keamanan akun</div>
        </div>
    </div>
    <form method="POST" action="{{ route('profile.update') }}" class="pf-form" id="passwordForm">
        @csrf
        @method('PATCH')
        <input type="hidden" name="active_tab" value="password">
        <input type="hidden" name="update_password" value="1">

        <div class="pf-form-grid" style="max-width:520px;">
            <div class="pf-form-group pf-span-2">
                <label class="pf-label">Password Saat Ini <span class="pf-req">*</span></label>
                <div class="pf-input-wrap">
                    <i class="fas fa-lock pf-input-icon"></i>
                    <input type="password" name="current_password" id="cur_pw" class="pf-input pf-input--icon pf-input--toggle" placeholder="••••••••" required autocomplete="current-password">
                    <button type="button" class="pf-pw-toggle" onclick="togglePw('cur_pw', this)"><i class="fas fa-eye"></i></button>
                </div>
                @error('current_password', 'updatePassword')<div class="pf-error">{{ $message }}</div>@enderror
            </div>

            <div class="pf-form-group pf-span-2">
                <label class="pf-label">Password Baru <span class="pf-req">*</span></label>
                <div class="pf-input-wrap">
                    <i class="fas fa-key pf-input-icon"></i>
                    <input type="password" name="password" id="new_pw" class="pf-input pf-input--icon pf-input--toggle" placeholder="Min. 8 karakter" required autocomplete="new-password" oninput="checkStrength(this.value)">
                    <button type="button" class="pf-pw-toggle" onclick="togglePw('new_pw', this)"><i class="fas fa-eye"></i></button>
                </div>
                <div class="pf-pw-strength">
                    <div class="pf-pw-bar" id="pwBar"></div>
                </div>
                <div class="pf-pw-label" id="pwLabel"></div>
                @error('password', 'updatePassword')<div class="pf-error">{{ $message }}</div>@enderror
            </div>

            <div class="pf-form-group pf-span-2">
                <label class="pf-label">Konfirmasi Password Baru <span class="pf-req">*</span></label>
                <div class="pf-input-wrap">
                    <i class="fas fa-key pf-input-icon"></i>
                    <input type="password" name="password_confirmation" id="conf_pw" class="pf-input pf-input--icon pf-input--toggle" placeholder="Ulangi password baru" required autocomplete="new-password">
                    <button type="button" class="pf-pw-toggle" onclick="togglePw('conf_pw', this)"><i class="fas fa-eye"></i></button>
                </div>
            </div>
        </div>

        <div class="pf-pw-tips">
            <div class="pf-pw-tip"><i class="fas fa-check-circle" id="tip-len"></i> Minimal 8 karakter</div>
            <div class="pf-pw-tip"><i class="fas fa-check-circle" id="tip-upper"></i> Mengandung huruf besar</div>
            <div class="pf-pw-tip"><i class="fas fa-check-circle" id="tip-num"></i> Mengandung angka</div>
            <div class="pf-pw-tip"><i class="fas fa-check-circle" id="tip-sym"></i> Mengandung simbol</div>
        </div>

        <div class="pf-form-footer" style="border-top:1px solid var(--border);">
            <button type="submit" class="pf-btn-save">
                <i class="fas fa-shield-alt"></i> Perbarui Password
            </button>
        </div>
    </form>
</div>

{{-- ===== STYLES ===== --}}
<style>
/* ===== HERO ===== */
.pf-hero {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: var(--shadow-md);
}
.pf-hero-bg {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, #2c2417 0%, #3d3225 45%, #1e1a14 100%);
}
.pf-hero-bg::after {
    content: '';
    position: absolute; top: -40%; right: -5%;
    width: 360px; height: 360px;
    background: radial-gradient(circle, rgba(212,175,55,0.18) 0%, transparent 70%);
    border-radius: 50%;
}
.pf-hero-inner {
    position: relative; z-index: 2;
    display: flex; align-items: center; gap: 28px;
    padding: 32px 36px; flex-wrap: wrap;
}

.pf-avatar-wrap { position: relative; flex-shrink: 0; }
.pf-avatar-img, .pf-avatar-init {
    width: 88px; height: 88px; border-radius: 50%;
    border: 3px solid rgba(212,175,55,0.5);
    object-fit: cover;
}
.pf-avatar-init {
    background: var(--gold);
    color: #1e1a14;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Playfair Display', serif;
    font-size: 32px; font-weight: 800;
}
.pf-avatar-edit {
    position: absolute; bottom: 0; right: 0;
    width: 28px; height: 28px;
    background: var(--gold); color: #1e1a14;
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 11px; cursor: pointer; border: 2px solid #1e1a14;
    transition: transform 0.2s;
}
.pf-avatar-edit:hover { transform: scale(1.1); }

.pf-hero-info { flex: 1; min-width: 0; }
.pf-hero-name {
    font-family: 'Playfair Display', serif;
    font-size: 26px; font-weight: 800; color: #fff; margin-bottom: 4px;
}
.pf-hero-email { font-size: 14px; color: rgba(255,255,255,0.55); margin-bottom: 12px; }
.pf-hero-meta { display: flex; gap: 8px; flex-wrap: wrap; }
.pf-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 11px; border-radius: 20px;
    font-size: 11.5px; font-weight: 600;
}
.pf-badge--role   { background: rgba(212,175,55,0.2); color: var(--gold); border: 1px solid rgba(212,175,55,0.3); }
.pf-badge--status { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.25); }
.pf-badge--info   { background: rgba(59,130,246,0.15); color: #93c5fd; border: 1px solid rgba(59,130,246,0.25); }
.pf-badge--info .fas { font-size: 9px; }
.pf-badge--status .fas { font-size: 7px; }

.pf-hero-stats {
    display: flex; align-items: center; gap: 24px;
    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px; padding: 16px 24px; flex-shrink: 0;
}
.pf-hero-stat { text-align: center; }
.pf-hero-stat-val { font-size: 22px; font-weight: 800; color: var(--gold); font-family: 'Playfair Display', serif; }
.pf-hero-stat-lbl { font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 2px; text-transform: uppercase; letter-spacing: 0.5px; }
.pf-hero-stat-div { width: 1px; height: 36px; background: rgba(255,255,255,0.12); }

/* ===== ALERT ===== */
.pf-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 14px 18px; border-radius: 10px; margin-bottom: 18px;
    font-size: 13.5px; font-weight: 500;
}
.pf-alert--success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.pf-alert--error   { background: #fce4ec; color: #991b1b; border: 1px solid #fca5a5; }

/* ===== TABS ===== */
.pf-tabs {
    display: flex; gap: 4px;
    background: #fff; border-radius: 10px; padding: 6px;
    box-shadow: var(--shadow); margin-bottom: 20px;
}
.pf-tab {
    flex: 1; padding: 10px 16px;
    background: transparent; border: none; border-radius: 8px;
    font-size: 13px; font-weight: 600; color: var(--text-mid);
    cursor: pointer; transition: all 0.2s;
    display: flex; align-items: center; justify-content: center; gap: 7px;
}
.pf-tab:hover { background: #faf8f5; color: var(--text-dark); }
.pf-tab.active { background: var(--gold); color: #1e1a14; box-shadow: 0 2px 8px rgba(212,175,55,0.35); }

/* ===== PANEL ===== */
.pf-panel { background: #fff; border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; margin-bottom: 20px; }
.pf-panel-header { padding: 22px 28px; border-bottom: 1px solid var(--border); }
.pf-panel-title { font-family: 'Playfair Display', serif; font-size: 17px; font-weight: 700; color: var(--text-dark); }
.pf-panel-sub { font-size: 13px; color: var(--text-mid); margin-top: 3px; }

/* ===== FORM ===== */
.pf-form { padding: 24px 28px; }
.pf-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.pf-span-2 { grid-column: span 2; }
.pf-form-group { display: flex; flex-direction: column; gap: 6px; }
.pf-label { font-size: 12px; font-weight: 700; color: var(--text-mid); text-transform: uppercase; letter-spacing: 0.5px; }
.pf-req { color: var(--danger); }

.pf-input-wrap { position: relative; }
.pf-input-icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    font-size: 13px; color: var(--text-light); pointer-events: none;
}
.pf-input {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid var(--border); border-radius: 9px;
    font-size: 13.5px; color: var(--text-dark); background: #fff;
    font-family: inherit; transition: border-color 0.2s, box-shadow 0.2s; outline: none;
}
.pf-input--icon { padding-left: 38px; }
.pf-input--toggle { padding-right: 42px; }
.pf-input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(212,175,55,0.15); }
.pf-textarea { resize: vertical; min-height: 80px; }
.pf-mono { font-family: 'Courier New', monospace; letter-spacing: 1px; }

.pf-pw-toggle {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: var(--text-light); cursor: pointer;
    font-size: 14px; padding: 3px; transition: color 0.2s;
}
.pf-pw-toggle:hover { color: var(--gold); }

.pf-error { font-size: 12px; color: var(--danger); margin-top: 4px; }

.pf-form-footer {
    display: flex; justify-content: flex-end; align-items: center; gap: 10px;
    padding: 18px 28px; margin-top: 4px;
}
.pf-btn-save {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 11px 24px;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: #1e1a14; border: none; border-radius: 9px;
    font-size: 13.5px; font-weight: 700; cursor: pointer;
    transition: opacity 0.2s, transform 0.2s; text-decoration: none;
}
.pf-btn-save:hover { opacity: 0.9; transform: translateY(-1px); color: #1e1a14; }
.pf-btn-cancel {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 11px 20px;
    background: #f3f4f6; color: var(--text-mid);
    border: 1px solid var(--border); border-radius: 9px;
    font-size: 13.5px; font-weight: 600; cursor: pointer; transition: all 0.2s;
}
.pf-btn-cancel:hover { background: #e5e7eb; color: var(--text-dark); }

/* ===== DATA VIEW ===== */
.pf-data-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
.pf-data-section { padding: 24px 28px; border-right: 1px solid var(--border); }
.pf-data-section:last-child { border-right: none; }
.pf-data-section-title {
    display: flex; align-items: center; gap: 8px;
    font-size: 12px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.8px; color: var(--text-mid); margin-bottom: 14px;
}
.pf-data-section-title i { color: var(--gold); }
.pf-data-rows { display: flex; flex-direction: column; gap: 0; }
.pf-data-row {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 11px 0; border-bottom: 1px solid #f3f1ec;
}
.pf-data-row:last-child { border-bottom: none; }
.pf-data-label { font-size: 12px; color: var(--text-mid); font-weight: 500; width: 130px; flex-shrink: 0; padding-top: 1px; }
.pf-data-value { font-size: 13.5px; font-weight: 500; color: var(--text-dark); flex: 1; }
.pf-data-note { font-size: 11.5px; color: var(--text-light); margin-left: 6px; }
.pf-link { color: var(--gold-dark); text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
.pf-link:hover { color: var(--gold); }
.pf-status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;
}
.pf-status--aktif { background: #d1fae5; color: #065f46; }
.pf-status--aktif .fas { font-size: 7px; }
.pf-status--nonaktif { background: #fce4ec; color: #991b1b; }

/* ===== PASSWORD TIPS ===== */
.pf-pw-strength { height: 5px; background: #f3f4f6; border-radius: 3px; margin-top: 8px; overflow: hidden; }
.pf-pw-bar { height: 100%; width: 0; border-radius: 3px; transition: width 0.3s, background 0.3s; }
.pf-pw-label { font-size: 11.5px; font-weight: 600; margin-top: 4px; color: var(--text-light); }
.pf-pw-tips { display: flex; flex-wrap: wrap; gap: 10px; padding: 0 28px 4px; }
.pf-pw-tip { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text-light); }
.pf-pw-tip i { font-size: 13px; transition: color 0.2s; }
.pf-pw-tip i.met { color: var(--success); }

/* ===== EMPTY STATE ===== */
.pf-empty-jemaat { text-align: center; padding: 56px 24px; }
.pf-empty-icon { font-size: 52px; color: #ddd; margin-bottom: 16px; }
.pf-empty-jemaat h3 { font-family: 'Playfair Display', serif; font-size: 18px; color: var(--text-dark); margin-bottom: 8px; }
.pf-empty-jemaat p { font-size: 14px; color: var(--text-mid); max-width: 360px; margin: 0 auto; line-height: 1.6; }

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .pf-hero-inner { flex-direction: column; align-items: flex-start; padding: 24px 20px; }
    .pf-hero-stats { width: 100%; justify-content: center; }
    .pf-data-grid { grid-template-columns: 1fr; }
    .pf-data-section { border-right: none; border-bottom: 1px solid var(--border); }
    .pf-data-section:last-child { border-bottom: none; }
}
@media (max-width: 640px) {
    .pf-form-grid { grid-template-columns: 1fr; }
    .pf-span-2 { grid-column: span 1; }
    .pf-form, .pf-form-footer { padding-left: 16px; padding-right: 16px; }
    .pf-tabs { flex-direction: column; }
}
</style>

<script>
// Tab switch
function switchTab(tab) {
    ['profil','jemaat','password'].forEach(function(t) {
        document.getElementById('tab-' + t).style.display = (t === tab) ? 'block' : 'none';
        document.querySelectorAll('.pf-tab').forEach(function(btn, i) {
            btn.classList.toggle('active', ['profil','jemaat','password'][i] === tab);
        });
    });
}

// Jemaat edit toggle
function showJemaatEdit() {
    document.getElementById('jemaatReadView').style.display = 'none';
    document.getElementById('jemaatEditView').style.display = 'block';
}
function hideJemaatEdit() {
    document.getElementById('jemaatReadView').style.display = 'block';
    document.getElementById('jemaatEditView').style.display = 'none';
}

// Show jemaat tab if errors on jemaat fields
@if($errors->has('nama_lengkap') || $errors->has('nomor_hp') || $errors->has('alamat') || $errors->has('tanggal_lahir'))
    document.addEventListener('DOMContentLoaded', function() {
        switchTab('jemaat');
        showJemaatEdit();
    });
@elseif($errors->has('current_password') || $errors->has('password'))
    document.addEventListener('DOMContentLoaded', function() { switchTab('password'); });
@endif

// Avatar preview
function previewFoto(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const wrap = document.querySelector('.pf-avatar-wrap');
        let img = wrap.querySelector('.pf-avatar-img');
        let init = wrap.querySelector('.pf-avatar-init');
        if (!img) {
            img = document.createElement('img');
            img.className = 'pf-avatar-img';
            img.alt = 'Foto Profil';
            if (init) init.replaceWith(img);
        }
        img.src = e.target.result;
        // Auto-submit foto
        input.closest('form')?.submit();
    };
    reader.readAsDataURL(input.files[0]);
}

// Password toggle
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.querySelector('i').className = isText ? 'fas fa-eye' : 'fas fa-eye-slash';
}

// Password strength
function checkStrength(val) {
    const bar = document.getElementById('pwBar');
    const label = document.getElementById('pwLabel');
    const tips = {
        len:   val.length >= 8,
        upper: /[A-Z]/.test(val),
        num:   /[0-9]/.test(val),
        sym:   /[^A-Za-z0-9]/.test(val),
    };
    // Update tips
    Object.keys(tips).forEach(function(k) {
        const el = document.getElementById('tip-' + k);
        if (el) el.classList.toggle('met', tips[k]);
    });
    const score = Object.values(tips).filter(Boolean).length;
    const levels = ['','#ef4444','#f97316','#eab308','#22c55e'];
    const labels = ['','Sangat Lemah','Lemah','Cukup','Kuat'];
    bar.style.width = (score * 25) + '%';
    bar.style.background = levels[score] || '';
    label.textContent = score > 0 ? labels[score] : '';
    label.style.color = levels[score] || '';
}
</script>

@endsection
