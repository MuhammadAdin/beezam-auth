<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beezam Auth — Dashboard Konsumen</title>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap');

        :root {
            /* Wax & honeycomb palette */
            --wax-50:    #FFF8EC;   /* candle cream */
            --wax-100:   #FBEFD9;
            --wax-200:   #F3DDB3;
            --comb-300:  #E6C788;
            --comb-400:  #D6A95C;
            --honey-500: #C9911F;   /* primary gold */
            --honey-600: #AD7818;
            --bark-700:  #6B4A23;
            --bark-800:  #4A3318;
            --ink:       #2E2013;   /* deep walnut ink */
            --forest-600:#3B5D3F;   /* contrast accent: leaves */
            --forest-700:#2C4730;
            --seal-600:  #A8362B;   /* wax seal red, "asli" */
            --seal-100:  #F6DEDA;
            --paper:     #FFFFFF;
            --line:      rgba(74, 51, 24, 0.12);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--wax-50);
            color: var(--ink);
            overflow-x: hidden;
        }

        /* Subtle honeycomb texture — fixed, low-contrast, not floating emoji */
        .honeycomb-bg {
            background-color: var(--wax-50);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='88' height='152'%3E%3Cpath d='M44 0 L88 25 L88 75 L44 100 L0 75 L0 25 Z' fill='none' stroke='%23C9911F' stroke-opacity='0.08' stroke-width='1.5'/%3E%3C/svg%3E");
            background-size: 88px 152px;
        }

        /* ── NAVBAR ── */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 9999;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 248, 236, 0.92);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--line);
            transition: box-shadow 0.3s ease;
        }
        .navbar.scrolled { box-shadow: 0 4px 24px rgba(46,32,19,0.08); }

        .nav-brand {
            display: flex; align-items: center; gap: 12px;
            text-decoration: none;
        }
        .nav-logo {
            width: 40px; height: 40px;
            border-radius: 10px;
            overflow: hidden;
            border: 1.5px solid var(--wax-200);
            flex-shrink: 0;
        }
        .nav-logo img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .nav-brand-text {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 18px; color: var(--bark-800); line-height: 1.1;
            letter-spacing: -0.01em;
        }
        .nav-brand-sub {
            font-family: 'Space Mono', monospace;
            font-size: 9px; color: var(--honey-600);
            font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase;
            margin-top: 2px;
        }
        .nav-right { display: flex; align-items: center; gap: 10px; }
        .nav-admin-btn {
            display: flex; align-items: center; gap: 6px;
            padding: 9px 18px;
            border: 1.5px solid var(--bark-800);
            border-radius: 99px;
            color: var(--bark-800); font-size: 13px; font-weight: 600;
            text-decoration: none;
            background: transparent;
            transition: all 0.2s ease;
        }
        .nav-admin-btn:hover {
            background: var(--bark-800);
            color: var(--wax-50);
        }

        .page-wrapper { padding-top: 78px; }

        /* ── HERO — asymmetric two-column ledger layout ── */
        .hero {
            padding: 48px 24px 0;
            position: relative;
            overflow: hidden;
        }
        .hero-content {
            position: relative; z-index: 1;
            max-width: 1080px; margin: 0 auto;
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 0;
            border: 1px solid var(--line);
            border-radius: 18px 18px 0 0;
            overflow: hidden;
            background: var(--paper);
        }
        .hero-main {
            padding: 44px 40px 40px;
            border-right: 1px solid var(--line);
        }
        .hero-side {
            background: var(--bark-800);
            padding: 32px 28px;
            display: flex; flex-direction: column;
            justify-content: space-between;
            color: var(--wax-50);
        }

        /* Wax seal — the signature element, now top-right corner mark */
        .wax-seal {
            width: 64px; height: 64px;
            position: relative;
            display: flex; align-items: center; justify-content: center;
            clip-path: polygon(50% 0%, 93% 25%, 93% 75%, 50% 100%, 7% 75%, 7% 25%);
            background: linear-gradient(150deg, var(--seal-600) 0%, #8C2A21 100%);
            box-shadow: 0 6px 18px rgba(168,54,43,0.35);
            margin-bottom: 28px;
        }
        .wax-seal img {
            width: 78%; height: 78%; object-fit: cover;
            clip-path: polygon(50% 0%, 93% 25%, 93% 75%, 50% 100%, 7% 75%, 7% 25%);
            opacity: 0.92;
            mix-blend-mode: luminosity;
        }

        .hero-eyebrow {
            font-family: 'Space Mono', monospace;
            font-size: 11px; font-weight: 700;
            letter-spacing: 0.22em; text-transform: uppercase;
            color: var(--forest-700);
            margin-bottom: 18px;
        }

        .hero-title {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: clamp(28px, 4.5vw, 44px);
            line-height: 1.15;
            color: var(--bark-800);
            margin-bottom: 18px;
            letter-spacing: -0.01em;
            max-width: 460px;
        }
        .hero-title em {
            font-style: italic;
            font-weight: 500;
            color: var(--honey-500);
        }
        .hero-subtitle {
            font-size: 15px; color: var(--bark-700);
            line-height: 1.7; max-width: 420px; margin-bottom: 40px;
        }

        /* ── CTA — anchored bottom-left of main column, ticket-stub style ── */
        .cta-row {
            display: flex; align-items: stretch;
            border: 1.5px solid var(--ink);
            border-radius: 12px;
            overflow: hidden;
            max-width: 380px;
        }
        .cta-btn {
            display: flex; align-items: center; gap: 10px;
            background: var(--ink);
            color: var(--wax-50);
            font-family: 'Inter', sans-serif;
            font-size: 14px; font-weight: 600;
            padding: 16px 22px;
            border: none; cursor: pointer;
            transition: background 0.25s ease;
            letter-spacing: 0.01em;
            flex: 1;
        }
        .cta-btn:hover { background: var(--forest-700); }
        .cta-btn:active { transform: scale(0.99); }
        .cta-code {
            display: flex; align-items: center; justify-content: center;
            font-family: 'Space Mono', monospace;
            font-size: 11px; font-weight: 700;
            letter-spacing: 0.05em;
            padding: 0 16px;
            color: var(--bark-700);
            border-left: 1.5px dashed var(--line);
            white-space: nowrap;
        }
        .cta-hint {
            margin-top: 14px; font-size: 12px; color: var(--bark-700);
            font-family: 'Space Mono', monospace;
        }

        /* ── STAT LIST — vertical, inside dark side panel ── */
        .stat-list {
            display: flex; flex-direction: column;
            gap: 0;
        }
        .stat-item {
            display: flex; justify-content: space-between; align-items: baseline;
            padding: 14px 0;
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }
        .stat-item:first-of-type { border-top: 1px solid rgba(255,255,255,0.12); }
        .stat-num {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 22px; color: var(--wax-50);
            line-height: 1.2;
        }
        .stat-lbl {
            font-size: 11px; color: var(--comb-300);
            font-weight: 500;
            letter-spacing: 0.02em;
            text-align: right;
        }

        /* ── TRUST LIST — small caps list in side panel footer ── */
        .trust-bar {
            display: flex; flex-direction: column;
            gap: 8px; margin-top: 28px;
        }
        .trust-item {
            font-size: 11px; color: var(--comb-300); font-weight: 500;
            display: flex; align-items: center; gap: 8px;
        }
        .trust-item::before {
            content: '';
            width: 5px; height: 5px;
            background: var(--honey-500);
            clip-path: polygon(50% 0%, 93% 25%, 93% 75%, 50% 100%, 7% 75%, 7% 25%);
            flex-shrink: 0;
        }

        /* ── SECTION ── */
        .section {
            max-width: 1100px; margin: 0 auto;
            padding: 64px 24px;
        }
        .section-eyebrow {
            font-family: 'Space Mono', monospace;
            text-align: center; font-size: 11px; font-weight: 700;
            letter-spacing: 0.22em; text-transform: uppercase;
            color: var(--forest-700); margin-bottom: 12px;
        }
        .section-title {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: clamp(24px, 4vw, 34px);
            color: var(--bark-800); text-align: center; margin-bottom: 40px;
            letter-spacing: -0.01em;
        }

        /* ── KATEGORI GRID ── */
        .kategori-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 18px;
        }
        .kategori-card {
            background: var(--paper);
            border-radius: 4px 18px 4px 18px;
            overflow: hidden;
            border: 1px solid var(--line);
            transition: transform 0.25s ease, border-color 0.25s ease;
            cursor: pointer; text-decoration: none;
            display: block;
        }
        .kategori-card:hover {
            transform: translateY(-4px);
            border-color: var(--honey-500);
        }
        .kategori-img, .kategori-img-placeholder {
            width: 100%; height: 140px;
            background: var(--wax-100);
            display: flex; align-items: center; justify-content: center;
            font-size: 40px;
        }
        .kategori-img img { width: 100%; height: 100%; object-fit: cover; }
        .kategori-body { padding: 16px; }
        .kategori-name {
            font-family: 'Fraunces', serif;
            font-weight: 600; font-size: 16px; color: var(--bark-800); margin-bottom: 4px;
        }
        .kategori-count {
            font-family: 'Space Mono', monospace;
            font-size: 11px; color: var(--bark-700);
            display: flex; align-items: center; gap: 4px;
        }
        .kategori-badge {
            display: inline-block;
            background: var(--forest-600); color: var(--wax-50);
            font-size: 10px; font-weight: 700;
            letter-spacing: 0.06em; text-transform: uppercase;
            padding: 4px 10px; border-radius: 4px; margin-top: 10px;
        }

        /* ── CARA KERJA — vertical ledger list, not circular numbers ── */
        .how-it-works { background: var(--bark-800); padding: 64px 24px; }
        .how-it-works .section-eyebrow { color: var(--comb-400); }
        .how-it-works .section-title { color: var(--wax-50); }

        .steps {
            display: flex; flex-direction: column;
            max-width: 640px; margin: 0 auto;
            border-top: 1px solid rgba(255,255,255,0.12);
        }
        .step {
            display: flex; align-items: flex-start; gap: 24px;
            padding: 24px 0;
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }
        .step-number {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 15px; color: var(--honey-500);
            flex-shrink: 0; width: 32px; padding-top: 2px;
        }
        .step-title {
            font-family: 'Fraunces', serif;
            font-weight: 600; font-size: 17px; color: var(--wax-50); margin-bottom: 4px;
        }
        .step-desc { font-size: 13px; color: var(--comb-300); line-height: 1.6; }

        /* ── FITUR ── */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1px;
            border: 1px solid var(--line);
            background: var(--line);
        }
        .feature-card {
            background: var(--paper); padding: 28px 24px;
        }
        .feature-icon {
            font-size: 22px; margin-bottom: 14px;
            color: var(--honey-500);
        }
        .feature-title {
            font-family: 'Fraunces', serif;
            font-weight: 600; font-size: 16px; color: var(--bark-800); margin-bottom: 8px;
        }
        .feature-desc { font-size: 13px; color: var(--bark-700); line-height: 1.65; }

        /* ── FOOTER ── */
        .footer {
            background: var(--ink);
            color: var(--comb-300);
            padding: 40px 24px; text-align: center;
        }
        .footer-brand {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            font-family: 'Fraunces', serif; font-weight: 600;
            font-size: 18px; color: var(--wax-50); margin-bottom: 6px;
        }
        .footer-logo {
            width: 32px; height: 32px;
            border-radius: 8px; overflow: hidden;
            flex-shrink: 0;
        }
        .footer-logo img { width: 100%; height: 100%; object-fit: cover; }
        .footer p { font-size: 12px; line-height: 1.7; }
        .footer a { color: var(--honey-500); text-decoration: none; font-weight: 600; }
        .footer a:hover { text-decoration: underline; }

        /* ── MODAL SCANNER ── */
        .modal-overlay {
            position: fixed; inset: 0; z-index: 100;
            display: flex; align-items: flex-end; justify-content: center;
            background: rgba(46, 32, 19, 0);
            backdrop-filter: blur(0px);
            pointer-events: none;
            transition: background 0.4s ease, backdrop-filter 0.4s ease;
            visibility: hidden;
        }
        .modal-overlay.open {
            background: rgba(46, 32, 19, 0.78);
            backdrop-filter: blur(8px);
            pointer-events: all; visibility: visible;
        }
        .modal-sheet {
            width: 100%; max-width: 480px;
            background: var(--paper);
            border-radius: 24px 24px 0 0;
            padding: 0 0 32px;
            transform: translateY(100%);
            transition: transform 0.45s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden; max-height: 92vh;
            display: flex; flex-direction: column;
        }
        @media (min-width: 600px) {
            .modal-overlay { align-items: center; }
            .modal-sheet {
                border-radius: 20px; max-width: 440px;
                transform: scale(0.9) translateY(20px); opacity: 0;
                transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1), opacity 0.3s ease;
            }
            .modal-overlay.open .modal-sheet { transform: scale(1) translateY(0); opacity: 1; }
        }
        .modal-overlay.open .modal-sheet { transform: translateY(0); }

        .modal-handle {
            width: 40px; height: 4px; background: var(--wax-200);
            border-radius: 99px; margin: 12px auto 0; flex-shrink: 0;
        }
        .modal-header {
            padding: 16px 20px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid var(--line); flex-shrink: 0;
        }
        .modal-title {
            font-family: 'Fraunces', serif; font-weight: 600;
            font-size: 18px; color: var(--bark-800);
        }
        .modal-close {
            width: 36px; height: 36px; border-radius: 10px;
            border: 1.5px solid var(--line); background: var(--paper);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--bark-700);
            transition: all 0.2s; font-size: 18px;
        }
        .modal-close:hover {
            background: var(--wax-100); border-color: var(--honey-500); color: var(--bark-800);
        }
        .modal-body { flex: 1; overflow-y: auto; }

        .scanner-container { position: relative; background: #1A130B; overflow: hidden; }
        #qr-reader { width: 100% !important; }
        #qr-reader video { width: 100% !important; object-fit: cover; }
        #qr-reader__scan_region { background: transparent !important; }
        #qr-reader__dashboard { display: none !important; }
        #qr-reader img[alt="Info icon"] { display: none !important; }

        .scan-frame-overlay {
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
            pointer-events: none;
        }
        .scan-frame { width: 220px; height: 220px; position: relative; }
        .corner {
            position: absolute; width: 32px; height: 32px;
            border-color: var(--honey-500); border-style: solid;
        }
        .corner-tl { top:0; left:0; border-width:3px 0 0 3px; }
        .corner-tr { top:0; right:0; border-width:3px 3px 0 0; }
        .corner-br { bottom:0; right:0; border-width:0 3px 3px 0; }
        .corner-bl { bottom:0; left:0; border-width:0 0 3px 3px; }
        .scan-line {
            position: absolute; left: 8px; right: 8px; height: 2px;
            background: linear-gradient(90deg, transparent, var(--honey-500), transparent);
            box-shadow: 0 0 10px rgba(201,145,31,0.8);
            border-radius: 2px;
            animation: scanMove 2.2s ease-in-out infinite;
        }
        @keyframes scanMove {
            0%   { top: 12px; opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { top: 200px; opacity: 0; }
        }
        .scan-vignette {
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at center, transparent 45%, rgba(0,0,0,0.5) 100%);
            pointer-events: none;
        }
        .scanner-hint { padding: 14px 20px; text-align: center; background: var(--paper); }
        .scanner-hint p { font-size: 13px; color: var(--bark-700); }
        .scanner-hint strong { color: var(--bark-800); }
        .status-row {
            display: flex; align-items: center; justify-content: center;
            gap: 8px; margin-top: 8px;
        }
        .status-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--honey-500);
            animation: dotPulse 1.4s ease-in-out infinite;
        }
        @keyframes dotPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(0.7); }
        }
        .status-text {
            font-family: 'Space Mono', monospace;
            font-size: 11px; color: var(--bark-700); font-weight: 700;
            letter-spacing: 0.04em;
        }

        .manual-input-area {
            padding: 16px 20px;
            border-top: 1px solid var(--line);
            background: var(--wax-50);
        }
        .manual-input-label {
            font-size: 12px; color: var(--bark-700);
            font-weight: 600; margin-bottom: 8px; display: block;
        }
        .manual-input-row { display: flex; gap: 8px; }
        .manual-input-row input {
            flex: 1; padding: 10px 14px;
            border: 1.5px solid var(--wax-200);
            border-radius: 10px; font-size: 14px;
            font-family: 'Inter', sans-serif;
            outline: none; transition: border-color 0.2s; background: var(--paper);
        }
        .manual-input-row input:focus { border-color: var(--honey-500); }
        .manual-input-row input::placeholder { color: var(--comb-400); }
        .btn-manual {
            padding: 10px 18px;
            background: var(--ink); color: var(--wax-50);
            border: none; border-radius: 10px;
            font-size: 13px; font-weight: 600;
            cursor: pointer; transition: background 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-manual:hover { background: var(--forest-700); }

        /* ── PROCESSING OVERLAY ── */
        .processing-overlay {
            position: fixed; inset: 0; z-index: 200;
            background: rgba(46,32,19,0.92);
            display: none; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 18px; backdrop-filter: blur(12px);
        }
        .processing-overlay.show { display: flex; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .processing-ring {
            width: 72px; height: 72px;
            border: 3px solid rgba(201,145,31,0.2);
            border-top-color: var(--honey-500);
            border-radius: 50%;
            animation: spin 0.9s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .processing-text { color: var(--wax-50); font-size: 15px; font-weight: 500; }
        .processing-sub {
            color: var(--comb-400); font-size: 12px;
            font-family: 'Space Mono', monospace; letter-spacing: 0.04em;
        }

        /* ── TOAST ── */
        .toast {
            position: fixed; bottom: 32px; left: 50%;
            transform: translateX(-50%) translateY(16px);
            background: var(--ink); color: var(--wax-50);
            padding: 13px 22px; border-radius: 12px;
            font-size: 13px; z-index: 300; max-width: 340px;
            text-align: center; box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            opacity: 0; transition: all 0.3s ease; pointer-events: none;
        }
        .toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

        /* ── FADE UP — used sparingly, hero only ── */
        .fade-up {
            opacity: 0; transform: translateY(20px);
            animation: fadeUp 0.6s ease forwards;
        }
        .fade-up:nth-child(1) { animation-delay: 0.05s; }
        .fade-up:nth-child(2) { animation-delay: 0.12s; }
        .fade-up:nth-child(3) { animation-delay: 0.19s; }
        .fade-up:nth-child(4) { animation-delay: 0.26s; }
        .fade-up:nth-child(5) { animation-delay: 0.33s; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 768px) {
            .hero-content {
                grid-template-columns: 1fr;
                border-radius: 14px 14px 0 0;
            }
            .hero-main { border-right: none; border-bottom: 1px solid var(--line); padding: 32px 24px; }
            .hero-side { padding: 24px; }
            .stat-list { flex-direction: row; }
            .stat-item {
                flex: 1; flex-direction: column; align-items: flex-start;
                border-bottom: none; border-right: 1px solid rgba(255,255,255,0.12);
                padding: 0 12px 0 0;
            }
            .stat-item:first-of-type { border-top: none; }
            .stat-item:last-child { border-right: none; padding-right: 0; }
            .stat-lbl { text-align: left; }
            .trust-bar { flex-direction: row; flex-wrap: wrap; gap: 6px 14px; margin-top: 20px; }
        }

        @media (max-width: 480px) {
            .cta-row { max-width: 100%; flex-direction: column; }
            .cta-code { border-left: none; border-top: 1.5px dashed var(--line); padding: 8px; }
            .kategori-grid { grid-template-columns: repeat(2, 1fr); }
            .features-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body class="honeycomb-bg">

{{-- ================================================================
NAVBAR
================================================================ --}}
<nav class="navbar" id="navbar">
    <a href="{{ route('customer.dashboard') }}" class="nav-brand">
        <div class="nav-logo">
            <img src="{{ asset('images/logo.jpeg') }}" alt="Beezam Logo">
        </div>
        <div>
            <div class="nav-brand-text">Beezam Honey</div>
            <div class="nav-brand-sub">Verifikasi Keaslian</div>
        </div>
    </a>
    <div class="nav-right">
        <a href="{{ route('login') }}" class="nav-admin-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                <polyline points="10 17 15 12 10 7"/>
                <line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
            Login
        </a>
    </div>
</nav>

{{-- ================================================================
PAGE WRAPPER
================================================================ --}}
<div class="page-wrapper">

    {{-- ================================================================
    HERO SECTION
    ================================================================ --}}
    <section class="hero honeycomb-bg">
        <div class="hero-content">

            <div class="hero-main">
                <div class="wax-seal fade-up">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="">
                </div>

                <p class="hero-eyebrow fade-up">Sertifikasi HMAC-SHA256 · Per Botol</p>

                <h1 class="hero-title fade-up">
                    Setiap Botol Punya<br><em>Tanda Tangan</em> Sendiri
                </h1>

                <p class="hero-subtitle fade-up">
                    Madu Beezam dikemas dengan kode QR unik.
                    Pindai kemasan Anda untuk memastikan keasliannya dalam hitungan detik.
                </p>

                <div class="fade-up">
                    <div class="cta-row">
                        <button class="cta-btn" onclick="openScanner()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5">
                                <path d="M3 7V5a2 2 0 012-2h2M17 3h2a2 2 0 012 2v2M21 17v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2"/>
                                <rect x="7" y="7" width="10" height="10" rx="2"/>
                            </svg>
                            Pindai QR Code
                        </button>
                        <div class="cta-code">/verify</div>
                    </div>
                    <p class="cta-hint">ARAHKAN KAMERA KE LABEL BOTOL</p>
                </div>
            </div>

            <div class="hero-side">
                <div class="stat-list fade-up">
                    <div class="stat-item">
                        <span class="stat-lbl">Botol<br>Terdaftar</span>
                        <span class="stat-num">{{ number_format($totalProduk) }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-lbl">Varian<br>Madu</span>
                        <span class="stat-num">{{ $totalKategori }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-lbl">Verifikasi<br>Tercatat</span>
                        <span class="stat-num">{{ number_format($totalScan) }}</span>
                    </div>
                </div>

                <div class="trust-bar fade-up">
                    <div class="trust-item">Hash unik per botol</div>
                    <div class="trust-item">Verifikasi instan</div>
                    <div class="trust-item">Deteksi duplikasi scan</div>
                    <div class="trust-item">Gratis untuk konsumen</div>
                </div>
            </div>

        </div>
    </section>

    {{-- ================================================================
    KATALOG PRODUK
    ================================================================ --}}
    @if($kategoris->count() > 0)
    <section class="section">
        <p class="section-eyebrow">Produk Kami</p>
        <h2 class="section-title">Katalog Madu Beezam</h2>

        <div class="kategori-grid">
            @foreach($kategoris as $kat)
            <div class="kategori-card">
                @if($kat->foto_produk && file_exists(public_path('storage/' . $kat->foto_produk)))
                    <div class="kategori-img">
                        <img src="{{ asset('storage/' . $kat->foto_produk) }}"
                             alt="{{ $kat->nama_kategori }}">
                    </div>
                @else
                    <div class="kategori-img-placeholder">🍯</div>
                @endif
                <div class="kategori-body">
                    <div class="kategori-name">{{ $kat->nama_kategori }}</div>
                    <div class="kategori-count">
                        {{ $kat->products_count ?? 0 }} unit terdaftar
                    </div>
                    @if(isset($kat->deskripsi) && $kat->deskripsi)
                        <div style="font-size:12px; color:var(--bark-700); margin-top:8px; line-height:1.5;">
                            {{ Str::limit($kat->deskripsi, 60) }}
                        </div>
                    @endif
                    <span class="kategori-badge">Asli</span>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ================================================================
    FITUR
    ================================================================ --}}
    <section class="section" style="padding-top: 0;">
        <p class="section-eyebrow">Mengapa Beezam Auth?</p>
        <h2 class="section-title">Perlindungan untuk Setiap Botol Asli</h2>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">⬡</div>
                <h3 class="feature-title">Kunci HMAC-SHA256</h3>
                <p class="feature-desc">Setiap botol dilindungi hash unik yang tidak bisa diduplikasi tanpa kunci rahasia produsen.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⌖</div>
                <h3 class="feature-title">Tanpa Aplikasi Tambahan</h3>
                <p class="feature-desc">Cukup gunakan kamera browser di ponsel Anda — tidak perlu memasang aplikasi apapun.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">◬</div>
                <h3 class="feature-title">Deteksi Pemindaian Berulang</h3>
                <p class="feature-desc">QR Code yang dipindai berulang kali ditandai otomatis — indikasi produk tidak asli sedang beredar.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⬢</div>
                <h3 class="feature-title">Satu Kode, Satu Botol</h3>
                <p class="feature-desc">Tidak ada dua botol Beezam dengan kode QR yang sama — setiap kemasan punya identitas sendiri.</p>
            </div>
        </div>
    </section>

    {{-- ================================================================
    CARA KERJA
    ================================================================ --}}
    <section class="how-it-works">
        <div style="max-width:900px; margin:0 auto; padding:0 24px;">
            <p class="section-eyebrow">Cara Kerja</p>
            <h2 class="section-title">Verifikasi dalam 3 Langkah</h2>

            <div class="steps">
                <div class="step">
                    <div class="step-number">01</div>
                    <div>
                        <h4 class="step-title">Buka kamera</h4>
                        <p class="step-desc">Tekan "Pindai QR Code" dan izinkan akses kamera browser Anda.</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">02</div>
                    <div>
                        <h4 class="step-title">Pindai kode pada kemasan</h4>
                        <p class="step-desc">Arahkan kamera ke QR Code di label botol madu Beezam Anda.</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">03</div>
                    <div>
                        <h4 class="step-title">Lihat status keaslian</h4>
                        <p class="step-desc">Sistem menampilkan hasil verifikasi secara instan dan akurat.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================================================================
    FOOTER
    ================================================================ --}}
    <footer class="footer">
        <div class="footer-brand">
            <div class="footer-logo">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Beezam Logo">
            </div>
            Beezam Honey
        </div>
        <p style="margin-top:6px; opacity:0.75;">
            Madu Hutan Asli — Kuningan, Jawa Barat
        </p>
        <p style="margin-top:10px;">
            © {{ date('Y') }} Beezam Honey ·
            <a href="{{ route('login') }}">Masuk sebagai admin</a>
        </p>
    </footer>

</div>{{-- end page-wrapper --}}

{{-- ================================================================
MODAL SCANNER
================================================================ --}}
<div class="modal-overlay" id="scannerModal" onclick="handleOverlayClick(event)">
    <div class="modal-sheet" id="modalSheet">
        <div class="modal-handle"></div>

        <div class="modal-header">
            <h3 class="modal-title">Pindai QR Code</h3>
            <button class="modal-close" onclick="closeScanner()">✕</button>
        </div>

        <div class="modal-body">
            <div class="scanner-container" id="scannerContainer">
                <div id="qr-reader" style="width:100%;"></div>
                <div class="scan-frame-overlay">
                    <div class="scan-frame">
                        <div class="corner corner-tl"></div>
                        <div class="corner corner-tr"></div>
                        <div class="corner corner-br"></div>
                        <div class="corner corner-bl"></div>
                        <div class="scan-line"></div>
                    </div>
                </div>
                <div class="scan-vignette"></div>
            </div>

            <div class="scanner-hint">
                <p>Arahkan kamera ke <strong>QR Code pada label</strong> madu Beezam</p>
                <div class="status-row">
                    <div class="status-dot"></div>
                    <span class="status-text" id="scannerStatus">MENGINISIALISASI KAMERA...</span>
                </div>
            </div>

            <div class="manual-input-area">
                <span class="manual-input-label">Atau masukkan kode secara manual:</span>
                <div class="manual-input-row">
                    <input type="text" id="manualKode"
                           placeholder="Masukkan kode produk..."
                           onkeydown="if(event.key==='Enter') submitManual()">
                    <button class="btn-manual" onclick="submitManual()">Cek</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Processing Overlay --}}
