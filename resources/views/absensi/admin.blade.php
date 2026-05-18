@extends('layouts.dashboard')

@section('title', 'Manajemen Absensi')
@section('page-title', 'Manajemen Absensi')

@section('content')

{{-- Stats --}}
<div class="adm-stats">
    <div class="adm-stat adm-stat--orange">
        <div class="adm-stat-icon"><i class="fas fa-hourglass-half"></i></div>
        <div><div class="adm-stat-label">Menunggu</div><div class="adm-stat-value">{{ $stats['pending'] }}</div></div>
    </div>
    <div class="adm-stat adm-stat--green">
        <div class="adm-stat-icon"><i class="fas fa-check-circle"></i></div>
        <div><div class="adm-stat-label">Disetujui</div><div class="adm-stat-value">{{ $stats['approved'] }}</div></div>
    </div>
    <div class="adm-stat adm-stat--red">
        <div class="adm-stat-icon"><i class="fas fa-times-circle"></i></div>
        <div><div class="adm-stat-label">Ditolak</div><div class="adm-stat-value">{{ $stats['rejected'] }}</div></div>
    </div>
    <div class="adm-stat adm-stat--blue">
        <div class="adm-stat-icon"><i class="fas fa-clipboard-list"></i></div>
        <div><div class="adm-stat-label">Total</div><div class="adm-stat-value">{{ $stats['total'] }}</div></div>
    </div>
</div>

{{-- Filter --}}
<form method="GET" class="adm-filter-bar">
    <select name="status" class="adm-filter-input">
        <option value="">Semua Status</option>
        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
    </select>
    <select name="jemaat_id" class="adm-filter-input">
        <option value="">Semua Jemaat</option>
        @foreach($jemaats as $j)
            <option value="{{ $j->id }}" {{ request('jemaat_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_lengkap }}</option>
        @endforeach
    </select>
    <input type="date" name="tanggal" class="adm-filter-input" value="{{ request('tanggal') }}">
    <button type="submit" class="adm-filter-btn"><i class="fas fa-search"></i> Filter</button>
    <a href="{{ route('admin.absensi.index') }}" class="adm-filter-reset">Reset</a>

    @if($stats['pending'] > 0)
    <button type="button" onclick="bulkApproveAll()" class="adm-bulk-btn">
        <i class="fas fa-check-double"></i> Setujui Semua Pending ({{ $stats['pending'] }})
    </button>
    @endif
</form>

{{-- Table --}}
<div class="adm-card">
    <div class="adm-card-header">
        <span>Daftar Absensi Jemaat</span>
        <span class="adm-total">{{ $absensis->total() }} total</span>
    </div>

    @if($absensis->count() > 0)
    <div style="overflow-x:auto;">
        <table class="adm-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll" style="cursor:pointer;"></th>
                    <th>Jemaat</th>
                    <th>Tanggal</th>
                    <th>Jenis Ibadah</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Persetujuan</th>
                    <th>Disetujui Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensis as $abs)
                <tr class="{{ $abs->approval_status === 'pending' ? 'row-pending' : '' }}">
                    <td>
                        @if($abs->approval_status === 'pending')
                        <input type="checkbox" class="abs-chk" value="{{ $abs->id }}" style="cursor:pointer;">
                        @endif
                    </td>
                    <td>
                        <div class="adm-jemaat-cell">
                            <div class="adm-avatar">{{ strtoupper(substr($abs->jemaat->nama_lengkap ?? 'J', 0, 1)) }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $abs->jemaat->nama_lengkap ?? '-' }}</div>
                                <div style="font-size:11px;color:var(--text-light);">{{ $abs->jemaat->nomor_hp ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $abs->tanggal->format('d M Y') }}</div>
                        <div style="font-size:11px;color:var(--text-light);">{{ $abs->tanggal->format('l') }}</div>
                    </td>
                    <td style="font-size:13px;">{{ $abs->jenis_ibadah }}</td>
                    <td>
                        @if($abs->status === 'hadir')
                            <span class="badge badge-success">Hadir</span>
                        @elseif($abs->status === 'izin')
                            <span class="badge badge-info">Izin</span>
                        @else
                            <span class="badge badge-danger">Tidak Hadir</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--text-mid);max-width:140px;">{{ $abs->alasan_izin ?? $abs->keterangan ?? '-' }}</td>
                    <td>
                        @if($abs->approval_status === 'pending')
                            <span class="badge badge-warning">⏳ Menunggu</span>
                        @elseif($abs->approval_status === 'approved')
                            <span class="badge badge-success">✅ Disetujui</span>
                        @else
                            <span class="badge badge-danger">❌ Ditolak</span>
                        @endif
                    </td>
                    <td style="font-size:12px;">
                        @if($abs->approvedBy)
                            <div style="font-weight:600;">{{ $abs->approvedBy->name }}</div>
                            <div style="color:var(--text-light);">{{ $abs->approved_at ? $abs->approved_at->format('d M Y') : '' }}</div>
                        @else
                            <span style="color:var(--text-light);">-</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;align-items:center;">
                            @if($abs->approval_status === 'pending')
                                {{-- Approve --}}
                                <form action="{{ route('admin.absensi.approve', $abs->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="adm-action-btn adm-btn-approve" title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>

                                {{-- Reject --}}
                                <button onclick="showRejectModal({{ $abs->id }})" class="adm-action-btn adm-btn-reject" title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                            @elseif($abs->approval_status === 'approved')
                                <span style="font-size:11px;color:var(--success);">Selesai</span>
                            @else
                                <span style="font-size:11px;color:var(--text-light);">{{ $abs->catatan_admin ? Str::limit($abs->catatan_admin, 30) : 'Ditolak' }}</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:16px 20px;">
        {{ $absensis->appends(request()->query())->links() }}
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-clipboard"></i>
        <p>Belum ada data absensi.
        @if(request()->filled('status') || request()->filled('jemaat_id') || request()->filled('tanggal'))
            <br>Coba ubah filter pencarian.
        @endif
        </p>
    </div>
    @endif
