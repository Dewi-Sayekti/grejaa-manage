@extends('layouts.dashboard')

@section('title', 'Edit Konten Pelayanan')
@section('page-title', 'Edit Konten Pelayanan')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fas fa-edit" style="color:var(--gold);margin-right:8px;"></i>Ubah Konten Pelayanan</h3>
    </div>
    <div class="card-body">
        <p style="color: var(--text-mid); font-size: 14px; margin-bottom: 24px;">
            Perbarui informasi pelayanan, video ibadah, atau materi pembinaan yang sudah ada.
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

        <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="category" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Kategori Pelayanan <span style="color:var(--danger);">*</span>
                </label>
                <div style="position:relative;">
                    <select name="category" id="category" 
                        style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none; background:#fff; cursor:pointer;"
                        onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                        required>
                        <option value="jadwal_ibadah" {{ (old('category', $service->category) == 'jadwal_ibadah') ? 'selected' : '' }}>Jadwal Ibadah / Ibadah Raya</option>
                        <option value="kelompok_kecil" {{ (old('category', $service->category) == 'kelompok_kecil') ? 'selected' : '' }}>Kelompok Kecil / COOL</option>
                        <option value="sekolah_minggu" {{ (old('category', $service->category) == 'sekolah_minggu') ? 'selected' : '' }}>Sekolah Minggu / Anak</option>
                        <option value="musik" {{ (old('category', $service->category) == 'musik') ? 'selected' : '' }}>Pujian & Penyembahan (Musik)</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="title" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Judul Konten <span style="color:var(--danger);">*</span>
                </label>
                <div style="position:relative;">
                    <i class="fas fa-heading" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-light); font-size:14px;"></i>
                    <input type="text" name="title" id="title" value="{{ old('title', $service->title) }}" 
                        style="width:100%; padding:12px 14px 12px 40px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none;"
                        onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                        required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="description" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Deskripsi
                </label>
                <textarea name="description" id="description" rows="4" 
                    style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none; resize:vertical;"
                    onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                    onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">{{ old('description', $service->description) }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="video_link" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Tautan YouTube (Opsional)
                </label>
                <div style="position:relative;">
                    <i class="fab fa-youtube" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-light); font-size:14px;"></i>
                    <input type="text" name="video_link" id="video_link" value="{{ old('video_link', $service->video_link) }}" 
                        style="width:100%; padding:12px 14px 12px 40px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none;"
                        onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Upload Video (Opsional)
                </label>
                
                <div id="drop-zone" style="border:2px dashed var(--border); border-radius:12px; padding:32px; text-align:center; background:#fafafa; transition:all 0.3s; cursor:pointer; position:relative; {{ $service->video_path ? 'border-color:var(--gold); background:#fff;' : '' }}">
                    <input type="file" name="video_file" id="video_file" accept="video/mp4,video/x-m4v,video/*" 
                        style="position:absolute; inset:0; opacity:0; cursor:pointer; z-index:10;"
                        onchange="previewFile(this)">
                    
                    <div id="upload-placeholder" style="{{ $service->video_path ? 'display:none;' : '' }}">
                        <div style="font-size:40px; color:var(--gold); margin-bottom:12px;"><i class="fas fa-video"></i></div>
                        <div style="font-weight:600; color:var(--text-dark); margin-bottom:4px;">Klik atau seret file video baru ke sini</div>
                        <div style="font-size:12px; color:var(--text-mid);">Format: MP4, MOV, dll. Ukuran maksimal 20MB. Biarkan kosong jika tidak diubah.</div>
                    </div>

                    <div id="file-preview-container" style="{{ $service->video_path ? 'display:flex;' : 'display:none;' }} flex-direction:column; align-items:center;">
                        <div style="font-size:40px; color:var(--gold); margin-bottom:12px;"><i class="fas fa-file-video"></i></div>
                        <div style="font-size:13px; color:var(--gold); font-weight:600;">
                            <i class="fas fa-check-circle"></i> <span id="file-name" style="color:var(--text-dark);">{{ $service->video_path ? 'Menggunakan file saat ini' : '' }}</span>
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
                <a href="{{ route('admin.services.index', ['category' => $service->category]) }}" style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; border-radius:8px; background:#f3f4f6; color:var(--text-dark); font-size:14px; font-weight:600; transition:all 0.2s;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const hasExistingFile = {{ $service->video_path ? 'true' : 'false' }};

    function previewFile(input) {
        const placeholder = document.getElementById('upload-placeholder');
        const container = document.getElementById('file-preview-container');
        const fileNameSpan = document.getElementById('file-name');
        const dropZone = document.getElementById('drop-zone');

        if (input.files && input.files[0]) {
            fileNameSpan.textContent = 'File Baru: ' + input.files[0].name;
            placeholder.style.display = 'none';
            container.style.display = 'flex';
            dropZone.style.borderColor = 'var(--gold)';
            dropZone.style.background = '#fff';
        }
    }

    function resetFile() {
        const input = document.getElementById('video_file');
        const placeholder = document.getElementById('upload-placeholder');
        const container = document.getElementById('file-preview-container');
        const fileNameSpan = document.getElementById('file-name');
        const dropZone = document.getElementById('drop-zone');
        
        input.value = '';
        
        if (hasExistingFile) {
            fileNameSpan.textContent = 'Menggunakan file saat ini';
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
