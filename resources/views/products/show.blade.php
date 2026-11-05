@extends('layouts.app')

@php use App\Support\PublicFile; @endphp

@section('title', 'Detail Produk')
@section('page-title', 'Detail Produk')

@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('categories.index') }}">Kategori Produk</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('categories.show', $product->category_id) }}">
                {{ $product->category->nama_kategori }}
            </a>
        </li>
        <li class="breadcrumb-item active">Detail Unit</li>
    </ol>
</nav>

<div class="row g-3">

    {{-- Kolom Kiri: Info Produk + QR Code --}}
    <div class="col-lg-5">

        {{-- QR Code Card --}}
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-qr-code me-2 text-primary"></i>
                QR Code Produk
            </div>
            <div class="card-body text-center">

               @if(PublicFile::exists($product->qr_code))
                    <img
                        src="{{ asset('storage/' . $product->qr_code) }}"
                        alt="QR Code {{ $product->kode_produk }}"
                        style="width:200px; height:200px;
                               border:8px solid #fff;
                               box-shadow:0 2px 12px rgba(0,0,0,0.15);
                               border-radius:8px;"
                    >
                    <div class="mt-3" style="font-size:12px; color:#999;">
                        Scan QR untuk verifikasi produk
                    </div>
                    <div class="mt-1">
                        <code style="font-size:11px; background:#f0f4f8;
                                     padding:3px 8px; border-radius:4px;">
                            {{ $product->kode_produk }}
                        </code>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <a href="{{ asset('storage/' . $product->qr_code) }}"
                           download="QR-{{ $product->kode_produk }}.png"
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-1"></i>Download QR
                        </a>
                        <a href="{{ route('products.regenerate-qr', $product->id) }}"
                           class="btn btn-outline-secondary btn-sm"
                           onclick="return confirm('Regenerate QR Code?')">
                            <i class="bi bi-arrow-clockwise me-1"></i>Regenerate
                        </a>
                    </div>
                @else
                    <div class="py-3 text-muted">
                        <i class="bi bi-qr-code" style="font-size:64px; color:#dee2e6;"></i>
                        <p class="mt-2 mb-3" style="font-size:13px;">QR Code belum di-generate</p>
                        <a href="{{ route('products.regenerate-qr', $product->id) }}"
                           class="btn btn-primary btn-sm">
                            <i class="bi bi-qr-code me-1"></i>Generate QR Code
                        </a>
                    </div>
                @endif

            </div>
        </div>

        {{-- Info Produk Card --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-box-seam me-2 text-primary"></i>
                Informasi Produk
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:14px;">
                    <tr>
                        <td class="text-muted" width="45%">Kategori</td>
                        <td class="fw-semibold">{{ $product->category->nama_kategori }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Batch</td>
                        <td>{{ $product->category->batch_produk }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tgl Produksi</td>
                        <td>
                            {{ \Carbon\Carbon::parse($product->category->tanggal_produksi)
                                ->format('d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Scan</td>
                        <td>
                            <span class="badge bg-primary">{{ $product->total_scan }}x</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($product->status_produk == 'ASLI')
                                <span class="badge badge-asli px-2 py-1">
                                    <i class="bi bi-check-circle me-1"></i>ASLI
                                </span>
                            @elseif($product->status_produk == 'MENCURIGAKAN')
                                <span class="badge badge-mencurigakan px-2 py-1">
                                    <i class="bi bi-exclamation-triangle me-1"></i>MENCURIGAKAN
                                </span>
                            @else
                                <span class="badge bg-secondary px-2 py-1">BELUM DISCAN</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer bg-white d-flex gap-2 flex-wrap">
                <a href="{{ route('categories.show', $product->category_id) }}"
                   class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Kategori
                </a>
                <a href="{{ route('labels.print', $product->id) }}"
                   class="btn btn-primary btn-sm">
                    <i class="bi bi-printer me-1"></i>Print Label
                </a>
                <a href="{{ route('verify.check', $product->kode_produk) }}"
                   target="_blank" class="btn btn-success btn-sm">
                    <i class="bi bi-patch-check me-1"></i>Test Verifikasi
                </a>
            </div>
        </div>

    </div>

    {{-- Kolom Kanan: HMAC Detail + Scan Log --}}
    <div class="col-lg-7">

        {{-- Detail HMAC --}}
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-shield-lock me-2 text-success"></i>
                Detail HMAC-SHA256
            </div>
            <div class="card-body">

                <div class="p-3 rounded mb-3"
                     style="background:#f0f4f8; border-left:4px solid #1a73e8;">
                    <div style="font-size:11px; color:#666; font-weight:600;
                                text-transform:uppercase; margin-bottom:6px;">
                        Formula:
                    </div>
                    <code style="font-size:12px; color:#333; line-height:1.8;">
                        M = kode_produk + nama_kategori<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;+ batch_produk + tanggal_produksi<br>
                        hash = HMAC‑SHA256(M, secret_key)
                    </code>
                </div>

                <div class="mb-3">
                    <label style="font-size:11px; color:#999; font-weight:600;
                                  text-transform:uppercase;">Secret Key:</label>
                    <div class="p-2 rounded mt-1"
                         style="background:#fff3cd; border:1px solid #ffc107;
                                font-family:monospace; font-size:12px; word-break:break-all;">
                        {{ $product->secret_key }}
                    </div>
                </div>

                <div class="mb-3">
                    <label style="font-size:11px; color:#999; font-weight:600;
                                  text-transform:uppercase;">Hash HMAC-SHA256:</label>
                    <div class="p-2 rounded mt-1"
                         style="background:#d1edff; border:1px solid #1a73e8;
                                font-family:monospace; font-size:11px;
                                word-break:break-all; letter-spacing:0.5px;">
                        {{ $product->hash_hmac }}
                    </div>
                    <small class="text-muted">64 karakter hex = 256 bit</small>
                </div>

                <div class="p-2 rounded text-center text-white
                    {{ $hashValid ? 'bg-success' : 'bg-danger' }}">
                    @if($hashValid)
                        <i class="bi bi-shield-check me-1"></i>
                        Hash Valid — Data Tidak Dimanipulasi
                    @else
                        <i class="bi bi-shield-x me-1"></i>
                        Hash TIDAK Valid — Data Bermasalah!
                    @endif
                </div>

            </div>
        </div>

        {{-- Riwayat Scan --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-clock-history me-2 text-primary"></i>
                    Riwayat Scan
                </span>
                <span class="badge bg-primary">{{ $product->total_scan }}x scan</span>
            </div>
            <div class="card-body p-0">
                @if($scanLogs->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-qr-code" style="font-size:32px;"></i>
                        <p class="mt-2 mb-0" style="font-size:13px;">Belum ada aktivitas scan.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>IP Address</th>
                                    <th>Waktu Scan</th>
                                    <th>Device</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scanLogs as $i => $log)
                                <tr>
                                    <td class="text-muted">{{ $i + 1 }}</td>
                                    <td>
                                        <code style="font-size:12px;">{{ $log->ip_address }}</code>
                                    </td>
                                    <td style="font-size:12px;">
                                        {{ \Carbon\Carbon::parse($log->scanned_at)
                                            ->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td style="font-size:11px; color:#666;">
                                        @php
                                            $ua = $log->user_agent ?? '';
                                            if (str_contains($ua, 'Mobile')) echo '📱 Mobile';
                                            elseif (str_contains($ua, 'Tablet')) echo '📟 Tablet';
                                            else echo '🖥️ Desktop';
                                        @endphp
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