@extends('layouts.dashboard')

@section('title', 'Buat Berita Baru')
@section('page-title', 'Berita & Pengumuman')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3>📝 Tambah Berita atau Acara</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:8px; font-weight:700;">Judul *</label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="Judul berita atau acara" 
                       style="width:100%; padding:12px; border:1px solid var(--border); border-radius:10px; background:var(--bg-body); color:var(--text-dark);">
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Kategori</label>
                    <select name="is_event" id="is_event" onchange="toggleEventFields()"
                            style="width:100%; padding:12px; border:1px solid var(--border); border-radius:10px; background:var(--bg-body); color:var(--text-dark);">
                        <option value="0" {{ old('is_event') == '0' ? 'selected' : '' }}>Berita / Pengumuman</option>
                        <option value="1" {{ old('is_event') == '1' ? 'selected' : '' }}>Acara (Event)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Status</label>
                    <select name="is_published"
                            style="width:100%; padding:12px; border:1px solid var(--border); border-radius:10px; background:var(--bg-body); color:var(--text-dark);">
                        <option value="1" {{ old('is_published', '1') == '1' ? 'selected' : '' }}>Published</option>
                        <option value="0" {{ old('is_published') == '0' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>

            <div id="event-fields" style="display: {{ old('is_event') == '1' ? 'block' : 'none' }}; border: 1px solid var(--gold); padding: 20px; border-radius: 10px; margin-bottom: 20px; background: rgba(212, 175, 55, 0.05);">
                <h4 style="margin-top:0; margin-bottom:15px; color:var(--gold);"><i class="fas fa-calendar-star"></i> Detail Acara</h4>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label style="display:block; margin-bottom:8px; font-weight:700;">Lokasi Acara</label>
                        <input type="text" name="lokasi_acara" value="{{ old('lokasi_acara') }}" placeholder="Contoh: Gedung Serbaguna"
                               style="width:100%; padding:12px; border:1px solid var(--border); border-radius:10px; background:var(--bg-body); color:var(--text-dark);">
                    </div>
                    <div class="form-group">
                        <label style="display:block; margin-bottom:8px; font-weight:700;">Tanggal Acara</label>
                        <input type="datetime-local" name="tanggal_acara" value="{{ old('tanggal_acara') }}"
                               style="width:100%; padding:12px; border:1px solid var(--border); border-radius:10px; background:var(--bg-body); color:var(--text-dark);">
                    </div>
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Kuota (Opsional)</label>
                    <input type="number" name="kuota" value="{{ old('kuota') }}" placeholder="Kosongkan jika tidak terbatas"
                           style="width:100%; padding:12px; border:1px solid var(--border); border-radius:10px; background:var(--bg-body); color:var(--text-dark);">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:8px; font-weight:700;">Ringkasan (Excerpt)</label>
                <textarea name="excerpt" placeholder="Ringkasan singkat berita..." rows="2"
                          style="width:100%; padding:12px; border:1px solid var(--border); border-radius:10px; background:var(--bg-body); color:var(--text-dark);">{{ old('excerpt') }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:8px; font-weight:700;">Konten Lengkap *</label>
                <textarea name="content" required placeholder="Tulis isi berita di sini..." rows="8"
                          style="width:100%; padding:12px; border:1px solid var(--border); border-radius:10px; background:var(--bg-body); color:var(--text-dark);">{{ old('content') }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label style="display:block; margin-bottom:8px; font-weight:700;">Gambar Utama</label>
                <input type="file" name="image" accept="image/*"
                       style="width:100%; padding:10px; border:1px dashed var(--border); border-radius:10px; background:var(--bg-body); color:var(--text-dark);">
                <small style="color:var(--text-mid); display:block; margin-top:5px;">Rekomendasi ukuran: 1200x600px. Maks 2MB.</small>
            </div>

            <div style="display:flex; gap:15px;">
                <button type="submit" class="topbar-logout" style="flex:1; padding:15px; font-size:16px;">💾 Simpan Berita</button>
                <a href="{{ route('admin.news.index') }}" class="topbar-btn" style="padding:15px 30px; text-decoration:none; text-align:center;">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleEventFields() {
    const isEvent = document.getElementById('is_event').value;
    const fields = document.getElementById('event-fields');
    fields.style.display = isEvent == '1' ? 'block' : 'none';
}
</script>
@endsection
