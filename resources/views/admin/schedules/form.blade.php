@extends('layouts.dashboard')

@section('title', isset($schedule) ? 'Edit Jadwal' : 'Tambah Jadwal')
@section('page-title', isset($schedule) ? 'Edit Jadwal Ibadah' : 'Tambah Jadwal Ibadah')

@section('content')

@php $isEdit = isset($schedule); @endphp

<div style="max-width:760px;">

    {{-- Breadcrumb --}}
    <div class="scf-breadcrumb">
        <a href="{{ route('admin.schedules.index') }}"><i class="fas fa-calendar-alt"></i> Jadwal Ibadah</a>
        <i class="fas fa-chevron-right"></i>
        <span>{{ $isEdit ? 'Edit' : 'Tambah' }}</span>
    </div>

    <div class="scf-card">
        <div class="scf-card-header">
            <div class="scf-header-icon">
                <i class="fas fa-{{ $isEdit ? 'edit' : 'calendar-plus' }}"></i>
            </div>
            <div>
                <div class="scf-card-title">{{ $isEdit ? 'Edit Jadwal: '.$schedule->title : 'Jadwal Ibadah Baru' }}</div>
                <div class="scf-card-sub">{{ $isEdit ? 'Perbarui informasi jadwal ibadah' : 'Tambahkan jadwal ibadah rutin atau acara khusus' }}</div>
            </div>
        </div>

        @if($errors->any())
        <div class="scf-alert scf-alert--error">
            <i class="fas fa-exclamation-circle"></i>
            <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        </div>
        @endif

        <form action="{{ $isEdit ? route('admin.schedules.update', $schedule) : route('admin.schedules.store') }}" method="POST" class="scf-form">
            @csrf
            @if($isEdit) @method('PUT') @endif

            {{-- Nama & Emoji --}}
            <div class="scf-section-label">Informasi Dasar</div>
            <div class="scf-grid scf-grid--3-1">
                <div class="scf-group">
                    <label class="scf-label">Nama Jadwal <span class="scf-req">*</span></label>
                    <div class="scf-input-wrap">
                        <i class="fas fa-church scf-icon"></i>
                        <input type="text" name="title" class="scf-input scf-input--icon"
                            value="{{ old('title', $isEdit ? $schedule->title : '') }}"
                            placeholder="Contoh: Ibadah Raya Minggu" required>
                    </div>
                </div>
                <div class="scf-group">
                    <label class="scf-label">Emoji / Ikon</label>
                    <div class="scf-emoji-grid">
                        @foreach(['⛪','🙏','🎸','🌅','🌙','👶','📖','🎺','✝️','🕊️'] as $em)
                        <label class="scf-emoji-opt">
                            <input type="radio" name="emoji" value="{{ $em }}" {{ old('emoji', $isEdit ? $schedule->emoji : '⛪') === $em ? 'checked' : '' }}>
                            <span>{{ $em }}</span>
                        </label>
                        @endforeach
                    </div>
                    <input type="text" name="emoji_custom" class="scf-input" style="margin-top:6px;font-size:18px;"
                        placeholder="Atau ketik emoji lain..."
                        value="{{ old('emoji_custom', '') }}">
                </div>
            </div>

            <div class="scf-group">
                <label class="scf-label">Deskripsi <span class="scf-req">*</span></label>
                <textarea name="description" class="scf-input scf-textarea" rows="3"
                    placeholder="Keterangan singkat tentang ibadah ini...">{{ old('description', $isEdit ? $schedule->description : '') }}</textarea>
            </div>

            {{-- Jadwal --}}
            <div class="scf-section-label" style="margin-top:24px;">Waktu & Tempat</div>

            <div class="scf-group">
                <label class="scf-label">Jenis Jadwal</label>
                <div class="scf-radio-group">
                    <label class="scf-radio">
                        <input type="radio" name="is_recurring" value="1" id="radio_rutin"
                            {{ old('is_recurring', $isEdit ? ($schedule->is_recurring ? '1' : '0') : '1') === '1' ? 'checked' : '' }}
                            onchange="toggleJenis()">
                        <span class="scf-radio-box scf-radio--blue"><i class="fas fa-sync-alt"></i> Rutin Mingguan</span>
                    </label>
                    <label class="scf-radio">
                        <input type="radio" name="is_recurring" value="0" id="radio_event"
                            {{ old('is_recurring', $isEdit ? ($schedule->is_recurring ? '1' : '0') : '1') === '0' ? 'checked' : '' }}
                            onchange="toggleJenis()">
                        <span class="scf-radio-box scf-radio--gold"><i class="fas fa-calendar-day"></i> Acara Sekali</span>
                    </label>
                </div>
            </div>

            <div class="scf-grid scf-grid--2">
                {{-- Hari (rutin) --}}
                <div class="scf-group" id="group_day">
                    <label class="scf-label">Hari Ibadah</label>
                    <div class="scf-input-wrap">
                        <i class="fas fa-calendar-week scf-icon"></i>
                        <select name="day" class="scf-input scf-input--icon">
                            <option value="">— Pilih Hari —</option>
                            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $d)
                                <option value="{{ $d }}" {{ old('day', $isEdit ? $schedule->day : '') === $d ? 'selected' : '' }}>{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tanggal (sekali) --}}
                <div class="scf-group" id="group_tanggal" style="display:none;">
                    <label class="scf-label">Tanggal Acara</label>
                    <div class="scf-input-wrap">
                        <i class="fas fa-calendar-day scf-icon"></i>
                        <input type="date" name="tanggal" class="scf-input scf-input--icon"
                            value="{{ old('tanggal', $isEdit && $schedule->tanggal ? $schedule->tanggal->format('Y-m-d') : '') }}">
                    </div>
                </div>

                <div class="scf-group">
                    <label class="scf-label">Lokasi / Gedung</label>
                    <div class="scf-input-wrap">
                        <i class="fas fa-map-marker-alt scf-icon"></i>
                        <input type="text" name="location" class="scf-input scf-input--icon"
                            value="{{ old('location', $isEdit ? $schedule->location : '') }}"
                            placeholder="Contoh: Gedung Utama, Lantai 2">
                    </div>
                </div>
            </div>

            <div class="scf-grid scf-grid--2">
                <div class="scf-group">
                    <label class="scf-label">Waktu Mulai</label>
                    <div class="scf-input-wrap">
                        <i class="far fa-clock scf-icon"></i>
                        <input type="time" name="start_time" class="scf-input scf-input--icon"
                            value="{{ old('start_time', $isEdit && $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '') }}">
                    </div>
                </div>
                <div class="scf-group">
                    <label class="scf-label">Waktu Selesai</label>
                    <div class="scf-input-wrap">
                        <i class="far fa-clock scf-icon"></i>
                        <input type="time" name="end_time" class="scf-input scf-input--icon"
                            value="{{ old('end_time', $isEdit && $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '') }}">
                    </div>
                </div>
            </div>

            {{-- Pengaturan --}}
            <div class="scf-section-label" style="margin-top:24px;">Pengaturan</div>
            <div class="scf-grid scf-grid--2">
                <div class="scf-group">
                    <label class="scf-label">Urutan Tampil</label>
                    <div class="scf-input-wrap">
                        <i class="fas fa-sort-numeric-up scf-icon"></i>
                        <input type="number" name="order" min="0" class="scf-input scf-input--icon"
                            value="{{ old('order', $isEdit ? $schedule->order : 0) }}"
                            placeholder="0 = tampil paling atas">
                    </div>
                </div>
                <div class="scf-group">
                    <label class="scf-label">Status</label>
                    <label class="scf-switch-label">
                        <div class="scf-switch-wrap">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" id="is_active"
                                {{ old('is_active', $isEdit ? $schedule->is_active : true) ? 'checked' : '' }}>
                            <span class="scf-switch"></span>
                        </div>
                        <span class="scf-switch-text">Jadwal aktif & tampil di dashboard</span>
                    </label>
                </div>
            </div>

            {{-- Footer --}}
            <div class="scf-footer">
                <a href="{{ route('admin.schedules.index') }}" class="scf-btn-cancel">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="scf-btn-save">
                    <i class="fas fa-save"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Jadwal' }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.scf-breadcrumb { display:flex; align-items:center; gap:8px; font-size:13px; color:var(--text-mid); margin-bottom:16px; }
