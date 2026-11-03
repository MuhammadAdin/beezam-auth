@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Tambah Kategori Produk</h5>
    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-tags me-2"></i>Informasi Kategori
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('categories.store') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama_kategori"
                               class="form-control @error('nama_kategori') is-invalid @enderror"
                               value="{{ old('nama_kategori') }}"
                               placeholder="Contoh: Madu Hutan, Madu Ruqyah">
                        @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" rows="3"
                                  class="form-control @error('deskripsi') is-invalid @enderror"
                                  placeholder="Deskripsi singkat kategori produk (opsional)">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Batch Produk <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="batch_produk"
                               class="form-control @error('batch_produk') is-invalid @enderror"
                               value="{{ old('batch_produk') }}"
                               placeholder="Contoh: BATCH-2025-001">
                        @error('batch_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Tanggal Produksi <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="tanggal_produksi"
                               class="form-control @error('tanggal_produksi') is-invalid @enderror"
                               value="{{ old('tanggal_produksi') }}">
                        @error('tanggal_produksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- FOTO PRODUK --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Foto Produk
                            <span class="text-muted fw-normal">(opsional)</span>
                        </label>
                        <input type="file" name="foto_produk" id="foto_produk"
                               class="form-control @error('foto_produk') is-invalid @enderror"
                               accept="image/jpg,image/jpeg,image/png,image/webp">
                        <div class="form-text">Format: JPG, PNG, WEBP. Maks 2MB.</div>
                        @error('foto_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Preview --}}
                        <div id="preview-container" class="mt-2" style="display:none;">
                            <img id="preview-img" src=""
                                 style="width:120px; height:120px; object-fit:cover;
                                        border-radius:10px; border:2px solid #dee2e6;">
                        </div>
                    </div>

                    <div class="alert alert-info" style="font-size:13px;">
                        <i class="bi bi-info-circle me-1"></i>
                        Setelah kategori dibuat, kamu bisa generate unit produk (QR Code)
                        sebanyak yang dibutuhkan dari halaman detail kategori.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Simpan Kategori
                        </button>
                        <a href="{{ route('categories.index') }}"
                           class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('foto_produk').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('preview-img').src = ev.target.result;
            document.getElementById('preview-container').style.display = 'block';
        };
        reader.readAsDataURL(file);
    });
</script>

@endsection