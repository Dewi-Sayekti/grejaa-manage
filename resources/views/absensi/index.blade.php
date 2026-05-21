@extends('layouts.dashboard')

@section('title', 'Presensi Saya')
@section('page-title', 'Presensi Ibadah')

@section('content')

{{-- Stats --}}
<div class="abs-stats">
    <div class="abs-stat abs-stat--gold">
        <div class="abs-stat-icon"><i class="fas fa-clipboard-list"></i></div>
        <div><div class="abs-stat-label">Total Disetujui</div><div class="abs-stat-value">{{ $stats['total'] }}</div></div>
    </div>
    <div class="abs-stat abs-stat--green">
        <div class="abs-stat-icon"><i class="fas fa-check-circle"></i></div>
        <div><div class="abs-stat-label">Hadir</div><div class="abs-stat-value">{{ $stats['hadir'] }}</div></div>
    </div>
    <div class="abs-stat abs-stat--blue">
        <div class="abs-stat-icon"><i class="fas fa-file-alt"></i></div>
        <div><div class="abs-stat-label">Izin</div><div class="abs-stat-value">{{ $stats['izin'] }}</div></div>
    </div>
    <div class="abs-stat abs-stat--orange">
        <div class="abs-stat-icon"><i class="fas fa-hourglass-half"></i></div>
        <div><div class="abs-stat-label">Menunggu</div><div class="abs-stat-value">{{ $stats['pending'] }}</div></div>
    </div>
</div>

