@extends('layouts.dashboard')

@section('title', 'Absensi: '.$schedule->title)
@section('page-title', 'Absensi Jadwal')

@section('content')

{{-- Header --}}
<div class="sv-header">
    <div class="sv-header-left">
        <a href="{{ route('admin.schedules.index') }}" class="sv-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div class="sv-title-wrap">
            <span class="sv-emoji">{{ $schedule->emoji ?? '📅' }}</span>
            <div>
                <h2 class="sv-title">{{ $schedule->title }}</h2>
                <div class="sv-meta">
                    @if($schedule->day)<span><i class="fas fa-calendar-week"></i> {{ $schedule->day }}</span>@endif
                    @if($schedule->tanggal)<span><i class="fas fa-calendar-day"></i> {{ $schedule->tanggal->format('d M Y') }}</span>@endif
                    @if($schedule->location)<span><i class="fas fa-map-marker-alt"></i> {{ $schedule->location }}</span>@endif
                    @if($schedule->start_time)<span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}@if($schedule->end_time) – {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}@endif WIB</span>@endif
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="sv-edit-btn">
        <i class="fas fa-edit"></i> Edit Jadwal
    </a>
</div>

{{-- Stats --}}
<div class="sv-stats">
    <div class="sv-stat sv-stat--blue">
        <div class="sv-stat-icon"><i class="fas fa-clipboard-list"></i></div>
        <div><div class="sv-stat-lbl">Total Disetujui</div><div class="sv-stat-val">{{ $absensiStats['total'] }}</div></div>
    </div>
    <div class="sv-stat sv-stat--green">
        <div class="sv-stat-icon"><i class="fas fa-check-circle"></i></div>
        <div><div class="sv-stat-lbl">Hadir</div><div class="sv-stat-val">{{ $absensiStats['hadir'] }}</div></div>
    </div>
    <div class="sv-stat sv-stat--gold">
        <div class="sv-stat-icon"><i class="fas fa-file-alt"></i></div>
        <div><div class="sv-stat-lbl">Izin</div><div class="sv-stat-val">{{ $absensiStats['izin'] }}</div></div>
    </div>
    <div class="sv-stat sv-stat--orange">
        <div class="sv-stat-icon"><i class="fas fa-hourglass-half"></i></div>
        <div><div class="sv-stat-lbl">Menunggu</div><div class="sv-stat-val">{{ $absensiStats['pending'] }}</div></div>
    </div>
</div>

