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

            {{-- Judul --}}
            <div class="form-group" style="margin-bottom: 24px;">
                <label style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Judul Berita <span style="color:var(--danger);">*</span>
                </label>
                <div style="position:relative;">
                    <i class="fas fa-heading" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-light); font-size:14px;"></i>
                    <input type="text" name="title" value="{{ old('title', $news->title) }}"
                        style="width:100%; padding:12px 14px 12px 40px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; outline:none; box-sizing:border-box;"
                        onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                        required>
                </div>
            </div>

            {{-- Kutipan --}}
            <div class="form-group" style="margin-bottom: 24px;">
                <label style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Kutipan Singkat <span style="color:var(--danger);">*</span>
                </label>
                <textarea name="excerpt" rows="3"
                    style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; outline:none; resize:vertical; box-sizing:border-box;"
                    onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                    onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                    required>{{ old('excerpt', $news->excerpt) }}</textarea>
            </div>

            {{-- Isi --}}
            <div class="form-group" style="margin-bottom: 24px;">
                <label style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Isi Lengkap
                </label>
                <textarea name="content" rows="8"
                    style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; outline:none; resize:vertical; box-sizing:border-box;"
                    onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                    onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">{{ old('content', $news->content) }}</textarea>
            </div>

            {{-- Tanggal & Publikasi --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:24px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                        Tanggal Publikasi
                    </label>
                    <input type="date" name="published_at"
                        value="{{ old('published_at', $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('Y-m-d') : '') }}"
                        style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; outline:none; box-sizing:border-box;"
                        onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                </div>
                <div style="display:flex; align-items:center;">
                    <label style="display:flex; align-items:center; cursor:pointer; font-size:14px; color:var(--text-dark); margin-top:28px;">
                        <input type="checkbox" name="is_published" value="1"
                               {{ old('is_published', $news->is_published) ? 'checked' : '' }}
                               style="width:18px; height:18px; margin-right:10px; accent-color:var(--gold);">
                        <span style="font-weight:600;">Publikasikan Sekarang</span>
                    </label>
                </div>
            </div>

            {{-- ===== SECTION ACARA ===== --}}
            <div style="border-top:1px solid var(--border); padding-top:24px; margin-bottom:24px;">
                <div style="font-size:14px; font-weight:700; color:var(--text-dark); margin-bottom:16px;">
                    <i class="fas fa-calendar-star" style="color:var(--gold);margin-right:6px;"></i> Pengaturan Acara
                    <span style="font-size:12px; font-weight:400; color:var(--text-light); margin-left:6px;">(opsional — aktifkan jika ini adalah acara yang bisa didaftari jemaat)</span>
                </div>

                <div style="display:flex; align-items:center; gap:10px; margin-bottom:18px;">
                    <input type="checkbox" name="is_event" id="is_event" value="1"
                           {{ old('is_event', $news->is_event) ? 'checked' : '' }}
                           onchange="toggleEventFields(this.checked)"
                           style="width:16px; height:16px; accent-color:var(--gold);">
                    <label for="is_event" style="font-size:13px; font-weight:600; color:var(--text-dark); cursor:pointer;">
                        Ini adalah acara (aktifkan fitur pendaftaran peserta)
                    </label>
                </div>

                <div id="eventFields" style="display:{{ old('is_event', $news->is_event) ? 'block' : 'none' }}; background:#fdfaf4; border:1px solid #f0ebe0; border-radius:10px; padding:20px;">

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                        <div>
                            <label style="display:block; font-size:12px; font-weight:600; color:var(--text-dark); margin-bottom:6px; text-transform:uppercase; letter-spacing:.4px;">Tanggal & Jam Mulai</label>
                            <input type="datetime-local" name="tanggal_acara"
                                   value="{{ old('tanggal_acara', $news->tanggal_acara ? $news->tanggal_acara->format('Y-m-d\TH:i') : '') }}"
                                   style="width:100%; padding:10px 12px; border:1px solid var(--border); border-radius:8px; font-size:13px; outline:none; font-family:inherit; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='var(--gold)'"
                                   onblur="this.style.borderColor='var(--border)'">
                        </div>
                        <div>
                            <label style="display:block; font-size:12px; font-weight:600; color:var(--text-dark); margin-bottom:6px; text-transform:uppercase; letter-spacing:.4px;">Tanggal & Jam Selesai</label>
                            <input type="datetime-local" name="tanggal_acara_selesai"
                                   value="{{ old('tanggal_acara_selesai', $news->tanggal_acara_selesai ? $news->tanggal_acara_selesai->format('Y-m-d\TH:i') : '') }}"
                                   style="width:100%; padding:10px 12px; border:1px solid var(--border); border-radius:8px; font-size:13px; outline:none; font-family:inherit; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='var(--gold)'"
                                   onblur="this.style.borderColor='var(--border)'">
                        </div>
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:var(--text-dark); margin-bottom:6px; text-transform:uppercase; letter-spacing:.4px;">Lokasi Acara</label>
                        <input type="text" name="lokasi_acara"
                               value="{{ old('lokasi_acara', $news->lokasi_acara) }}"
                               placeholder="Contoh: Gedung Utama Gereja YHS"
                               style="width:100%; padding:10px 12px; border:1px solid var(--border); border-radius:8px; font-size:13px; outline:none; font-family:inherit; box-sizing:border-box;"
                               onfocus="this.style.borderColor='var(--gold)'"
                               onblur="this.style.borderColor='var(--border)'">
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                        <div>
                            <label style="display:block; font-size:12px; font-weight:600; color:var(--text-dark); margin-bottom:6px; text-transform:uppercase; letter-spacing:.4px;">
                                Kuota Peserta
                                <span style="font-weight:400; color:var(--text-light); text-transform:none;">(kosong = tidak terbatas)</span>
                            </label>
                            <input type="number" name="kuota" min="1"
                                   value="{{ old('kuota', $news->kuota) }}"
                                   placeholder="Contoh: 100"
                                   style="width:100%; padding:10px 12px; border:1px solid var(--border); border-radius:8px; font-size:13px; outline:none; font-family:inherit; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='var(--gold)'"
                                   onblur="this.style.borderColor='var(--border)'">
                        </div>
                        <div>
                            <label style="display:block; font-size:12px; font-weight:600; color:var(--text-dark); margin-bottom:6px; text-transform:uppercase; letter-spacing:.4px;">Batas Waktu Pendaftaran</label>
                            <input type="datetime-local" name="batas_pendaftaran"
                                   value="{{ old('batas_pendaftaran', $news->batas_pendaftaran ? $news->batas_pendaftaran->format('Y-m-d\TH:i') : '') }}"
                                   style="width:100%; padding:10px 12px; border:1px solid var(--border); border-radius:8px; font-size:13px; outline:none; font-family:inherit; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='var(--gold)'"
                                   onblur="this.style.borderColor='var(--border)'">
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; gap:10px;">
                        <input type="checkbox" name="buka_pendaftaran" id="buka_pendaftaran" value="1"
                               {{ old('buka_pendaftaran', $news->buka_pendaftaran) ? 'checked' : '' }}
                               style="width:16px; height:16px; accent-color:var(--gold);">
                        <label for="buka_pendaftaran" style="font-size:13px; font-weight:600; color:var(--text-dark); cursor:pointer;">
                            Buka pendaftaran sekarang
                        </label>
                    </div>
                </div>
            </div>
            {{-- ===== END SECTION ACARA ===== --}}

            <div style="display:flex; align-items:center; gap:12px; border-top:1px solid var(--border); padding-top:24px;">
                <button type="submit" class="topbar-logout" style="background:linear-gradient(135deg, var(--gold), var(--gold-dark)); border-radius:8px; padding:12px 24px; font-size:14px; display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.news.index') }}" style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; border-radius:8px; background:#f3f4f6; color:var(--text-dark); font-size:14px; font-weight:600;"
                   onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleEventFields(show) {
    document.getElementById('eventFields').style.display = show ? 'block' : 'none';
}
</script>
@endsection