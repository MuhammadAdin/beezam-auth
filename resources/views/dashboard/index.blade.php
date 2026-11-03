@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
/* ═══════════════════════════════════════════════
   MOBILE RESPONSIVE — Beezam Auth Dashboard
   ═══════════════════════════════════════════════ */

/* ── Tema Madu — selaraskan dengan halaman login ── */
body {
    background:
        url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l30 17.3v34.6L30 69.3 0 51.9V17.3L30 0zm0 6L6 19.5v27L30 60l24-13.5v-27L30 6z' fill='%23EF9F27' fill-opacity='0.06'/%3E%3C/svg%3E"),
        linear-gradient(160deg, #1e0e02 0%, #412402 45%, #6b3a08 100%) !important;
    background-attachment: fixed !important;
}

/* ── Sidebar overlay di mobile ── */
@media (max-width: 991.98px) {
    .sidebar {
        position: fixed !important;
        top: 0;
        left: -280px;
        width: 260px !important;
        height: 100vh !important;
        z-index: 1050;
        transition: left 0.3s ease;
        overflow-y: auto;
        box-shadow: 4px 0 20px rgba(0,0,0,0.25);
    }
    .sidebar.show {
        left: 0;
    }
    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
        padding-bottom: 80px;
    }
    #sidebarToggleBtn { display: flex !important; }
    #bottomNav        { display: flex !important; }
}

@media (min-width: 992px) {
    #sidebarToggleBtn { display: none !important; }
    #bottomNav        { display: none !important; }
}

/* ── Backdrop ── */
#sidebarBackdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1040;
}
#sidebarBackdrop.show { display: block; }

/* ── Hamburger button ── */
#sidebarToggleBtn {
    display: none;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    font-size: 22px;
    color: inherit;
    padding: 4px 8px;
    cursor: pointer;
    border-radius: 8px;
    margin-right: 4px;
    line-height: 1;
}
#sidebarToggleBtn:hover { background: rgba(0,0,0,0.07); }

