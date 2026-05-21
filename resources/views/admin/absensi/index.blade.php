@extends('layouts.dashboard')

@section('title', 'Manajemen Presensi')
@section('page-title', 'Presensi Jemaat')

@section('content')

<style>
/* ======== STATS ======== */
.pres-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:22px; }
.pres-stat { background:#fff; border-radius:12px; padding:16px 18px; display:flex; align-items:center; gap:14px; box-shadow:var(--shadow); border-left:4px solid; }
.pres-stat--orange { border-color:#f59e0b; }
.pres-stat--green  { border-color:#10b981; }
.pres-stat--red    { border-color:#ef4444; }
.pres-stat--blue   { border-color:#3b82f6; }
.pres-stat-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
.pres-stat--orange .pres-stat-icon { background:#fef3c7; color:#d97706; }
.pres-stat--green  .pres-stat-icon { background:#d1fae5; color:#059669; }
.pres-stat--red    .pres-stat-icon { background:#fee2e2; color:#dc2626; }
.pres-stat--blue   .pres-stat-icon { background:#dbeafe; color:#2563eb; }
.pres-stat-label { font-size:11px; font-weight:700; color:var(--text-mid); text-transform:uppercase; letter-spacing:.5px; }
.pres-stat-value { font-size:26px; font-weight:800; color:var(--text-dark); line-height:1; margin-top:2px; }

/* ======== TOOLBAR ======== */
.pres-toolbar { background:#fff; border-radius:12px; box-shadow:var(--shadow); padding:14px 18px; margin-bottom:16px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
.pres-toolbar-title { font-family:'Playfair Display',serif; font-size:15px; font-weight:700; color:var(--text-dark); display:flex; align-items:center; gap:8px; }
.pres-toolbar-actions { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }

/* ======== FILTER BAR ======== */
.pres-filter { background:#fff; border-radius:12px; box-shadow:var(--shadow); padding:14px 18px; margin-bottom:16px; }
.pres-filter-grid { display:grid; grid-template-columns:1fr 1fr 1fr 1fr auto; gap:10px; align-items:end; }
.pres-filter-group label { display:block; font-size:10px; font-weight:700; color:var(--text-mid); text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px; }
.pres-filter-group select,
.pres-filter-group input { width:100%; padding:8px 10px; border:1.5px solid var(--border); border-radius:8px; font-size:12.5px; color:var(--text-dark); background:#fff; outline:none; transition:border-color .2s; }
.pres-filter-group select:focus,
.pres-filter-group input:focus { border-color:var(--gold); }

/* ======== BUTTONS ======== */
.btn-pres { display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer; border:none; text-decoration:none; transition:all .2s; white-space:nowrap; }
.btn-pres--gold   { background:var(--gold); color:#1e1a14; }
.btn-pres--gold:hover { background:var(--gold-dark); }
.btn-pres--green  { background:#10b981; color:#fff; }
.btn-pres--green:hover { background:#059669; }
.btn-pres--blue   { background:#3b82f6; color:#fff; }
.btn-pres--blue:hover { background:#2563eb; }
.btn-pres--outline { background:#fff; border:1.5px solid var(--border)!important; color:var(--text-mid); }
.btn-pres--outline:hover { border-color:var(--gold)!important; color:var(--gold); }
.btn-pres--sm { padding:5px 10px; font-size:11px; }
.btn-pres--danger { background:#ef4444; color:#fff; }
.btn-pres--danger:hover { background:#dc2626; }

/* ======== BULK ACTION ======== */
.bulk-bar { display:none; background:linear-gradient(135deg,#1e1a14,#2a2520); border-radius:10px; padding:10px 16px; margin-bottom:12px; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; }
.bulk-bar.active { display:flex; }
.bulk-bar-info { color:#fff; font-size:13px; font-weight:600; display:flex; align-items:center; gap:8px; }
.bulk-bar-info span { background:var(--gold); color:#1e1a14; padding:2px 8px; border-radius:6px; font-size:12px; }

/* ======== TABLE ======== */
.pres-card { background:#fff; border-radius:12px; box-shadow:var(--shadow); overflow:hidden; }
.pres-table { width:100%; border-collapse:collapse; }
.pres-table th { text-align:left; padding:11px 14px; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--text-mid); background:#faf8f5; border-bottom:1px solid var(--border); white-space:nowrap; }
.pres-table td { padding:11px 14px; border-bottom:1px solid #f3f1ec; vertical-align:middle; }
.pres-table tbody tr:hover { background:#fdfcfa; }
.pres-table th:first-child,
.pres-table td:first-child { padding-left:18px; }

/* Badges */
.badge-pres { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:99px; font-size:11px; font-weight:700; }
.badge-pres--hadir   { background:#d1fae5; color:#065f46; }
.badge-pres--izin    { background:#dbeafe; color:#1e40af; }
.badge-pres--tidak   { background:#fee2e2; color:#991b1b; }
.badge-pres--pending  { background:#fef3c7; color:#92400e; }
.badge-pres--approved { background:#d1fae5; color:#065f46; }
.badge-pres--rejected { background:#fee2e2; color:#991b1b; }

/* Jemaat info */
.jemaat-cell { display:flex; align-items:center; gap:10px; }
.jemaat-avatar { width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,var(--gold),var(--gold-dark)); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:800; color:#1e1a14; flex-shrink:0; }
.jemaat-name { font-weight:700; font-size:13px; color:var(--text-dark); }
.jemaat-hp { font-size:11px; color:var(--text-mid); margin-top:1px; }

/* Method badge */
.method-badge { display:inline-flex; align-items:center; gap:3px; padding:2px 7px; border-radius:5px; font-size:10px; font-weight:600; background:#f3f4f6; color:#6b7280; }

/* Action buttons inline */
.action-btns { display:flex; gap:5px; align-items:center; }

/* Catatan col */
.catatan-text { font-size:11.5px; color:var(--text-mid); max-width:120px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

/* Reject modal */
.modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:9000; align-items:center; justify-content:center; }
.modal-overlay.active { display:flex; }
.modal-box { background:#fff; border-radius:14px; padding:24px; width:100%; max-width:440px; box-shadow:0 20px 60px rgba(0,0,0,.2); }
.modal-title { font-family:'Playfair Display',serif; font-size:17px; font-weight:700; color:var(--text-dark); margin-bottom:16px; }
.modal-label { font-size:11px; font-weight:700; color:var(--text-mid); text-transform:uppercase; letter-spacing:.4px; margin-bottom:6px; display:block; }
.modal-textarea { width:100%; padding:10px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:13px; color:var(--text-dark); font-family:inherit; outline:none; resize:vertical; min-height:90px; transition:border-color .2s; }
.modal-textarea:focus { border-color:var(--gold); }
.modal-footer { display:flex; gap:8px; justify-content:flex-end; margin-top:16px; }

/* Pagination area */
.pres-pagination { padding:14px 18px; border-top:1px solid var(--border); }

/* Empty state */
.pres-empty { text-align:center; padding:56px 20px; }
.pres-empty i { font-size:40px; color:#e5e7eb; margin-bottom:14px; display:block; }
.pres-empty p { font-size:13.5px; color:var(--text-mid); }

/* Checkbox */
.pres-checkbox { width:15px; height:15px; accent-color:var(--gold); cursor:pointer; }

@media(max-width:1100px){ .pres-stats{grid-template-columns:repeat(2,1fr);} .pres-filter-grid{grid-template-columns:1fr 1fr;} }
@media(max-width:600px){ .pres-stats{grid-template-columns:repeat(2,1fr);} .pres-filter-grid{grid-template-columns:1fr;} }
</style>

{{-- ======== STATS ======== --}}
<div class="pres-stats">
    <div class="pres-stat pres-stat--orange">
        <div class="pres-stat-icon"><i class="fas fa-hourglass-half"></i></div>
        <div>
            <div class="pres-stat-label">Menunggu</div>
            <div class="pres-stat-value">{{ $stats['pending'] }}</div>
        </div>
    </div>
    <div class="pres-stat pres-stat--green">
        <div class="pres-stat-icon"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="pres-stat-label">Disetujui</div>
            <div class="pres-stat-value">{{ $stats['approved'] }}</div>
        </div>
    </div>
    <div class="pres-stat pres-stat--red">
        <div class="pres-stat-icon"><i class="fas fa-times-circle"></i></div>
        <div>
            <div class="pres-stat-label">Ditolak</div>
            <div class="pres-stat-value">{{ $stats['rejected'] }}</div>
        </div>
    </div>
    <div class="pres-stat pres-stat--blue">
        <div class="pres-stat-icon"><i class="fas fa-clipboard-list"></i></div>
        <div>
            <div class="pres-stat-label">Total</div>
            <div class="pres-stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
</div>

{{-- ======== TOOLBAR ======== --}}
<div class="pres-toolbar">
    <div class="pres-toolbar-title">
        <i class="fas fa-clipboard-check" style="color:var(--gold);"></i>
        Daftar Presensi Jemaat
    </div>
    <div class="pres-toolbar-actions">
        <a href="{{ route('admin.absensi.massal') }}" class="btn-pres btn-pres--blue">
            <i class="fas fa-users"></i> Input Massal
        </a>
        <a href="{{ route('admin.absensi.rekap') }}" class="btn-pres btn-pres--gold">
            <i class="fas fa-chart-bar"></i> Rekap & Grafik
        </a>
        <a href="{{ route('admin.absensi.export.excel') }}" class="btn-pres btn-pres--green">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        <a href="{{ route('admin.absensi.export.pdf') }}" class="btn-pres btn-pres--outline">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

{{-- ======== FILTER ======== --}}
<div class="pres-filter">
    <form method="GET" action="{{ route('admin.absensi.index') }}">
        <div class="pres-filter-grid">
            <div class="pres-filter-group">
                <label>Status Approval</label>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>⏳ Menunggu</option>
                    <option value="approved"  {{ request('status') == 'approved'  ? 'selected' : '' }}>✅ Disetujui</option>
                    <option value="rejected"  {{ request('status') == 'rejected'  ? 'selected' : '' }}>❌ Ditolak</option>
                </select>
            </div>
            <div class="pres-filter-group">
                <label>Jemaat</label>
                <select name="jemaat_id">
                    <option value="">Semua Jemaat</option>
                    @foreach($jemaats as $j)
                    <option value="{{ $j->id }}" {{ request('jemaat_id') == $j->id ? 'selected' : '' }}>
                        {{ $j->nama_lengkap }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="pres-filter-group">
                <label>Jadwal</label>
                <select name="schedule_id">
                    <option value="">Semua Jadwal</option>
                    @foreach($schedules as $sc)
                    <option value="{{ $sc->id }}" {{ request('schedule_id') == $sc->id ? 'selected' : '' }}>
                        {{ $sc->title }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="pres-filter-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}">
            </div>
            <div class="pres-filter-group">
                <label>&nbsp;</label>
                <div style="display:flex;gap:6px;">
                    <button type="submit" class="btn-pres btn-pres--gold" style="flex:1;">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.absensi.index') }}" class="btn-pres btn-pres--outline">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- ======== SESSION MESSAGES ======== --}}
@if(session('success'))
<div style="background:#d1fae5;color:#065f46;padding:12px 16px;border-radius:10px;margin-bottom:14px;font-size:13px;font-weight:600;display:flex;gap:8px;align-items:center;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:#fee2e2;color:#991b1b;padding:12px 16px;border-radius:10px;margin-bottom:14px;font-size:13px;font-weight:600;display:flex;gap:8px;align-items:center;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

{{-- ======== BULK APPROVE BAR ======== --}}
<div class="bulk-bar" id="bulkBar">
    <div class="bulk-bar-info">
        <i class="fas fa-check-square" style="color:var(--gold);"></i>
        <span id="bulkCount">0</span> item dipilih
    </div>
    <form action="{{ route('admin.absensi.bulk-approve') }}" method="POST" id="bulkForm">
        @csrf
        <div id="bulkInputs"></div>
        <button type="submit" class="btn-pres btn-pres--green" onclick="return confirm('Setujui semua yang dipilih?')">
            <i class="fas fa-check-double"></i> Setujui Semua
        </button>
    </form>
</div>

{{-- ======== TABLE ======== --}}
<div class="pres-card">
    <div style="overflow-x:auto;">
        <table class="pres-table">
            <thead>
                <tr>
                    <th><input type="checkbox" class="pres-checkbox" id="checkAll" title="Pilih semua"></th>
                    <th>Tanggal</th>
                    <th>Jemaat</th>
                    <th>Jadwal / Ibadah</th>
                    <th>Status</th>
                    <th>Approval</th>
                    <th>Metode</th>
                    <th>Catatan</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensis as $absensi)
                <tr>
                    <td>
                        @if($absensi->approval_status == 'pending')
                        <input type="checkbox" class="pres-checkbox row-check"
                               value="{{ $absensi->id }}" data-id="{{ $absensi->id }}">
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:700;font-size:13px;">
                            {{ $absensi->tanggal ? $absensi->tanggal->format('d M Y') : '-' }}
                        </div>
                        <div style="font-size:11px;color:var(--text-mid);">
                            {{ $absensi->tanggal ? $absensi->tanggal->format('l') : '' }}
                        </div>
                    </td>
                    <td>
                        <div class="jemaat-cell">
                            <div class="jemaat-avatar">
                                {{ strtoupper(substr($absensi->jemaat->nama_lengkap ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="jemaat-name">{{ $absensi->jemaat->nama_lengkap ?? 'Tidak Diketahui' }}</div>
                                <div class="jemaat-hp">{{ $absensi->jemaat->nomor_hp ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:13px;font-weight:600;color:var(--text-dark);">{{ $absensi->jenis_ibadah }}</div>
                        @if($absensi->schedule)
                        <div style="font-size:11px;color:var(--text-mid);">{{ $absensi->schedule->location ?? '' }}</div>
                        @endif
                    </td>
                    <td>
                        @if($absensi->status === 'hadir')
                            <span class="badge-pres badge-pres--hadir"><i class="fas fa-check"></i> Hadir</span>
                        @elseif($absensi->status === 'izin')
                            <span class="badge-pres badge-pres--izin"><i class="fas fa-file-alt"></i> Izin</span>
                        @else
                            <span class="badge-pres badge-pres--tidak"><i class="fas fa-times"></i> Tidak Hadir</span>
                        @endif
                    </td>
                    <td>
                        @if($absensi->approval_status === 'pending')
                            <span class="badge-pres badge-pres--pending">⏳ Menunggu</span>
                        @elseif($absensi->approval_status === 'approved')
                            <span class="badge-pres badge-pres--approved">✅ Disetujui</span>
                            @if($absensi->approved_at)
                            <div style="font-size:10px;color:var(--text-mid);margin-top:2px;">
                                {{ $absensi->approved_at->format('d M, H:i') }}
                            </div>
                            @endif
                        @else
                            <span class="badge-pres badge-pres--rejected">❌ Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if($absensi->input_method === 'qr')
                            <span class="method-badge"><i class="fas fa-qrcode"></i> QR</span>
                        @elseif($absensi->input_method === 'admin')
                            <span class="method-badge"><i class="fas fa-user-shield"></i> Admin</span>
                        @else
                            <span class="method-badge"><i class="fas fa-user"></i> Manual</span>
                        @endif
                    </td>
                    <td>
                        <div class="catatan-text" title="{{ $absensi->catatan_admin ?? $absensi->alasan_izin ?? '-' }}">
                            {{ Str::limit($absensi->catatan_admin ?? $absensi->alasan_izin ?? '-', 35) }}
                        </div>
                    </td>
                    <td>
                        @if($absensi->approval_status == 'pending')
                        <div class="action-btns" style="justify-content:center;">
                            <form action="{{ route('admin.absensi.approve', $absensi) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-pres btn-pres--green btn-pres--sm" title="Setujui">
                                    <i class="fas fa-check"></i> Setujui
                                </button>
                            </form>
                            <button type="button" class="btn-pres btn-pres--danger btn-pres--sm"
                                    onclick="openRejectModal({{ $absensi->id }})" title="Tolak">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </div>
                        @else
                        <div style="text-align:center;color:var(--text-light);font-size:12px;">—</div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="pres-empty">
                            <i class="fas fa-clipboard-list"></i>
                            <p>Tidak ada data presensi yang ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pres-pagination">{{ $absensis->links() }}</div>
</div>

{{-- ======== REJECT MODAL ======== --}}
<div class="modal-overlay" id="rejectModal">
    <div class="modal-box">
        <div class="modal-title">❌ Tolak Presensi</div>
        <form id="rejectForm" method="POST">
            @csrf @method('PATCH')
            <label class="modal-label">Catatan Admin (opsional)</label>
            <textarea name="catatan_admin" class="modal-textarea"
                      placeholder="Tuliskan alasan penolakan..."></textarea>
            <div class="modal-footer">
                <button type="button" class="btn-pres btn-pres--outline" onclick="closeRejectModal()">Batal</button>
                <button type="submit" class="btn-pres btn-pres--danger">
                    <i class="fas fa-times-circle"></i> Tolak Presensi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Reject modal
function openRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/absensi/' + id + '/reject';
    document.getElementById('rejectModal').classList.add('active');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('active');
}
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});

// Bulk select
const checkAll    = document.getElementById('checkAll');
const rowChecks   = document.querySelectorAll('.row-check');
const bulkBar     = document.getElementById('bulkBar');
const bulkCount   = document.getElementById('bulkCount');
const bulkInputs  = document.getElementById('bulkInputs');

function updateBulkBar() {
    const checked = document.querySelectorAll('.row-check:checked');
    bulkBar.classList.toggle('active', checked.length > 0);
    bulkCount.textContent = checked.length;
    bulkInputs.innerHTML = '';
    checked.forEach(c => {
        const inp = document.createElement('input');
        inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = c.value;
        bulkInputs.appendChild(inp);
    });
}

checkAll.addEventListener('change', function() {
    rowChecks.forEach(c => c.checked = this.checked);
    updateBulkBar();
});
rowChecks.forEach(c => c.addEventListener('change', updateBulkBar));
</script>

@endsection
