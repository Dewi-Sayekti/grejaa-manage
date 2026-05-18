{{-- @extends('admin.layouts.app')

@section('page-title', 'Dashboard Admin')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Jemaat</h3>
        <div class="value">{{ $totalJemaat }}</div>
    </div>
    <div class="stat-card">
        <h3>User Terdaftar</h3>
        <div class="value">{{ $totalUser }}</div>
    </div>
    <div class="stat-card">
        <h3>Menunggu Persetujuan</h3>
        <div class="value" style="color: #fbbf24;">{{ $userPending }}</div>
    </div>
    <div class="stat-card">
        <h3>Pemasukan</h3>
        <div class="value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <h3>Pengeluaran (Tahun Ini)</h3>
        <div class="value" style="color: #fca5a5;">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <h3>Saldo</h3>
        <div class="value" style="@if($totalPemasukan - $totalPengeluaran < 0) color: #fca5a5; @else color: #86efac; @endif">
            Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
        </div>
    </div>
</div>

<!-- Notifikasi Terbaru -->
<div class="content-card">
    <h2 style="margin-top: 0; color: #2d3748;">📢 Notifikasi Terbaru</h2>
    @if($notifikasiTerbaru->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Tipe</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notifikasiTerbaru as $notif)
                    <tr>
                        <td><strong>{{ $notif->judul }}</strong></td>
                        <td>
                            <span class="badge @if($notif->tipe == 'penting') badge-warning @elseif($notif->tipe == 'event') badge-info @else badge-success @endif">
                                {{ ucfirst($notif->tipe) }}
                            </span>
                        </td>
                        <td>{{ $notif->tanggal_kirim->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.notifikasi.edit', $notif) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('admin.notifikasi.destroy', $notif) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus notifikasi ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #718096;">Tidak ada notifikasi terbaru.</p>
    @endif
    <a href="{{ route('admin.notifikasi.create') }}" class="btn btn-primary" style="margin-top: 15px;">+ Buat Notifikasi Baru</a>
</div>

<!-- Transaksi Keuangan Terbaru -->
<div class="content-card">
    <h2 style="margin-top: 0; color: #2d3748;">💰 Transaksi Keuangan Terbaru</h2>
    @if($transaksiTerbaru->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksiTerbaru as $transaksi)
                    <tr>
                        <td>{{ $transaksi->tanggal_transaksi->format('d M Y') }}</td>
                        <td>
                            <span class="badge @if($transaksi->tipe == 'pemasukan') badge-success @else badge-warning @endif">
                                {{ ucfirst($transaksi->tipe) }}
                            </span>
                        </td>
                        <td>{{ $transaksi->kategori }}</td>
                        <td>
                            <strong style="@if($transaksi->tipe == 'pengeluaran') color: #f56565; @else color: #48bb78; @endif">
                                @if($transaksi->tipe == 'pengeluaran') - @endif Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td>{{ Str::limit($transaksi->keterangan, 30) }}</td>
                        <td>
                            <a href="{{ route('admin.keuangan.edit', $transaksi) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('admin.keuangan.destroy', $transaksi) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus transaksi ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #718096;">Tidak ada transaksi terbaru.</p>
    @endif
    <a href="{{ route('admin.keuangan.create') }}" class="btn btn-primary" style="margin-top: 15px;">+ Buat Transaksi Baru</a>
</div>

@endsection --}}
