<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1e1a14; background: #fff; }

    .header { text-align: center; padding: 20px 0 16px; border-bottom: 2px solid #d4af37; margin-bottom: 20px; }
    .header h1 { font-size: 18px; color: #1e1a14; font-weight: bold; }
    .header h2 { font-size: 13px; color: #6b7280; font-weight: normal; margin-top: 4px; }
    .header .meta { font-size: 10px; color: #9ca3af; margin-top: 6px; }

    table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    thead th {
        background: #1e3a5f;
        color: #fff;
        padding: 8px 10px;
        text-align: left;
        font-size: 10px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    tbody tr:nth-child(even) td { background: #f7f5f0; }
    tbody tr:nth-child(odd)  td { background: #fff; }
    tbody td { padding: 7px 10px; border-bottom: 1px solid #e8e4dc; font-size: 10.5px; }

    .rank { display: inline-block; width: 20px; height: 20px; border-radius: 50%; text-align: center; line-height: 20px; font-size: 9px; font-weight: bold; color: #fff; }
    .rank-1 { background: #d4af37; }
    .rank-2 { background: #9ca3af; }
    .rank-3 { background: #b45309; }
    .rank-n { background: #e5e7eb; color: #6b7280; }

    .pill { display: inline-block; padding: 2px 8px; border-radius: 10px; font-weight: bold; font-size: 9.5px; }
    .pill-hadir { background: #d1fae5; color: #065f46; }
    .pill-izin  { background: #dbeafe; color: #1e40af; }
    .pill-tidak { background: #fee2e2; color: #991b1b; }

    .bar-wrap { display: flex; align-items: center; gap: 6px; }
    .bar-bg { width: 80px; height: 7px; background: #e5e7eb; border-radius: 4px; overflow: hidden; display: inline-block; vertical-align: middle; }
    .bar-fill { height: 100%; border-radius: 4px; }

    .summary { margin-top: 8px; padding: 12px 16px; background: #f7f5f0; border-left: 3px solid #d4af37; border-radius: 4px; }
    .summary p { font-size: 10px; color: #6b7280; margin-top: 3px; }

    .footer { margin-top: 30px; text-align: right; font-size: 9px; color: #9ca3af; border-top: 1px solid #e8e4dc; padding-top: 8px; }
</style>
</head>
<body>

<div class="header">
    <h1>Gereja YHS</h1>
    <h2>{{ $judul }}</h2>
    <div class="meta">Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i') }} WIB</div>
</div>

<table>
    <thead>
        <tr>
            <th width="30">No</th>
            <th>Nama Jemaat</th>
            <th width="60" style="text-align:center">Hadir</th>
            <th width="60" style="text-align:center">Izin</th>
            <th width="80" style="text-align:center">Tidak Hadir</th>
            <th width="50" style="text-align:center">Total</th>
            <th width="120">Persentase</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekapJemaat as $i => $row)
        <tr>
            <td style="text-align:center">
                <span class="rank rank-{{ $i < 3 ? $i + 1 : 'n' }}">{{ $i + 1 }}</span>
            </td>
            <td><strong>{{ $row->jemaat?->nama_lengkap ?? '-' }}</strong></td>
            <td style="text-align:center"><span class="pill pill-hadir">{{ $row->total_hadir }}</span></td>
            <td style="text-align:center"><span class="pill pill-izin">{{ $row->total_izin }}</span></td>
            <td style="text-align:center"><span class="pill pill-tidak">{{ $row->total_tidak_hadir }}</span></td>
            <td style="text-align:center;font-weight:bold">{{ $row->total }}</td>
            <td>
                <div class="bar-wrap">
                    <div class="bar-bg">
                        <div class="bar-fill" style="
                            width: {{ $row->persentase }}%;
                            background: {{ $row->persentase >= 80 ? '#10b981' : ($row->persentase >= 50 ? '#f59e0b' : '#ef4444') }};
                        "></div>
                    </div>
                    <strong style="color: {{ $row->persentase >= 80 ? '#065f46' : ($row->persentase >= 50 ? '#92400e' : '#991b1b') }}">
                        {{ $row->persentase }}%
                    </strong>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="summary">
    <strong>Ringkasan Bulan {{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}</strong>
    <p>Total Jemaat Terekam: {{ $rekapJemaat->count() }} orang</p>
    <p>Rata-rata Kehadiran: {{ $rekapJemaat->count() > 0 ? round($rekapJemaat->avg('persentase')) : 0 }}%</p>
    <p>Kehadiran Tertinggi: {{ $rekapJemaat->first()?->jemaat?->nama_lengkap ?? '-' }} ({{ $rekapJemaat->first()?->persentase ?? 0 }}%)</p>
</div>

<div class="footer">
    Dokumen ini digenerate otomatis oleh sistem Gereja YHS &bull; {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>
