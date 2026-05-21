@extends('layouts.dashboard')

@section('title', 'Presensi Massal')
@section('page-title', 'Input Presensi Massal')

@section('content')

<style>
.massal-wrap { display: flex; flex-direction: column; gap: 1.5rem; }

.filter-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
}

.filter-card h3 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}

.filter-row {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 1rem;
    align-items: end;
}

.form-group label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: var(--text-mid);
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: .4rem;
}

.form-group select,
.form-group input {
    width: 100%;
    padding: .6rem .9rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: .9rem;
    background: var(--card-bg);
    color: var(--text-dark);
    transition: border-color .2s;
}

.form-group select:focus,
.form-group input:focus {
    outline: none;
    border-color: var(--gold);
}

.btn { padding: .65rem 1.4rem; border-radius: 8px; font-size: .9rem; font-weight: 600; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: .4rem; transition: all .2s; }
.btn-gold  { background: var(--gold); color: #1e1a14; }
.btn-gold:hover  { background: var(--gold-dark); }
.btn-green { background: var(--success); color: #fff; }
.btn-green:hover { background: #059669; }
.btn-outline { background: transparent; border: 1.5px solid var(--border); color: var(--text-mid); }
.btn-outline:hover { border-color: var(--gold); color: var(--gold); }

/* Table presensi massal */
.massal-table-wrap {
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
}

.massal-table-header {
    padding: 1.2rem 1.5rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.massal-table-header h3 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: .5rem;
}

.massal-info {
    font-size: .82rem;
    color: var(--text-mid);
    background: var(--gold-light);
    padding: .3rem .8rem;
    border-radius: 20px;
    font-weight: 500;
}

.quick-actions {
    display: flex;
    gap: .5rem;
    padding: .8rem 1.5rem;
    background: #fafafa;
    border-bottom: 1px solid var(--border);
}

.quick-label { font-size: .8rem; color: var(--text-mid); font-weight: 500; align-self: center; margin-right: .3rem; }

.btn-quick {
    padding: .3rem .9rem;
    border-radius: 20px;
    font-size: .78rem;
    font-weight: 600;
    cursor: pointer;
    border: 1.5px solid;
    background: transparent;
    transition: all .15s;
}

.btn-quick.hadir    { border-color: var(--success); color: var(--success); }
.btn-quick.hadir:hover    { background: var(--success); color: #fff; }
.btn-quick.izin     { border-color: var(--info); color: var(--info); }
.btn-quick.izin:hover     { background: var(--info); color: #fff; }
.btn-quick.tidak    { border-color: var(--danger); color: var(--danger); }
.btn-quick.tidak:hover    { background: var(--danger); color: #fff; }

table.massal-tbl { width: 100%; border-collapse: collapse; }
table.massal-tbl th {
    text-align: left;
    padding: .7rem 1rem;
    font-size: .78rem;
    font-weight: 700;
    color: var(--text-mid);
    text-transform: uppercase;
    letter-spacing: .05em;
    background: #f9f8f6;
    border-bottom: 1px solid var(--border);
}

table.massal-tbl td {
    padding: .75rem 1rem;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}

table.massal-tbl tr:last-child td { border-bottom: none; }
table.massal-tbl tr:hover td { background: #faf9f7; }

.jemaat-nama { font-weight: 600; font-size: .9rem; color: var(--text-dark); }
.jemaat-hp   { font-size: .78rem; color: var(--text-light); }

.status-radio { display: flex; gap: .5rem; }

.radio-option {
    position: relative;
}

.radio-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
}

.radio-option label {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    padding: .35rem .75rem;
    border-radius: 20px;
    font-size: .78rem;
    font-weight: 600;
    cursor: pointer;
    border: 1.5px solid var(--border);
    color: var(--text-mid);
    transition: all .15s;
    user-select: none;
}

.radio-option input[value="hadir"]:checked + label   { background: #d1fae5; border-color: var(--success); color: #065f46; }
.radio-option input[value="izin"]:checked + label     { background: #dbeafe; border-color: var(--info);    color: #1e40af; }
.radio-option input[value="tidak_hadir"]:checked + label { background: #fee2e2; border-color: var(--danger); color: #991b1b; }
.radio-option label:hover { border-color: var(--gold); color: var(--gold); }

.existing-badge {
    font-size: .7rem;
    padding: .2rem .5rem;
    border-radius: 10px;
    font-weight: 600;
}

.existing-badge.hadir       { background: #d1fae5; color: #065f46; }
.existing-badge.izin        { background: #dbeafe; color: #1e40af; }
.existing-badge.tidak_hadir { background: #fee2e2; color: #991b1b; }

.save-bar {
    padding: 1.2rem 1.5rem;
    background: var(--gold-light);
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.save-info { font-size: .88rem; color: #7a6a20; font-weight: 500; }

@media (max-width: 640px) {
    .filter-row { grid-template-columns: 1fr; }
    .status-radio { flex-wrap: wrap; }
}
</style>

<div class="massal-wrap">

    {{-- Filter Pilih Schedule & Tanggal --}}
    <div class="filter-card">
        <h3><i class="fas fa-sliders-h" style="color:var(--gold)"></i> Pilih Jadwal & Tanggal</h3>
        <form method="GET" action="{{ route('admin.absensi.massal') }}">
            <div class="filter-row">
                <div class="form-group">
                    <label>Jadwal Ibadah</label>
                    <select name="schedule_id" required>
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach($schedules as $s)
                            <option value="{{ $s->id }}" {{ request('schedule_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->emoji }} {{ $s->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal', today()->toDateString()) }}" max="{{ today()->toDateString() }}" required>
                </div>
                <div>
                    <button type="submit" class="btn btn-gold">
                        <i class="fas fa-search"></i> Tampilkan Jemaat
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if($selectedSchedule && isset($jemaats))
    {{-- Form Input Massal --}}
    <form method="POST" action="{{ route('admin.absensi.massal.store') }}">
        @csrf
        <input type="hidden" name="schedule_id" value="{{ $selectedSchedule->id }}">
        <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">

        <div class="massal-table-wrap">
            <div class="massal-table-header">
                <h3>
                    <i class="fas fa-users" style="color:var(--gold)"></i>
                    {{ $selectedSchedule->emoji }} {{ $selectedSchedule->title }}
                    &mdash; {{ \Carbon\Carbon::parse($selectedTanggal)->translatedFormat('l, d F Y') }}
                </h3>
                <span class="massal-info">{{ $jemaats->count() }} Jemaat Aktif</span>
            </div>

            {{-- Tombol Isi Cepat Semua --}}
            <div class="quick-actions">
                <span class="quick-label">Isi semua:</span>
                <button type="button" class="btn-quick hadir" onclick="setAll('hadir')">
                    <i class="fas fa-check"></i> Hadir Semua
                </button>
                <button type="button" class="btn-quick izin" onclick="setAll('izin')">
                    <i class="fas fa-file-alt"></i> Izin Semua
                </button>
                <button type="button" class="btn-quick tidak" onclick="setAll('tidak_hadir')">
                    <i class="fas fa-times"></i> Tidak Hadir Semua
                </button>
            </div>

            <table class="massal-tbl">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Jemaat</th>
                        <th>Status Absensi</th>
                        <th>Data Sebelumnya</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jemaats as $i => $jemaat)
                    @php
                        $existingStatus = $existingAbsensis[$jemaat->id] ?? null;
                    @endphp
                    <tr>
                        <td style="color:var(--text-light);font-size:.82rem;">{{ $i + 1 }}</td>
                        <td>
                            <div class="jemaat-nama">{{ $jemaat->nama_lengkap }}</div>
                            @if($jemaat->nomor_hp)
                                <div class="jemaat-hp"><i class="fas fa-phone" style="font-size:.7rem"></i> {{ $jemaat->nomor_hp }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="status-radio">
                                <div class="radio-option">
                                    <input type="radio" name="absensis[{{ $jemaat->id }}]"
                                           id="hadir_{{ $jemaat->id }}" value="hadir"
                                           {{ ($existingStatus ?? 'hadir') === 'hadir' ? 'checked' : '' }}>
                                    <label for="hadir_{{ $jemaat->id }}">
                                        <i class="fas fa-check"></i> Hadir
                                    </label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" name="absensis[{{ $jemaat->id }}]"
                                           id="izin_{{ $jemaat->id }}" value="izin"
                                           {{ $existingStatus === 'izin' ? 'checked' : '' }}>
                                    <label for="izin_{{ $jemaat->id }}">
                                        <i class="fas fa-file-alt"></i> Izin
                                    </label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" name="absensis[{{ $jemaat->id }}]"
                                           id="tidak_{{ $jemaat->id }}" value="tidak_hadir"
                                           {{ $existingStatus === 'tidak_hadir' ? 'checked' : '' }}>
                                    <label for="tidak_{{ $jemaat->id }}">
                                        <i class="fas fa-times"></i> Tidak Hadir
                                    </label>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($existingStatus)
                                <span class="existing-badge {{ $existingStatus }}">
                                    {{ match($existingStatus) {
                                        'hadir'       => '✓ Sudah: Hadir',
                                        'izin'        => '~ Sudah: Izin',
                                        'tidak_hadir' => '✗ Sudah: Tidak Hadir',
                                        default       => $existingStatus,
                                    } }}
                                </span>
                            @else
                                <span style="font-size:.75rem;color:var(--text-light)">Belum ada</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="save-bar">
                <div class="save-info">
                    <i class="fas fa-info-circle"></i>
                    Data yang sudah ada akan diperbarui. Semua status langsung disetujui.
                </div>
                <div style="display:flex;gap:.7rem;">
                    <a href="{{ route('admin.absensi.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-green">
                        <i class="fas fa-save"></i> Simpan Presensi Massal
                    </button>
                </div>
            </div>
        </div>
    </form>

    @else
    <div style="text-align:center;padding:3rem;color:var(--text-light);">
        <i class="fas fa-arrow-up" style="font-size:2rem;margin-bottom:1rem;display:block;color:var(--gold);opacity:.5"></i>
        Pilih jadwal dan tanggal di atas untuk menampilkan daftar jemaat.
    </div>
    @endif

</div>

@push('scripts')
<script>
function setAll(status) {
    document.querySelectorAll(`input[type="radio"][value="${status}"]`).forEach(r => r.checked = true);
}
</script>
@endpush

@endsection
