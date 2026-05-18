@extends('layouts.dashboard')

@section('title', 'Manajemen Pemimpin')
@section('page-title', 'Manajemen Pemimpin')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-tie" style="color:var(--gold);margin-right:8px;"></i> Daftar Pemimpin</h3>
        <a href="{{ route('admin.pastors.create') }}" class="btn-primary" style="padding: 8px 16px; border-radius: 8px; font-size: 13px; background: var(--gold); color: white;">
            <i class="fas fa-plus"></i> Tambah
        </a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Peran/Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pastors as $pastor)
                    <tr>
                        <td>
                            @if($pastor->image_path)
                                <img src="{{ asset($pastor->image_path) }}" alt="{{ $pastor->name }}" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                            @else
                                <div style="width: 50px; height: 50px; border-radius: 50%; background: #eee; display: flex; align-items: center; justify-content: center;"><i class="fas fa-user text-gray-400"></i></div>
                            @endif
                        </td>
                        <td style="font-weight: 500;">{{ $pastor->name }}</td>
                        <td>{{ $pastor->role }}</td>
                        <td>
                            <a href="{{ route('admin.pastors.edit', $pastor) }}" style="color: var(--info); margin-right: 10px;"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.pastors.destroy', $pastor) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: var(--danger); background: none; border: none; cursor: pointer;"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">Belum ada data pemimpin.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