<div class="abs-layout">

    {{-- === PANEL KIRI: Form catat absensi === --}}
    <div>
        {{-- Jadwal Aktif --}}
        <div class="abs-schedules-card">
            <div class="abs-schedules-header">
                <i class="fas fa-calendar-alt" style="color:var(--gold);"></i>
                <span>Jadwal Ibadah Aktif</span>
            </div>
            <div class="abs-schedules-body">
                @forelse($schedules as $sc)
                <div class="abs-schedule-item {{ $selectedSchedule && $selectedSchedule->id == $sc->id ? 'abs-schedule-item--active' : '' }}"
                     onclick="pilihJadwal({{ $sc->id }}, '{{ addslashes($sc->title) }}', '{{ $sc->day ?? '' }}', '{{ $sc->tanggal ? $sc->tanggal->format('Y-m-d') : '' }}')">
                    <span class="abs-schedule-emoji">{{ $sc->emoji ?? '📅' }}</span>
                    <div class="abs-schedule-info">
                        <div class="abs-schedule-name">{{ $sc->title }}</div>
                        <div class="abs-schedule-meta">
                            @if($sc->tanggal)
                                <i class="fas fa-calendar-day"></i> {{ $sc->tanggal->format('d M Y') }}
                            @elseif($sc->day)
                                <i class="fas fa-sync-alt"></i> {{ $sc->day }}
                            @endif
                            @if($sc->start_time)
                                · {{ \Carbon\Carbon::parse($sc->start_time)->format('H:i') }}
                                @if($sc->end_time)–{{ \Carbon\Carbon::parse($sc->end_time)->format('H:i') }}@endif WIB
                            @endif
                            @if($sc->location)
                                · <i class="fas fa-map-marker-alt"></i> {{ $sc->location }}
                            @endif
                        </div>
                    </div>
                    <i class="fas fa-chevron-right abs-schedule-arrow"></i>
                </div>
                @empty
                <div class="empty-state" style="padding:24px;">
                    <i class="fas fa-calendar-times"></i>
                    <p>Belum ada jadwal aktif.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Form Absensi --}}
        <div class="abs-form-card" id="formCard" style="{{ $selectedSchedule ? '' : 'display:none;' }}">
            <div class="abs-form-header">
                <i class="fas fa-clipboard-check" style="color:var(--gold);"></i>
                <span id="formTitle">Catat Kehadiran</span>
            </div>

            <div class="abs-form-selected" id="formSelected">
                @if($selectedSchedule)
                    <span class="abs-form-sc-emoji">{{ $selectedSchedule->emoji ?? '📅' }}</span>
                    <div>
                        <div class="abs-form-sc-name">{{ $selectedSchedule->title }}</div>
                        <div class="abs-form-sc-meta">
                            @if($selectedSchedule->day) {{ $selectedSchedule->day }} · @endif
                            @if($selectedSchedule->start_time) {{ \Carbon\Carbon::parse($selectedSchedule->start_time)->format('H:i') }} WIB @endif
                        </div>
                    </div>
                @endif
            </div>

            <form action="{{ route('absensi.store') }}" method="POST" class="abs-form" id="absensiForm">
                @csrf
                <input type="hidden" name="schedule_id" id="scheduleIdInput" value="{{ $selectedSchedule ? $selectedSchedule->id : '' }}">
                <input type="hidden" name="jenis_ibadah" id="jenisIbadahInput" value="{{ $selectedSchedule ? $selectedSchedule->title : '' }}">

                <div class="abs-form-group">
                    <label class="abs-form-label">Tanggal Ibadah <span style="color:var(--danger);">*</span></label>
                    <input type="date" name="tanggal" id="tanggalInput"
                        max="{{ date('Y-m-d') }}"
                        value="{{ old('tanggal', $selectedSchedule && $selectedSchedule->tanggal ? $selectedSchedule->tanggal->format('Y-m-d') : date('Y-m-d')) }}"
                        required class="abs-input">
                </div>

                <div class="abs-form-group">
                    <label class="abs-form-label">Status Kehadiran <span style="color:var(--danger);">*</span></label>
                    <div class="abs-radio-group">
                        <label class="abs-radio">
                            <input type="radio" name="status" value="hadir" checked onchange="toggleAlasan(this.value)">
                            <span class="abs-radio-box abs-radio--green"><i class="fas fa-check"></i> Hadir</span>
                        </label>
                        <label class="abs-radio">
                            <input type="radio" name="status" value="izin" onchange="toggleAlasan(this.value)">
                            <span class="abs-radio-box abs-radio--blue"><i class="fas fa-file-alt"></i> Izin</span>
                        </label>
                        <label class="abs-radio">
                            <input type="radio" name="status" value="tidak_hadir" onchange="toggleAlasan(this.value)">
                            <span class="abs-radio-box abs-radio--red"><i class="fas fa-times"></i> Tidak Hadir</span>
                        </label>
                    </div>
                </div>

                <div class="abs-form-group" id="alasanGroup" style="display:none;">
                    <label class="abs-form-label">Alasan</label>
                    <textarea name="alasan_izin" class="abs-input" rows="2"
                        placeholder="Tuliskan alasan izin / ketidakhadiran..."></textarea>
                </div>

                <div class="abs-form-group">
                    <label class="abs-form-label">Keterangan <span style="color:var(--text-light);font-weight:400;">(opsional)</span></label>
                    <textarea name="keterangan" class="abs-input" rows="2"
                        placeholder="Keterangan tambahan..."></textarea>
                </div>

                <button type="submit" class="abs-submit-btn">
                    <i class="fas fa-paper-plane"></i> Kirim & Tunggu Persetujuan
                </button>

                <div class="abs-note">
                    <i class="fas fa-info-circle"></i>
                    Presensi akan diverifikasi admin sebelum tercatat resmi.
                </div>
            </form>
        </div>

        {{-- Placeholder saat belum pilih jadwal --}}
        <div id="formPlaceholder" class="abs-form-placeholder" style="{{ $selectedSchedule ? 'display:none;' : '' }}">
            <i class="fas fa-hand-pointer"></i>
            <p>Pilih jadwal ibadah di atas<br>untuk mulai mencatat kehadiran</p>
        </div>
    </div>

    {{-- === PANEL KANAN: Riwayat === --}}
    <div class="abs-list-card">
        <div class="abs-list-header">
            <span>Riwayat Presensi</span>
            <span class="abs-total">{{ $absensis->total() }} data</span>
        </div>

        @if(session('success'))
        <div style="background:#d1fae5;color:#065f46;padding:12px 16px;font-size:13px;font-weight:500;display:flex;gap:8px;align-items:center;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div style="background:#fce4ec;color:#991b1b;padding:12px 16px;font-size:13px;font-weight:500;display:flex;gap:8px;align-items:center;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @if($absensis->count() > 0)
        <div style="overflow-x:auto;">
            <table class="abs-table">
                <thead>
                    <tr>
                        <th>Jadwal</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Approval</th>
                        <th>Catatan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensis as $abs)
                    <tr>
                        <td>
                            <div style="font-weight:600;font-size:13px;">{{ $abs->jenis_ibadah }}</div>
                            @if($abs->schedule)
                            <div style="font-size:11px;color:var(--text-light);">
                                {{ $abs->schedule->location ?? '' }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:600;font-size:13px;">{{ $abs->tanggal->format('d M Y') }}</div>
                            <div style="font-size:11px;color:var(--text-light);">{{ $abs->tanggal->format('l') }}</div>
                        </td>
                        <td>
                            @if($abs->status === 'hadir') <span class="badge badge-success">Hadir</span>
                            @elseif($abs->status === 'izin') <span class="badge badge-info">Izin</span>
                            @else <span class="badge badge-danger">Tidak Hadir</span> @endif
                        </td>
                        <td>
                            @if($abs->approval_status === 'pending')
                                <span class="badge badge-warning">⏳ Menunggu</span>
                            @elseif($abs->approval_status === 'approved')
                                <span class="badge badge-success">✅ Disetujui</span>
                            @else
                                <span class="badge badge-danger">❌ Ditolak</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:var(--text-mid);max-width:130px;">
                            {{ Str::limit($abs->catatan_admin ?? $abs->alasan_izin ?? '-', 40) }}
                        </td>
                        <td>
                            @if($abs->approval_status === 'pending')
                            <form action="{{ route('absensi.destroy', $abs) }}" method="POST" onsubmit="return confirm('Hapus absensi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="abs-del-btn" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:14px 16px;">{{ $absensis->links() }}</div>
        @else
        <div class="empty-state" style="padding:48px 20px;">
            <i class="fas fa-clipboard"></i>
            <p>Belum ada riwayat presensi.<br>Pilih jadwal dan catat presensi pertama Anda!</p>
        </div>
        @endif
    </div>
</div>

<style>
.abs-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:22px; }
.abs-stat { background:#fff; border-radius:10px; padding:14px 16px; display:flex; align-items:center; gap:12px; box-shadow:var(--shadow); border-left:3px solid; }
.abs-stat--gold   { border-color:var(--gold); }
.abs-stat--green  { border-color:#059669; }
.abs-stat--blue   { border-color:#2563eb; }
.abs-stat--orange { border-color:#f59e0b; }
.abs-stat-icon { width:38px; height:38px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:15px; }
.abs-stat--gold   .abs-stat-icon { background:var(--gold-light); color:var(--gold-dark); }
.abs-stat--green  .abs-stat-icon { background:#d1fae5; color:#059669; }
.abs-stat--blue   .abs-stat-icon { background:#dbeafe; color:#2563eb; }
.abs-stat--orange .abs-stat-icon { background:#fef3c7; color:#d97706; }
.abs-stat-label { font-size:11px; font-weight:600; color:var(--text-mid); text-transform:uppercase; letter-spacing:.4px; }
.abs-stat-value { font-size:22px; font-weight:700; color:var(--text-dark); }

.abs-layout { display:grid; grid-template-columns:320px 1fr; gap:20px; }

/* Schedules card */
.abs-schedules-card { background:#fff; border-radius:12px; box-shadow:var(--shadow); overflow:hidden; margin-bottom:16px; }
.abs-schedules-header { display:flex; align-items:center; gap:9px; padding:14px 16px; border-bottom:1px solid var(--border); font-family:'Playfair Display',serif; font-size:14px; font-weight:700; color:var(--text-dark); }
.abs-schedules-body { padding:6px 0; }
.abs-schedule-item { display:flex; align-items:center; gap:12px; padding:11px 16px; cursor:pointer; transition:background .2s; border-left:3px solid transparent; }
.abs-schedule-item:hover { background:#fdfcfa; border-left-color:var(--gold-light); }
.abs-schedule-item--active { background:var(--gold-light)!important; border-left-color:var(--gold)!important; }
.abs-schedule-emoji { font-size:22px; flex-shrink:0; }
.abs-schedule-info { flex:1; min-width:0; }
.abs-schedule-name { font-size:13px; font-weight:600; color:var(--text-dark); }
.abs-schedule-meta { font-size:11px; color:var(--text-mid); margin-top:2px; display:flex; align-items:center; gap:3px; flex-wrap:wrap; }
.abs-schedule-arrow { font-size:10px; color:var(--text-light); flex-shrink:0; }

/* Form card */
.abs-form-card { background:#fff; border-radius:12px; box-shadow:var(--shadow); overflow:hidden; }
.abs-form-header { display:flex; align-items:center; gap:9px; padding:14px 16px; border-bottom:1px solid var(--border); font-family:'Playfair Display',serif; font-size:14px; font-weight:700; color:var(--text-dark); }
.abs-form-selected { display:flex; align-items:center; gap:12px; padding:12px 16px; background:linear-gradient(135deg,#2c2417,#1e1a14); }
.abs-form-sc-emoji { font-size:24px; flex-shrink:0; }
.abs-form-sc-name { font-size:14px; font-weight:700; color:#fff; }
.abs-form-sc-meta { font-size:12px; color:rgba(255,255,255,.6); margin-top:3px; }
.abs-form { padding:16px; }
.abs-form-group { margin-bottom:14px; }
.abs-form-label { display:block; font-size:11px; font-weight:700; color:var(--text-mid); text-transform:uppercase; letter-spacing:.4px; margin-bottom:6px; }
.abs-input { width:100%; padding:10px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:13px; color:var(--text-dark); background:#fff; font-family:inherit; outline:none; transition:border-color .2s; }
.abs-input:focus { border-color:var(--gold); box-shadow:0 0 0 3px rgba(212,175,55,.12); }

.abs-radio-group { display:flex; gap:7px; flex-wrap:wrap; }
.abs-radio input { display:none; }
.abs-radio-box { display:inline-flex; align-items:center; gap:5px; padding:7px 12px; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; border:2px solid transparent; transition:all .2s; }
.abs-radio--green  { background:#f0fdf4; color:#059669; border-color:#a7f3d0; }
.abs-radio--blue   { background:#eff6ff; color:#2563eb; border-color:#bfdbfe; }
.abs-radio--red    { background:#fff5f5; color:#dc2626; border-color:#fecaca; }
.abs-radio input:checked + .abs-radio--green  { background:#059669; color:#fff; }
.abs-radio input:checked + .abs-radio--blue   { background:#2563eb; color:#fff; }
.abs-radio input:checked + .abs-radio--red    { background:#dc2626; color:#fff; }

.abs-submit-btn { width:100%; padding:12px; background:linear-gradient(135deg,var(--gold),var(--gold-dark)); color:#1e1a14; border:none; border-radius:8px; font-size:13.5px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:opacity .2s,transform .2s; }
.abs-submit-btn:hover { opacity:.9; transform:translateY(-1px); }
.abs-note { display:flex; align-items:center; gap:6px; margin-top:10px; padding:9px 11px; background:var(--gold-light); border-radius:7px; font-size:11.5px; color:var(--gold-dark); }

.abs-form-placeholder { background:#fff; border-radius:12px; box-shadow:var(--shadow); text-align:center; padding:36px 20px; color:var(--text-mid); }
.abs-form-placeholder i { font-size:36px; color:var(--border); margin-bottom:12px; display:block; }
.abs-form-placeholder p { font-size:13px; line-height:1.6; }

/* Riwayat */
.abs-list-card { background:#fff; border-radius:12px; box-shadow:var(--shadow); overflow:hidden; }
.abs-list-header { display:flex; align-items:center; justify-content:space-between; padding:14px 18px; border-bottom:1px solid var(--border); font-family:'Playfair Display',serif; font-size:15px; font-weight:700; color:var(--text-dark); }
.abs-total { font-size:12px; font-weight:500; color:var(--text-mid); }
.abs-table { width:100%; border-collapse:collapse; }
.abs-table th { text-align:left; padding:10px 14px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:var(--text-mid); background:#faf8f5; border-bottom:1px solid var(--border); }
.abs-table td { padding:11px 14px; border-bottom:1px solid #f3f1ec; }
.abs-table tbody tr:hover { background:#fdfcfa; }
.abs-del-btn { background:none; border:none; color:var(--danger); cursor:pointer; padding:5px 7px; border-radius:5px; transition:background .2s; }
.abs-del-btn:hover { background:#fce4ec; }

@media(max-width:1100px){ .abs-layout{grid-template-columns:1fr;} }
@media(max-width:560px){ .abs-stats{grid-template-columns:repeat(2,1fr);} }
</style>

<script>
// Data jadwal untuk JS
const jadwalData = @json($schedules->map(fn($s) => [
    'id'      => $s->id,
    'title'   => $s->title,
    'emoji'   => $s->emoji ?? '📅',
    'day'     => $s->day ?? '',
    'tanggal' => $s->tanggal ? $s->tanggal->format('Y-m-d') : '',
    'start_time' => $s->start_time ? \Carbon\Carbon::parse($s->start_time)->format('H:i') : '',
    'location'   => $s->location ?? '',
]));

function pilihJadwal(id, title, day, tanggal) {
    // Update active state
    document.querySelectorAll('.abs-schedule-item').forEach(el => el.classList.remove('abs-schedule-item--active'));
    event.currentTarget.classList.add('abs-schedule-item--active');

    const sc = jadwalData.find(j => j.id === id);

    // Update hidden inputs
    document.getElementById('scheduleIdInput').value = id;
    document.getElementById('jenisIbadahInput').value = title;

    // Set tanggal default
    if (tanggal) {
        document.getElementById('tanggalInput').value = tanggal;
    } else {
        document.getElementById('tanggalInput').value = '{{ date('Y-m-d') }}';
    }

    // Update form header
    document.getElementById('formTitle').textContent = 'Catat Kehadiran — ' + title;

    // Update selected display
    const sel = document.getElementById('formSelected');
    sel.innerHTML = `<span class="abs-form-sc-emoji">${sc.emoji}</span><div>
        <div class="abs-form-sc-name">${title}</div>
        <div class="abs-form-sc-meta">${day || (tanggal ? formatTanggal(tanggal) : '')} ${sc.start_time ? '· '+sc.start_time+' WIB' : ''} ${sc.location ? '· '+sc.location : ''}</div>
    </div>`;

    // Show form
    document.getElementById('formCard').style.display = 'block';
    document.getElementById('formPlaceholder').style.display = 'none';

    // Scroll to form on mobile
    if (window.innerWidth < 1100) {
        document.getElementById('formCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function formatTanggal(d) {
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
    const dt = new Date(d);
    return dt.getDate() + ' ' + months[dt.getMonth()] + ' ' + dt.getFullYear();
}

function toggleAlasan(val) {
    document.getElementById('alasanGroup').style.display = (val === 'izin' || val === 'tidak_hadir') ? 'block' : 'none';
}

// Pre-select if schedule_id in URL
@if($selectedSchedule)
document.addEventListener('DOMContentLoaded', function() {
    const item = document.querySelector('.abs-schedule-item--active');
    if (item) item.scrollIntoView({ block: 'nearest' });
});
@endif
</script>
@endsection