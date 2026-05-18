@extends('layouts.dashboard')
@section('title', 'Pendaftar: '.$news->title)
@section('page-title', 'Pendaftar Acara')

@section('content')

<div style="margin-bottom:18px;">
    <a href="{{ route('admin.news.index') }}" style="color:var(--gold-dark);font-size:13px;text-decoration:none;">
        <i class="fas fa-arrow-left"></i> Kembali ke Berita & Acara
    </a>
    <h2 style="font-family:'Playfair Display',serif;font-size:20px;margin:8px 0 0;color:var(--text-dark);">{{ $news->title }}</h2>
    @if($news->tanggal_acara)
    <div style="font-size:12px;color:var(--text-mid);margin-top:4px;">
        <i class="fas fa-calendar" style="color:var(--gold);"></i>
        {{ $news->tanggal_acara->translatedFormat('l, d F Y · H:i') }} WIB
    </div>
    @endif
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:22px;">
    @foreach([
        ['Total Daftar',   $stats['total'],     'fas fa-list',         '#d4af37', '#fdf8e7'],
        ['Menunggu',       $stats['pending'],   'fas fa-hourglass-half','#d97706', '#fef3c7'],
        ['Terkonfirmasi',  $stats['confirmed'], 'fas fa-check-circle', '#059669', '#d1fae5'],
        ['Dibatalkan',     $stats['cancelled'], 'fas fa-times-circle', '#dc2626', '#fee2e2'],
        ['Total Peserta',  $stats['peserta'],   'fas fa-users',        '#2563eb', '#dbeafe'],
    ] as [$label, $val, $icon, $color, $bg])
    <div style="background:#fff;border-radius:10px;padding:14px 16px;box-shadow:var(--shadow);border-left:3px solid {{ $color }};display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:9px;background:{{ $bg }};color:{{ $color }};display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;">
            <i class="{{ $icon }}"></i>
        </div>
        <div>
            <div style="font-size:10.5px;font-weight:600;color:var(--text-mid);text-transform:uppercase;letter-spacing:.4px;">{{ $label }}</div>
            <div style="font-size:22px;font-weight:700;color:var(--text-dark);">{{ $val }}</div>
        </div>
    </div>
    @endforeach
</div>

@if(session('success'))
<div style="padding:12px 18px;background:#d1fae5;color:#065f46;border-radius:9px;margin-bottom:16px;font-size:13px;display:flex;align-items:center;gap:8px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- Tabel --}}
<div style="background:#fff;border-radius:14px;box-shadow:var(--shadow);overflow:hidden;">
    <div style="padding:16px 22px;border-bottom:1px solid var(--border);font-family:'Playfair Display',serif;font-size:15px;font-weight:700;">
        Daftar Peserta
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#faf8f5;">
                    @foreach(['Waktu Daftar','Nama','Kontak','Peserta','Catatan','Status','Aksi'] as $h)
                    <th style="text-align:left;padding:11px 16px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-mid);border-bottom:1px solid var(--border);white-space:nowrap;">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($registrasis as $reg)
                <tr style="border-bottom:1px solid #f3f1ec;">
                    <td style="padding:13px 16px;font-size:12px;color:var(--text-mid);">
                        {{ $reg->created_at->format('d M Y') }}<br>
                        <span style="font-size:11px;">{{ $reg->created_at->format('H:i') }}</span>
                    </td>
                    <td style="padding:13px 16px;">
                        <div style="font-size:13px;font-weight:600;color:var(--text-dark);">{{ $reg->nama_lengkap }}</div>
                        <div style="font-size:11px;color:var(--text-light);margin-top:2px;">{{ $reg->kode_registrasi }}</div>
                    </td>
                    <td style="padding:13px 16px;font-size:12px;color:var(--text-mid);">
                        {{ $reg->email }}<br>{{ $reg->nomor_hp ?? '—' }}
                    </td>
                    <td style="padding:13px 16px;font-size:14px;font-weight:700;color:var(--text-dark);text-align:center;">
                        {{ $reg->jumlah_peserta }}
                    </td>
                    <td style="padding:13px 16px;font-size:12px;color:var(--text-mid);max-width:150px;">
                        {{ $reg->catatan ? \Illuminate\Support\Str::limit($reg->catatan, 50) : '—' }}
                    </td>
                    <td style="padding:13px 16px;">
                        @php
                            $colors = ['confirmed'=>['#d1fae5','#065f46'],'pending'=>['#fef3c7','#92400e'],'cancelled'=>['#fee2e2','#991b1b']];
                            $c = $colors[$reg->status];
                        @endphp
                        <span style="background:{{ $c[0] }};color:{{ $c[1] }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                            {{ $reg->status_label }}
                        </span>
                        @if($reg->confirmed_at)
                        <div style="font-size:10px;color:var(--text-light);margin-top:3px;">{{ $reg->confirmed_at->format('d M Y') }}</div>
                        @endif
                    </td>
                    <td style="padding:13px 16px;">
                        @if($reg->status === 'pending')
                        <form action="{{ route('admin.acara.confirm', $reg) }}" method="POST" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" style="background:#d1fae5;color:#065f46;border:none;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:600;cursor:pointer;margin-right:5px;">
                                <i class="fas fa-check"></i> Konfirmasi
                            </button>
                        </form>
                        <form action="{{ route('admin.acara.reject', $reg) }}" method="POST" style="display:inline;" onsubmit="return confirm('Batalkan pendaftaran ini?')">
                            @csrf @method('PATCH')
                            <button type="submit" style="background:#fee2e2;color:#dc2626;border:none;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:600;cursor:pointer;">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </form>
                        @elseif($reg->status === 'confirmed')
                        <form action="{{ route('admin.acara.reject', $reg) }}" method="POST" style="display:inline;" onsubmit="return confirm('Batalkan konfirmasi?')">
                            @csrf @method('PATCH')
                            <button type="submit" style="background:#fee2e2;color:#dc2626;border:none;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:600;cursor:pointer;">
                                <i class="fas fa-ban"></i> Batalkan
                            </button>
                        </form>
                        @else
                        <span style="color:#ccc;font-size:12px;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="padding:40px;text-align:center;color:var(--text-light);">Belum ada pendaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px 22px;">{{ $registrasis->links() }}</div>
</div>
@endsection