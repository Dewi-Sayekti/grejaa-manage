@extends('layouts.dashboard')

@section('title', 'Berita & Pengumuman')
@section('page-title', 'Berita & Pengumuman')

@section('content')

<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.news.index') }}" style="padding: 8px 16px; border-radius: 8px; font-size: 14px; text-decoration: none; background: var(--gold); color: #fff; border: 1px solid var(--gold); margin-right: 10px;">Berita & Pengumuman</a>
    <a href="{{ route('admin.schedules.index') }}" style="padding: 8px 16px; border-radius: 8px; font-size: 14px; text-decoration: none; background: #fff; color: var(--text-dark); border: 1px solid var(--border);">Jadwal Ibadah</a>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-newspaper" style="color:var(--gold);margin-right:8px;"></i> Daftar Berita & Acara</h3>
        <a href="{{ route('admin.news.create') }}" class="btn-primary" style="padding: 8px 16px; border-radius: 8px; font-size: 13px; background: var(--gold); color: white; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
            <i class="fas fa-plus"></i> Tambah Berita
        </a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal Publikasi</th>
                    <th>Judul</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Pendaftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $item)
                <tr>
                    <td style="font-size:13px;color:var(--text-mid);">
                        {{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->format('d M Y') : '-' }}
                    </td>
                    <td style="font-weight:500; font-size:13px;">
                        {{ $item->title }}
                        @if($item->tanggal_acara)
                        <div style="font-size:11px; color:var(--text-light); margin-top:2px;">
                            <i class="fas fa-calendar" style="color:var(--gold);"></i>
                            {{ $item->tanggal_acara->format('d M Y, H:i') }} WIB
                        </div>
                        @endif
                    </td>
                    <td>
                        @if($item->is_event)
                            <span style="background:#dbeafe; color:#1e40af; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600;">
                                <i class="fas fa-calendar-star"></i> Acara
                            </span>
                        @else
                            <span style="background:#f3f4f6; color:#6b7280; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600;">
                                <i class="fas fa-newspaper"></i> Berita
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($item->is_published)
                            <span class="badge badge-success">Terbit</span>
                        @else
                            <span class="badge badge-warning">Draft</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($item->is_event)
                            <a href="{{ route('admin.acara.registrasi', $item) }}"
                               style="display:inline-flex; align-items:center; gap:5px; background:var(--gold-light); color:var(--gold-dark); padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; text-decoration:none;">
                                <i class="fas fa-users"></i>
                                {{ $item->registrasis_count ?? $item->registrasis()->count() }} pendaftar
                            </a>
                        @else
                            <span style="color:#ccc; font-size:12px;">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.news.edit', $item) }}"
                           style="color:var(--info); margin-right:10px; font-size:13px;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.news.destroy', $item) }}" method="POST"
                              style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    style="color:var(--danger); background:none; border:none; cursor:pointer; font-size:13px;">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px; color:var(--text-light);">
                        Belum ada berita atau acara.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection