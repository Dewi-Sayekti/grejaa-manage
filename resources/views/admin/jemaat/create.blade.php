@extends('layouts.dashboard')

@section('title', 'Tambah Data Jemaat')
@section('page-title', 'Tambah Data Jemaat')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>➕ Form Tambah Jemaat</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.jemaat.store') }}" method="POST">
            @csrf

            <div class="grid-2">
                <!-- Nama Lengkap -->
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                    @error('nama_lengkap')<div style="color:var(--danger); font-size:12px; margin-top:5px;">{{ $message }}</div>@enderror
                </div>

                <!-- Email -->
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                    @error('email')<div style="color:var(--danger); font-size:12px; margin-top:5px;">{{ $message }}</div>@enderror
                </div>

                <!-- Password -->
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Password</label>
                    <div style="position:relative;">
                        <input type="password" id="password" name="password" required style="width:100%; padding:10px; padding-right:44px; border:1px solid var(--border); border-radius:8px;">
                        <button type="button" onclick="toggleAdminPassword('password', 'eye-create-1')"
                            style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#9ca3af; padding:4px;">
                            <i id="eye-create-1" class="fas fa-eye-slash" style="font-size:15px;"></i>
                        </button>
                    </div>
                    @error('password')<div style="color:var(--danger); font-size:12px; margin-top:5px;">{{ $message }}</div>@enderror
                </div>

                <!-- Konfirmasi Password -->
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Konfirmasi Password</label>
                    <div style="position:relative;">
                        <input type="password" id="password_confirmation" name="password_confirmation" required style="width:100%; padding:10px; padding-right:44px; border:1px solid var(--border); border-radius:8px;">
                        <button type="button" onclick="toggleAdminPassword('password_confirmation', 'eye-create-2')"
                            style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#9ca3af; padding:4px;">
                            <i id="eye-create-2" class="fas fa-eye-slash" style="font-size:15px;"></i>
                        </button>
                    </div>
                </div>

                <!-- Jenis Kelamin -->
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')<div style="color:var(--danger); font-size:12px; margin-top:5px;">{{ $message }}</div>@enderror
                </div>

                <!-- Tempat Lahir -->
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                    @error('tempat_lahir')<div style="color:var(--danger); font-size:12px; margin-top:5px;">{{ $message }}</div>@enderror
                </div>

                <!-- Tanggal Lahir -->
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                    @error('tanggal_lahir')<div style="color:var(--danger); font-size:12px; margin-top:5px;">{{ $message }}</div>@enderror
                </div>

                <!-- Nomor HP -->
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Nomor HP</label>
                    <input type="text" name="nomor_hp" value="{{ old('nomor_hp') }}" required style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                    @error('nomor_hp')<div style="color:var(--danger); font-size:12px; margin-top:5px;">{{ $message }}</div>@enderror
                </div>

                <!-- Status Pernikahan -->
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Status Pernikahan</label>
                    <select name="status_pernikahan" required style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                        <option value="">-- Pilih --</option>
                        <option value="Belum Menikah" {{ old('status_pernikahan') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                        <option value="Menikah" {{ old('status_pernikahan') == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                        <option value="Duda" {{ old('status_pernikahan') == 'Duda' ? 'selected' : '' }}>Duda</option>
                        <option value="Janda" {{ old('status_pernikahan') == 'Janda' ? 'selected' : '' }}>Janda</option>
                    </select>
                    @error('status_pernikahan')<div style="color:var(--danger); font-size:12px; margin-top:5px;">{{ $message }}</div>@enderror
                </div>

                <!-- Tanggal Baptis -->
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Tanggal Baptis (Opsional)</label>
                    <input type="date" name="tanggal_baptis" value="{{ old('tanggal_baptis') }}" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                    @error('tanggal_baptis')<div style="color:var(--danger); font-size:12px; margin-top:5px;">{{ $message }}</div>@enderror
                </div>
                
                <!-- Status Aktif -->
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Status Aktif</label>
                    <select name="status_aktif" required style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
                        <option value="Aktif" {{ old('status_aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ old('status_aktif') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status_aktif')<div style="color:var(--danger); font-size:12px; margin-top:5px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Alamat -->
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Alamat Lengkap</label>
                <textarea name="alamat" required rows="3" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">{{ old('alamat') }}</textarea>
                @error('alamat')<div style="color:var(--danger); font-size:12px; margin-top:5px;">{{ $message }}</div>@enderror
            </div>

            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <a href="{{ route('admin.jemaat.index') }}" class="topbar-logout" style="background:#6b7280;">Batal</a>
                <button type="submit" class="topbar-logout">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleAdminPassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}
</script>
@endsection
