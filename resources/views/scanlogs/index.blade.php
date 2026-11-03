@extends('layouts.app')

@section('title', 'Log Scan QR')
@section('page-title', 'Log Scan QR Code')

@section('content')

{{-- ===== STAT CARDS ===== --}}
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card text-center p-3">
            <div style="font-size:28px; font-weight:700; color:#1a73e8;">
                {{ $totalScan }}
            </div>
            <div style="font-size:12px; color:#999;">Total Semua Scan</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center p-3">
            <div style="font-size:28px; font-weight:700; color:#28a745;">
                {{ $produkAsli }}
            </div>
            <div style="font-size:12px; color:#999;">Produk Status Asli</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center p-3">
            <div style="font-size:28px; font-weight:700; color:#fd7e14;">
                {{ $produkMencurigakan }}
            </div>
            <div style="font-size:12px; color:#999;">Produk Mencurigakan</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center p-3">
            <div style="font-size:28px; font-weight:700; color:#6f42c1;">
                {{ $scanHariIni }}
            </div>
            <div style="font-size:12px; color:#999;">Scan Hari Ini</div>
        </div>
    </div>

</div>

<div class="row g-3">

    {{-- Tabel Log Scan --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2 text-primary"></i>
                Riwayat Scan Terbaru
            </div>

            {{-- Filter --}}
            <div class="card-body border-bottom py-2">
                <form method="GET" action="{{ route('scanlogs.index') }}"
                      class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input
                            type="text"
                            name="kode"
                            class="form-control form-control-sm"
                            placeholder="Cari kode produk..."
                            value="{{ request('kode') }}"
                        >
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="ASLI"
                                {{ request('status') == 'ASLI' ? 'selected' : '' }}>
                                ✅ Asli
                            </option>
                            <option value="MENCURIGAKAN"
                                {{ request('status') == 'MENCURIGAKAN' ? 'selected' : '' }}>
                                ⚠️ Mencurigakan
                            </option>
                            <option value="BARU"
                                {{ request('status') == 'BARU' ? 'selected' : '' }}>
                                — Baru
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('scanlogs.index') }}"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x"></i>
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                @if($scanLogs->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-qr-code"
                           style="font-size:48px; color:#dee2e6;"></i>
                        <h6 class="mt-3">Belum ada data scan</h6>
                        <p style="font-size:13px;">
                            Data scan akan muncul saat konsumen scan QR Code.
                        </p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>IP Address</th>
                                    <th>Device</th>
                                    <th>Waktu Scan</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scanLogs as $log)
                                <tr>
                                    <td>
                                        @if($log->product)
                                            <div class="fw-semibold" style="font-size:13px;">
                                                {{ $log->product->category->nama_kategori ?? '-' }}
                                            </div>
                                            <div style="font-size:11px; color:#999;">
                                                {{ $log->product->kode_produk }}
                                            </div>
                                        @else
                                            <span class="text-muted" style="font-size:13px;">
                                                Produk dihapus
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <code style="font-size:12px;">
                                            {{ $log->ip_address ?? '-' }}
                                        </code>
                                    </td>
                                    <td style="font-size:12px;">
                                        @php $ua = $log->user_agent ?? ''; @endphp
                                        @if(str_contains($ua, 'Mobile') ||
                                            str_contains($ua, 'Android') ||
                                            str_contains($ua, 'iPhone'))
                                            <span class="badge bg-light text-dark border">
                                                📱 Mobile
                                            </span>
                                        @elseif(str_contains($ua, 'Tablet') ||
                                                str_contains($ua, 'iPad'))
                                            <span class="badge bg-light text-dark border">
                                                📟 Tablet
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark border">
                                                🖥️ Desktop
                                            </span>
                                        @endif
                                    </td>
                                    <td style="font-size:12px; color:#666;">
                                        {{ \Carbon\Carbon::parse($log->scanned_at)
                                            ->format('d/m/Y H:i:s') }}
                                        <div style="font-size:10px; color:#bbb;">
                                            {{ \Carbon\Carbon::parse($log->scanned_at)
                                                ->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($log->product)
                                            @if($log->product->status_produk == 'ASLI')
                                                <span class="badge rounded-pill px-2"
                                                      style="background:#28a745;color:#fff;">
                                                    ✅ Asli
                                                </span>
                                            @elseif($log->product->status_produk == 'MENCURIGAKAN')
                                                <span class="badge rounded-pill px-2"
                                                      style="background:#fd7e14;color:#fff;">
                                                    ⚠️ Curiga
                                                </span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill px-2">
                                                    — Baru
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->product)
                                            <a href="{{ route('scanlogs.show', $log->product->id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($scanLogs->hasPages())
                        <div class="p-3 border-top">
                            {{ $scanLogs->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">

        @if($produkMencurigakan > 0)
        <div class="card mb-3 border-warning">
            <div class="card-header"
                 style="background:#fff3cd; border-bottom:1px solid #ffc107;">
                <i class="bi bi-exclamation-triangle me-2 text-warning"></i>
                <span class="text-warning fw-bold">Peringatan Deteksi</span>
            </div>
            <div class="card-body" style="background:#fffdf0;">
                <p style="font-size:13px; color:#856404;">
                    <i class="bi bi-info-circle me-1"></i>
                    Terdapat <strong>{{ $produkMencurigakan }} produk</strong>
                    dengan jumlah scan melebihi batas normal (>3x).
                </p>
                <div style="font-size:12px; color:#856404;">
                    <strong>Tindakan yang disarankan:</strong>
                    <ul class="mt-1 mb-0" style="padding-left:16px;">
                        <li>Periksa produk yang mencurigakan</li>
                        <li>Cek IP address yang sering scan</li>
                        <li>Konfirmasi ke tim lapangan</li>
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2 text-primary"></i>
                Top Produk Terscan
            </div>
            <div class="card-body p-0">
                @if($topScanProduk->isEmpty())
                    <div class="text-center py-4 text-muted" style="font-size:13px;">
                        Belum ada data scan
                    </div>
                @else
                    @foreach($topScanProduk as $i => $produk)
                    <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div style="flex:1;">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-pill"
                                          style="background:
                                            {{ $i == 0 ? '#ffd700' :
                                               ($i == 1 ? '#c0c0c0' :
                                               ($i == 2 ? '#cd7f32' : '#dee2e6')) }};
                                          color:{{ $i < 3 ? '#333' : '#666' }};
                                          width:24px; height:24px;
                                          display:flex; align-items:center;
                                          justify-content:center;
                                          font-size:11px; font-weight:700;">
                                        {{ $i + 1 }}
                                    </span>
                                    <div>
                                        <div class="fw-semibold" style="font-size:13px;">
                                            {{ Str::limit($produk->category->nama_kategori ?? '-', 25) }}
                                        </div>
                                        <div style="font-size:11px; color:#999;">
                                            {{ $produk->kode_produk }}
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $maxScan = $topScanProduk->first()->total_scan;
                                    $pct     = $maxScan > 0
                                               ? ($produk->total_scan / $maxScan) * 100 : 0;
                                    $barColor = $produk->total_scan > 3 ? '#fd7e14' : '#28a745';
                                @endphp
                                <div class="mt-2"
                                     style="background:#f0f0f0; border-radius:10px;
                                            height:6px; overflow:hidden;">
                                    <div style="width:{{ $pct }}%;
                                                background:{{ $barColor }};
                                                height:100%; border-radius:10px;">
                                    </div>
                                </div>
                            </div>

                            <div class="ms-3 text-end">
                                <div style="font-size:20px; font-weight:700;
                                            color:{{ $produk->total_scan > 3
                                                    ? '#fd7e14' : '#1a73e8' }};">
                                    {{ $produk->total_scan }}
                                </div>
                                <div style="font-size:10px; color:#999;">scan</div>
                                @if($produk->status_produk == 'ASLI')
                                    <span class="badge mt-1"
                                          style="background:#28a745; color:#fff; font-size:9px;">
                                        ✅ Asli
                                    </span>
                                @elseif($produk->status_produk == 'MENCURIGAKAN')
                                    <span class="badge mt-1"
                                          style="background:#fd7e14; color:#fff; font-size:9px;">
                                        ⚠️ Curiga
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-2 text-end">
                            <a href="{{ route('scanlogs.show', $produk->id) }}"
                               class="btn btn-outline-primary btn-sm"
                               style="font-size:11px; padding:2px 10px;">
                                Detail Scan
                            </a>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
</div>

@endsection