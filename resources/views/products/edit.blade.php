@extends('layouts.app')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">

        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index') }}">Data Produk</a>
                </li>
                <li class="breadcrumb-item active">Edit Produk</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2 text-warning"></i>
                Edit Produk: {{ $product->nama_produk }}
            </div>
            <div class="card-body p-4">

                {{-- Info kode produk --}}
                <div class="alert alert-warning py-2 mb-4" style="font-size:13px;">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Mengubah data produk akan <strong>me-regenerate Hash HMAC-SHA256</strong>
                    secara otomatis. QR Code lama tidak perlu dicetak ulang.
                </div>

                {{-- Info readonly --}}
                <div class="mb-3 p-3 rounded" style="background:#f0f4f8;">
                    <div style="font-size:12px; color:#666; font-weight:600; margin-bottom:8px;">
                        DATA TIDAK DAPAT DIUBAH:
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label style="font-size:11px; color:#999;">Kode Produk</label>
                            <div>
                                <code style="font-size:13px;">{{ $product->kode_produk }}</code>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label style="font-size:11px; color:#999;">Hash HMAC (saat ini)</label>
                            <div style="font-size:11px; color:#666; word-break:break-all;">
                                {{ substr($product->hash_hmac, 0, 32) }}...
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama Produk --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama Produk <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama_produk"
                            class="form-control @error('nama_produk') is-invalid @enderror"
                            value="{{ old('nama_produk', $product->nama_produk) }}"
                        >
                        @error('nama_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Batch Produk --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Batch Produk <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="batch_produk"
                            class="form-control @error('batch_produk') is-invalid @enderror"
                            value="{{ old('batch_produk', $product->batch_produk) }}"
                        >
                        @error('batch_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Produksi --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Tanggal Produksi <span class="text-danger">*</span>
                        </label>
                        <input
                            type="date"
                            name="tanggal_produksi"
                            class="form-control @error('tanggal_produksi') is-invalid @enderror"
                            value="{{ old('tanggal_produksi', $product->tanggal_produksi) }}"
                        >
                        @error('tanggal_produksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="bi bi-save me-1"></i> Update Produk
                        </button>
                        <a href="{{ route('products.index') }}"
                           class="btn btn-outline-secondary px-4">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection