<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keaslian Produk Beezam</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background:
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l30 17.3v34.6L30 69.3 0 51.9V17.3L30 0zm0 6L6 19.5v27L30 60l24-13.5v-27L30 6z' fill='%23EF9F27' fill-opacity='0.06'/%3E%3C/svg%3E"),
                linear-gradient(160deg, #1e0e02 0%, #412402 45%, #6b3a08 100%);
            font-family: 'Nunito', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        /* ── Kartu utama ── */
        .verify-card {
            background: #fffdf8;
            border-radius: 24px;
            border-top: 5px solid #EF9F27;
            box-shadow:
                0 32px 64px rgba(0,0,0,0.45),
                0 0 0 1px rgba(239,159,39,0.2);
            overflow: hidden;
            max-width: 480px;
            width: 100%;
            animation: cardIn 0.7s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(28px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ── Tetesan madu ── */
        .drip-row {
            display: flex;
            justify-content: center;
            gap: 24px;
            padding: 0 40px;
            margin-top: -1px;
        }
        .drip {
            width: 8px;
            background: linear-gradient(to bottom, #EF9F27, #FAC775);
            border-radius: 0 0 8px 8px;
            animation: drip 3s ease-in-out infinite;
        }
        .drip:nth-child(1) { height: 20px; animation-delay: 0s; }
        .drip:nth-child(2) { height: 13px; animation-delay: 0.5s; }
        .drip:nth-child(3) { height: 22px; animation-delay: 1s; }
        .drip:nth-child(4) { height: 11px; animation-delay: 1.5s; }
        .drip:nth-child(5) { height: 17px; animation-delay: 0.25s; }
        @keyframes drip {
            0%,100% { transform: scaleY(1); }
            50%      { transform: scaleY(1.18); }
        }

        /* ── Header ── */
        .verify-header {
            background: linear-gradient(160deg, #412402 0%, #854F0B 100%);
            padding: 30px 30px 24px;
            text-align: center;
            position: relative;
        }
        .verify-header::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 0l20 11.5v23L20 46 0 34.5v-23L20 0zm0 4L4 13.5v19L20 42l16-9.5v-19L20 4z' fill='%23ffffff' fill-opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        .shield-icon {
            width: 76px;
            height: 76px;
            background: rgba(239,159,39,0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            border: 3px solid rgba(239,159,39,0.4);
            box-shadow: 0 0 0 6px rgba(239,159,39,0.08), 0 8px 24px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-7px); }
        }

        .verify-header h4 {
            font-family: 'Cinzel', serif;
            font-weight: 700;
            font-size: 19px;
            color: #FAC775;
            letter-spacing: 1.5px;
            margin: 0 0 6px;
            position: relative;
            z-index: 1;
        }

        .verify-header p {
            color: rgba(250,199,117,0.65);
            font-size: 13px;
            margin: 0;
            position: relative;
            z-index: 1;
            line-height: 1.6;
        }

        .badge-tech {
            background: rgba(255,255,255,0.08);
            color: #FAC775;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            border: 1px solid rgba(239,159,39,0.35);
            display: inline-block;
            margin-top: 12px;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
        }

        /* ── Body ── */
        .verify-body {
            padding: 28px 30px 24px;
        }

        /* ── Gold divider ── */
        .gold-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
        }
        .gold-divider::before,
        .gold-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(239,159,39,0.5), transparent);
        }
        .gold-divider span {
            font-size: 11px;
            font-weight: 700;
            color: #BA7517;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* ── Alert ── */
        .alert-honey {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: #fff8ec;
            border: 1px solid #FAC775;
            border-left: 4px solid #EF9F27;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            color: #633806;
            margin-bottom: 18px;
        }

        /* ── Form ── */
        .form-label-honey {
            display: block;
            font-size: 11.5px;
            font-weight: 700;
            color: #7c5a2a;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
        }

        .input-shell {
            display: flex;
            align-items: center;
            background: #fff;
            border: 1.5px solid #e8d5b2;
            border-radius: 12px;
            padding: 0 14px;
            gap: 10px;
            transition: border-color 0.25s, box-shadow 0.25s;
        }
        .input-shell:focus-within {
            border-color: #EF9F27;
            box-shadow: 0 0 0 4px rgba(239,159,39,0.14);
        }
        .input-shell .shell-icon {
            font-size: 17px;
            color: #c49a47;
            flex-shrink: 0;
            transition: color 0.25s;
        }
        .input-shell:focus-within .shell-icon { color: #EF9F27; }
        .input-shell input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            padding: 13px 0;
            font-size: 13.5px;
            font-family: 'Nunito', sans-serif;
            color: #2d1a06;
            font-weight: 500;
        }
        .input-shell input::placeholder { color: #c4a87a; font-weight: 400; }

        .form-hint {
            font-size: 11.5px;
            color: #b8956a;
            margin-top: 6px;
        }

        /* ── Tombol scan ── */
        .btn-scan {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 13px;
            margin-bottom: 14px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: #fff;
            font-family: 'Nunito', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(30,126,52,0.35);
            transition: all 0.25s;
        }
        .btn-scan:hover {
            box-shadow: 0 6px 20px rgba(30,126,52,0.5);
            transform: translateY(-2px);
            color: #fff;
        }

        /* ── Tombol stop ── */
        #btn-stop-scan {
            display: none;
            width: 100%;
            padding: 10px;
            margin-bottom: 14px;
            border: none;
            border-radius: 12px;
            background: rgba(220,53,69,0.1);
            border: 1.5px solid rgba(220,53,69,0.4);
            color: #c0392b;
            font-family: 'Nunito', sans-serif;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        #btn-stop-scan:hover { background: rgba(220,53,69,0.18); }

        /* ── Tombol verifikasi ── */
        .btn-verify {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px;
            margin-top: 4px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #FAC775 0%, #EF9F27 55%, #BA7517 100%);
            color: #2d1a06;
            font-family: 'Cinzel', serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(186,117,23,0.4);
            transition: all 0.25s;
            margin-bottom: 24px;
        }
        .btn-verify:hover {
            background: linear-gradient(135deg, #EF9F27 0%, #BA7517 100%);
            box-shadow: 0 6px 22px rgba(186,117,23,0.55);
            transform: translateY(-2px);
            color: #2d1a06;
        }

        /* ── Divider atau ── */
        .or-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
        }
        .or-divider hr { flex: 1; margin: 0; border-color: rgba(239,159,39,0.2); }
        .or-divider span { font-size: 12px; color: #b8956a; }

        /* ── Scanner ── */
        #scanner-container {
            display: none;
            margin-bottom: 16px;
        }
        #scanner-wrapper {
            position: relative;
            width: 100%;
            border-radius: 14px;
            overflow: hidden;
            background: #000;
            border: 2px solid rgba(239,159,39,0.3);
        }
        #scanner-video {
            width: 100%;
            display: block;
            border-radius: 12px;
        }
        #scanner-canvas { display: none; }

        .scanner-overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }
        .scanner-frame {
            width: 200px;
            height: 200px;
            border: 3px solid #EF9F27;
            border-radius: 12px;
            box-shadow: 0 0 0 9999px rgba(0,0,0,0.5);
            position: relative;
        }
        .scanner-frame::before {
            content: '';
            position: absolute;
            top: -3px; left: -3px;
            right: -3px;
            height: 4px;
            background: #EF9F27;
            animation: scan-line 2s linear infinite;
            border-radius: 2px;
        }
        @keyframes scan-line {
            0%   { top: -3px; }
            100% { top: calc(100% - 1px); }
        }
        .scanner-status {
            text-align: center;
            font-size: 13px;
            padding: 8px;
            border-radius: 0 0 12px 12px;
            background: #1e0e02;
            color: rgba(250,199,117,0.6);
            font-family: 'Nunito', sans-serif;
        }
        .scanner-status.detecting { color: #EF9F27; font-weight: 700; }

        /* ── Cara verifikasi ── */
        .how-box {
            background: rgba(239,159,39,0.06);
            border: 1px solid rgba(239,159,39,0.2);
            border-radius: 14px;
            padding: 16px 18px;
        }
        .how-title {
            font-size: 11px;
            font-weight: 700;
            color: #7c5a2a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 14px;
        }
        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }
        .step-item:last-child { margin-bottom: 0; }
        .step-num {
            width: 26px;
            height: 26px;
            background: linear-gradient(135deg, #FAC775, #EF9F27);
            color: #2d1a06;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .step-text {
            font-size: 13px;
            color: #7c5a2a;
            padding-top: 4px;
            line-height: 1.5;
        }
        .step-text strong { color: #2d1a06; }

        /* ── Footer ── */
        .verify-footer {
            background: rgba(239,159,39,0.06);
            padding: 14px 30px;
            text-align: center;
            border-top: 1px solid rgba(239,159,39,0.2);
            font-size: 11.5px;
            color: #b8956a;
        }
        .verify-footer strong { color: #7c5a2a; }
    
    /* ── Tombol kembali ke halaman konsumen ── */
    .btn-kembali {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px;
        margin-top: 14px;
        border: 1.5px solid #EF9F27;
        border-radius: 12px;
        background: transparent;
        color: #BA7517;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-decoration: none;
        transition: all 0.25s;
    }
    .btn-kembali:hover {
        background: rgba(239,159,39,0.12);
        color: #7c5a2a;
        transform: translateY(-1px);
    }
</style>
</head>
<body>

<div class="verify-card">

    {{-- Tetesan Madu --}}
    <div class="drip-row">
        <div class="drip"></div>
        <div class="drip"></div>
        <div class="drip"></div>
        <div class="drip"></div>
        <div class="drip"></div>
    </div>

    {{-- Header --}}
    <div class="verify-header">
        <div class="shield-icon">
            <i class="bi bi-shield-check" style="font-size:32px;color:#EF9F27;"></i>
        </div>
        <h4>Verifikasi Keaslian Produk</h4>
        <p>Masukkan kode produk yang tertera<br>pada kemasan produk Beezam Honey</p>
        <span class="badge-tech">
            <i class="bi bi-lock me-1"></i>HMAC-SHA256 Secured
        </span>
    </div>

    {{-- Body --}}
    <div class="verify-body">

        <div class="gold-divider"><span>Verifikasi Produk</span></div>

        {{-- Alert error --}}
        @if(session('error'))
            <div class="alert-honey">
                <i class="bi bi-exclamation-octagon-fill" style="font-size:17px;flex-shrink:0;margin-top:1px;"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        {{-- Tombol scan ── --}}
        <button type="button" class="btn-scan" id="btn-start-scan">
            <i class="bi bi-camera" style="font-size:18px;"></i>Scan QR Code dengan Kamera
        </button>

        {{-- Tombol stop ── --}}
        <button type="button" id="btn-stop-scan">
            <i class="bi bi-x-circle me-2"></i>Tutup Kamera
        </button>

        {{-- Scanner area ── --}}
        <div id="scanner-container">
            <div id="scanner-wrapper">
                <video id="scanner-video" playsinline autoplay muted></video>
                <canvas id="scanner-canvas"></canvas>
                <div class="scanner-overlay">
                    <div class="scanner-frame"></div>
                </div>
            </div>
            <div class="scanner-status" id="scanner-status">
                Arahkan kamera ke QR Code produk...
            </div>
        </div>

        {{-- Divider atau ── --}}
        <div class="or-divider">
            <hr><span>atau input manual</span><hr>
        </div>

        {{-- Form input manual ── --}}
        <form action="{{ route('verify.search') }}" method="GET">
            <div class="mb-3">
                <label class="form-label-honey" for="input-kode">
                    Kode Produk
                </label>
                <div class="input-shell">
                    <i class="bi bi-upc-scan shell-icon"></i>
                    <input
                        type="text"
                        name="kode"
                        id="input-kode"
                        placeholder="Contoh: PRD-1704067200-AB3X9K"
                        autofocus
                    >
                </div>
                <div class="form-hint">
                    <i class="bi bi-info-circle me-1"></i>
                    Kode produk tertera pada label kemasan produk Beezam.
                </div>
            </div>

            <button type="submit" class="btn-verify">
                <i class="bi bi-search" style="font-size:16px;"></i>Verifikasi Sekarang
            </button>
        </form>


        {{-- Tombol kembali ke halaman konsumen --}}
        <a href="{{ route('customer.dashboard') }}" class="btn-kembali">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        {{-- Cara verifikasi ── --}}
        <div class="how-box">
            <div class="how-title">
                <i class="bi bi-question-circle me-1"></i>Cara Verifikasi Produk
            </div>

            <div class="step-item">
                <div class="step-num">1</div>
                <div class="step-text">
                    Klik <strong>Scan QR Code dengan Kamera</strong> lalu
                    arahkan ke QR Code pada kemasan produk
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">2</div>
                <div class="step-text">
                    Atau lihat kode produk pada kemasan dan masukkan
                    secara manual di kolom di atas
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">3</div>
                <div class="step-text">
                    Sistem akan otomatis mengarahkan ke halaman hasil
                    verifikasi setelah QR terdeteksi
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">4</div>
                <div class="step-text">
                    Status keaslian ditentukan menggunakan algoritma
                    <strong>HMAC-SHA256</strong>
                </div>
            </div>
        </div>

    </div>

    {{-- Footer --}}
    <div class="verify-footer">
        <i class="bi bi-shield-lock me-1"></i>
        Sistem ini menggunakan enkripsi <strong>HMAC-SHA256</strong>
        untuk menjamin keaslian produk Beezam Honey.
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

<script>
    const btnStart  = document.getElementById('btn-start-scan');
    const btnStop   = document.getElementById('btn-stop-scan');
    const container = document.getElementById('scanner-container');
    const video     = document.getElementById('scanner-video');
    const canvas    = document.getElementById('scanner-canvas');
    const status    = document.getElementById('scanner-status');
    const inputKode = document.getElementById('input-kode');
    const ctx       = canvas.getContext('2d');

    let stream    = null;
    let animFrame = null;
    let detected  = false;

    btnStart.addEventListener('click', async () => {
        detected = false;
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment' }
            });
            video.srcObject = stream;
            video.play();

            container.style.display = 'block';
            btnStart.style.display  = 'none';
            btnStop.style.display   = 'block';

            requestAnimationFrame(scanFrame);
        } catch (err) {
            alert('Kamera tidak dapat diakses. Pastikan izin kamera sudah diberikan.');
        }
    });

    btnStop.addEventListener('click', stopScanner);

    function stopScanner() {
        if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
        if (animFrame) { cancelAnimationFrame(animFrame); animFrame = null; }
        container.style.display = 'none';
        btnStart.style.display  = 'block';
        btnStop.style.display   = 'none';
        status.textContent      = 'Arahkan kamera ke QR Code produk...';
        status.className        = 'scanner-status';
        detected                = false;
    }

    function scanFrame() {
        if (detected) return;
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code      = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: 'dontInvert'
            });

            if (code) {
                detected = true;
                status.textContent = '✅ QR Code terdeteksi! Mengalihkan...';
                status.className   = 'scanner-status detecting';

                const url   = code.data;
                const match = url.match(/\/verify\/([A-Z0-9\-]+)$/i);

                if (match) {
                    setTimeout(() => { stopScanner(); window.location.href = url; }, 600);
                } else {
                    inputKode.value    = url;
                    status.textContent = '✅ Kode terdeteksi! Klik Verifikasi.';
                    setTimeout(stopScanner, 1000);
                }
                return;
            }
        }
        animFrame = requestAnimationFrame(scanFrame);
    }
</script>
</body>
</html>