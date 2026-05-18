@extends('layouts.dashboard')

@section('title', 'Data Jemaat')
@section('page-title', 'Manajemen Data Jemaat')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>👥 Daftar Jemaat</h3>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jemaats as $jemaat)
                        <tr>
                            <td><strong>{{ $jemaat->nama_lengkap }}</strong></td>
                            <td>{{ $jemaat->alamat }}</td>
                            <td>{{ $jemaat->nomor_hp }}</td>
                            <td>
                                <span class="badge {{ $jemaat->status_aktif == 'Aktif' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $jemaat->status_aktif }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex; gap:5px;">
                                    <a href="{{ route('admin.jemaat.show', $jemaat) }}" class="topbar-btn"><i class="fas fa-eye"></i></a>
                                    <form action="{{ route('admin.jemaat.destroy', $jemaat) }}" method="POST" onsubmit="return confirm('Hapus data jemaat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="topbar-btn" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:20px;">Belum ada data jemaat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:20px;">
            {{ $jemaats->links() }}
        </div>
    </div>
</div>
@endsection
