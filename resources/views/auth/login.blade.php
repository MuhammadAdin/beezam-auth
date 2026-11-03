@extends('layouts.guest')

@section('title', 'Login Admin - Beezam Honey')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Nunito:wght@400;500;600;700&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    /* ============================================================
       LATAR BELAKANG — gradien coklat tua madu + pola hexagon
    ============================================================ */
    body {
        min-height: 100vh;
        background:
            url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l30 17.3v34.6L30 69.3 0 51.9V17.3L30 0zm0 6L6 19.5v27L30 60l24-13.5v-27L30 6z' fill='%23EF9F27' fill-opacity='0.06'/%3E%3C/svg%3E"),
            linear-gradient(160deg, #1e0e02 0%, #412402 45%, #6b3a08 100%);
        font-family: 'Nunito', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        overflow-x: hidden;
    }

    /* ============================================================
       KARTU UTAMA
    ============================================================ */
    .login-card {
        background: #fffdf8;
        width: 100%;
        max-width: 420px;
        border-radius: 24px;
        border-top: 5px solid #EF9F27;
        box-shadow:
            0 32px 64px rgba(0,0,0,0.45),
            0 0 0 1px rgba(239,159,39,0.2);
        padding: 0;
        overflow: hidden;
        animation: cardIn 0.7s cubic-bezier(.22,1,.36,1) both;
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(28px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0)   scale(1); }
    }

    /* Tetesan madu di atas kartu */
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

    /* ============================================================
       HEADER — logo & nama brand
    ============================================================ */
    .card-header {
        background: linear-gradient(160deg, #412402 0%, #854F0B 100%);
        padding: 28px 32px 24px;
        text-align: center;
        position: relative;
    }
    .card-header::after {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 0l20 11.5v23L20 46 0 34.5v-23L20 0zm0 4L4 13.5v19L20 42l16-9.5v-19L20 4z' fill='%23ffffff' fill-opacity='0.04'/%3E%3C/svg%3E");
        pointer-events: none;
    }

    .inst-row {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }
    .inst-badge {
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(239,159,39,0.35);
        border-radius: 20px;
        padding: 5px 14px;
        font-size: 11.5px;
        font-weight: 600;
        color: #FAC775;
        letter-spacing: 0.4px;
    }
    .inst-badge img {
        height: 20px;
        width: auto;
        filter: drop-shadow(0 1px 2px rgba(0,0,0,0.4));
    }

    .logo-wrap {
        position: relative;
        z-index: 1;
        margin-bottom: 14px;
    }
    .logo-wrap img {
        width: 82px;
        height: 82px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(239,159,39,0.6);
        box-shadow: 0 0 0 6px rgba(239,159,39,0.12), 0 8px 24px rgba(0,0,0,0.35);
        animation: float 4s ease-in-out infinite;
    }
    /* Fallback jika gambar tidak tersedia */
    .logo-wrap .logo-fallback {
        width: 82px;
        height: 82px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FAC775, #EF9F27);
        border: 3px solid rgba(239,159,39,0.6);
        box-shadow: 0 0 0 6px rgba(239,159,39,0.12), 0 8px 24px rgba(0,0,0,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        animation: float 4s ease-in-out infinite;
        margin: 0 auto;
    }
    @keyframes float {
        0%,100% { transform: translateY(0); }
        50%      { transform: translateY(-7px); }
    }

    .brand-title {
        font-family: 'Cinzel', serif;
        font-size: 22px;
        font-weight: 700;
        color: #FAC775;
        letter-spacing: 2px;
        position: relative;
        z-index: 1;
        margin-bottom: 4px;
    }
    .brand-sub {
        font-size: 11.5px;
        color: rgba(250,199,117,0.65);
        font-weight: 500;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 1;
    }

    /* ============================================================
       BODY FORM
    ============================================================ */
    .card-body {
        padding: 28px 32px 32px;
    }

    /* Garis emas pemisah */
    .gold-divider {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 24px;
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

    /* ============================================================
       ALERT
    ============================================================ */
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
        margin-bottom: 20px;
        animation: slideIn 0.3s ease;
    }
    .alert-success {
        background: #f0faf5;
        border-color: #6ee7b7;
        border-left-color: #10b981;
        color: #065f46;
    }
    .alert-honey i { font-size: 17px; flex-shrink: 0; margin-top: 1px; }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ============================================================
       FORM ELEMENTS
    ============================================================ */
    .form-group { margin-bottom: 18px; }

    .form-label {
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
    .input-shell.is-invalid {
        border-color: #e24b4a;
        box-shadow: 0 0 0 4px rgba(226,75,74,0.1);
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
        font-size: 14px;
        font-family: 'Nunito', sans-serif;
        color: #2d1a06;
        font-weight: 500;
    }
    .input-shell input::placeholder { color: #c4a87a; font-weight: 400; }

    .eye-toggle {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        font-size: 17px;
        color: #c4a87a;
        display: flex;
        align-items: center;
        transition: color 0.2s;
    }
    .eye-toggle:hover { color: #EF9F27; }

    /* Error feedback */
    .invalid-feedback {
        display: block;
        font-size: 12px;
        color: #c0392b;
        margin-top: 6px;
        font-weight: 500;
    }

    /* ============================================================
       TOMBOL LOGIN
    ============================================================ */
    .btn-login {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px;
        margin-top: 8px;
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
    }
    .btn-login:hover {
        background: linear-gradient(135deg, #EF9F27 0%, #BA7517 100%);
        box-shadow: 0 6px 22px rgba(186,117,23,0.55);
        transform: translateY(-2px);
    }
    .btn-login:active { transform: translateY(0); box-shadow: 0 2px 8px rgba(186,117,23,0.3); }
    .btn-login i { font-size: 18px; }

    /* ============================================================
       FOOTER
    ============================================================ */
    .card-footer-text {
        text-align: center;
        font-size: 11px;
        color: #b8956a;
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid #f0e0c0;
        letter-spacing: 0.3px;
    }
    .card-footer-text span { color: #EF9F27; font-weight: 700; }

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

<div class="login-card">

    {{-- Tetesan Madu --}}
    <div class="drip-row">
        <div class="drip"></div>
        <div class="drip"></div>
        <div class="drip"></div>
        <div class="drip"></div>
        <div class="drip"></div>
    </div>

    {{-- Header --}}
    <div class="card-header">

        {{-- Logo Instansi --}}
        <div class="inst-row">
            <div class="inst-badge">
                <img src="{{ asset('images/11zon_resized.png') }}" alt="Logo Uniku">
            
            </div>
            <div class="inst-badge">
                <img src="{{ asset('images/ic_collections_white_18dp.png') }}" alt="Logo FKOM">
                
            </div>
        </div>

        {{-- Logo Utama --}}
        <div class="logo-wrap">
            <img src="{{ asset('images/logo.jpeg') }}" alt="Logo Beezam Honey"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="logo-fallback" style="display:none;">🍯</div>
        </div>

        <h1 class="brand-title">Beezam Honey</h1>
        <p class="brand-sub">Sistem Autentikasi Keaslian Produk</p>

    </div>

    {{-- Body Form --}}
    <div class="card-body">

        <div class="gold-divider"><span>Login Admin</span></div>

        {{-- Alert Error --}}
        @if(session('error'))
            <div class="alert-honey">
                <i class="bi bi-exclamation-octagon-fill"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="alert-honey alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('login.post') }}" method="POST" novalidate>
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label" for="email">Email Admin</label>
                <div class="input-shell @error('email') is-invalid @enderror">
                    <i class="bi bi-envelope shell-icon"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Masukkan email terdaftar..."
                        value="{{ old('email') }}"
                        autocomplete="email"
                        autofocus
                    >
                </div>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label" for="passwordInput">Password</label>
                <div class="input-shell @error('password') is-invalid @enderror">
                    <i class="bi bi-lock shell-icon"></i>
                    <input
                        type="password"
                        id="passwordInput"
                        name="password"
                        placeholder="Masukkan password Anda..."
                        autocomplete="current-password"
                    >
                    <button type="button" class="eye-toggle" onclick="togglePassword()" aria-label="Tampilkan/sembunyikan password">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tombol --}}
            <button type="submit" class="btn-login">
                Masuk
                <i class="bi bi-arrow-right-circle"></i>
            </button>

        </form>

        {{-- Tombol kembali ke halaman konsumen --}}
        <a href="{{ route('customer.dashboard') }}" class="btn-kembali">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <p class="card-footer-text">
            &copy; {{ date('Y') }} <span>Beezam Honey</span> &mdash; Universitas Kuningan
        </p>

    </div>
</div>

@endsection

@section('scripts')
<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('eyeIcon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
            icon.closest('.eye-toggle').style.color = '#EF9F27';
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
            icon.closest('.eye-toggle').style.color = '';
        }
    }
</script>
@endsection