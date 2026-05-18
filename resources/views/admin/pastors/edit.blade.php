@extends('layouts.dashboard')

@section('title', 'Edit Pemimpin')
@section('page-title', 'Edit Data Pemimpin')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fas fa-user-edit" style="color:var(--gold);margin-right:8px;"></i>Ubah Data Pemimpin</h3>
    </div>
    <div class="card-body">
        <p style="color: var(--text-mid); font-size: 14px; margin-bottom: 24px;">
            Perbarui informasi profil pemimpin atau pendeta yang sudah ada.
        </p>

        @if($errors->any())
            <div style="background:#fef2f2; border-left:4px solid var(--danger); padding:16px; border-radius:8px; margin-bottom:24px;">
                <div style="display:flex; align-items:center; color:#991b1b; font-weight:600; margin-bottom:8px;">
                    <i class="fas fa-exclamation-circle" style="margin-right:8px;"></i>
                    <span>Terjadi Kesalahan:</span>
                </div>
                <ul style="margin: 0; padding-left: 20px; color:#b91c1c; font-size:14px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pastors.update', $pastor->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="name" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Nama Lengkap <span style="color:var(--danger);">*</span>
                </label>
                <div style="position:relative;">
                    <i class="fas fa-user" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-light); font-size:14px;"></i>
                    <input type="text" name="name" id="name" value="{{ old('name', $pastor->name) }}" 
                        placeholder="Contoh: Pdt. John Doe, M.Th" 
                        style="width:100%; padding:12px 14px 12px 40px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none;"
                        onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                        required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="role" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Peran / Jabatan <span style="color:var(--danger);">*</span>
                </label>
                <div style="position:relative;">
                    <i class="fas fa-briefcase" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-light); font-size:14px;"></i>
                    <input type="text" name="role" id="role" value="{{ old('role', $pastor->role) }}" 
                        placeholder="Contoh: Gembala Sidang" 
                        style="width:100%; padding:12px 14px 12px 40px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none;"
                        onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                        required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="description" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Deskripsi Singkat
                </label>
                <textarea name="description" id="description" rows="4" 
                    style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none; resize:vertical;"
                    onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                    onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">{{ old('description', $pastor->description) }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label for="image" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Ubah Foto Profil (Opsional)
                </label>
                
                <div id="drop-zone" style="border:2px dashed var(--border); border-radius:12px; padding:32px; text-align:center; background:#fafafa; transition:all 0.3s; cursor:pointer; position:relative; {{ $pastor->image_path ? 'border-color:var(--gold); background:#fff;' : '' }}">
                    <input type="file" name="image" id="image" accept="image/*" 
                        style="position:absolute; inset:0; opacity:0; cursor:pointer; z-index:10;"
                        onchange="previewImage(this)">
                    
                    <div id="upload-placeholder" style="{{ $pastor->image_path ? 'display:none;' : '' }}">
                        <div style="font-size:40px; color:var(--gold); margin-bottom:12px;"><i class="fas fa-camera"></i></div>
                        <div style="font-weight:600; color:var(--text-dark); margin-bottom:4px;">Klik atau seret foto baru ke sini</div>
                        <div style="font-size:12px; color:var(--text-mid);">Format: JPEG, PNG, JPG. Biarkan kosong jika tidak ingin mengubah.</div>
                    </div>

                    <div id="image-preview-container" style="{{ $pastor->image_path ? 'display:flex;' : 'display:none;' }} flex-direction:column; align-items:center;">
                        <img id="image-preview" src="{{ $pastor->image_path ? asset($pastor->image_path) : '#' }}" alt="Preview" style="max-width:200px; max-height:200px; border-radius:50%; box-shadow:var(--shadow-md); margin-bottom:12px; object-fit:cover; width:200px; height:200px;">
                        <div style="font-size:13px; color:var(--gold); font-weight:600;">
                            <i class="fas fa-check-circle"></i> <span id="file-name" style="color:var(--text-dark);">{{ $pastor->image_path ? 'Gunakan foto saat ini atau pilih yang baru' : '' }}</span>
                        </div>
                        <button type="button" onclick="resetFile()" style="margin-top:8px; background:none; border:none; color:var(--danger); font-size:12px; font-weight:600; cursor:pointer; z-index:20; position:relative;">
                            <i class="fas fa-times"></i> Hapus Pilihan
                        </button>
                    </div>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:12px; border-top:1px solid var(--border); padding-top:24px;">
                <button type="submit" class="topbar-logout" style="background:linear-gradient(135deg, var(--gold), var(--gold-dark)); border-radius:8px; padding:12px 24px; font-size:14px; display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.pastors.index') }}" style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; border-radius:8px; background:#f3f4f6; color:var(--text-dark); font-size:14px; font-weight:600; transition:all 0.2s;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const hasExistingImage = {{ $pastor->image_path ? 'true' : 'false' }};
    const existingImageUrl = "{{ $pastor->image_path ? asset($pastor->image_path) : '' }}";

    function previewImage(input) {
        const placeholder = document.getElementById('upload-placeholder');
        const container = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        const fileNameSpan = document.getElementById('file-name');
        const dropZone = document.getElementById('drop-zone');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                fileNameSpan.textContent = 'Foto Baru: ' + input.files[0].name;
                placeholder.style.display = 'none';
                container.style.display = 'flex';
                dropZone.style.borderColor = 'var(--gold)';
                dropZone.style.background = '#fff';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function resetFile() {
        const input = document.getElementById('image');
        const placeholder = document.getElementById('upload-placeholder');
        const container = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        const fileNameSpan = document.getElementById('file-name');
        const dropZone = document.getElementById('drop-zone');
        
        input.value = '';
        
        if (hasExistingImage) {
            preview.src = existingImageUrl;
            fileNameSpan.textContent = 'Gunakan foto saat ini atau pilih yang baru';
            container.style.display = 'flex';
            placeholder.style.display = 'none';
            dropZone.style.borderColor = 'var(--gold)';
            dropZone.style.background = '#fff';
        } else {
            placeholder.style.display = 'block';
            container.style.display = 'none';
            dropZone.style.borderColor = 'var(--border)';
            dropZone.style.background = '#fafafa';
        }
    }
</script>
@endsection
