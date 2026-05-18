@extends('layouts.dashboard')

@section('title', 'Jadwal Ibadah')
@section('page-title', 'Jadwal Ibadah')

@section('content')

{{-- Stats --}}
<div class="sc-stats">
    <div class="sc-stat sc-stat--gold">
        <div class="sc-stat-icon"><i class="fas fa-calendar-alt"></i></div>
        <div><div class="sc-stat-lbl">Total Jadwal</div><div class="sc-stat-val">{{ $stats['total'] }}</div></div>
    </div>
    <div class="sc-stat sc-stat--green">
        <div class="sc-stat-icon"><i class="fas fa-check-circle"></i></div>
        <div><div class="sc-stat-lbl">Aktif</div><div class="sc-stat-val">{{ $stats['aktif'] }}</div></div>
    </div>
    <div class="sc-stat sc-stat--blue">
        <div class="sc-stat-icon"><i class="fas fa-sync"></i></div>
        <div><div class="sc-stat-lbl">Rutin Mingguan</div><div class="sc-stat-val">{{ $stats['rutin'] }}</div></div>
    </div>
    <div class="sc-stat sc-stat--purple">
        <div class="sc-stat-icon"><i class="fas fa-clipboard-check"></i></div>
        <div><div class="sc-stat-lbl">Total Absensi</div><div class="sc-stat-val">{{ $stats['absensi'] }}</div></div>
    </div>
</div>

