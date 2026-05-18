@extends('layouts.dashboard')

@section('title', 'Tambah Acara Mendatang')
@section('page-title', 'Tambah Acara Mendatang')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fas fa-calendar-plus" style="color:var(--gold);margin-right:8px;"></i>Form Acara Baru</h3>
    </div>
    <div class="card-body">
        <p style="color: var(--text-mid); font-size: 14px; margin-bottom: 24px;">
            Jadwalkan acara atau kegiatan rutin gereja agar jemaat dapat mengetahuinya.
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

        <form action="{{ route('admin.schedules.store') }}" method="POST">
            @csrf

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="title" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Nama Acara <span style="color:var(--danger);">*</span>
                </label>
                <div style="position:relative;">
                    <i class="fas fa-calendar-alt" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-light); font-size:14px;"></i>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" 
                        placeholder="Contoh: Ibadah Raya 1" 
                        style="width:100%; padding:12px 14px 12px 40px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none;"
                        onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                        required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="description" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                    Deskripsi Singkat <span style="color:var(--danger);">*</span>
                </label>
                <textarea name="description" id="description" rows="3" 
                    placeholder="Tuliskan keterangan tempat atau jenis acara..." 
                    style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none; resize:vertical;"
                    onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                    onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'" required>{{ old('description') }}</textarea>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:24px;">
                <div class="form-group">
                    <label for="day" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                        Hari (Opsional)
                    </label>
                    <div style="position:relative;">
                        <select name="day" id="day" 
                            style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none; background:#fff; cursor:pointer;"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                            onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                            <option value="">Pilih Hari</option>
                            <option value="Senin" {{ old('day') == 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ old('day') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ old('day') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ old('day') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ old('day') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                            <option value="Sabtu" {{ old('day') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                            <option value="Minggu" {{ old('day') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="emoji" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                        Ikon / Emoji (Opsional)
                    </label>
                    <div style="position:relative;">
                        <input type="text" name="emoji" id="emoji" value="{{ old('emoji') }}" 
                            placeholder="Contoh: ⛪ atau 🎸" 
                            style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none;"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                            onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                    </div>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:30px;">
                <div class="form-group">
                    <label for="start_time" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                        Waktu Mulai (Opsional)
                    </label>
                    <div style="position:relative;">
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" 
                            style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none;"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                            onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                    </div>
                </div>

                <div class="form-group">
                    <label for="end_time" style="display:block; font-size:13px; font-weight:600; color:var(--text-dark); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
                        Waktu Selesai (Opsional)
                    </label>
                    <div style="position:relative;">
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" 
                            style="width:100%; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-family:inherit; font-size:14px; transition:all 0.2s; outline:none;"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 3px rgba(212,175,55,0.1)'"
                            onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                    </div>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:12px; border-top:1px solid var(--border); padding-top:24px;">
                <button type="submit" class="topbar-logout" style="background:linear-gradient(135deg, var(--gold), var(--gold-dark)); border-radius:8px; padding:12px 24px; font-size:14px; display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-save"></i> Simpan Acara
                </button>
                <a href="{{ route('admin.schedules.index') }}" style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; border-radius:8px; background:#f3f4f6; color:var(--text-dark); font-size:14px; font-weight:600; transition:all 0.2s;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
