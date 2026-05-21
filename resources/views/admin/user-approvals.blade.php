@extends('layouts.dashboard')

@section('title', 'Persetujuan User')
@section('page-title', 'Persetujuan Registrasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>📂 Manajemen Registrasi Jemaat</h3>
    </div>
    <div class="card-body" style="padding:0;">
        <!-- Tabs -->
        <div style="display:flex; border-bottom:1px solid var(--border); padding:0 20px; overflow-x:auto;">
            <button onclick="showTab('pending')" id="tab-pending-btn" class="tab-btn active" style="padding:15px 20px; border:none; background:none; cursor:pointer; font-weight:700; color:var(--gold); border-bottom:2px solid var(--gold); white-space:nowrap;">
                Menunggu ({{ $pendingUsers->count() }})
            </button>
            <button onclick="showTab('approved')" id="tab-approved-btn" class="tab-btn" style="padding:15px 20px; border:none; background:none; cursor:pointer; font-weight:700; color:var(--text-mid); white-space:nowrap;">
                Disetujui ({{ $approvedUsers->count() }})
            </button>
            <button onclick="showTab('rejected')" id="tab-rejected-btn" class="tab-btn" style="padding:15px 20px; border:none; background:none; cursor:pointer; font-weight:700; color:var(--text-mid); white-space:nowrap;">
                Ditolak ({{ $rejectedUsers->count() }})
            </button>
        </div>

        <!-- Pending Tab -->
        <div id="pending-content" class="tab-content">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Diajukan Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingUsers as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div style="display:flex; gap:10px;">
                                        <form action="{{ route('admin.user-approvals.approve', $user->id) }}" method="POST" style="margin:0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="topbar-logout" style="padding:6px 12px; font-size:11px; background:var(--success);">Setujui</button>
                                        </form>
                                        <button onclick="openRejectModal({{ $user->id }}, '{{ $user->name }}')" class="topbar-logout" style="padding:6px 12px; font-size:11px; background:var(--danger);">Tolak</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding:30px;">Tidak ada pendaftaran baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Approved Tab -->
        <div id="approved-content" class="tab-content" style="display:none;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Disetujui Oleh</th>
                            <th>Disetujui Pada</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($approvedUsers as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span style="font-weight:600; color:var(--text-dark);">
                                        {{ $user->approvedBy->name ?? 'System/Auto' }}
                                    </span>
                                </td>
                                <td>{{ $user->approved_at ? $user->approved_at->format('d/m/Y H:i') : $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <select id="role-{{ $user->id }}" onchange="updateUserRole({{ $user->id }})" style="padding:6px 10px; border-radius:6px; border:1px solid var(--border); background:var(--bg-body); color:var(--text-dark); font-size:13px; cursor:pointer;">
                                        <option value="jemaat" {{ $user->role === 'jemaat' ? 'selected' : '' }}>Jemaat</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </td>
                                <td>
                                    <button onclick="confirmRoleChange({{ $user->id }}, document.getElementById('role-{{ $user->id }}').value)" class="topbar-logout" style="padding:6px 12px; font-size:11px; background:var(--gold); color:#1e1a14;">Simpan</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:30px;">Belum ada user yang disetujui.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rejected Tab -->
        <div id="rejected-content" class="tab-content" style="display:none;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Ditolak Oleh</th>
                            <th>Ditolak Pada</th>
                            <th>Alasan Penolakan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rejectedUsers as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span style="font-weight:600; color:var(--text-dark);">
                                        {{ $user->rejectedBy->name ?? 'System/Auto' }}
                                    </span>
                                </td>
                                <td>{{ $user->rejected_at ? $user->rejected_at->format('d/m/Y H:i') : '-' }}</td>
                                <td><span style="color:var(--danger); font-size:12px;">{{ $user->rejection_reason }}</span></td>
                                <td>
                                    <form action="{{ route('admin.user-approvals.approve', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="topbar-btn" style="font-size:10px; padding:4px 8px;">Pulihkan (Setujui)</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:30px;">Tidak ada riwayat penolakan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Penolakan -->
<div id="rejectModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center; backdrop-filter:blur(5px);">
    <div class="card" style="max-width:400px; width:90%;">
        <div class="card-header">
            <h3>Tolak Registrasi</h3>
            <button onclick="closeRejectModal()" style="background:none; border:none; color:var(--text-mid); cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body">
            <p id="rejectInfo" style="font-size:13px; margin-bottom:15px; color:var(--text-mid);"></p>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group" style="margin-bottom:15px;">
                    <label style="display:block; margin-bottom:5px; font-size:12px; font-weight:700;">Alasan Penolakan</label>
                    <textarea name="rejection_reason" required style="width:100%; padding:10px; border-radius:8px; border:1px solid var(--border); background:var(--bg-body); color:var(--text-dark); min-height:80px;"></textarea>
                </div>
                <button type="submit" class="topbar-logout" style="width:100%; padding:12px; background:var(--danger);">Konfirmasi Tolak</button>
            </form>
        </div>
    </div>
</div>

<script>
function showTab(tab) {
    // Hide all
    document.getElementById('pending-content').style.display = 'none';
    document.getElementById('approved-content').style.display = 'none';
    document.getElementById('rejected-content').style.display = 'none';

    // Reset buttons
    const btns = ['pending', 'approved', 'rejected'];
    btns.forEach(b => {
        document.getElementById('tab-' + b + '-btn').style.color = 'var(--text-mid)';
        document.getElementById('tab-' + b + '-btn').style.borderBottom = 'none';
    });

    // Show selected
    document.getElementById(tab + '-content').style.display = 'block';
    document.getElementById('tab-' + tab + '-btn').style.color = 'var(--gold)';
    document.getElementById('tab-' + tab + '-btn').style.borderBottom = '2px solid var(--gold)';
}

function openRejectModal(id, name) {
    document.getElementById('rejectInfo').innerText = "Berikan alasan penolakan untuk pendaftaran " + name + ":";
    document.getElementById('rejectForm').action = "{{ url('admin/user-approvals') }}/" + id + "/reject";
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}

function confirmRoleChange(userId, newRole) {
    Swal.fire({
        title: 'Ubah Role',
        text: 'Anda yakin ingin mengubah role user ini ke ' + (newRole === 'admin' ? 'Admin' : 'Jemaat') + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ubah',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d4af37',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if (result.isConfirmed) {
            updateUserRole(userId, newRole, true);
        }
    });
}

function updateUserRole(userId, newRole = null, confirmed = false) {
    if (!newRole) {
        newRole = document.getElementById('role-' + userId).value;
    }

    if (!confirmed) {
        confirmRoleChange(userId, newRole);
        return;
    }

    const formData = new FormData();
    formData.append('role', newRole);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PATCH');

    fetch('{{ url("admin/user-approvals") }}/' + userId + '/update-role', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d4af37'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', data.message || 'Gagal mengubah role', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Terjadi kesalahan', 'error');
    });
}
</script>
@endsection
