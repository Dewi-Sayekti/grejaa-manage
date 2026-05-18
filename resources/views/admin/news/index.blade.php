@extends('layouts.dashboard')

@section('title', 'Berita & Pengumuman')
@section('page-title', 'Berita & Pengumuman')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>📰 Daftar Berita & Acara</h3>
        <a href="{{ route('admin.news.create') }}" class="topbar-logout" style="padding: 8px 16px;">+ Tambah Berita</a>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Dibuat Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $item)
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    @if($item->image_path)
                                        <img src="{{ Storage::url($item->image_path) }}" style="width:40px; height:40px; border-radius:5px; object-fit:cover;">
                                    @else
                                        <div style="width:40px; height:40px; border-radius:5px; background:var(--bg-body); display:flex; align-items:center; justify-content:center; color:var(--text-light);"><i class="fas fa-image"></i></div>
                                    @endif
                                    <strong>{{ $item->title }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $item->is_event ? 'badge-info' : 'badge-success' }}">
                                    {{ $item->is_event ? 'Event/Acara' : 'Berita' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $item->is_published ? 'badge-success' : 'badge-warning' }}">
                                    {{ $item->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div style="display:flex; gap:5px;">
                                    <a href="{{ route('admin.news.edit', $item) }}" class="topbar-btn"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.news.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus berita ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="topbar-btn" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px;">Belum ada berita atau pengumuman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:20px;">
            {{ $news->links() }}
        </div>
    </div>
</div>
@endsection