.scf-breadcrumb a { color:var(--gold-dark); text-decoration:none; display:flex; align-items:center; gap:5px; }
.scf-breadcrumb a:hover { color:var(--gold); }
.scf-breadcrumb i.fa-chevron-right { font-size:10px; }

.scf-card { background:#fff; border-radius:12px; box-shadow:var(--shadow); overflow:hidden; }
.scf-card-header { display:flex; align-items:center; gap:16px; padding:22px 28px; border-bottom:1px solid var(--border); }
.scf-header-icon { width:44px; height:44px; border-radius:10px; background:linear-gradient(135deg,var(--gold),var(--gold-dark)); color:#1e1a14; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
.scf-card-title { font-family:'Playfair Display',serif; font-size:16px; font-weight:700; color:var(--text-dark); }
.scf-card-sub { font-size:12px; color:var(--text-mid); margin-top:2px; }

.scf-alert { display:flex; align-items:flex-start; gap:10px; padding:14px 20px; background:#fce4ec; color:#991b1b; border-bottom:1px solid #fca5a5; font-size:13px; }
.scf-alert--error { background:#fef2f2; }

.scf-form { padding:24px 28px; }
.scf-section-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:var(--gold-dark); margin-bottom:14px; display:flex; align-items:center; gap:8px; }
.scf-section-label::after { content:''; flex:1; height:1px; background:var(--border); }

.scf-grid { display:grid; gap:16px; margin-bottom:16px; }
.scf-grid--2   { grid-template-columns:1fr 1fr; }
.scf-grid--3-1 { grid-template-columns:2fr 1fr; }

.scf-group { display:flex; flex-direction:column; gap:6px; margin-bottom:16px; }
.scf-label { font-size:12px; font-weight:700; color:var(--text-mid); text-transform:uppercase; letter-spacing:.5px; }
.scf-req { color:var(--danger); }
.scf-input-wrap { position:relative; }
.scf-icon { position:absolute; left:13px; top:50%; transform:translateY(-50%); font-size:13px; color:var(--text-light); pointer-events:none; }
.scf-input { width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:13.5px; color:var(--text-dark); background:#fff; font-family:inherit; outline:none; transition:border-color .2s,box-shadow .2s; }
.scf-input:focus { border-color:var(--gold); box-shadow:0 0 0 3px rgba(212,175,55,.13); }
.scf-input--icon { padding-left:38px; }
.scf-textarea { resize:vertical; min-height:80px; }

/* Emoji grid */
.scf-emoji-grid { display:flex; flex-wrap:wrap; gap:6px; }
.scf-emoji-opt input { display:none; }
.scf-emoji-opt span { display:flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:8px; font-size:20px; cursor:pointer; border:2px solid var(--border); transition:all .2s; }
.scf-emoji-opt input:checked + span { border-color:var(--gold); background:var(--gold-light); }
.scf-emoji-opt span:hover { border-color:var(--gold-light); }

/* Radio toggle */
.scf-radio-group { display:flex; gap:10px; flex-wrap:wrap; }
.scf-radio input { display:none; }
.scf-radio-box { display:inline-flex; align-items:center; gap:7px; padding:9px 16px; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; border:2px solid var(--border); transition:all .2s; }
.scf-radio--blue { color:#2563eb; background:#eff6ff; border-color:#bfdbfe; }
.scf-radio--gold { color:var(--gold-dark); background:var(--gold-light); border-color:#e8d89a; }
.scf-radio input:checked + .scf-radio--blue { background:#2563eb; color:#fff; border-color:#2563eb; }
.scf-radio input:checked + .scf-radio--gold { background:var(--gold); color:#1e1a14; border-color:var(--gold); }

/* Switch */
.scf-switch-label { display:flex; align-items:center; gap:12px; cursor:pointer; padding:11px 14px; border:1.5px solid var(--border); border-radius:9px; background:#fafafa; }
.scf-switch-wrap { position:relative; flex-shrink:0; }
.scf-switch-wrap input[type=checkbox] { display:none; }
.scf-switch { display:block; width:42px; height:24px; background:#d1d5db; border-radius:12px; transition:background .3s; position:relative; }
.scf-switch::after { content:''; position:absolute; top:3px; left:3px; width:18px; height:18px; background:#fff; border-radius:50%; transition:left .3s; box-shadow:0 1px 3px rgba(0,0,0,.2); }
.scf-switch-wrap input:checked ~ .scf-switch { background:var(--gold); }
.scf-switch-wrap input:checked ~ .scf-switch::after { left:21px; }
.scf-switch-text { font-size:13px; font-weight:500; color:var(--text-dark); }

.scf-footer { display:flex; justify-content:flex-end; gap:10px; border-top:1px solid var(--border); padding-top:20px; margin-top:8px; }
.scf-btn-save { display:inline-flex; align-items:center; gap:7px; padding:11px 24px; background:linear-gradient(135deg,var(--gold),var(--gold-dark)); color:#1e1a14; border:none; border-radius:9px; font-size:13.5px; font-weight:700; cursor:pointer; transition:opacity .2s,transform .2s; }
.scf-btn-save:hover { opacity:.9; transform:translateY(-1px); }
.scf-btn-cancel { display:inline-flex; align-items:center; gap:7px; padding:11px 20px; background:#f3f4f6; color:var(--text-mid); border:1px solid var(--border); border-radius:9px; font-size:13.5px; font-weight:600; text-decoration:none; transition:all .2s; }
.scf-btn-cancel:hover { background:#e5e7eb; color:var(--text-dark); }

@media(max-width:640px){ .scf-grid--2,.scf-grid--3-1 { grid-template-columns:1fr; } .scf-form,.scf-footer{ padding-left:16px; padding-right:16px; } }
</style>

<script>
function toggleJenis() {
    const isRutin = document.getElementById('radio_rutin').checked;
    document.getElementById('group_day').style.display    = isRutin ? 'block' : 'none';
    document.getElementById('group_tanggal').style.display = isRutin ? 'none' : 'block';
}

// Emoji custom override
document.querySelector('input[name="emoji_custom"]').addEventListener('input', function() {
    if (this.value) document.querySelectorAll('input[name="emoji"]').forEach(r => r.checked = false);
});

// Form submit: resolve emoji
document.querySelector('form').addEventListener('submit', function() {
    const custom = document.querySelector('input[name="emoji_custom"]').value.trim();
    if (custom) {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'emoji';
        hidden.value = custom;
        this.appendChild(hidden);
    }
});

// Init on load
document.addEventListener('DOMContentLoaded', function() {
    toggleJenis();
    @if($isEdit && !$schedule->is_recurring)
        document.getElementById('radio_event').checked = true;
        toggleJenis();
    @endif
});

// Form submit handler untuk show loading popup
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const jadwalTitle = document.querySelector('input[name="title"]').value;

    // Show loading popup
    Swal.fire({
        title: 'Menyimpan Jadwal...',
        text: jadwalTitle,
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: (modal) => {
            Swal.showLoading();
        }
    });

    // Disable submit button
    submitBtn.disabled = true;
});
</script>
@endsection
