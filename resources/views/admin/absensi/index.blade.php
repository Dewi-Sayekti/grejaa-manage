@extends('layouts.dashboard')

@section('title', 'Manajemen Absensi')
@section('page-title', 'Absensi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>📋 Konfirmasi Kehadiran Jemaat</h3>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jemaat</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $absensi)
                        <tr>
                            <td>{{ $absensi->tanggal ? $absensi->tanggal->format('d/m/Y') : '-' }}</td>
                            <td>
                                <strong>{{ $absensi->jemaat->nama_lengkap ?? 'User' }}</strong>
                                <div style="font-size:11px; color:var(--text-light);">{{ $absensi->jemaat->nomor_hp ?? '' }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $absensi->status == 'hadir' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($absensi->status) }}
                                </span>
                            </td>
                            <td>{{ $absensi->keterangan ?? '-' }}</td>
                            <td>
                                @if($absensi->approval_status == 'pending')
                                    <div style="display:flex; gap:5px;">
                                        <form action="{{ route('admin.absensi.approve', $absensi) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="topbar-logout" style="padding:6px 12px; font-size:11px; background:var(--success);">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.absensi.reject', $absensi) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="topbar-logout" style="padding:6px 12px; font-size:11px; background:var(--danger);">Reject</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="badge {{ $absensi->approval_status == 'approved' ? 'badge-success' : 'badge-danger' }}">
                                        {{ ucfirst($absensi->approval_status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px;">Tidak ada pengajuan absensi yang perlu dikonfirmasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:20px;">
            {{ $absensis->links() }}
        </div>
    </div>
</div>
@endsection