/* ── Bottom Navigation ── */
#bottomNav {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    z-index: 1030;
    background: #fffdf8;
    border-top: 2px solid rgba(239,159,39,0.3);
    padding: 6px 0 max(10px, env(safe-area-inset-bottom));
    box-shadow: 0 -4px 16px rgba(0,0,0,0.12);
    display: none;
}
.bnav-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    text-decoration: none;
    color: #c4a87a;
    font-size: 10px;
    padding: 2px 4px;
    transition: color 0.2s;
}
.bnav-item.active, .bnav-item:hover { color: #EF9F27; }
.bnav-item i { font-size: 21px; }

/* ── Stat cards — 2 kolom di xs ── */
@media (max-width: 575.98px) {
    .stat-card {
        padding: 14px 16px !important;
        border-radius: 12px !important;
    }
    .stat-card .stat-number { font-size: 26px !important; }
    .stat-card .stat-icon   { font-size: 26px !important; }
    .stat-label             { font-size: 11px !important; }
    .card-header            { font-size: 13px !important; }
    .table                  { font-size: 12px !important; }
    .table th, .table td    { padding: 7px 10px !important; }
    .dash-greeting h5       { font-size: 16px; }
    .alert-mencurigakan     { flex-direction: column !important; gap: 8px !important; }
}

/* ── Grafik: horizontal scroll di mobile, side-by-side di desktop ── */
.grafik-scroll-wrap {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    padding-bottom: 4px;
}
.grafik-scroll-wrap::-webkit-scrollbar { display: none; }
.grafik-card-wrap {
    flex: 0 0 82vw;
    scroll-snap-align: start;
    min-width: 260px;
}
.grafik-card-wrap.wide { flex: 0 0 85vw; }

@media (min-width: 992px) {
    .grafik-scroll-wrap {
        overflow-x: visible;
        flex-wrap: nowrap;
    }
    .grafik-card-wrap      { flex: 1; }
    .grafik-card-wrap.wide { flex: 0 0 33%; }
}

/* ── Card override — tema madu ── */
.card {
    background: #fffdf8 !important;
    border: 1px solid rgba(239,159,39,0.2) !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
}
.card-header {
    background: linear-gradient(160deg, #412402 0%, #854F0B 100%) !important;
    color: #FAC775 !important;
    border-bottom: 1px solid rgba(239,159,39,0.3) !important;
    font-weight: 600;
}
.card-header .text-primary { color: #FAC775 !important; }
.card-header a.btn-primary {
    background: linear-gradient(135deg, #FAC775, #EF9F27) !important;
    border-color: #EF9F27 !important;
    color: #2d1a06 !important;
    font-weight: 600;
}
.card-header a.btn-outline-primary {
    border-color: rgba(250,199,117,0.6) !important;
    color: #FAC775 !important;
}
.card-header a.btn-outline-primary:hover {
    background: rgba(239,159,39,0.2) !important;
}

/* ── Greeting card ── */
.dash-greeting h5 { color: #FAC775; }
.dash-greeting .text-muted { color: rgba(250,199,117,0.65) !important; }

/* ── Tabel ── */
.table thead th {
    background: rgba(239,159,39,0.08) !important;
    color: #7c5a2a !important;
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid rgba(239,159,39,0.2) !important;
}
.table tbody tr:hover { background: rgba(239,159,39,0.04) !important; }
.table td, .table th { border-color: rgba(239,159,39,0.1) !important; }

/* ── Info sistem link ── */
.info-link { font-weight: 700; color: #EF9F27 !important; text-decoration: none; font-size: 13px; }
.info-val  { font-weight: 700; color: #EF9F27 !important; font-size: 13px; }
.info-lbl  { font-size: 11px; color: #b8956a; }

/* ── Alert mencurigakan ── */
.alert-mencurigakan-wrap {
    background: rgba(255,248,225,0.95) !important;
    border-left: 4px solid #EF9F27 !important;
    border: 1px solid rgba(239,159,39,0.4);
    border-radius: 10px;
}
</style>
@endpush

@section('content')

{{-- ── Backdrop ── --}}
<div id="sidebarBackdrop"></div>

{{-- ── Sambutan ── --}}
<div class="mb-4 dash-greeting">
    <h5 class="fw-bold mb-1">
        Selamat datang, {{ $currentUser->name ?? 'Admin' }}! 👋
    </h5>
    <p class="text-muted mb-0" style="font-size:13px;">
        <i class="bi bi-clock me-1"></i>
        {{ now()->locale('id')->isoFormat('dddd, D MMMM Y — HH:mm') }}
    </p>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#EF9F27,#BA7517);">
            <i class="bi bi-box-seam stat-icon"></i>
            <div class="stat-number">{{ $totalProduk }}</div>
            <div class="stat-label">Total Unit Produk</div>
            <div class="mt-2">
                <a href="{{ route('products.index') }}" style="color:rgba(45,26,6,0.75);font-size:12px;font-weight:600;">
                    Kelola produk <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#854F0B,#412402);">
            <i class="bi bi-qr-code-scan stat-icon"></i>
            <div class="stat-number">{{ $totalScan }}</div>
            <div class="stat-label">Total Scan QR</div>
            <div class="mt-2">
                <a href="{{ route('scanlogs.index') }}" style="color:rgba(250,199,117,0.8);font-size:12px;">
                    Lihat log <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#FAC775,#EF9F27);">
            <i class="bi bi-patch-check stat-icon"></i>
            <div class="stat-number" style="color:#2d1a06;">{{ $produkAsli }}</div>
            <div class="stat-label" style="color:rgba(45,26,6,0.75);">Produk Asli</div>
            <div class="mt-2" style="color:rgba(45,26,6,0.6);font-size:12px;font-weight:600;">
                <i class="bi bi-shield-check"></i> Terverifikasi aman
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#c0392b,#922b21);">
            <i class="bi bi-exclamation-triangle stat-icon"></i>
            <div class="stat-number">{{ $produkMencurigakan }}</div>
            <div class="stat-label">Mencurigakan</div>
            <div class="mt-2">
                <a href="{{ route('scanlogs.index') }}?status=MENCURIGAKAN"
                   style="color:rgba(255,255,255,0.8);font-size:12px;">
                    Investigasi <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ===== ALERT MENCURIGAKAN ===== --}}
@if($produkMencurigakan > 0)
<div class="alert mb-4 d-flex align-items-start gap-3 alert-mencurigakan alert-mencurigakan-wrap">
    <i class="bi bi-exclamation-triangle-fill fs-4 mt-1 flex-shrink-0" style="color:#EF9F27;"></i>
    <div>
        <div class="fw-bold" style="color:#856404;">
            ⚠️ Peringatan: {{ $produkMencurigakan }} Produk Mencurigakan
        </div>
        <div style="font-size:13px;color:#856404;margin-top:3px;">
            Terdapat produk dengan jumlah scan melebihi batas normal (>3x). Segera lakukan investigasi.
        </div>
        <a href="{{ route('scanlogs.index') }}?status=MENCURIGAKAN"
           class="btn btn-sm mt-2"
           style="background:linear-gradient(135deg,#FAC775,#EF9F27);color:#2d1a06;font-weight:600;border:none;">
            <i class="bi bi-search me-1"></i>Periksa Sekarang
        </a>
    </div>
</div>
@endif

{{-- ===== TABEL PRODUK + SCAN ===== --}}
<div class="row g-3">
    <div class="col-12 col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-box-seam me-2"></i>Unit Produk Terbaru</span>
                <a href="{{ route('categories.index') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-tags"></i><span class="d-none d-sm-inline"> Kategori</span>
                </a>
            </div>
            <div class="card-body p-0">
                @if($produkTerbaru->isEmpty())
                    <div class="text-center py-5" style="color:#b8956a;">
                        <i class="bi bi-inbox" style="font-size:36px;color:#e8d5b2;"></i>
                        <p class="mt-2 mb-2">Belum ada produk</p>
                        <a href="{{ route('categories.index') }}" class="btn btn-sm"
                           style="background:linear-gradient(135deg,#FAC775,#EF9F27);color:#2d1a06;font-weight:600;border:none;">
                            Buat Kategori & Generate Unit
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>Produk</th><th>Scan</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($produkTerbaru as $produk)
                                <tr style="cursor:pointer;"
                                    onclick="window.location='{{ route('products.show', $produk->id) }}'">
                                    <td>
                                        <div class="fw-semibold" style="font-size:13px;color:#2d1a06;">
                                            {{ Str::limit($produk->category->nama_kategori ?? '-', 18) }}
                                        </div>
                                        <div style="font-size:11px;color:#b8956a;">
                                            {{ $produk->category->batch_produk ?? '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge"
                                              style="background:rgba(239,159,39,0.12);color:#7c5a2a;border:1px solid rgba(239,159,39,0.3);">
                                            {{ $produk->total_scan }}x
                                        </span>
                                    </td>
                                    <td>
                                        @if($produk->status_produk == 'ASLI')
                                            <span class="badge badge-asli rounded-pill px-2">✅ Asli</span>
                                        @elseif($produk->status_produk == 'MENCURIGAKAN')
                                            <span class="badge badge-mencurigakan rounded-pill px-2">⚠️</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-2">— Baru</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-2 text-end border-top" style="border-color:rgba(239,159,39,0.15) !important;">
                        <a href="{{ route('products.index') }}" style="font-size:13px;color:#EF9F27;text-decoration:none;">
                            Lihat semua <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Riwayat Scan Terbaru</span>
                <a href="{{ route('scanlogs.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($riwayatScan->isEmpty())
                    <div class="text-center py-5" style="color:#b8956a;">
                        <i class="bi bi-qr-code" style="font-size:36px;color:#e8d5b2;"></i>
                        <p class="mt-2 mb-0">Belum ada aktivitas scan</p>
                        <small style="color:#c4a87a;">Data muncul otomatis saat konsumen scan QR</small>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="d-none d-sm-table-cell">IP</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatScan as $log)
                                <tr>
                                    <td>
                                        <div class="fw-semibold" style="font-size:13px;color:#2d1a06;">
                                            {{ Str::limit($log->product->category->nama_kategori ?? 'Dihapus', 16) }}
                                        </div>
                                        <div style="font-size:11px;color:#b8956a;">
                                            {{ $log->product->kode_produk ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="d-none d-sm-table-cell">
                                        <code style="font-size:11px;color:#7c5a2a;">{{ $log->ip_address }}</code>
                                    </td>
                                    <td style="font-size:11px;color:#7c5a2a;">
                                        {{ \Carbon\Carbon::parse($log->scanned_at)->format('d/m H:i') }}
                                        <div style="font-size:10px;color:#c4a87a;">
                                            {{ \Carbon\Carbon::parse($log->scanned_at)->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($log->product)
                                            @if($log->product->status_produk == 'ASLI')
                                                <span class="badge badge-asli rounded-pill px-2">✅</span>
                                            @elseif($log->product->status_produk == 'MENCURIGAKAN')
                                                <span class="badge badge-mencurigakan rounded-pill px-2">⚠️</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill px-2">—</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ===== GRAFIK ===== --}}
<div class="mt-3 mb-1">
    <p class="d-lg-none mb-2" style="font-size:11px;color:rgba(250,199,117,0.65);">
        <i class="bi bi-arrow-left-right me-1"></i>Geser untuk lihat grafik
    </p>
    <div class="grafik-scroll-wrap">
        <div class="grafik-card-wrap wide">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-graph-up me-2"></i>Scan per Hari
                    <span style="color:rgba(250,199,117,0.55);font-weight:400;font-size:12px;">(7 hari terakhir)</span>
                </div>
                <div class="card-body">
                    <canvas id="chartScanHarian" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="grafik-card-wrap">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-pie-chart me-2"></i>Status Produk
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center gap-3">
                    <canvas id="chartStatusProduk" style="max-width:150px;max-height:150px;"></canvas>
                    <ul class="list-unstyled mb-0 w-100">
                        <li class="d-flex justify-content-between align-items-center py-1 border-bottom"
                            style="border-color:rgba(239,159,39,0.15) !important;">
                            <span style="font-size:13px;color:#2d1a06;">
                                <span style="display:inline-block;width:10px;height:10px;border-radius:50%;
                                             background:#EF9F27;margin-right:6px;"></span>Baru
                            </span>
                            <strong style="color:#7c5a2a;">{{ number_format($statusBaru) }}</strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-1 border-bottom"
                            style="border-color:rgba(239,159,39,0.15) !important;">
                            <span style="font-size:13px;color:#2d1a06;">
                                <span style="display:inline-block;width:10px;height:10px;border-radius:50%;
                                             background:#28a745;margin-right:6px;"></span>Asli
                            </span>
                            <strong style="color:#7c5a2a;">{{ number_format($statusAsli) }}</strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-1">
                            <span style="font-size:13px;color:#2d1a06;">
                                <span style="display:inline-block;width:10px;height:10px;border-radius:50%;
                                             background:#c0392b;margin-right:6px;"></span>Mencurigakan
                            </span>
                            <strong class="{{ $statusMencurigakan > 0 ? 'text-danger' : '' }}"
                                    style="{{ $statusMencurigakan == 0 ? 'color:#7c5a2a;' : '' }}">
                                {{ number_format($statusMencurigakan) }}
                            </strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== INFO SISTEM ===== --}}
<div class="row g-3 mt-1 mb-2">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-3">
                <div class="row text-center g-2">
                    <div class="col-6 col-md-3 border-end" style="border-color:rgba(239,159,39,0.2) !important;">
                        <div class="info-lbl">Algoritma Hash</div>
                        <div class="info-val">HMAC-SHA256</div>
                    </div>
                    <div class="col-6 col-md-3 border-end" style="border-color:rgba(239,159,39,0.2) !important;">
                        <div class="info-lbl">Batas Scan Normal</div>
                        <div class="info-val">≤ 3 kali</div>
                    </div>
                    <div class="col-6 col-md-3 mt-2 mt-md-0 border-md-end"
                         style="border-color:rgba(239,159,39,0.2) !important;">
                        <div class="info-lbl">Total Template Label</div>
                        <div class="info-val">
                            {{ \App\Models\LabelTemplate::count() }} template
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mt-2 mt-md-0">
                        <div class="info-lbl">Halaman Verifikasi</div>
                        <a href="{{ route('verify.index') }}" target="_blank" class="info-link">
                            Buka <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== BOTTOM NAVIGATION (mobile only) ===== --}}
<nav id="bottomNav" style="display:none;">
    <a href="{{ route('dashboard') }}" class="bnav-item active">
        <i class="bi bi-speedometer2"></i><span>Dashboard</span>
    </a>
    <a href="{{ route('categories.index') }}" class="bnav-item">
        <i class="bi bi-tags"></i><span>Kategori</span>
    </a>
    <a href="{{ route('products.index') }}" class="bnav-item">
        <i class="bi bi-box-seam"></i><span>Produk</span>
    </a>
    <a href="{{ route('scanlogs.index') }}" class="bnav-item">
        <i class="bi bi-clock-history"></i><span>Log Scan</span>
    </a>
    <a href="{{ route('verify.index') }}" target="_blank" class="bnav-item">
        <i class="bi bi-qr-code-scan"></i><span>Verifikasi</span>
    </a>
</nav>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Sidebar toggle ───────────────────────────────────────────────
    const sidebar   = document.querySelector('.sidebar');
    const backdrop  = document.getElementById('sidebarBackdrop');
    const toggleBtn = document.getElementById('sidebarToggleBtn');

    function openSidebar()  {
        sidebar?.classList.add('show');
        backdrop?.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        sidebar?.classList.remove('show');
        backdrop?.classList.remove('show');
        document.body.style.overflow = '';
    }

    toggleBtn?.addEventListener('click', openSidebar);
    backdrop?.addEventListener('click', closeSidebar);

    // Tutup sidebar saat item diklik di mobile
    sidebar?.querySelectorAll('a').forEach(a =>
        a.addEventListener('click', () => { if (window.innerWidth < 992) closeSidebar(); })
    );

    // ── Charts ───────────────────────────────────────────────────────
    const labelHari = {!! $labelHari !!};
    const dataScan  = {!! $dataScan !!};

    const statusBaru         = {{ $statusBaru }};
    const statusAsli         = {{ $statusAsli }};
    const statusMencurigakan = {{ $statusMencurigakan }};

    new Chart(document.getElementById('chartScanHarian'), {
        type: 'line',
        data: {
            labels: labelHari,
            datasets: [{
                label: 'Jumlah Scan',
                data: dataScan,
                borderColor: '#EF9F27',
                backgroundColor: 'rgba(239,159,39,0.08)',
                tension: 0.35,
                fill: true,
                pointBackgroundColor: '#EF9F27',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} scan` } },
            },
            scales: {
                x: {
                    grid: { color: 'rgba(239,159,39,0.07)' },
                    ticks: { font: { size: 11 }, maxRotation: 0, color: '#b8956a' },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(239,159,39,0.07)' },
                    ticks: { font: { size: 11 }, precision: 0, color: '#b8956a' },
                },
            },
        },
    });

    new Chart(document.getElementById('chartStatusProduk'), {
        type: 'doughnut',
        data: {
            labels: ['Baru', 'Asli', 'Mencurigakan'],
            datasets: [{
                data: [statusBaru, statusAsli, statusMencurigakan],
                backgroundColor: ['#EF9F27', '#28a745', '#c0392b'],
                borderColor: '#fffdf8',
                borderWidth: 3,
                hoverOffset: 8,
            }],
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed.toLocaleString('id-ID')}` },
                },
            },
        },
    });
});
</script>
@endpush