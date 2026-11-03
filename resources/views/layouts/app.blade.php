<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Autentikasi Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:      #EF9F27;
            --primary-dark: #BA7517;
            --primary-light:#FAC775;
            --brown-deep:   #1e0e02;
            --brown-dark:   #412402;
            --brown-mid:    #6b3a08;
            --brown-side:   #854F0B;
            --cream:        #fffdf8;
            --cream-border: rgba(239,159,39,0.2);
            --text-dark:    #2d1a06;
            --text-mid:     #7c5a2a;
            --text-light:   #b8956a;
            --sidebar-width: 250px;
        }

        body {
            background:
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l30 17.3v34.6L30 69.3 0 51.9V17.3L30 0zm0 6L6 19.5v27L30 60l24-13.5v-27L30 6z' fill='%23EF9F27' fill-opacity='0.06'/%3E%3C/svg%3E"),
                linear-gradient(160deg, #1e0e02 0%, #412402 45%, #6b3a08 100%);
            background-attachment: fixed;
            font-family: 'Nunito', sans-serif;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, #412402 0%, #1e0e02 100%);
            border-right: 1px solid rgba(239,159,39,0.15);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            box-shadow: 2px 0 16px rgba(0,0,0,0.4);
            transition: left 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 20px 20px 15px;
            border-bottom: 1px solid rgba(239,159,39,0.2);
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 0l20 11.5v23L20 46 0 34.5v-23L20 0zm0 4L4 13.5v19L20 42l16-9.5v-19L20 4z' fill='%23ffffff' fill-opacity='0.03'/%3E%3C/svg%3E");
        }

        .sidebar-brand h5 {
            color: #FAC775;
            font-family: 'Cinzel', serif;
            font-weight: 700;
            font-size: 13px;
            margin: 0;
            line-height: 1.4;
            letter-spacing: 1px;
        }

        .sidebar-brand small {
            color: rgba(250,199,117,0.55);
            font-size: 10.5px;
        }

        .sidebar-nav {
            padding: 15px 0;
        }

        .sidebar-nav .nav-label {
            color: rgba(250,199,117,0.4);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 10px 20px 5px;
        }

        .sidebar-nav .nav-link {
            color: rgba(250,199,117,0.75);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background: rgba(239,159,39,0.12);
            color: #FAC775;
            border-left-color: #EF9F27;
        }

        .sidebar-nav .nav-link i {
            width: 18px;
            text-align: center;
            font-size: 16px;
            color: #c49a47;
        }

        .sidebar-nav .nav-link:hover i,
        .sidebar-nav .nav-link.active i {
            color: #EF9F27;
        }

        /* ── Main wrapper ── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ── */
        .topbar {
            background: rgba(30,14,2,0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(239,159,39,0.2);
            padding: 12px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 12px rgba(0,0,0,0.3);
            position: sticky;
            top: 0;
            z-index: 99;
            flex-shrink: 0;
            gap: 10px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 4px;
            min-width: 0;
        }

        .topbar-title {
            font-family: 'Cinzel', serif;
            font-weight: 600;
            color: #FAC775;
            font-size: 15px;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .topbar-user .avatar {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, #FAC775, #EF9F27);
            color: #2d1a06;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
            border: 2px solid rgba(239,159,39,0.4);
            box-shadow: 0 0 0 3px rgba(239,159,39,0.1);
        }

        .topbar-user .user-name > div:first-child {
            font-size: 13px;
            font-weight: 600;
            color: #FAC775;
        }

        .topbar-user .user-name > div:last-child {
            font-size: 11px;
            color: rgba(250,199,117,0.5);
        }

        /* ── Hamburger button (mobile only) ── */
        #sidebarToggleBtn {
            display: none;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            font-size: 22px;
            color: #FAC775;
            padding: 4px 6px;
            cursor: pointer;
            border-radius: 8px;
            line-height: 1;
            flex-shrink: 0;
        }
        #sidebarToggleBtn:hover { background: rgba(239,159,39,0.1); }

        /* ── Sidebar backdrop (mobile) ── */
        #sidebarBackdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 1040;
        }
        #sidebarBackdrop.show { display: block; }

        /* ── Bottom Navigation (mobile only) ── */
        #bottomNav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            z-index: 1030;
            background: #1e0e02;
            border-top: 1px solid rgba(239,159,39,0.25);
            padding: 6px 0 max(10px, env(safe-area-inset-bottom));
            box-shadow: 0 -4px 16px rgba(0,0,0,0.3);
        }

        .bnav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            text-decoration: none;
            color: rgba(250,199,117,0.4);
            font-size: 10px;
            padding: 2px 4px;
            transition: color 0.2s;
        }
        .bnav-item.active, .bnav-item:hover { color: #EF9F27; }
        .bnav-item i { font-size: 21px; }

        /* ── Content ── */
        .content-area {
            padding: 25px;
            flex: 1;
        }

        /* ── Cards ── */
        .card {
            border: 1px solid var(--cream-border) !important;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            background: var(--cream) !important;
        }

        .card-header {
            background: linear-gradient(160deg, #412402 0%, #854F0B 100%) !important;
            border-bottom: 1px solid rgba(239,159,39,0.25) !important;
            border-radius: 14px 14px 0 0 !important;
            padding: 14px 20px;
            font-weight: 600;
            color: #FAC775 !important;
        }

        .stat-card {
            border-radius: 14px;
            padding: 20px;
            color: #fff;
            position: relative;
            overflow: hidden;
            border: none !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25) !important;
        }

        .stat-card .stat-icon {
            font-size: 40px;
            opacity: 0.2;
            position: absolute;
            right: 15px;
            top: 15px;
        }

        .stat-card .stat-number {
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
        }

        .stat-card .stat-label {
            font-size: 13px;
            opacity: 0.85;
            margin-top: 5px;
        }

        .badge-asli         { background-color: #28a745; color: #fff; }
        .badge-mencurigakan { background-color: #ffc107; color: #333; }
        .badge-tidak        { background-color: #dc3545; color: #fff; }

        .table thead th {
            background: rgba(239,159,39,0.08) !important;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #7c5a2a !important;
            border-bottom: 1px solid rgba(239,159,39,0.2) !important;
        }

        .table td {
            font-size: 14px;
            vertical-align: middle;
            border-color: rgba(239,159,39,0.1) !important;
            color: #2d1a06;
        }

        .table tbody tr:hover td {
            background: rgba(239,159,39,0.04) !important;
        }

        /* ── Buttons ── */
        .btn-primary {
            background: linear-gradient(135deg, #FAC775, #EF9F27) !important;
            border-color: #EF9F27 !important;
            color: #2d1a06 !important;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #EF9F27, #BA7517) !important;
            border-color: #BA7517 !important;
            color: #2d1a06 !important;
            box-shadow: 0 4px 14px rgba(186,117,23,0.4) !important;
        }
        .btn-outline-primary {
            border-color: rgba(239,159,39,0.5) !important;
            color: #EF9F27 !important;
        }
        .btn-outline-primary:hover {
            background: rgba(239,159,39,0.12) !important;
            border-color: #EF9F27 !important;
            color: #EF9F27 !important;
        }

        /* ── Alerts ── */
        .alert-success {
            background: rgba(40,167,69,0.1) !important;
            border-color: rgba(40,167,69,0.4) !important;
            color: #1e7e34 !important;
        }
        .alert-danger {
            background: rgba(220,53,69,0.1) !important;
            border-color: rgba(220,53,69,0.4) !important;
            color: #a71d2a !important;
        }

        /* ══════════════════════════════════════
           RESPONSIVE — Mobile (<992px)
        ══════════════════════════════════════ */
        @media (max-width: 991.98px) {
            .sidebar {
                left: -280px;
                width: 260px !important;
                height: 100vh !important;
                box-shadow: 4px 0 24px rgba(0,0,0,0.5);
            }
            .sidebar.show { left: 0; }

            .main-wrapper {
                margin-left: 0 !important;
                width: 100%;
            }

            .content-area {
                padding: 16px;
                padding-bottom: 80px;
            }

            .topbar { padding: 10px 16px; }

            #sidebarToggleBtn { display: flex !important; }
            #bottomNav        { display: flex !important; }

            .topbar-user .user-name { display: none; }

            .stat-card {
                padding: 14px 16px !important;
                border-radius: 12px !important;
            }
            .stat-card .stat-number { font-size: 26px !important; }
            .stat-card .stat-icon   { font-size: 28px !important; }
            .stat-label             { font-size: 11px !important; }
        }

        @media (max-width: 575.98px) {
            .topbar-title { font-size: 13px; }
            .card-header  { font-size: 13px !important; padding: 12px 16px !important; }
            .table        { font-size: 12px !important; }
            .table th, .table td { padding: 7px 10px !important; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── Sidebar backdrop ── --}}
<div id="sidebarBackdrop"></div>

{{-- ── Sidebar ── --}}
<div class="sidebar">
    <div class="sidebar-brand">
        <h5><i class="bi bi-shield-check me-2" style="color:#EF9F27;"></i>Beezam Honey</h5>
        <small>Autentikasi Produk Beezam Honey</small>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>

        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="{{ route('categories.index') }}"
           class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Kategori Produk
        </a>

        <a href="{{ route('products.index') }}"
           class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Unit Produk
        </a>

        <a href="{{ route('scanlogs.index') }}"
           class="nav-link {{ request()->routeIs('scanlogs.*') ? 'active' : '' }}">
            <i class="bi bi-qr-code-scan"></i> Log Scan QR
        </a>

        <div class="nav-label mt-2">Label</div>

        <a href="{{ route('labels.index') }}"
           class="nav-link {{ request()->routeIs('labels.*') ? 'active' : '' }}">
            <i class="bi bi-printer"></i> Template Label
        </a>

        <div class="nav-label mt-2">Lainnya</div>

        {{-- Menu "Halaman Verifikasi" disembunyikan dari navbar admin.
             Hapus tanda komentar ini untuk menampilkannya lagi:
        <a href="{{ route('verify.index') }}" target="_blank" class="nav-link">
            <i class="bi bi-patch-check"></i> Halaman Verifikasi
        </a>
        --}}

        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit"
                    class="nav-link border-0 bg-transparent w-100 text-start"
                    style="color:rgba(250,199,117,0.75);">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </nav>
</div>

{{-- ── Main wrapper ── --}}
<div class="main-wrapper">

    {{-- Topbar --}}
    <div class="topbar">
        <div class="topbar-left">
            <button id="sidebarToggleBtn" type="button" aria-label="Buka menu">
                <i class="bi bi-list"></i>
            </button>
            <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        </div>
        <div class="topbar-user">
            <div class="avatar">
                {{ strtoupper(substr($currentUser->name ?? 'A', 0, 1)) }}
            </div>
            <div class="user-name">
                <div>{{ $currentUser->name ?? 'Admin' }}</div>
                <div>Administrator</div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="content-area">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

{{-- ── Bottom Navigation (mobile only) ── --}}
<nav id="bottomNav" style="display:none;">
    <a href="{{ route('dashboard') }}"
       class="bnav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i><span>Dashboard</span>
    </a>
    <a href="{{ route('categories.index') }}"
       class="bnav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
        <i class="bi bi-tags"></i><span>Kategori</span>
    </a>
    <a href="{{ route('products.index') }}"
       class="bnav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
        <i class="bi bi-box-seam"></i><span>Produk</span>
    </a>
    <a href="{{ route('scanlogs.index') }}"
       class="bnav-item {{ request()->routeIs('scanlogs.*') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i><span>Log Scan</span>
    </a>
    <a href="{{ route('labels.index') }}"
       class="bnav-item {{ request()->routeIs('labels.*') ? 'active' : '' }}">
        <i class="bi bi-printer"></i><span>Label</span>
    </a>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const sidebar   = document.querySelector('.sidebar');
    const backdrop  = document.getElementById('sidebarBackdrop');
    const toggleBtn = document.getElementById('sidebarToggleBtn');

    function openSidebar() {
        sidebar.classList.add('show');
        backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('show');
        backdrop.classList.remove('show');
        document.body.style.overflow = '';
    }

    toggleBtn.addEventListener('click', openSidebar);
    backdrop.addEventListener('click', closeSidebar);

    sidebar.querySelectorAll('a, button[type="submit"]').forEach(el => {
        el.addEventListener('click', () => {
            if (window.innerWidth < 992) closeSidebar();
        });
    });
</script>

@stack('scripts')
</body>
</html>