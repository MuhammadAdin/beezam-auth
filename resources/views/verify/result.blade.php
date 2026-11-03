<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Verifikasi Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            padding: 30px 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Nunito', sans-serif;
            background:
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l30 17.3v34.6L30 69.3 0 51.9V17.3L30 0zm0 6L6 19.5v27L30 60l24-13.5v-27L30 6z' fill='%23EF9F27' fill-opacity='0.06'/%3E%3C/svg%3E"),
                linear-gradient(160deg, #1e0e02 0%, #412402 45%, #6b3a08 100%);
        }

        /* ── Kartu ── */
        .result-card {
            background: #fffdf8;
            border-radius: 24px;
            border-top: 5px solid #EF9F27;
            box-shadow:
                0 32px 64px rgba(0,0,0,0.45),
                0 0 0 1px rgba(239,159,39,0.2);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
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

        /* Warna drip sesuai status */
        .drip-asli         { background: linear-gradient(to bottom, #28a745, #6ee7a0); }
        .drip-mencurigakan { background: linear-gradient(to bottom, #EF9F27, #FAC775); }
        .drip-tidak        { background: linear-gradient(to bottom, #dc3545, #f08080); }
        .drip-notfound     { background: linear-gradient(to bottom, #6c757d, #adb5bd); }

        /* ── Header ── */
        .result-header {
            padding: 30px 30px 24px;
            text-align: center;
            position: relative;
        }
        .result-header::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 0l20 11.5v23L20 46 0 34.5v-23L20 0zm0 4L4 13.5v19L20 42l16-9.5v-19L20 4z' fill='%23ffffff' fill-opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        .result-header.asli         { background: linear-gradient(160deg, #1a4d2e 0%, #28a745 100%); }
        .result-header.mencurigakan { background: linear-gradient(160deg, #412402 0%, #854F0B 100%); }
        .result-header.tidak        { background: linear-gradient(160deg, #6b0f0f 0%, #c62828 100%); }
        .result-header.notfound     { background: linear-gradient(160deg, #2c2c2c 0%, #546e7a 100%); }

        .status-icon-wrap {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            border: 3px solid rgba(255,255,255,0.3);
            position: relative;
            z-index: 1;
            animation: pulse 2.5s infinite;
        }
        @keyframes pulse {
            0%   { box-shadow: 0 0 0 0 rgba(255,255,255,0.35); }
            70%  { box-shadow: 0 0 0 16px rgba(255,255,255,0); }
            100% { box-shadow: 0 0 0 0 rgba(255,255,255,0); }
        }

        .status-title {
            font-family: 'Cinzel', serif;
            font-size: 19px;
            font-weight: 700;
            color: #FAC775;
            letter-spacing: 2px;
            margin: 0 0 6px;
            position: relative;
            z-index: 1;
        }
        .result-header.asli .status-title,
        .result-header.tidak .status-title,
        .result-header.notfound .status-title {
            color: #fff;
        }

        .status-subtitle {
            font-size: 13px;
            color: rgba(255,255,255,0.75);
            margin: 0;
            position: relative;
            z-index: 1;
        }

        /* ── Body ── */
        .result-body { padding: 24px 28px 20px; }

        /* ── Pesan status ── */
        .status-msg {
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 13px;
            line-height: 1.6;
            text-align: center;
            margin-bottom: 18px;
            border-left: 4px solid;
        }
        .status-msg.asli         { background: rgba(40,167,69,0.08);  border-color: #28a745; color: #1a4d2e; }
        .status-msg.mencurigakan { background: rgba(239,159,39,0.1);  border-color: #EF9F27; color: #633806; }
        .status-msg.tidak        { background: rgba(220,53,69,0.08);  border-color: #dc3545; color: #6b0f0f; }
        .status-msg.notfound     { background: rgba(108,117,125,0.08);border-color: #6c757d; color: #333; }

        /* ── Scan counter ── */
        .scan-counter {
            text-align: center;
            padding: 14px;
            border-radius: 14px;
            margin-bottom: 16px;
            border: 1px solid;
        }
        .scan-counter.asli         { background: rgba(40,167,69,0.07);  border-color: rgba(40,167,69,0.2); }
        .scan-counter.mencurigakan { background: rgba(239,159,39,0.08); border-color: rgba(239,159,39,0.25); }
        .scan-counter.tidak        { background: rgba(220,53,69,0.07);  border-color: rgba(220,53,69,0.2); }

        .scan-num {
            font-size: 40px;
            font-weight: 800;
            line-height: 1;
        }
        .scan-num.asli         { color: #28a745; }
        .scan-num.mencurigakan { color: #EF9F27; }
        .scan-num.tidak        { color: #dc3545; }

        .scan-label {
            font-size: 12px;
            color: #b8956a;
            margin-top: 4px;
        }
        .progress-track {
            background: rgba(239,159,39,0.15);
            border-radius: 10px;
            height: 8px;
            overflow: hidden;
            margin: 10px 0 5px;
        }
        .progress-fill {
            height: 100%;
            border-radius: 10px;
            transition: width 1s ease;
        }
        .progress-hint {
            font-size: 11px;
            color: #b8956a;
            margin-top: 3px;
        }

        /* ── Foto produk ── */
        .product-img {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 14px;
            border: 3px solid rgba(239,159,39,0.3);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }

        /* ── Info rows ── */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid rgba(239,159,39,0.12);
            font-size: 14px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label {
            color: #b8956a;
            font-size: 12px;
            width: 40%;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .info-value {
            font-weight: 600;
            color: #2d1a06;
            text-align: right;
            width: 58%;
            word-break: break-word;
        }

        /* ── Info keamanan ── */
        .security-box {
            background: rgba(239,159,39,0.06);
            border: 1px solid rgba(239,159,39,0.18);
            border-radius: 14px;
            padding: 14px 16px;
            margin-top: 16px;
            font-size: 12px;
            color: #7c5a2a;
        }
        .security-box strong { color: #2d1a06; }
        .security-box ul {
            padding-left: 16px;
            margin: 8px 0 0;
            line-height: 2;
        }

        /* ── Produk tidak ditemukan ── */
        .notfound-box {
            text-align: center;
            padding: 20px 0;
            color: #b8956a;
        }

        /* ── Tombol kembali ── */
        .btn-back {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px;
            margin-top: 20px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #FAC775 0%, #EF9F27 55%, #BA7517 100%);
            color: #2d1a06;
            font-family: 'Cinzel', serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(186,117,23,0.4);
            transition: all 0.25s;
        }
        .btn-back:hover {
            background: linear-gradient(135deg, #EF9F27 0%, #BA7517 100%);
            box-shadow: 0 6px 22px rgba(186,117,23,0.55);
            transform: translateY(-2px);
            color: #2d1a06;
        }


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
        /* ── Footer ── */
        .result-footer {
            background: rgba(239,159,39,0.06);
            padding: 12px 28px;
            text-align: center;
            border-top: 1px solid rgba(239,159,39,0.2);
            font-size: 11.5px;
            color: #b8956a;
        }
        .result-footer strong { color: #7c5a2a; }
    </style>
</head>

@php
    $statusClass = match($status) {
        'ASLI'                => 'asli',
        'MENCURIGAKAN'        => 'mencurigakan',
        'TIDAK_TERVERIFIKASI' => 'tidak',
        default               => 'notfound',
    };
@endphp

<body>

<div class="result-card">

    {{-- Tetesan Madu --}}
    <div class="drip-row">
        <div class="drip drip-{{ $statusClass }}"></div>
        <div class="drip drip-{{ $statusClass }}"></div>
        <div class="drip drip-{{ $statusClass }}"></div>
        <div class="drip drip-{{ $statusClass }}"></div>
        <div class="drip drip-{{ $statusClass }}"></div>
    </div>

    {{-- Header --}}
    <div class="result-header {{ $statusClass }}">
        <div class="status-icon-wrap">
            @if($status === 'ASLI')
                <i class="bi bi-patch-check-fill" style="font-size:36px;color:#fff;"></i>
            @elseif($status === 'MENCURIGAKAN')
                <i class="bi bi-exclamation-triangle-fill" style="font-size:36px;color:#FAC775;"></i>
            @elseif($status === 'TIDAK_TERVERIFIKASI')
                <i class="bi bi-shield-x" style="font-size:36px;color:#fff;"></i>
            @else
                <i class="bi bi-question-circle" style="font-size:36px;color:#fff;"></i>
            @endif
        </div>

        @if($status === 'ASLI')
            <div class="status-title">✅ PRODUK ASLI</div>
            <p class="status-subtitle">Produk ini telah terverifikasi keasliannya</p>
        @elseif($status === 'MENCURIGAKAN')
            <div class="status-title">⚠️ MENCURIGAKAN</div>
            <p class="status-subtitle">Produk ini berpotensi dipalsukan</p>
        @elseif($status === 'TIDAK_TERVERIFIKASI')
            <div class="status-title">❌ TIDAK TERVERIFIKASI</div>
            <p class="status-subtitle">Hash tidak cocok — produk kemungkinan palsu</p>
        @else
            <div class="status-title">🔍 TIDAK DITEMUKAN</div>
            <p class="status-subtitle">Kode produk tidak ada dalam database</p>
        @endif
    </div>

    {{-- Body --}}
    <div class="result-body">

        {{-- Pesan status --}}
        <div class="status-msg {{ $statusClass }}">{{ $message }}</div>

        @if($product)

            {{-- Scan counter --}}
            <div class="scan-counter {{ $statusClass }}">
                <div class="scan-num {{ $statusClass }}">{{ $product->total_scan }}</div>
                <div class="scan-label">Total scan QR Code</div>

                @php
                    $pct      = min(($product->total_scan / 3) * 100, 100);
                    $barColor = $product->total_scan <= 3 ? '#28a745' : '#EF9F27';
                @endphp
                <div class="progress-track">
                    <div class="progress-fill"
                         style="width:{{ $pct }}%; background:{{ $barColor }};"></div>
                </div>
                <div class="progress-hint">
                    @if($product->total_scan <= 3)
                        {{ $product->total_scan }}/3 scan normal
                    @else
                        Melebihi batas 3x scan ({{ $product->total_scan }}x)
                    @endif
                </div>
            </div>

            {{-- Foto produk --}}
            @if($product->category->foto_produk)
            <div class="text-center mb-3">
                <img src="{{ asset('storage/' . $product->category->foto_produk) }}"
                     alt="{{ $product->category->nama_kategori }}"
                     class="product-img">
            </div>
            @endif

            {{-- Detail produk --}}
            <div class="info-row">
                <div class="info-label">Nama Produk</div>
                <div class="info-value">{{ $product->category->nama_kategori }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Batch Produk</div>
                <div class="info-value">{{ $product->category->batch_produk }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Produksi</div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($product->category->tanggal_produksi)->format('d F Y') }}
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Kode Produk</div>
                <div class="info-value">
                    <code style="font-size:11px;color:#7c5a2a;">{{ $product->kode_produk }}</code>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Verifikasi Hash</div>
                <div class="info-value">
                    @if($status !== 'TIDAK_TERVERIFIKASI')
                        <span style="color:#28a745;font-weight:700;">
                            <i class="bi bi-shield-check"></i> Valid
                        </span>
                    @else
                        <span style="color:#dc3545;font-weight:700;">
                            <i class="bi bi-shield-x"></i> Tidak Valid
                        </span>
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Waktu Verifikasi</div>
                <div class="info-value" style="font-size:12px;">
                    {{ now()->format('d/m/Y H:i:s') }}
                </div>
            </div>

            {{-- Info keamanan --}}
            <div class="security-box">
                <strong><i class="bi bi-shield-lock me-1"></i>Cara Sistem Mencegah Pemalsuan QR:</strong>
                <ul>
                    <li>Setiap produk memiliki <strong>secret key unik</strong> yang hanya diketahui sistem</li>
                    <li>QR berisi kode produk yang di-verifikasi ulang dengan <strong>HMAC-SHA256</strong></li>
                    <li>Hash tidak bisa dipalsukan tanpa mengetahui secret key</li>
                    <li>Scan lebih dari <strong>3 kali</strong> otomatis ditandai mencurigakan</li>
                    <li>Setiap scan dicatat: <strong>IP, waktu, dan perangkat</strong></li>
                </ul>
            </div>

        @else
            {{-- Produk tidak ditemukan --}}
            <div class="notfound-box">
                <i class="bi bi-database-x" style="font-size:40px;color:#e8d5b2;"></i>
                <p class="mt-2" style="font-size:13px;">
                    Kode: <code style="color:#7c5a2a;">{{ $kodeProduk }}</code> tidak ditemukan.
                </p>
            </div>
        @endif

        {{-- Tombol kembali --}}
        <a href="{{ route('verify.index') }}" class="btn-back">
            <i class="bi bi-arrow-left" style="font-size:16px;"></i>Verifikasi Produk Lain
        </a>

        {{-- Tombol kembali ke halaman konsumen --}}
        <a href="{{ route('customer.dashboard') }}" class="btn-kembali">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

    </div>

    {{-- Footer --}}
    <div class="result-footer">
        <i class="bi bi-shield-lock me-1"></i>
        Diverifikasi menggunakan <strong>HMAC-SHA256</strong> &bull;
        {{ now()->format('d/m/Y H:i') }}
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>