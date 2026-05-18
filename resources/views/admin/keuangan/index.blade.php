@extends('layouts.dashboard')
@section('title', 'Manajemen Keuangan')
@section('page-title', 'Keuangan')

@section('content')

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    @foreach([
        ['Total Saldo',  'Rp '.number_format($stats['saldo'],0,',','.'), 'fas fa-wallet',      '#d4af37', '#fdf8e7'],
        ['Pemasukan',  'Rp '.number_format($stats['pemasukan'],0,',','.'),               'fas fa-arrow-down', '#059669', '#d1fae5'],
        ['Pengeluaran',        'Rp '.number_format($stats['pengeluaran'],0,',','.'),   'fas fa-arrow-up',     '#e11d48', '#ffe4e6'],
        ['Pemasukan Bulan Ini',         'Rp '.number_format($stats['bulan_ini'],0,',','.'),                        'fas fa-calendar',        '#2563eb', '#dbeafe'],
    ] as [$label, $val, $icon, $color, $bg])
    <div style="background:#fff;border-radius:12px;padding:18px;box-shadow:var(--shadow);border-left:4px solid {{ $color }};display:flex;align-items:center;gap:14px;">
        <div style="width:42px;height:42px;border-radius:10px;background:{{ $bg }};color:{{ $color }};display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0;">
            <i class="{{ $icon }}"></i>
        </div>
        <div>
            <div style="font-size:11px;font-weight:600;color:var(--text-mid);text-transform:uppercase;letter-spacing:.4px;">{{ $label }}</div>
            <div style="font-size:16px;font-weight:700;color:var(--text-dark);margin-top:2px;">{{ $val }}</div>
        </div>
    </div>
    @endforeach
</div>

<!-- Graph Container -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3>📊 Grafik Keuangan Bulanan</h3>
    </div>
    <div class="card-body" style="padding:20px;">
        <canvas id="keuanganChart" width="400" height="150"></canvas>
    </div>
</div>

{{-- Include Chart.js from CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('keuanganChart').getContext('2d');
        const labels = {!! json_encode(array_map(function($m){return $m['month'];}, $monthlyData)) !!};
        const pemasukanData = {!! json_encode(array_map(function($m){return $m['pemasukan'];}, $monthlyData)) !!};
        const pengeluaranData = {!! json_encode(array_map(function($m){return $m['pengeluaran'];}, $monthlyData)) !!};
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: pemasukanData,
                        backgroundColor: 'rgba(16, 185, 129, 0.5)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pengeluaran',
                        data: pengeluaranData,
                        backgroundColor: 'rgba(239, 68, 68, 0.5)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>

{{-- Filter + Table --}}
<div style="background:#fff;border-radius:12px;box-shadow:var(--shadow);overflow:hidden;">
    <div style="padding:16px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div style="font-family:'Playfair Display',serif;font-size:15px;font-weight:700;">Daftar Transaksi</div>
        
        <div style="display:flex; gap: 10px;">
            <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;">
                <input type="text" name="search" placeholder="Cari kategori..." value="{{ request('search') }}" class="psb-filter">
                <select name="tipe" class="psb-filter" onchange="this.form.submit()">
                    <option value="">Semua Tipe</option>
                    <option value="pemasukan" {{ request('tipe') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ request('tipe') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
                <button type="submit" class="topbar-btn" style="padding: 0 15px; font-size: 12px;">Filter</button>
            </form>
            <a href="{{ route('admin.keuangan.create') }}" class="topbar-logout" style="padding: 8px 16px; font-size: 12.5px;">+ Tambah</a>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#faf8f5;">
                    @foreach(['Tanggal','Tipe','Kategori','Jumlah','Keterangan', 'Aksi'] as $h)
                    <th style="text-align:left;padding:11px 16px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-mid);border-bottom:1px solid var(--border);">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($keuangan as $item)
                <tr style="border-bottom:1px solid #f3f1ec;">
                    <td style="padding:13px 16px;font-size:12px;color:var(--text-mid);">{{ $item->tanggal_transaksi ? \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y') : '-' }}</td>
                    <td style="padding:13px 16px;">
                        @php 
                        $c = $item->tipe == 'pemasukan' ? ['#d1fae5','#065f46'] : ['#ffe4e6','#e11d48']; 
                        @endphp
                        <span style="background:{{ $c[0] }};color:{{ $c[1] }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ ucfirst($item->tipe) }}</span>
                    </td>
                    <td style="padding:13px 16px;font-size:13px;font-weight:600;color:var(--text-dark);">{{ $item->kategori }}</td>
                    <td style="padding:13px 16px;font-size:14px;font-weight:700;color:var(--text-dark);">
                        <span style="{{ $item->tipe == 'pengeluaran' ? 'color: #e11d48;' : 'color: #059669;' }}">
                            {{ $item->tipe == 'pengeluaran' ? '-' : '+' }} Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                        </span>
                    </td>
                    <td style="padding:13px 16px;font-size:12px;color:var(--text-mid);"><span title="{{ $item->keterangan }}">{{ Str::limit($item->keterangan, 30) }}</span></td>
                    <td style="padding:13px 16px;">
                        <div style="display:flex; gap:5px;">
                            <a href="{{ route('admin.keuangan.edit', $item) }}" class="topbar-btn" style="padding: 5px 8px;"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.keuangan.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?');" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button class="topbar-btn" style="color:var(--danger); padding: 5px 8px;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="padding:40px;text-align:center;color:var(--text-light);">Belum ada data keuangan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding:16px 22px;">{{ $keuangan->links() }}</div>
</div>

<style>
.psb-filter { border:1.5px solid #e5e0d5;border-radius:8px;padding:7px 12px;font-size:12.5px;outline:none;background:#fff;cursor:pointer; }
.psb-filter:focus { border-color:var(--gold); }
</style>
@endsection
