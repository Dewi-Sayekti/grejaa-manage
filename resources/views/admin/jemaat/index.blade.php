@extends('layouts.dashboard')

@section('title', 'Data Jemaat')
@section('page-title', 'Manajemen Data Jemaat')

@section('content')
<div class="card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h3>👥 Daftar Jemaat</h3>
        <a href="{{ route('admin.jemaat.create') }}" class="topbar-logout"><i class="fas fa-plus"></i> Tambah Jemaat</a>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>J.Kelamin</th>
                        <th>TTL</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Status Perkawinan</th>
                        <th>Tgl Baptis</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jemaats as $jemaat)
                        <tr>
                            <td><strong>{{ $jemaat->nama_lengkap }}</strong></td>
                            <td>{{ $jemaat->jenis_kelamin }}</td>
                            <td>{{ $jemaat->tempat_lahir }}, {{ $jemaat->tanggal_lahir ? $jemaat->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                            <td>{{ $jemaat->alamat }}</td>
                            <td>{{ $jemaat->nomor_hp }}</td>
                            <td>{{ $jemaat->status_pernikahan }}</td>
                            <td>{{ $jemaat->tanggal_baptis ? $jemaat->tanggal_baptis->format('d/m/Y') : '-' }}</td>
                            <td>
                                <span class="badge {{ $jemaat->status_aktif == 'Aktif' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $jemaat->status_aktif }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex; gap:5px;">
                                    <a href="{{ route('admin.jemaat.edit', $jemaat) }}" class="topbar-btn" style="color:var(--info);" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.jemaat.destroy', $jemaat) }}" method="POST" onsubmit="return confirm('Hapus data jemaat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="topbar-btn" style="color:var(--danger);" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center; padding:20px;">Belum ada data jemaat.</td>
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