<div class="processing-overlay" id="processingOverlay">
    <div class="processing-ring"></div>
    <p class="processing-text">Memverifikasi produk...</p>
    <p class="processing-sub">MENGECEK INTEGRITAS HMAC-SHA256</p>
</div>

{{-- Toast --}}
<div class="toast" id="toast"></div>

{{-- ================================================================
JAVASCRIPT
================================================================ --}}
<script>
    /* ── NAVBAR SCROLL ── */
    window.addEventListener('scroll', () => {
        document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 20);
    });

    /* ── STATE ── */
    let html5QrCode = null;
    let isScanning  = false;

    /* ── BUKA MODAL ── */
    function openScanner() {
        document.getElementById('scannerModal').classList.add('open');
        document.body.style.overflow = 'hidden';
        setTimeout(startCamera, 500);
    }

    /* ── TUTUP MODAL ── */
    function closeScanner() {
        stopCamera();
        document.getElementById('scannerModal').classList.remove('open');
        document.body.style.overflow = '';
        document.getElementById('scannerStatus').textContent = 'MENGINISIALISASI KAMERA...';
        document.getElementById('manualKode').value = '';
    }

    function handleOverlayClick(event) {
        if (event.target === document.getElementById('scannerModal')) closeScanner();
    }

    /* ── START KAMERA ── */
    function startCamera() {
        const statusEl = document.getElementById('scannerStatus');

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            statusEl.textContent = 'BROWSER TIDAK MENDUKUNG KAMERA';
            return;
        }

        html5QrCode = new Html5Qrcode("qr-reader");

        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 12, qrbox: { width: 220, height: 220 }, aspectRatio: 1.0 },
            onQrSuccess,
            () => {}
        ).then(() => {
            isScanning = true;
            statusEl.textContent = 'KAMERA AKTIF — ARAHKAN KE QR CODE';
        }).catch(err => {
            statusEl.textContent = 'TIDAK BISA AKSES KAMERA';
            console.error(err);
        });
    }

    /* ── STOP KAMERA ── */
    function stopCamera() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().catch(() => {});
            isScanning = false;
        }
    }

    /* ── QR BERHASIL TERBACA ── */
    function onQrSuccess(decodedText) {
        stopCamera();
        document.getElementById('processingOverlay').classList.add('show');

        try {
            const url = new URL(decodedText);

            // Format QR: /verify/{kode_produk}
            const match = url.pathname.match(/\/verify\/(.+)/);

            if (match && match[1]) {
                // QR valid — langsung redirect ke URL dalam QR
                setTimeout(() => {
                    window.location.href = decodedText;
                }, 1200);
                return;
            }

            throw new Error('Bukan QR Beezam');

        } catch (e) {
            document.getElementById('processingOverlay').classList.remove('show');
            showToast('QR Code ini bukan milik produk Beezam');
            setTimeout(() => {
                if (document.getElementById('scannerModal').classList.contains('open')) {
                    startCamera();
                }
            }, 2000);
        }
    }

    /* ── INPUT MANUAL ── */
    function submitManual() {
        const kode = document.getElementById('manualKode').value.trim();
        if (!kode) { showToast('Masukkan kode produk terlebih dahulu'); return; }

        stopCamera();
        document.getElementById('processingOverlay').classList.add('show');

        setTimeout(() => {
            window.location.href = '/verify/manual?kode=' + encodeURIComponent(kode);
        }, 800);
    }

    /* ── TOAST ── */
    function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3500);
    }

    /* ── ESC KEY ── */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeScanner();
    });
</script>

</body>
</html>