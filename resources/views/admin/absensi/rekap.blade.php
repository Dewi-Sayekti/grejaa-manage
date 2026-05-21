@extends('layouts.dashboard')

@section('title', 'Rekap Presensi')
@section('page-title', 'Rekap & Grafik Presensi')

@section('content')

<style>
.rekap-wrap { display: flex; flex-direction: column; gap: 1.5rem; }

/* Filter */
.filter-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
}

.filter-row { display: grid; grid-template-columns: 1fr 1fr auto auto; gap: 1rem; align-items: end; }

.form-group label {
    display: block;
    font-size: .78rem;
    font-weight: 700;
    color: var(--text-mid);
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: .4rem;
}

.form-group select,
.form-group input[type="month"] {
    width: 100%;
    padding: .6rem .9rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: .9rem;
    background: var(--card-bg);
    color: var(--text-dark);
}

.btn { padding: .65rem 1.2rem; border-radius: 8px; font-size: .85rem; font-weight: 600; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: .4rem; text-decoration: none; transition: all .2s; }
.btn-gold   { background: var(--gold); color: #1e1a14; }
.btn-gold:hover   { background: var(--gold-dark); }
.btn-green  { background: var(--success); color: #fff; }
.btn-green:hover  { background: #059669; }
.btn-red    { background: var(--danger); color: #fff; }
.btn-red:hover    { background: #dc2626; }
.btn-outline { background: transparent; border: 1.5px solid var(--border); color: var(--text-mid); }

/* Grafik cards */
.charts-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }

.chart-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    padding: 1.5rem;
}

.chart-card h3 {
    font-size: .95rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1.2rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}

.chart-wrap { position: relative; height: 240px; }

/* Tabel rekap */
.rekap-table-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
}

.table-header {
    padding: 1.2rem 1.5rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.table-header h3 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: .5rem;
}

.export-btns { display: flex; gap: .6rem; }

table.rekap-tbl { width: 100%; border-collapse: collapse; }
table.rekap-tbl th {
    text-align: left;
    padding: .7rem 1rem;
    font-size: .75rem;
    font-weight: 700;
    color: var(--text-mid);
    text-transform: uppercase;
    letter-spacing: .05em;
    background: #f9f8f6;
    border-bottom: 1px solid var(--border);
}

table.rekap-tbl td { padding: .8rem 1rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
table.rekap-tbl tr:last-child td { border-bottom: none; }
table.rekap-tbl tr:hover td { background: #faf9f7; }

.nama-jemaat { font-weight: 600; font-size: .9rem; }

.stat-pill {
    display: inline-block;
    padding: .2rem .65rem;
    border-radius: 20px;
    font-size: .78rem;
    font-weight: 700;
    min-width: 32px;
    text-align: center;
}

.pill-hadir    { background: #d1fae5; color: #065f46; }
.pill-izin     { background: #dbeafe; color: #1e40af; }
.pill-tidak    { background: #fee2e2; color: #991b1b; }

/* Progress bar persentase */
.persen-wrap { display: flex; align-items: center; gap: .6rem; }
.persen-bar-bg { flex: 1; height: 8px; background: var(--border); border-radius: 4px; overflow: hidden; max-width: 100px; }
.persen-bar { height: 100%; border-radius: 4px; transition: width .4s ease; }
.persen-text { font-size: .82rem; font-weight: 700; min-width: 36px; }

.badge-rank {
    display: inline-block;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    font-size: .72rem;
    font-weight: 700;
    text-align: center;
    line-height: 24px;
    color: #fff;
}

.rank-1 { background: #d4af37; }
.rank-2 { background: #9ca3af; }
.rank-3 { background: #b45309; }
.rank-n { background: var(--border); color: var(--text-mid); }

@media (max-width: 900px) {
    .charts-grid { grid-template-columns: 1fr; }
    .filter-row  { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
    .filter-row { grid-template-columns: 1fr; }
}
</style>

<div class="rekap-wrap">

    {{-- Filter --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.absensi.rekap') }}">
            <div class="filter-row">
                <div class="form-group">
                    <label>Bulan</label>
                    <input type="month" name="bulan" value="{{ $bulan }}">
                </div>
                <div class="form-group">
                    <label>Filter Jemaat (Opsional)</label>
                    <select name="jemaat_id">
                        <option value="">Semua Jemaat</option>
                        @foreach($jemaats as $j)
                            <option value="{{ $j->id }}" {{ $jemaatId == $j->id ? 'selected' : '' }}>
                                {{ $j->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-gold">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                </div>
                <div>
                    <a href="{{ route('admin.absensi.rekap') }}" class="btn btn-outline">Reset</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Grafik --}}
    <div class="charts-grid">
        {{-- Grafik per Schedule --}}
        <div class="chart-card">
            <h3><i class="fas fa-chart-bar" style="color:var(--gold)"></i> Kehadiran per Jadwal &mdash; {{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}</h3>
            <div class="chart-wrap">
                <canvas id="chartSchedule"></canvas>
            </div>
        </div>

        {{-- Grafik Pie / Donut --}}
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie" style="color:var(--gold)"></i> Distribusi Status Bulan Ini</h3>
            <div class="chart-wrap">
                <canvas id="chartPie"></canvas>
            </div>
        </div>
    </div>

    {{-- Tabel Rekap Per Jemaat --}}
    <div class="rekap-table-card">
        <div class="table-header">
            <h3>
                <i class="fas fa-table" style="color:var(--gold)"></i>
                Rekap Kehadiran &mdash; {{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}
                @if($jemaatId)
                    <span style="font-size:.8rem;color:var(--text-mid);font-weight:400">(filter: 1 jemaat)</span>
                @endif
            </h3>
            <div class="export-btns">
                <a href="{{ route('admin.absensi.export.excel', ['bulan' => $bulan, 'jemaat_id' => $jemaatId]) }}" class="btn btn-green">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="{{ route('admin.absensi.export.pdf', ['bulan' => $bulan, 'jemaat_id' => $jemaatId]) }}" class="btn btn-red">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>

        @if($rekapJemaat->isEmpty())
            <div style="text-align:center;padding:3rem;color:var(--text-light);">
                <i class="fas fa-inbox" style="font-size:2rem;margin-bottom:.5rem;display:block;opacity:.4"></i>
                Tidak ada data untuk bulan ini.
            </div>
        @else
        <table class="rekap-tbl">
            <thead>
                <tr>
                    <th width="40">No</th>
                    <th>Nama Jemaat</th>
                    <th>Hadir</th>
                    <th>Izin</th>
                    <th>Tidak Hadir</th>
                    <th>Total</th>
                    <th>Persentase Hadir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekapJemaat as $i => $row)
                <tr>
                    <td>
                        @if($i < 3)
                            <span class="badge-rank rank-{{ $i + 1 }}">{{ $i + 1 }}</span>
                        @else
                            <span class="badge-rank rank-n">{{ $i + 1 }}</span>
                        @endif
                    </td>
                    <td><div class="nama-jemaat">{{ $row->jemaat?->nama_lengkap ?? '-' }}</div></td>
                    <td><span class="stat-pill pill-hadir">{{ $row->total_hadir }}</span></td>
                    <td><span class="stat-pill pill-izin">{{ $row->total_izin }}</span></td>
                    <td><span class="stat-pill pill-tidak">{{ $row->total_tidak_hadir }}</span></td>
                    <td style="font-weight:600">{{ $row->total }}</td>
                    <td>
                        <div class="persen-wrap">
                            <div class="persen-bar-bg">
                                <div class="persen-bar" style="
                                    width: {{ $row->persentase }}%;
                                    background: {{ $row->persentase >= 80 ? 'var(--success)' : ($row->persentase >= 50 ? 'var(--warning)' : 'var(--danger)') }};
                                "></div>
                            </div>
                            <span class="persen-text" style="color: {{ $row->persentase >= 80 ? 'var(--success)' : ($row->persentase >= 50 ? 'var(--warning)' : 'var(--danger)') }}">
                                {{ $row->persentase }}%
                            </span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const grafikData = @json($grafikSchedule);
const gold = '#d4af37';
const green = '#10b981';
const blue = '#3b82f6';
const red = '#ef4444';

// Grafik Bar per Schedule
new Chart(document.getElementById('chartSchedule'), {
    type: 'bar',
    data: {
        labels: grafikData.map(d => d.schedule),
        datasets: [
            { label: 'Hadir',        data: grafikData.map(d => d.hadir),        backgroundColor: green + 'cc' },
            { label: 'Izin',         data: grafikData.map(d => d.izin),         backgroundColor: blue  + 'cc' },
            { label: 'Tidak Hadir',  data: grafikData.map(d => d.tidak_hadir),  backgroundColor: red   + 'cc' },
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});

// Total untuk pie
const totalHadir = grafikData.reduce((s, d) => s + d.hadir, 0);
const totalIzin  = grafikData.reduce((s, d) => s + d.izin, 0);
const totalTidak = grafikData.reduce((s, d) => s + d.tidak_hadir, 0);

new Chart(document.getElementById('chartPie'), {
    type: 'doughnut',
    data: {
        labels: ['Hadir', 'Izin', 'Tidak Hadir'],
        datasets: [{
            data: [totalHadir, totalIzin, totalTidak],
            backgroundColor: [green, blue, red],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        const total = totalHadir + totalIzin + totalTidak;
                        const pct   = total > 0 ? Math.round(ctx.raw / total * 100) : 0;
                        return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                    }
                }
            }
        },
        cutout: '65%'
    }
});
</script>
@endpush

@endsection
