@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Edit Kategori</h5>
    <a href="{{ route('categories.show', $category->id) }}"
       class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-pencil me-2"></i>Edit Informasi Kategori
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

                <form action="{{ route('categories.update', $category->id) }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama_kategori"
                               class="form-control @error('nama_kategori') is-invalid @enderror"
                               value="{{ old('nama_kategori', $category->nama_kategori) }}">
                        @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" rows="3"
                                  class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $category->deskripsi) }}</textarea>
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
                               value="{{ old('batch_produk', $category->batch_produk) }}">
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
                               value="{{ old('tanggal_produksi', $category->tanggal_produksi->format('Y-m-d')) }}">
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

                        {{-- Preview foto lama --}}
                        @if($category->foto_produk)
                            <div class="mb-2 d-flex align-items-center gap-3">
                                <img src="{{ asset('storage/' . $category->foto_produk) }}"
                                     id="preview-img"
                                     style="width:100px; height:100px; object-fit:cover;
                                            border-radius:10px; border:2px solid #dee2e6;">
                                <div>
                                    <div style="font-size:12px; color:#666;" class="mb-1">
                                        Foto saat ini
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="hapus_foto"
                                               id="hapus_foto" class="form-check-input"
                                               value="1">
                                        <label for="hapus_foto"
                                               class="form-check-label text-danger"
                                               style="font-size:13px;">
                                            Hapus foto ini
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-2">
                                <img id="preview-img" src=""
                                     style="width:100px; height:100px; object-fit:cover;
                                            border-radius:10px; border:2px solid #dee2e6;
                                            display:none;">
                            </div>
                        @endif

                        <input type="file" name="foto_produk" id="foto_produk"
                               class="form-control @error('foto_produk') is-invalid @enderror"
                               accept="image/jpg,image/jpeg,image/png,image/webp">
                        <div class="form-text">
                            Upload foto baru untuk mengganti foto lama.
                            Format: JPG, PNG, WEBP. Maks 2MB.
                        </div>
                        @error('foto_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($category->products_count > 0)
                    <div class="alert alert-warning" style="font-size:13px;">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Kategori ini sudah memiliki
                        <strong>{{ $category->products_count }} unit</strong> produk.
                        Mengubah nama kategori, batch, atau tanggal produksi akan membuat
                        hash unit lama tidak cocok.
                    </div>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle me-1"></i> Update Kategori
                        </button>
                        <a href="{{ route('categories.show', $category->id) }}"
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
            const img = document.getElementById('preview-img');
            img.src = ev.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });
</script>

@endsection