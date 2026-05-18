@extends('layouts.dashboard')
@section('title', 'Persembahan Online')
@section('page-title', 'Persembahan Online')

@section('content')

<div class="psb-wrap">

    {{-- Info Card --}}
    <div class="psb-info-banner">
        <div class="psb-info-icon"><i class="fas fa-hand-holding-heart"></i></div>
        <div>
            <div class="psb-info-title">Persembahan Online Gereja YHS</div>
            <div class="psb-info-sub">Bayar via GoPay, OVO, Dana, atau QRIS. Aman & langsung tercatat.</div>
        </div>
    </div>

    <div class="psb-grid">

        {{-- FORM --}}
        <div class="psb-card">
            <div class="psb-card-header">
                <i class="fas fa-gift"></i> Berikan Persembahan
            </div>
            <div class="psb-card-body">

                {{-- Pilih Jenis --}}
                <div class="psb-field">
                    <label class="psb-label">Jenis Persembahan</label>
                    <div class="psb-jenis-grid">
                        @foreach([
                            ['persembahan', 'fas fa-church',        'Persembahan Umum'],
                            ['perpuluhan',  'fas fa-percent',       'Perpuluhan'],
                            ['diakonia',    'fas fa-hands-helping', 'Diakonia'],
                            ['misi',        'fas fa-globe-asia',    'Dana Misi'],
                            ['lainnya',     'fas fa-ellipsis-h',    'Lainnya'],
                        ] as [$val, $icon, $label])
                        <label class="psb-jenis-opt">
                            <input type="radio" name="jenis" value="{{ $val }}" {{ $val === 'persembahan' ? 'checked' : '' }}>
                            <div class="psb-jenis-card">
                                <i class="{{ $icon }}"></i>
                                <span>{{ $label }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Nominal --}}
                <div class="psb-field">
                    <label class="psb-label">Nominal Persembahan</label>
                    <div class="psb-nominal-quick">
                        @foreach([5000, 10000, 25000, 100000] as $nom)
                        <button type="button" class="psb-quick-btn" onclick="setNominal({{ $nom }})">
                            Rp {{ number_format($nom, 0, ',', '.') }}
                        </button>
                        @endforeach
                    </div>
                    <div class="psb-input-wrap">
                        <span class="psb-prefix">Rp</span>
                        <input type="text" id="jumlahDisplay" class="psb-input psb-input--nominal"
                               placeholder="0" oninput="formatNominal(this)" autocomplete="off">
                        <input type="hidden" id="jumlah" name="jumlah">
                    </div>
                    <div class="psb-hint">Minimum Rp 5.000</div>
                </div>

                {{-- Data Pemberi --}}
                <div class="psb-field">
                    <label class="psb-label">Nama Lengkap</label>
                    <input type="text" name="nama_pemberi" class="psb-input"
                           value="{{ Auth::check() ? Auth::user()->name : '' }}"
                           placeholder="Nama Anda" required>
                </div>
                <div class="psb-field">
                    <label class="psb-label">Email</label>
                    <input type="email" name="email" class="psb-input"
                           value="{{ Auth::check() ? Auth::user()->email : '' }}"
                           placeholder="email@contoh.com" required>
                </div>
                <div class="psb-field">
                    <label class="psb-label">No. HP <span class="psb-optional">(opsional)</span></label>
                    <input type="text" name="phone" class="psb-input" placeholder="08xxxxxxxxxx">
                </div>
                <div class="psb-field">
                    <label class="psb-label">Catatan <span class="psb-optional">(opsional)</span></label>
                    <textarea name="catatan" class="psb-input psb-textarea"
                              placeholder="Contoh: Persembahan Paskah 2026..." maxlength="200"></textarea>
                </div>

                {{-- Submit --}}
                <button type="button" id="payBtn" class="psb-pay-btn" onclick="bayar()">
                    <i class="fas fa-qrcode"></i> Lanjut Pembayaran
                </button>

                {{-- Payment icons --}}
                <div class="psb-payment-icons">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/200px-Gopay_logo.svg.png" alt="GoPay" title="GoPay">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_purple.svg/200px-Logo_ovo_purple.svg.png" alt="OVO" title="OVO">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/Logo_of_Dana_%28dana.id%29.svg/200px-Logo_of_Dana_%28dana.id%29.svg.png" alt="Dana" title="Dana">
                    <span class="psb-qris-badge">QRIS</span>
                </div>

            </div>
        </div>

        {{-- RIWAYAT (jika login) --}}
        @auth
        <div class="psb-card">
            <div class="psb-card-header">
                <i class="fas fa-history"></i> Riwayat Persembahan Saya
            </div>
            <div class="psb-card-body psb-card-body--list">
                @forelse($riwayat as $r)
                <div class="psb-history-item">
                    <div class="psb-history-icon psb-history-icon--{{ $r->status }}">
                        <i class="fas fa-{{ $r->status === 'settlement' ? 'check' : ($r->status === 'pending' ? 'clock' : 'times') }}"></i>
                    </div>
                    <div class="psb-history-info">
                        <div class="psb-history-jenis">{{ $r->jenis_label }}</div>
                        <div class="psb-history-date">{{ $r->created_at->format('d M Y, H:i') }}</div>
                        @if($r->payment_type)
                        <div class="psb-history-method">via {{ strtoupper($r->payment_type) }}</div>
                        @endif
                    </div>
                    <div class="psb-history-right">
                        <div class="psb-history-amount">Rp {{ number_format($r->jumlah, 0, ',', '.') }}</div>
                        <span class="psb-status psb-status--{{ $r->status }}">{{ $r->status_label }}</span>
                    </div>
                </div>
                @empty
                <div class="psb-empty">
                    <i class="fas fa-heart"></i>
                    <p>Belum ada riwayat persembahan</p>
                </div>
                @endforelse
            </div>
        </div>
        @endauth

    </div>
</div>

{{-- Midtrans Snap JS --}}
<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
function setNominal(val) {
    document.getElementById('jumlahDisplay').value = val.toLocaleString('id-ID');
    document.getElementById('jumlah').value = val;
    document.querySelectorAll('.psb-quick-btn').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
}

function formatNominal(el) {
    let raw = el.value.replace(/\D/g, '');
    el.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
    document.getElementById('jumlah').value = raw;
    document.querySelectorAll('.psb-quick-btn').forEach(b => b.classList.remove('active'));
}

function bayar() {
    const nama   = document.querySelector('[name=nama_pemberi]').value.trim();
    const email  = document.querySelector('[name=email]').value.trim();
    const jumlah = parseInt(document.getElementById('jumlah').value);
    const jenis  = document.querySelector('[name=jenis]:checked')?.value;

    if (!nama)              return alert('Nama wajib diisi.');
    if (!email)             return alert('Email wajib diisi.');
    if (!jumlah || jumlah < 5000) return alert('Nominal minimal Rp 5.000.');

    const btn = document.getElementById('payBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

    const data = new FormData();
    data.append('_token', '{{ csrf_token() }}');
    data.append('nama_pemberi', nama);
    data.append('email', email);
    data.append('phone', document.querySelector('[name=phone]').value);
    data.append('jenis', jenis);
    data.append('jumlah', jumlah);
    data.append('catatan', document.querySelector('[name=catatan]').value);

    fetch('{{ route("persembahan.store") }}', { method: 'POST', body: data })
        .then(r => r.json())
        .then(res => {
            if (res.error) {
                alert('Error: ' + res.error);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-qrcode"></i> Lanjut Pembayaran';
                return;
            }
            // Buka popup Midtrans Snap
            snap.pay(res.snap_token, {
                onSuccess: function(result) {
                    window.location = '{{ route("persembahan.finish") }}?order_id=' + res.order_id;
                },
                onPending: function(result) {
                    window.location = '{{ route("persembahan.finish") }}?order_id=' + res.order_id;
                },
                onError: function(result) {
                    alert('Pembayaran gagal. Silakan coba lagi.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-qrcode"></i> Lanjut Pembayaran';
                },
                onClose: function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-qrcode"></i> Lanjut Pembayaran';
                }
            });
        })
        .catch(() => {
            alert('Terjadi kesalahan. Coba lagi.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-qrcode"></i> Lanjut Pembayaran';
        });
}
</script>

<style>
.psb-wrap { max-width: 1000px; }
.psb-info-banner {
    display: flex; align-items: center; gap: 16px;
    background: linear-gradient(135deg, #2c2417, #3d3225);
    border-radius: 14px; padding: 20px 24px; margin-bottom: 24px; color: #fff;
}
.psb-info-icon { font-size: 32px; color: var(--gold); flex-shrink: 0; }
.psb-info-title { font-size: 17px; font-weight: 700; }
.psb-info-sub { font-size: 13px; color: rgba(255,255,255,0.65); margin-top: 3px; }

.psb-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
@media(max-width: 768px) { .psb-grid { grid-template-columns: 1fr; } }

.psb-card { background: #fff; border-radius: 14px; box-shadow: var(--shadow); overflow: hidden; }
.psb-card-header {
    padding: 16px 22px; font-size: 15px; font-weight: 700; color: var(--text-dark);
    border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px;
    font-family: 'Playfair Display', serif;
}
.psb-card-header i { color: var(--gold); }
.psb-card-body { padding: 20px 22px; display: flex; flex-direction: column; gap: 16px; }
.psb-card-body--list { padding: 0; }

.psb-label { font-size: 12px; font-weight: 600; color: var(--text-dark); display: block; margin-bottom: 7px; }
.psb-optional { font-weight: 400; color: var(--text-light); }
.psb-hint { font-size: 11px; color: var(--text-light); margin-top: 4px; }

.psb-jenis-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; }
.psb-jenis-opt input { display: none; }
.psb-jenis-card {
    border: 2px solid #e5e0d5; border-radius: 10px; padding: 10px 6px;
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    font-size: 11px; font-weight: 600; color: #999; cursor: pointer;
    transition: all 0.15s; text-align: center; background: #fafafa;
}
.psb-jenis-card i { font-size: 18px; color: #ccc; }
.psb-jenis-opt input:checked + .psb-jenis-card {
    border-color: var(--gold); background: var(--gold-light);
    color: var(--gold-dark); box-shadow: 0 0 0 3px rgba(212,175,55,0.15);
}
.psb-jenis-opt input:checked + .psb-jenis-card i { color: var(--gold-dark); }

.psb-nominal-quick { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 10px; }
.psb-quick-btn {
    padding: 7px 14px; border: 1.5px solid #e5e0d5; border-radius: 20px;
    font-size: 12px; font-weight: 600; cursor: pointer; background: #fff;
    color: var(--text-mid); transition: all 0.15s;
}
.psb-quick-btn:hover, .psb-quick-btn.active {
    border-color: var(--gold); background: var(--gold-light); color: var(--gold-dark);
}

.psb-input-wrap { display: flex; align-items: center; border: 1.5px solid #e5e0d5; border-radius: 9px; overflow: hidden; transition: border-color 0.15s; }
.psb-input-wrap:focus-within { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(212,175,55,0.12); }
.psb-prefix { padding: 0 12px; font-size: 13px; font-weight: 700; color: var(--gold-dark); background: var(--gold-light); height: 44px; display: flex; align-items: center; border-right: 1px solid #e5e0d5; }
.psb-input--nominal { border: none !important; box-shadow: none !important; flex: 1; font-size: 18px; font-weight: 700; padding: 0 14px; height: 44px; outline: none; }

.psb-input {
    width: 100%; border: 1.5px solid #e5e0d5; border-radius: 9px;
    padding: 10px 14px; font-size: 13.5px; outline: none;
    transition: border-color 0.15s; font-family: inherit; box-sizing: border-box;
}
.psb-input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(212,175,55,0.12); }
.psb-textarea { resize: vertical; min-height: 68px; }

.psb-pay-btn {
    width: 100%; padding: 14px; border: none; border-radius: 10px;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: #fff; font-size: 15px; font-weight: 700; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 10px;
    transition: opacity 0.2s, transform 0.2s;
}
.psb-pay-btn:hover:not(:disabled) { opacity: 0.9; transform: translateY(-1px); }
.psb-pay-btn:disabled { opacity: 0.6; cursor: not-allowed; }

.psb-payment-icons { display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap; }
.psb-payment-icons img { height: 22px; object-fit: contain; filter: grayscale(20%); }
.psb-qris-badge { background: #e30613; color: #fff; font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 5px; letter-spacing: 1px; }

/* History */
.psb-history-item { display: flex; align-items: center; gap: 14px; padding: 14px 20px; border-bottom: 1px solid #f3f1ec; }
.psb-history-item:last-child { border-bottom: none; }
.psb-history-icon { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
.psb-history-icon--settlement { background: #d1fae5; color: #059669; }
.psb-history-icon--pending    { background: #fef3c7; color: #d97706; }
.psb-history-icon--expire, .psb-history-icon--cancel, .psb-history-icon--deny { background: #fee2e2; color: #dc2626; }
.psb-history-jenis { font-size: 13px; font-weight: 600; color: var(--text-dark); }
.psb-history-date { font-size: 11px; color: var(--text-light); margin-top: 2px; }
.psb-history-method { font-size: 11px; color: var(--gold-dark); font-weight: 600; margin-top: 1px; }
.psb-history-right { margin-left: auto; text-align: right; }
.psb-history-amount { font-size: 14px; font-weight: 700; color: var(--text-dark); }
.psb-status { font-size: 10.5px; font-weight: 600; padding: 2px 8px; border-radius: 20px; margin-top: 3px; display: inline-block; }
.psb-status--settlement { background: #d1fae5; color: #065f46; }
.psb-status--pending    { background: #fef3c7; color: #92400e; }
.psb-status--expire, .psb-status--cancel, .psb-status--deny { background: #fee2e2; color: #991b1b; }

.psb-empty { padding: 40px; text-align: center; color: var(--text-light); }
.psb-empty i { font-size: 32px; margin-bottom: 12px; display: block; color: #e5e0d5; }
</style>

@endsection