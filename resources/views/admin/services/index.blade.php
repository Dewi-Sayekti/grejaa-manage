@extends('layouts.dashboard')

@section('title', 'Manajemen Pelayanan')
@section('page-title', 'Manajemen Pelayanan')

@section('content')

<div style="margin-bottom: 20px; display: flex; gap: 10px;">
    @php
        $categories = [
            'jadwal_ibadah' => 'Jadwal Ibadah',
            'kelompok_kecil' => 'Kelompok Kecil',
            'sekolah_minggu' => 'Sekolah Minggu',
            'musik' => 'Musik & Nyanyian'
        ];
    @endphp
    @foreach($categories as $key => $label)
        <a href="{{ route('admin.services.index', ['category' => $key]) }}" 
           style="padding: 8px 16px; border-radius: 8px; font-size: 14px; text-decoration: none; 
                  background: {{ $category == $key ? 'var(--gold)' : '#fff' }}; 
                  color: {{ $category == $key ? '#fff' : 'var(--text-dark)' }};
                  border: 1px solid {{ $category == $key ? 'var(--gold)' : 'var(--border)' }};">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-video" style="color:var(--gold);margin-right:8px;"></i> Konten: {{ $categories[$category] }}</h3>
        <a href="{{ route('admin.services.create', ['category' => $category]) }}" class="btn-primary" style="padding: 8px 16px; border-radius: 8px; font-size: 13px; background: var(--gold); color: white;">
            <i class="fas fa-plus"></i> Tambah Video/Link
        </a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Link Video</th>
                    <th>File Video</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contents as $item)
                    <tr>
                        <td style="font-weight: 500;">{{ $item->title }}</td>
                        <td>
                            @if($item->video_link)
                                <a href="{{ $item->video_link }}" target="_blank" style="color: var(--info);"><i class="fas fa-external-link-alt"></i> Buka Link</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->video_path)
                                <span class="badge badge-success">Diunggah</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.services.edit', $item) }}" style="color: var(--info); margin-right: 10px;"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.services.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: var(--danger); background: none; border: none; cursor: pointer;"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">Belum ada konten untuk kategori ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
