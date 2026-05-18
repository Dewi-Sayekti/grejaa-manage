@extends('layouts.dashboard')

@section('title', 'Edit Berita & Pengumuman')
@section('page-title', 'Edit Berita & Pengumuman')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fas fa-edit" style="color:var(--gold);margin-right:8px;"></i>Ubah Berita</h3>
    </div>
    <div class="card-body">
        <p style="color: var(--text-mid); font-size: 14px; margin-bottom: 24px;">
            Perbarui informasi berita atau pengumuman yang sudah ada.
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

        <form action="{{ route('admin.news.update', $news->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="title" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Judul Berita <span style="color:var(--danger);">*</span>
                </label>
                <div style="position:relative;">
                    <i class="fas fa-heading" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-light); font-size:14px;"></i>
                    <input type="text" name="title" id="title" value="{{ old('title', $news->title) }}" 
                        style="width:100%; padding:12px 14px 12px 40px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none;"
                        onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                        required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="excerpt" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Kutipan Singkat <span style="color:var(--danger);">*</span>
                </label>
                <textarea name="excerpt" id="excerpt" rows="3" 
                    style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none; resize:vertical;"
                    onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                    onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'" required>{{ old('excerpt', $news->excerpt) }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="content" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Isi Lengkap
                </label>
                <textarea name="content" id="content" rows="8" 
                    style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none; resize:vertical;"
                    onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                    onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">{{ old('content', $news->content) }}</textarea>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:30px;">
                <div class="form-group">
                    <label for="published_at" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                        Tanggal Publikasi
                    </label>
                    <div style="position:relative;">
                        <input type="date" name="published_at" id="published_at" value="{{ old('published_at', $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('Y-m-d') : '') }}" 
                            style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none;"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                            onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                    </div>
                </div>

                <div class="form-group" style="display:flex; align-items:center;">
                    <label style="display:flex; align-items:center; cursor:pointer; font-size:14px; color:var(--text-dark); margin-top:28px;">
                        <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $news->is_published) ? 'checked' : '' }}
                            style="width:18px; height:18px; margin-right:10px; accent-color:var(--gold);">
                        <span style="font-weight:600;">Publikasikan Sekarang</span>
                    </label>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:12px; border-top:1px solid var(--border); padding-top:24px;">
                <button type="submit" class="topbar-logout" style="background:linear-gradient(135deg, var(--gold), var(--gold-dark)); border-radius:8px; padding:12px 24px; font-size:14px; display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.news.index') }}" style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; border-radius:8px; background:#f3f4f6; color:var(--text-dark); font-size:14px; font-weight:600; transition:all 0.2s;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