</div>

{{-- Reject Modal --}}
<div id="rejectModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:14px;padding:28px;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <h3 style="font-family:'Playfair Display',serif;font-size:18px;margin-bottom:16px;color:var(--text-dark);">
            <i class="fas fa-times-circle" style="color:var(--danger);margin-right:8px;"></i>Tolak Absensi
        </h3>
        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')
            <div style="margin-bottom:16px;">
                <label style="font-size:12px;font-weight:600;color:var(--text-mid);display:block;margin-bottom:6px;">Catatan / Alasan Penolakan (opsional)</label>
                <textarea name="catatan_admin" rows="3" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-family:inherit;font-size:13px;resize:vertical;" placeholder="Tulis alasan penolakan..."></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeRejectModal()" style="padding:10px 20px;background:#f3f4f6;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Batal</button>
                <button type="submit" style="padding:10px 20px;background:var(--danger);color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;"><i class="fas fa-times"></i> Tolak</button>
            </div>
        </form>
    </div>
</div>

{{-- Bulk Approve Form --}}
<form id="bulkForm" action="{{ route('admin.absensi.bulk-approve') }}" method="POST" style="display:none;">
    @csrf
    <div id="bulkIds"></div>
</form>

<style>
.adm-stats {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px;
}
.adm-stat {
    background: #fff; border-radius: 12px; padding: 16px;
    display: flex; align-items: center; gap: 12px;
    box-shadow: var(--shadow); border-left: 4px solid;
}
.adm-stat--orange { border-color: #f59e0b; }
.adm-stat--green  { border-color: #059669; }
.adm-stat--red    { border-color: var(--danger); }
.adm-stat--blue   { border-color: #2563eb; }
.adm-stat-icon { width: 40px; height: 40px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 17px; }
.adm-stat--orange .adm-stat-icon { background: #fef3c7; color: #d97706; }
.adm-stat--green  .adm-stat-icon { background: #d1fae5; color: #059669; }
.adm-stat--red    .adm-stat-icon { background: #fce4ec; color: var(--danger); }
.adm-stat--blue   .adm-stat-icon { background: #dbeafe; color: #2563eb; }
.adm-stat-label { font-size: 11px; font-weight: 600; color: var(--text-mid); text-transform: uppercase; letter-spacing: 0.4px; }
.adm-stat-value { font-size: 24px; font-weight: 700; color: var(--text-dark); }

.adm-filter-bar {
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    background: #fff; border-radius: 10px; padding: 14px 16px;
    box-shadow: var(--shadow); margin-bottom: 20px;
}
.adm-filter-input {
    padding: 8px 12px; border: 1px solid var(--border); border-radius: 7px;
    font-size: 13px; color: var(--text-dark); background: #fff;
    outline: none; transition: border-color 0.2s; font-family: inherit;
}
.adm-filter-input:focus { border-color: var(--gold); }
.adm-filter-btn {
    padding: 8px 16px; background: var(--gold); color: var(--sidebar-bg);
    border: none; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; gap: 6px; transition: opacity 0.2s;
}
.adm-filter-btn:hover { opacity: 0.85; }
.adm-filter-reset { font-size: 13px; color: var(--text-mid); text-decoration: none; }
.adm-filter-reset:hover { color: var(--danger); }
.adm-bulk-btn {
    margin-left: auto; padding: 8px 16px;
    background: #059669; color: #fff;
    border: none; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; gap: 6px; transition: opacity 0.2s;
}
.adm-bulk-btn:hover { opacity: 0.9; }

.adm-card { background: #fff; border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; }
.adm-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-bottom: 1px solid var(--border);
    font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 700; color: var(--text-dark);
}
.adm-total { font-size: 12px; font-weight: 500; color: var(--text-mid); }
.adm-table { width: 100%; border-collapse: collapse; }
.adm-table th {
    text-align: left; padding: 11px 14px; font-size: 11px; font-weight: 600;
    text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-mid);
    background: #faf8f5; border-bottom: 1px solid var(--border);
}
.adm-table td { padding: 12px 14px; border-bottom: 1px solid #f3f1ec; }
.adm-table tbody tr:hover { background: #fdfcfa; }
.row-pending { background: #fffbeb !important; }
.adm-jemaat-cell { display: flex; align-items: center; gap: 10px; }
.adm-avatar {
    width: 32px; height: 32px; border-radius: 50%;
    background: var(--gold-light); color: var(--gold-dark);
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700; flex-shrink: 0;
}
.adm-action-btn {
    width: 30px; height: 30px; border-radius: 6px; border: none;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 12px; cursor: pointer; transition: all 0.2s;
}
.adm-btn-approve { background: #d1fae5; color: #059669; }
.adm-btn-approve:hover { background: #059669; color: #fff; }
.adm-btn-reject { background: #fce4ec; color: var(--danger); }
.adm-btn-reject:hover { background: var(--danger); color: #fff; }

@media (max-width: 1024px) { .adm-stats { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px) { .adm-stats { grid-template-columns: 1fr; } }
</style>

<script>
// Select all checkboxes
document.getElementById('checkAll')?.addEventListener('change', function() {
    document.querySelectorAll('.abs-chk').forEach(c => c.checked = this.checked);
});

// Reject modal
function showRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/absensi/' + id + '/reject';
    document.getElementById('rejectModal').style.display = 'flex';
}
function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}

// Bulk approve
function bulkApproveAll() {
    if (!confirm('Setujui semua absensi yang masih menunggu?')) return;
    const form = document.getElementById('bulkForm');
    const container = document.getElementById('bulkIds');
    container.innerHTML = '';
    document.querySelectorAll('.abs-chk:checked').forEach(function(chk) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = chk.value;
        container.appendChild(input);
    });
    // If none checked, collect all pending
    if (container.querySelectorAll('input').length === 0) {
        document.querySelectorAll('.abs-chk').forEach(function(chk) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = chk.value;
            container.appendChild(input);
        });
    }
    form.submit();
}

// Close modal on backdrop click
document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>

@endsection