{{-- Card --}}
<div class="sc-card">
    <div class="sc-card-header">
        <div>
            <div class="sc-card-title">Daftar Jadwal Ibadah</div>
            <div class="sc-card-sub">Kelola jadwal ibadah rutin dan acara gereja</div>
        </div>
        <a href="{{ route('admin.schedules.create') }}" class="sc-btn-add">
            <i class="fas fa-plus"></i> Tambah Jadwal
        </a>
    </div>

    @if(session('success'))
    <div class="sc-alert sc-alert--success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if($schedules->count() > 0)
    <div class="sc-table-wrap">
        <table class="sc-table">
            <thead>
                <tr>
                    <th width="40">Urut</th>
                    <th>Nama Jadwal</th>
                    <th>Hari / Tanggal</th>
                    <th>Waktu</th>
                    <th>Lokasi</th>
                    <th>Absensi</th>
                    <th>Status</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $sc)
                <tr>
                    <td class="sc-order-cell">
                        <span class="sc-order-badge">{{ $sc->order }}</span>
                    </td>
                    <td>
                        <div class="sc-name-cell">
                            <span class="sc-emoji">{{ $sc->emoji ?? '📅' }}</span>
                            <div>
                                <div class="sc-name">{{ $sc->title }}</div>
                                <div class="sc-desc">{{ Str::limit($sc->description, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($sc->tanggal)
                            <span class="sc-badge sc-badge--event">
                                <i class="fas fa-calendar-day"></i>
                                {{ $sc->tanggal->format('d M Y') }}
                            </span>
                        @elseif($sc->day)
                            <span class="sc-badge sc-badge--rutin">
                                <i class="fas fa-sync-alt"></i>
                                {{ $sc->day }}
                            </span>
                        @else
                            <span class="sc-badge sc-badge--none">—</span>
                        @endif
                    </td>
                    <td class="sc-time-cell">
                        @if($sc->start_time)
                            <i class="far fa-clock" style="color:var(--gold);"></i>
                            {{ \Carbon\Carbon::parse($sc->start_time)->format('H:i') }}
                            @if($sc->end_time) – {{ \Carbon\Carbon::parse($sc->end_time)->format('H:i') }} @endif
                            <span style="color:var(--text-light);font-size:11px;">WIB</span>
                        @else
                            <span style="color:var(--text-light);">—</span>
                        @endif
                    </td>
                    <td class="sc-loc-cell">
                        @if($sc->location)
                            <i class="fas fa-map-marker-alt" style="color:var(--text-light);font-size:11px;"></i>
                            {{ $sc->location }}
                        @else
                            <span style="color:var(--text-light);">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.schedules.show', $sc) }}" class="sc-absensi-count">
                            <i class="fas fa-users"></i> {{ $sc->absensis_count }}
                        </a>
                    </td>
                    <td>
                        <form action="{{ route('admin.schedules.toggle', $sc) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="sc-toggle {{ $sc->is_active ? 'sc-toggle--on' : 'sc-toggle--off' }}" title="{{ $sc->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <span class="sc-toggle-knob"></span>
                            </button>
                        </form>
                        <span class="sc-active-label {{ $sc->is_active ? 'sc-active-label--on' : 'sc-active-label--off' }}">
                            {{ $sc->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="sc-actions">
                            <a href="{{ route('admin.schedules.show', $sc) }}" class="sc-action-btn sc-action--view" title="Lihat Absensi">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.schedules.edit', $sc) }}" class="sc-action-btn sc-action--edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.schedules.destroy', $sc) }}" method="POST" onsubmit="return confirm('Hapus jadwal {{ addslashes($sc->title) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="sc-action-btn sc-action--del" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state" style="padding:60px 20px;">
        <i class="fas fa-calendar-times"></i>
        <p>Belum ada jadwal ibadah.<br>Klik <strong>Tambah Jadwal</strong> untuk mulai.</p>
        <a href="{{ route('admin.schedules.create') }}" class="sc-btn-add" style="display:inline-flex;margin-top:16px;">
            <i class="fas fa-plus"></i> Tambah Jadwal Pertama
        </a>
    </div>
    @endif
</div>

<style>
.sc-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
.sc-stat { background:#fff; border-radius:12px; padding:16px 18px; display:flex; align-items:center; gap:14px; box-shadow:var(--shadow); border-left:4px solid; }
.sc-stat--gold   { border-color:var(--gold); }
.sc-stat--green  { border-color:#059669; }
.sc-stat--blue   { border-color:#2563eb; }
.sc-stat--purple { border-color:#7c3aed; }
.sc-stat-icon { width:40px; height:40px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:17px; }
.sc-stat--gold   .sc-stat-icon { background:var(--gold-light); color:var(--gold-dark); }
.sc-stat--green  .sc-stat-icon { background:#d1fae5; color:#059669; }
.sc-stat--blue   .sc-stat-icon { background:#dbeafe; color:#2563eb; }
.sc-stat--purple .sc-stat-icon { background:#ede9fe; color:#7c3aed; }
.sc-stat-lbl { font-size:11px; font-weight:600; color:var(--text-mid); text-transform:uppercase; letter-spacing:.4px; }
.sc-stat-val { font-size:24px; font-weight:700; color:var(--text-dark); }

.sc-card { background:#fff; border-radius:12px; box-shadow:var(--shadow); overflow:hidden; }
.sc-card-header { display:flex; align-items:center; justify-content:space-between; padding:20px 24px; border-bottom:1px solid var(--border); }
.sc-card-title { font-family:'Playfair Display',serif; font-size:16px; font-weight:700; color:var(--text-dark); }
.sc-card-sub { font-size:12px; color:var(--text-mid); margin-top:2px; }
.sc-btn-add { display:inline-flex; align-items:center; gap:7px; padding:10px 18px; background:linear-gradient(135deg,var(--gold),var(--gold-dark)); color:#1e1a14; border-radius:8px; font-size:13px; font-weight:700; text-decoration:none; transition:opacity .2s; }
.sc-btn-add:hover { opacity:.88; color:#1e1a14; }

.sc-alert { padding:13px 20px; margin:16px 24px 0; border-radius:8px; font-size:13.5px; font-weight:500; display:flex; align-items:center; gap:8px; }
.sc-alert--success { background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; }

.sc-table-wrap { overflow-x:auto; }
.sc-table { width:100%; border-collapse:collapse; }
.sc-table th { text-align:left; padding:11px 16px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:var(--text-mid); background:#faf8f5; border-bottom:1px solid var(--border); }
.sc-table td { padding:13px 16px; border-bottom:1px solid #f3f1ec; vertical-align:middle; }
.sc-table tbody tr:hover { background:#fdfcfa; }

.sc-order-cell { text-align:center; }
.sc-order-badge { display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; background:var(--gold-light); color:var(--gold-dark); border-radius:6px; font-size:12px; font-weight:700; }

.sc-name-cell { display:flex; align-items:center; gap:12px; }
.sc-emoji { font-size:26px; flex-shrink:0; }
.sc-name { font-size:13.5px; font-weight:600; color:var(--text-dark); }
.sc-desc { font-size:11.5px; color:var(--text-mid); margin-top:2px; }

.sc-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:11.5px; font-weight:600; }
.sc-badge--rutin { background:var(--gold-light); color:var(--gold-dark); }
.sc-badge--event { background:#dbeafe; color:#1e40af; }
.sc-badge--none { color:var(--text-light); font-size:13px; }
.sc-time-cell { font-size:13px; font-weight:500; color:var(--text-dark); white-space:nowrap; }
.sc-loc-cell { font-size:13px; color:var(--text-mid); }

.sc-absensi-count { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; background:#ede9fe; color:#7c3aed; border-radius:20px; font-size:12px; font-weight:600; text-decoration:none; }
.sc-absensi-count:hover { background:#7c3aed; color:#fff; }

/* Toggle switch */
.sc-toggle { position:relative; display:inline-block; width:40px; height:22px; border:none; border-radius:11px; cursor:pointer; transition:background .3s; vertical-align:middle; }
.sc-toggle--on { background:var(--gold); }
.sc-toggle--off { background:#d1d5db; }
.sc-toggle-knob { position:absolute; top:3px; width:16px; height:16px; background:#fff; border-radius:50%; transition:left .3s; box-shadow:0 1px 3px rgba(0,0,0,.2); }
.sc-toggle--on .sc-toggle-knob { left:21px; }
.sc-toggle--off .sc-toggle-knob { left:3px; }
.sc-active-label { font-size:11.5px; font-weight:600; margin-left:6px; vertical-align:middle; }
.sc-active-label--on { color:#059669; }
.sc-active-label--off { color:var(--text-light); }

.sc-actions { display:flex; align-items:center; gap:6px; }
.sc-action-btn { width:30px; height:30px; border-radius:7px; border:none; display:inline-flex; align-items:center; justify-content:center; font-size:12px; cursor:pointer; transition:all .2s; text-decoration:none; }
.sc-action--view { background:#ede9fe; color:#7c3aed; } .sc-action--view:hover { background:#7c3aed; color:#fff; }
.sc-action--edit { background:#dbeafe; color:#2563eb; } .sc-action--edit:hover { background:#2563eb; color:#fff; }
.sc-action--del  { background:#fce4ec; color:var(--danger); } .sc-action--del:hover { background:var(--danger); color:#fff; }

@media(max-width:1024px){ .sc-stats{grid-template-columns:repeat(2,1fr);} }
@media(max-width:560px){ .sc-stats{grid-template-columns:1fr;} }
</style>
@endsection