{{-- Table --}}
<div class="sv-card">
    <div class="sv-card-header">
        <span>Daftar Absensi Jemaat</span>
        <span class="sv-total">{{ $absensis->total() }} data</span>
    </div>
    @if($absensis->count() > 0)
    <div style="overflow-x:auto;">
        <table class="sv-table">
            <thead>
                <tr>
                    <th>Jemaat</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Persetujuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensis as $abs)
                <tr class="{{ $abs->approval_status === 'pending' ? 'row-pending' : '' }}">
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="sv-avatar">{{ strtoupper(substr($abs->jemaat->nama_lengkap ?? 'J', 0, 1)) }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $abs->jemaat->nama_lengkap ?? '-' }}</div>
                                <div style="font-size:11px;color:var(--text-light);">{{ $abs->jemaat->nomor_hp ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;font-weight:500;">{{ $abs->tanggal->format('d M Y') }}</td>
                    <td>
                        @if($abs->status === 'hadir') <span class="badge badge-success">Hadir</span>
                        @elseif($abs->status === 'izin') <span class="badge badge-info">Izin</span>
                        @else <span class="badge badge-danger">Tidak Hadir</span> @endif
                    </td>
                    <td style="font-size:12px;color:var(--text-mid);">{{ $abs->alasan_izin ?? $abs->keterangan ?? '-' }}</td>
                    <td>
                        @if($abs->approval_status === 'pending') <span class="badge badge-warning">⏳ Menunggu</span>
                        @elseif($abs->approval_status === 'approved') <span class="badge badge-success">✅ Disetujui</span>
                        @else <span class="badge badge-danger">❌ Ditolak</span> @endif
                    </td>
                    <td>
                        @if($abs->approval_status === 'pending')
                        <div style="display:flex;gap:6px;">
                            <form action="{{ route('admin.absensi.approve', $abs) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="sv-action-btn sv-btn--green" title="Setujui"><i class="fas fa-check"></i></button>
                            </form>
                            <button onclick="showReject({{ $abs->id }})" class="sv-action-btn sv-btn--red" title="Tolak"><i class="fas fa-times"></i></button>
                        </div>
                        @else
                        <span style="font-size:11px;color:var(--text-light);">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:14px 20px;">{{ $absensis->links() }}</div>
    @else
    <div class="empty-state" style="padding:48px 20px;">
        <i class="fas fa-clipboard"></i>
        <p>Belum ada absensi untuk jadwal ini.</p>
    </div>
    @endif
</div>

{{-- Reject Modal --}}
<div id="rejectModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:14px;padding:28px;max-width:400px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <h3 style="font-family:'Playfair Display',serif;font-size:17px;margin-bottom:14px;color:var(--text-dark);"><i class="fas fa-times-circle" style="color:var(--danger);margin-right:8px;"></i>Tolak Absensi</h3>
        <form id="rejectForm" method="POST">
            @csrf @method('PATCH')
            <textarea name="catatan_admin" rows="3" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-family:inherit;font-size:13px;margin-bottom:14px;" placeholder="Alasan penolakan (opsional)..."></textarea>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeReject()" style="padding:9px 18px;background:#f3f4f6;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Batal</button>
                <button type="submit" style="padding:9px 18px;background:var(--danger);color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Tolak</button>
            </div>
        </form>
    </div>
</div>

<style>
.sv-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:12px; }
.sv-header-left { display:flex; align-items:center; gap:16px; }
.sv-back { display:inline-flex; align-items:center; gap:6px; color:var(--text-mid); text-decoration:none; font-size:13px; font-weight:600; }
.sv-back:hover { color:var(--gold-dark); }
.sv-title-wrap { display:flex; align-items:center; gap:12px; }
.sv-emoji { font-size:32px; }
.sv-title { font-family:'Playfair Display',serif; font-size:20px; font-weight:700; color:var(--text-dark); }
.sv-meta { display:flex; flex-wrap:wrap; gap:12px; margin-top:4px; font-size:12.5px; color:var(--text-mid); }
.sv-meta span { display:inline-flex; align-items:center; gap:5px; }
.sv-edit-btn { display:inline-flex; align-items:center; gap:7px; padding:9px 16px; background:var(--gold-light); color:var(--gold-dark); border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; transition:all .2s; }
.sv-edit-btn:hover { background:var(--gold); color:#1e1a14; }

.sv-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:20px; }
.sv-stat { background:#fff; border-radius:10px; padding:14px 16px; display:flex; align-items:center; gap:12px; box-shadow:var(--shadow); border-left:3px solid; }
.sv-stat--blue   { border-color:#2563eb; }
.sv-stat--green  { border-color:#059669; }
.sv-stat--gold   { border-color:var(--gold); }
.sv-stat--orange { border-color:#f59e0b; }
.sv-stat-icon { width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:15px; }
.sv-stat--blue   .sv-stat-icon { background:#dbeafe; color:#2563eb; }
.sv-stat--green  .sv-stat-icon { background:#d1fae5; color:#059669; }
.sv-stat--gold   .sv-stat-icon { background:var(--gold-light); color:var(--gold-dark); }
.sv-stat--orange .sv-stat-icon { background:#fef3c7; color:#d97706; }
.sv-stat-lbl { font-size:11px; font-weight:600; color:var(--text-mid); text-transform:uppercase; letter-spacing:.4px; }
.sv-stat-val { font-size:22px; font-weight:700; color:var(--text-dark); }

.sv-card { background:#fff; border-radius:12px; box-shadow:var(--shadow); overflow:hidden; }
.sv-card-header { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid var(--border); font-family:'Playfair Display',serif; font-size:15px; font-weight:700; color:var(--text-dark); }
.sv-total { font-size:12px; font-weight:500; color:var(--text-mid); }
.sv-table { width:100%; border-collapse:collapse; }
.sv-table th { text-align:left; padding:11px 16px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:var(--text-mid); background:#faf8f5; border-bottom:1px solid var(--border); }
.sv-table td { padding:12px 16px; border-bottom:1px solid #f3f1ec; }
.sv-table tbody tr:hover { background:#fdfcfa; }
.row-pending { background:#fffbeb !important; }
.sv-avatar { width:30px; height:30px; border-radius:50%; background:var(--gold-light); color:var(--gold-dark); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0; }
.sv-action-btn { width:28px; height:28px; border-radius:6px; border:none; display:inline-flex; align-items:center; justify-content:center; font-size:12px; cursor:pointer; transition:all .2s; }
.sv-btn--green { background:#d1fae5; color:#059669; } .sv-btn--green:hover { background:#059669; color:#fff; }
.sv-btn--red   { background:#fce4ec; color:var(--danger); } .sv-btn--red:hover { background:var(--danger); color:#fff; }
@media(max-width:768px){ .sv-stats{grid-template-columns:repeat(2,1fr);} }
</style>

<script>
function showReject(id) {
    document.getElementById('rejectForm').action = '/admin/absensi/' + id + '/reject';
    document.getElementById('rejectModal').style.display = 'flex';
}
function closeReject() { document.getElementById('rejectModal').style.display = 'none'; }
document.getElementById('rejectModal').addEventListener('click', function(e){ if(e.target===this) closeReject(); });
</script>
@endsection