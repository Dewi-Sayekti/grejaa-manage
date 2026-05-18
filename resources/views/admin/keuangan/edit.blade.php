{{-- @extends('admin.layouts.app')

@section('page-title', 'Edit Transaksi Keuangan')

@section('content')

<div class="content-card" style="max-width: 600px;">
    <h2 style="margin-top: 0; color: #2d3748;">Edit Transaksi Keuangan</h2>

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.keuangan.update', $keuangan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="tipe">Tipe Transaksi *</label>
            <select name="tipe" id="tipe" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="pemasukan" @selected($keuangan->tipe == 'pemasukan')>Pemasukan</option>
                <option value="pengeluaran" @selected($keuangan->tipe == 'pengeluaran')>Pengeluaran</option>
            </select>
        </div>

        <div class="form-group">
            <label for="kategori">Kategori *</label>
            <input type="text" name="kategori" id="kategori" value="{{ old('kategori', $keuangan->kategori) }}" placeholder="e.g. Persembahan, Gaji, Sewa Gedung" required>
            <small style="color: #718096;">Contoh: Persembahan, Gaji Pelayan, Sewa Gedung, dll</small>
        </div>

        <div class="form-group">
            <label for="jumlah">Jumlah (Rp) *</label>
            <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', $keuangan->jumlah) }}" step="0.01" min="0" required>
        </div>

        <div class="form-group">
            <label for="tanggal_transaksi">Tanggal Transaksi *</label>
            <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" value="{{ old('tanggal_transaksi', $keuangan->tanggal_transaksi->format('Y-m-d')) }}" required>
        </div>

        <div class="form-group">
            <label for="jemaat_id">Dari/Untuk Jemaat (Opsional)</label>
            <select name="jemaat_id" id="jemaat_id">
                <option value="">-- Tidak ada (Transaksi Umum) --</option>
                @foreach($jemaat as $j)
                    <option value="{{ $j->id }}" @selected(old('jemaat_id', $keuangan->jemaat_id) == $j->id)>{{ $j->nama_lengkap }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan (Opsional)</label>
            <textarea name="keterangan" id="keterangan" placeholder="Masukkan detail transaksi jika diperlukan...">{{ old('keterangan', $keuangan->keterangan) }}</textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">💾 Perbarui Transaksi</button>
            <a href="{{ route('admin.keuangan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection --}}
