@extends('layouts.dashboard')

@section('title', 'Manajemen Acara Mendatang')
@section('page-title', 'Manajemen Acara Mendatang')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-calendar-check" style="color:var(--gold);margin-right:8px;"></i> Daftar Acara Mendatang</h3>
        <a href="{{ route('admin.schedules.create') }}" class="btn-primary" style="padding: 8px 16px; border-radius: 8px; font-size: 13px; background: var(--gold); color: white;">
            <i class="fas fa-plus"></i> Tambah
        </a>
    </div>
    
    @if(session('success'))
        <div style="background:#f0fdf4; border-left:4px solid var(--success); padding:16px; margin: 16px; border-radius:8px;">
            <div style="display:flex; align-items:center; color:#166534; font-weight:600;">
                <i class="fas fa-check-circle" style="margin-right:8px;"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Emoji</th>
                    <th>Nama Acara</th>
                    <th>Hari</th>
                    <th>Waktu</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                    <tr>
                        <td style="font-size: 24px; text-align:center;">{{ $schedule->emoji ?? '🗓️' }}</td>
                        <td style="font-weight: 500;">{{ $schedule->title }}</td>
                        <td>
                            @if($schedule->day)
                                <span style="background:var(--gold-light); color:var(--gold-dark); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">
                                    {{ $schedule->day }}
                                </span>
                            @else
                                <span style="color:var(--text-light); font-size: 13px;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($schedule->start_time)
                                <i class="far fa-clock text-gray-400"></i> {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} 
                                @if($schedule->end_time)
                                    - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                                @endif
                            @else
                                <span style="color:var(--text-light); font-size: 13px;">-</span>
                            @endif
                        </td>
                        <td>
                            <span style="display: inline-block; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 13px;">
                                {{ $schedule->description }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.schedules.edit', $schedule) }}" style="color: var(--info); margin-right: 10px;"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus acara ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: var(--danger); background: none; border: none; cursor: pointer;"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4" style="color: var(--text-mid);">Belum ada data acara mendatang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
