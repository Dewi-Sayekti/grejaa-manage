@extends('admin.layouts.app')

@section('page-title', 'Buat Notifikasi Baru')

@section('content')

<div class="content-card" style="max-width: 600px;">
    <h2 style="margin-top: 0; color: #2d3748;">Buat Notifikasi Baru</h2>

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.notifikasi.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="judul">Judul Notifikasi *</label>
            <input type="text" name="judul" id="judul" value="{{ old('judul') }}" placeholder="Contoh: Jadwal Ibadah Minggu Depan" required>
        </div>

        <div class="form-group">
            <label for="isi">Isi Notifikasi *</label>
            <textarea name="isi" id="isi" placeholder="Tulis pesan yang akan dikirim ke jemaat..." required>{{ old('isi') }}</textarea>
        </div>

        <div class="form-group">
            <label for="tipe">Tipe Notifikasi *</label>
            <select name="tipe" id="tipe" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="pengumuman" @selected(old('tipe') == 'pengumuman')>📢 Pengumuman</option>
                <option value="penting" @selected(old('tipe') == 'penting')>⚠️ Penting</option>
                <option value="event" @selected(old('tipe') == 'event')>📅 Event</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tanggal_kirim">Tanggal & Waktu Kirim *</label>
            <input type="datetime-local" name="tanggal_kirim" id="tanggal_kirim" value="{{ old('tanggal_kirim', now()->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="form-group">
            <label for="jemaat_id">Kirim ke (Opsional)</label>
            <select name="jemaat_id" id="jemaat_id">
                <option value="">-- Semua Jemaat --</option>
                @foreach($jemaat as $j)
                    <option value="{{ $j->id }}" @selected(old('jemaat_id') == $j->id)>{{ $j->nama_lengkap }}</option>
                @endforeach
            </select>
            <small style="color: #718096;">Jika dikosongkan, notifikasi akan dikirim ke semua jemaat</small>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">📤 Kirim Notifikasi</button>
            <a href="{{ route('admin.notifikasi.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
