@extends('layouts.app')

@section('title', 'Detail Scan Produk')
@section('page-title', 'Detail Scan Produk')

@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('scanlogs.index') }}">Log Scan QR</a>
        </li>
        <li class="breadcrumb-item active">
            {{ $product->category->nama_kategori }}
        </li>
    </ol>
</nav>

<div class="row g-3">

    {{-- Kolom Kiri --}}
    <div class="col-lg-4">

        {{-- Card Status Produk --}}
        <div class="card mb-3 text-white"
             style="background:
                @if($product->status_produk == 'ASLI')
                    linear-gradient(135deg,#2e7d32,#43a047);
                @elseif($product->status_produk == 'MENCURIGAKAN')
                    linear-gradient(135deg,#e65100,#f57c00);
                @else
                    linear-gradient(135deg,#546e7a,#78909c);
                @endif
             ">
            <div class="card-body text-center py-4">
                @if($product->status_produk == 'ASLI')
                    <i class="bi bi-patch-check-fill" style="font-size:48px;"></i>
                    <div class="mt-2 fw-bold fs-5">PRODUK ASLI</div>
                @elseif($product->status_produk == 'MENCURIGAKAN')
                    <i class="bi bi-exclamation-triangle-fill" style="font-size:48px;"></i>
                    <div class="mt-2 fw-bold fs-5">MENCURIGAKAN</div>
                @else
                    <i class="bi bi-dash-circle" style="font-size:48px;"></i>
                    <div class="mt-2 fw-bold fs-5">BELUM DISCAN</div>
                @endif

                <div style="font-size:13px; opacity:0.85; margin-top:6px;">
                    Total: <strong>{{ $product->total_scan }}x scan</strong>
                </div>

                <div class="mt-3 px-2">
                    @php $pct = min(($product->total_scan / 3) * 100, 100); @endphp
                    <div style="background:rgba(255,255,255,0.3);
                                border-radius:10px; height:8px; overflow:hidden;">
                        <div style="width:{{ $pct }}%;
                                    background:rgba(255,255,255,0.9);
                                    height:100%; border-radius:10px;">
                        </div>
                    </div>
                    <div style="font-size:11px; opacity:0.8; margin-top:4px;">
                        @if($product->total_scan <= 3)
                            {{ $product->total_scan }}/3 scan (normal)
                        @else
                            {{ $product->total_scan }} scan (melebihi batas!)
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Produk --}}
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-box-seam me-2 text-primary"></i>
                Info Produk
            </div>
            <div class="card-body" style="font-size:13px;">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted py-1">Kategori</td>
                        <td class="fw-semibold py-1">
                            {{ $product->category->nama_kategori }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted py-1">Batch</td>
                        <td class="py-1">{{ $product->category->batch_produk }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted py-1">Produksi</td>
                        <td class="py-1">
                            {{ \Carbon\Carbon::parse($product->category->tanggal_produksi)
                                ->format('d/m/Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted py-1">Kode</td>
                        <td class="py-1">
                            <code style="font-size:11px;">
                                {{ $product->kode_produk }}
                            </code>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('products.show', $product->id) }}"
                   class="btn btn-outline-primary btn-sm w-100">
                    <i class="bi bi-box-seam me-1"></i>Lihat Detail Produk
                </a>
            </div>
        </div>

        {{-- Deteksi Scan Cepat --}}
        @if($adaScanCepat)
        <div class="card border-danger mb-3">
            <div class="card-body" style="background:#ffebee;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-exclamation-octagon-fill text-danger fs-4"></i>
                    <span class="fw-bold text-danger" style="font-size:13px;">
                        Aktivitas Scan Cepat!
                    </span>
                </div>
                <p style="font-size:12px; color:#c62828; margin:0;">
                    Terdeteksi scan berulang dalam waktu singkat (&lt;1 menit).
                    Kemungkinan terjadi percobaan duplikasi produk secara massal.
                </p>
            </div>
        </div>
        @endif

        {{-- Top IP Address --}}
        @if($topIpAddress->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <i class="bi bi-geo-alt me-2 text-primary"></i>
                IP Address Tersering
            </div>
            <div class="card-body p-0">
                @foreach($topIpAddress as $ip)
                <div class="d-flex justify-content-between align-items-center
                            px-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <code style="font-size:12px;">
                        {{ $ip->ip_address ?? 'Unknown' }}
                    </code>
                    <span class="badge {{ $ip->jumlah > 2 ? 'bg-danger' : 'bg-primary' }}">
                        {{ $ip->jumlah }}x
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Kolom Kanan --}}
    <div class="col-lg-8">

        {{-- Scan Per Hari --}}
        @if($scanPerHari->isNotEmpty())
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-calendar3 me-2 text-primary"></i>
                Scan Per Hari (7 Hari Terakhir)
            </div>
            <div class="card-body p-0">
                @foreach($scanPerHari as $hari)
                <div class="px-3 py-2 d-flex align-items-center gap-3
                            {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div style="width:90px; font-size:13px; color:#666;">
                        {{ \Carbon\Carbon::parse($hari->tanggal)->format('d/m/Y') }}
                    </div>
                    <div style="flex:1;">
                        @php
                            $maxHari = $scanPerHari->max('jumlah');
                            $pctHari = $maxHari > 0
                                       ? ($hari->jumlah / $maxHari) * 100 : 0;
                        @endphp
                        <div style="background:#f0f4f8; border-radius:10px;
                                    height:18px; overflow:hidden;">
                            <div style="width:{{ $pctHari }}%;
                                        background:{{ $hari->jumlah > 3
                                                      ? '#fd7e14' : '#1a73e8' }};
                                        height:100%; border-radius:10px;">
                            </div>
                        </div>
                    </div>
                    <div style="width:40px; text-align:right; font-weight:700;
                                font-size:14px;
                                color:{{ $hari->jumlah > 3 ? '#fd7e14' : '#1a73e8' }};">
                        {{ $hari->jumlah }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Tabel Detail Log Scan --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-list-ul me-2 text-primary"></i>
                    Riwayat Scan Lengkap
                </span>
                <span class="badge bg-primary">
                    {{ $product->total_scan }} total
                </span>
            </div>
            <div class="card-body p-0">
                @if($scanLogs->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-qr-code"
                           style="font-size:36px; color:#dee2e6;"></i>
                        <p class="mt-2 mb-0" style="font-size:13px;">
                            Belum ada riwayat scan untuk produk ini.
                        </p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>IP Address</th>
                                    <th>Device / Browser</th>
                                    <th>Waktu Scan</th>
                                    <th>Ket</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scanLogs as $i => $log)
                                @php
                                    $nomorUrut = ($scanLogs->currentPage() - 1)
                                               * $scanLogs->perPage() + $i + 1;
                                    $ua        = $log->user_agent ?? '';
                                    $isMobile  = str_contains($ua, 'Mobile')
                                              || str_contains($ua, 'Android')
                                              || str_contains($ua, 'iPhone');
                                @endphp
                                <tr>
                                    <td class="text-muted">{{ $nomorUrut }}</td>
                                    <td>
                                        <code style="font-size:12px;">
                                            {{ $log->ip_address ?? '-' }}
                                        </code>
                                    </td>
                                    <td style="font-size:11px; color:#666; max-width:200px;">
                                        {{ $isMobile ? '📱' : '🖥️' }}
                                        {{ Str::limit($ua, 40) }}
                                    </td>
                                    <td style="font-size:12px;">
                                        {{ \Carbon\Carbon::parse($log->scanned_at)
                                            ->format('d/m/Y H:i:s') }}
                                        <div style="font-size:10px; color:#bbb;">
                                            {{ \Carbon\Carbon::parse($log->scanned_at)
                                                ->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($nomorUrut > 3)
                                            <span class="badge"
                                                  style="background:#fd7e14;
                                                         color:#fff; font-size:10px;">
                                                ⚠️ Curiga
                                            </span>
                                        @else
                                            <span class="badge bg-success"
                                                  style="font-size:10px;">
                                                ✅ Normal
                                            </span>
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
</div>

@endsection