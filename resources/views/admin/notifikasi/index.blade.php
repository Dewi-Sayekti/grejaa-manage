@extends('layouts.dashboard')

@section('title', 'Manajemen Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>📢 Daftar Notifikasi</h3>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.notifikasi.create') }}" class="topbar-logout" style="padding: 8px 16px;">+ Buat Baru</a>
            <button type="button" class="topbar-btn" onclick="showSendAllModal();" style="padding: 8px 16px; border: 1px solid var(--gold); background: transparent; color: var(--gold);">🚀 Kirim Massal</button>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tipe</th>
                        <th>Untuk</th>
                        <th>Tanggal Kirim</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifikasi as $notif)
                        <tr>
                            <td><strong>{{ $notif->judul }}</strong></td>
                            <td>
                                <span class="badge @if($notif->tipe == 'penting') badge-danger @elseif($notif->tipe == 'event') badge-info @else badge-success @endif">
                                    {{ ucfirst($notif->tipe) }}
                                </span>
                            </td>
                            <td>
                                @if($notif->jemaat_id)
                                    <i class="fas fa-user" style="font-size:10px; margin-right:5px;"></i> {{ $notif->jemaat->nama_lengkap ?? 'Dihapus' }}
                                @else
                                    <i class="fas fa-users" style="font-size:10px; margin-right:5px;"></i> <em>Semua Jemaat</em>
                                @endif
                            </td>
                            <td>{{ $notif->tanggal_kirim ? $notif->tanggal_kirim->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <div style="display:flex; gap:5px;">
                                    <a href="{{ route('admin.notifikasi.edit', $notif) }}" class="topbar-btn"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.notifikasi.destroy', $notif) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="topbar-btn" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px;">Belum ada notifikasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:20px;">
            {{ $notifikasi->links() }}
        </div>
    </div>
</div>

<!-- Modal Kirim ke Semua -->
<div id="sendAllModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    <div class="card" style="max-width: 500px; width: 90%; border: 1px solid var(--border);">
        <div class="card-header">
            <h3>Kirim Notifikasi Massal</h3>
            <button onclick="hideSendAllModal();" style="background:none; border:none; color:var(--text-mid); cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.notifikasi.send-to-all') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom:15px;">
                    <label style="display:block; margin-bottom:5px; font-size:12px; font-weight:700;">Judul</label>
                    <input type="text" name="judul" required placeholder="Judul pengumuman" style="width:100%; padding:10px; border-radius:8px; border:1px solid var(--border); background:var(--bg-body); color:var(--text-dark);">
                </div>
                <div class="form-group" style="margin-bottom:15px;">
                    <label style="display:block; margin-bottom:5px; font-size:12px; font-weight:700;">Isi Pesan</label>
                    <textarea name="isi" required placeholder="Tulis informasi di sini..." style="width:100%; padding:10px; border-radius:8px; border:1px solid var(--border); background:var(--bg-body); color:var(--text-dark); min-height:100px;"></textarea>
                </div>
                <div class="form-group" style="margin-bottom:20px;">
                    <label style="display:block; margin-bottom:5px; font-size:12px; font-weight:700;">Kategori</label>
                    <select name="tipe" required style="width:100%; padding:10px; border-radius:8px; border:1px solid var(--border); background:var(--bg-body); color:var(--text-dark);">
                        <option value="pengumuman">Pengumuman</option>
                        <option value="penting">Penting</option>
                        <option value="event">Event</option>
                    </select>
                </div>
                <button type="submit" class="topbar-logout" style="width:100%; padding:12px; font-size:14px;">📤 Kirim Sekarang</button>
            </form>
        </div>
    </div>
</div>

<script>
function showSendAllModal() {
    document.getElementById('sendAllModal').style.display = 'flex';
}
function hideSendAllModal() {
    document.getElementById('sendAllModal').style.display = 'none';
}
</script>
@endsection
