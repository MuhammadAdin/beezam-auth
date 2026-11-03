@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index') }}">Data Produk</a>
                </li>
                <li class="breadcrumb-item active">Tambah Produk</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2 text-primary"></i>
                Form Tambah Produk Baru
            </div>
            <div class="card-body p-4">

                {{-- Info otomatis --}}
                <div class="alert alert-info py-2 mb-4" style="font-size:13px;">
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>Kode Produk</strong>, <strong>Secret Key</strong>, dan
                    <strong>Hash HMAC-SHA256</strong> akan di-generate otomatis oleh sistem.
                </div>

                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    {{-- Nama Produk --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama Produk <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama_produk"
                            class="form-control @error('nama_produk') is-invalid @enderror"
                            placeholder="Contoh: Kopi Arabika Premium"
                            value="{{ old('nama_produk') }}"
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
                            placeholder="Contoh: BATCH-2024-001"
                            value="{{ old('batch_produk') }}"
                        >
                        <div class="form-text">
                            Nomor batch/lot produksi untuk identifikasi kelompok produk.
                        </div>
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
                            value="{{ old('tanggal_produksi') }}"
                        >
                        @error('tanggal_produksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Preview Generate Otomatis --}}
                    <div class="p-3 rounded mb-4"
                         style="background:#f8f9fa; border:1px dashed #dee2e6;">
                        <div style="font-size:12px; color:#666; font-weight:600;
                                    text-transform:uppercase; letter-spacing:0.5px;">
                            Yang Akan Di-Generate Otomatis:
                        </div>
                        <div class="mt-2" style="font-size:13px;">
                            <div class="d-flex gap-2 mb-1">
                                <span class="badge bg-primary">Kode Produk</span>
                                <span class="text-muted">PRD-[timestamp]-[random]</span>
                            </div>
                            <div class="d-flex gap-2 mb-1">
                                <span class="badge bg-secondary">Secret Key</span>
                                <span class="text-muted">32 karakter random</span>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="badge bg-success">Hash HMAC-SHA256</span>
                                <span class="text-muted">64 karakter hex</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Produk
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