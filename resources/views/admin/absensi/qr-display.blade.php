{{--
  Komponen ini bisa dimasukkan ke view admin/absensi/index.blade.php
  atau ditampilkan sebagai modal/panel terpisah.
  
  Cara pakai: @include('admin.absensi.qr-display', ['schedule' => $schedule])
  Atau tambahkan tombol "Tampilkan QR" di tabel jadwal.
--}}

<style>
/* QR Display Modal */
.qr-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.65);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.qr-modal-overlay.active { display: flex; }

.qr-modal {
    background: #1e1a14;
    border-radius: 20px;
    padding: 2.5rem;
    max-width: 480px;
    width: 90%;
    text-align: center;
    color: #fff;
    box-shadow: 0 25px 60px rgba(0,0,0,.5);
    position: relative;
}

.qr-modal .close-btn {
    position: absolute;
    top: 1rem; right: 1rem;
    background: rgba(255,255,255,.1);
    border: none;
    color: #fff;
    width: 32px; height: 32px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qr-modal .close-btn:hover { background: rgba(255,255,255,.2); }

.qr-modal h2 {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: .3rem;
    color: #d4af37;
}

.qr-modal .sub {
    font-size: .82rem;
    color: rgba(255,255,255,.6);
    margin-bottom: 1.8rem;
}

.qr-code-area {
    background: #fff;
    border-radius: 16px;
    padding: 1.5rem;
    margin: 0 auto 1.5rem;
    width: fit-content;
}

.qr-code-area img { display: block; }

.qr-url-box {
    background: rgba(255,255,255,.08);
    border-radius: 10px;
    padding: .75rem 1rem;
    font-size: .75rem;
    color: rgba(255,255,255,.7);
    word-break: break-all;
    margin-bottom: 1rem;
}

.qr-expires {
    font-size: .8rem;
    color: #f59e0b;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
    margin-bottom: 1.5rem;
}

.qr-countdown {
    font-size: 1.5rem;
    font-weight: 800;
    color: #d4af37;
    display: block;
    margin: .5rem 0;
}

.btn-regen {
    background: #d4af37;
    color: #1e1a14;
    border: none;
    padding: .7rem 1.5rem;
    border-radius: 8px;
    font-weight: 700;
    font-size: .9rem;
    cursor: pointer;
    transition: .2s;
}

.btn-regen:hover { background: #b8960b; }

/* Tombol di tabel */
.btn-show-qr {
    padding: .35rem .8rem;
    background: #1e1a14;
    color: #d4af37;
    border: 1.5px solid #d4af37;
    border-radius: 6px;
    font-size: .78rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    transition: .2s;
}

.btn-show-qr:hover { background: #d4af37; color: #1e1a14; }
</style>

{{-- Tombol (taruh di setiap baris jadwal di tabel) --}}
{{-- Contoh penggunaan di tabel schedules: --}}
{{--
<button class="btn-show-qr" onclick="showQr({{ $schedule->id }}, '{{ $schedule->title }}', '{{ $schedule->emoji }}')">
    <i class="fas fa-qrcode"></i> QR Absen
</button>
--}}

{{-- Modal QR --}}
<div class="qr-modal-overlay" id="qrModalOverlay">
    <div class="qr-modal">
        <button class="close-btn" onclick="closeQr()"><i class="fas fa-times"></i></button>

        <div style="font-size:2rem;margin-bottom:.5rem" id="qrEmoji">⛪</div>
        <h2 id="qrScheduleName">Memuat...</h2>
        <div class="sub">Scan QR di bawah untuk catat kehadiran</div>

        <div id="qrLoadingArea" style="padding:2rem;color:rgba(255,255,255,.5)">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <div style="margin-top:1rem;font-size:.85rem">Membuat QR Code...</div>
        </div>

        <div id="qrReadyArea" style="display:none">
            <div class="qr-code-area">
                <div id="qrCodeImg"></div>
            </div>

            <div class="qr-url-box" id="qrUrlBox"></div>

            <div class="qr-expires">
                <i class="fas fa-clock"></i>
                QR berlaku hingga <strong id="qrExpires"></strong>
                &bull; Sisa waktu: <span class="qr-countdown" id="qrCountdown">--:--</span>
            </div>

            <button class="btn-regen" id="qrRegenBtn" onclick="regenQr()">
                <i class="fas fa-sync-alt"></i> Perbarui QR
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
let currentScheduleId = null;
let countdownInterval  = null;
let expiresAt          = null;

async function showQr(scheduleId, name, emoji) {
    currentScheduleId = scheduleId;
    document.getElementById('qrScheduleName').textContent = name;
    document.getElementById('qrEmoji').textContent        = emoji || '⛪';
    document.getElementById('qrLoadingArea').style.display = '';
    document.getElementById('qrReadyArea').style.display   = 'none';
    document.getElementById('qrModalOverlay').classList.add('active');

    await generateQr(scheduleId);
}

async function generateQr(scheduleId) {
    try {
        const res  = await fetch(`/admin/absensi/qr/${scheduleId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        const data = await res.json();

        // Render QR
        document.getElementById('qrCodeImg').innerHTML = '';
        new QRCode(document.getElementById('qrCodeImg'), {
            text: data.url,
            width: 200,
            height: 200,
            colorDark: '#1e1a14',
            colorLight: '#ffffff',
        });

        document.getElementById('qrUrlBox').textContent    = data.url;
        document.getElementById('qrExpires').textContent   = data.expires_at;

        // Set countdown
        expiresAt = new Date();
        expiresAt.setHours(parseInt(data.expires_at.split(':')[0]));
        expiresAt.setMinutes(parseInt(data.expires_at.split(':')[1]));
        expiresAt.setSeconds(0);

        startCountdown();

        document.getElementById('qrLoadingArea').style.display = 'none';
        document.getElementById('qrReadyArea').style.display   = '';

    } catch (e) {
        alert('Gagal membuat QR Code. Coba lagi.');
        closeQr();
    }
}

async function regenQr() {
    if (!currentScheduleId) return;
    document.getElementById('qrLoadingArea').style.display = '';
    document.getElementById('qrReadyArea').style.display   = 'none';
    clearInterval(countdownInterval);
    await generateQr(currentScheduleId);
}

function startCountdown() {
    clearInterval(countdownInterval);
    countdownInterval = setInterval(() => {
        const now  = new Date();
        const diff = Math.max(0, Math.floor((expiresAt - now) / 1000));

        const mm = String(Math.floor(diff / 60)).padStart(2, '0');
        const ss = String(diff % 60).padStart(2, '0');
        document.getElementById('qrCountdown').textContent = `${mm}:${ss}`;

        if (diff === 0) {
            clearInterval(countdownInterval);
            document.getElementById('qrCountdown').textContent = 'EXPIRED';
            document.getElementById('qrCountdown').style.color = '#ef4444';
        }
    }, 1000);
}

function closeQr() {
    clearInterval(countdownInterval);
    document.getElementById('qrModalOverlay').classList.remove('active');
    currentScheduleId = null;
}

// Tutup modal klik overlay
document.getElementById('qrModalOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeQr();
});
</script>
@endpush
