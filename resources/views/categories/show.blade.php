@extends('layouts.app')

@section('title', 'Detail Kategori')
@section('page-title', 'Detail Kategori')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        @if($category->foto_produk)
            <img src="{{ asset('storage/' . $category->foto_produk) }}"
                 style="width:60px; height:60px; object-fit:cover;
                        border-radius:10px; border:2px solid #dee2e6;">
        @else
            <div style="width:60px; height:60px; background:#f0f4f8;
                        border-radius:10px; border:2px solid #dee2e6;
                        display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-image text-muted" style="font-size:24px;"></i>
            </div>
        @endif
        <div>
            <h5 class="mb-1 fw-bold">{{ $category->nama_kategori }}</h5>
            <p class="text-muted mb-0" style="font-size:13px;">
                Batch: {{ $category->batch_produk }} |
                Produksi: {{ $category->tanggal_produksi->format('d/m/Y') }}
                @if($category->labelTemplate)
                    | <span class="badge bg-success" style="font-size:11px;">
                        <i class="bi bi-file-image me-1"></i>{{ $category->labelTemplate->nama_template }}
                      </span>
                @else
                    | <span class="badge bg-secondary" style="font-size:11px;">Belum ada template</span>
                @endif
            </p>
        </div>
    </div>
    <div class="d-flex gap-2">
        {{-- ✅ BULK PRINT LABEL --}}
        @if($products->total() > 0)
        <a href="{{ route('labels.bulk-print', $category->id) }}"
           class="btn btn-success btn-sm">
            <i class="bi bi-printer me-1"></i> Bulk Print Label
        </a>
        @endif

        @if($products->total() > 0)
        <a href="{{ route('categories.export-pdf', $category->id) }}"
           class="btn btn-danger btn-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
        </a>
        @endif

        <a href="{{ route('categories.edit', $category->id) }}"
           class="btn btn-outline-warning btn-sm">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('categories.index') }}"
           class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center border-primary">
            <div class="card-body py-3">
                <div style="font-size:32px; font-weight:700; color:#1a73e8;">{{ $products->total() }}</div>
                <div class="text-muted" style="font-size:13px;">Total Unit</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-success">
            <div class="card-body py-3">
                <div style="font-size:32px; font-weight:700; color:#28a745;">
                    {{ $category->products()->where('status_produk', 'ASLI')->count() }}
                </div>
                <div class="text-muted" style="font-size:13px;">Unit Asli</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-warning">
            <div class="card-body py-3">
                <div style="font-size:32px; font-weight:700; color:#ffc107;">
                    {{ $category->unitMencurigakan() }}
                </div>
                <div class="text-muted" style="font-size:13px;">Mencurigakan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-secondary">
            <div class="card-body py-3">
                <div style="font-size:32px; font-weight:700; color:#6c757d;">
                    {{ $category->products()->where('status_produk', 'BARU')->count() }}
                </div>
                <div class="text-muted" style="font-size:13px;">Belum Scan</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Panel Kiri --}}
    <div class="col-md-4">

        @if($category->foto_produk)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light">
                <i class="bi bi-image me-1"></i> Foto Produk
            </div>
            <div class="card-body text-center p-3">
                <img src="{{ asset('storage/' . $category->foto_produk) }}"
                     alt="{{ $category->nama_kategori }}"
                     style="width:100%; max-height:220px; object-fit:cover; border-radius:10px;">
            </div>
        </div>
        @endif

        {{-- Generate Unit --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-qr-code me-2"></i>Generate Unit Produk
            </div>
            <div class="card-body">
                <p class="text-muted" style="font-size:13px;">
                    Generate unit produk baru dengan QR Code & label otomatis.
                </p>
                <form action="{{ route('categories.generate-units', $category->id) }}" method="POST">
                    @csrf

                    {{-- Pilih Template Label --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            Template Label
                        </label>
                        @if($templates->isEmpty())
                            <div class="alert alert-warning py-2 mb-0" style="font-size:12px;">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Belum ada template.
                                <a href="{{ route('labels.index') }}" class="alert-link">Upload dulu</a>
                            </div>
                        @else
                            <select name="label_template_id" class="form-select form-select-sm">
                                <option value="">— Tanpa template —</option>
                                @foreach($templates as $tmpl)
                                <option value="{{ $tmpl->id }}"
                                    {{ ($category->label_template_id == $tmpl->id) ? 'selected' : '' }}>
                                    {{ $tmpl->nama_template }}
                                    @if($tmpl->hasSavedPosition())
                                        (posisi QR ✓)
                                    @else
                                        (posisi belum disimpan)
                                    @endif
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                Template yang dipilih akan disimpan untuk kategori ini.
                            </div>
                        @endif
                    </div>

                    {{-- Jumlah Unit --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Jumlah Unit</label>
                        <input type="number" name="jumlah"
                               class="form-control @error('jumlah') is-invalid @enderror"
                               min="1" max="500" value="{{ old('jumlah', 10) }}">
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maksimal 500 unit sekaligus.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100"
                            onclick="return confirm('Generate unit produk baru?')">
                        <i class="bi bi-lightning-charge me-1"></i> Generate Sekarang
                    </button>
                </form>
            </div>
        </div>

        @if($category->deskripsi)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light">
                <i class="bi bi-info-circle me-1"></i> Deskripsi
            </div>
            <div class="card-body">
                <p class="mb-0" style="font-size:13px;">{{ $category->deskripsi }}</p>
            </div>
        </div>
        @endif

        @if($products->total() > 0)
        <div class="card shadow-sm border-danger">
            <div class="card-body">
                <form action="{{ route('categories.clear-units', $category->id) }}"
                      method="POST"
                      onsubmit="return confirm('Hapus SEMUA unit produk dalam kategori ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100 btn-sm">
                        <i class="bi bi-trash me-1"></i> Hapus Semua Unit
                    </button>
                </form>
                <p class="text-muted mt-2 mb-0" style="font-size:11px; text-align:center;">
                    Kategori tidak ikut terhapus
                </p>
            </div>
        </div>
        @endif
    </div>

    {{-- Tabel Unit Produk --}}
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-ul me-1"></i> Daftar Unit Produk</span>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary">{{ $products->total() }} unit</span>
                    @if($products->total() > 0)
                    <a href="{{ route('labels.bulk-print', $category->id) }}"
                       class="btn btn-success btn-sm py-0 px-2" style="font-size:11px;">
                        <i class="bi bi-printer"></i> Print
                    </a>
                    <a href="{{ route('categories.export-pdf', $category->id) }}"
                       class="btn btn-danger btn-sm py-0 px-2" style="font-size:11px;">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                @if($products->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size:40px; color:#dee2e6;"></i>
                        <p class="mt-2 mb-0" style="font-size:13px;">
                            Belum ada unit. Gunakan form di samping untuk generate.
                        </p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode Produk</th>
                                    <th>Status</th>
                                    <th>Scan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $i => $product)
                                <tr>
                                    <td class="text-muted" style="font-size:12px;">
                                        {{ ($products->currentPage() - 1) * $products->perPage() + $i + 1 }}
                                    </td>
                                    <td>
                                        <code style="font-size:11px; background:#f0f4f8; padding:2px 5px; border-radius:4px;">
                                            {{ $product->kode_produk }}
                                        </code>
                                    </td>
                                    <td>
                                        @if($product->status_produk == 'ASLI')
                                            <span class="badge badge-asli rounded-pill">Asli</span>
                                        @elseif($product->status_produk == 'MENCURIGAKAN')
                                            <span class="badge badge-mencurigakan rounded-pill">Mencurigakan</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill">Baru</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $product->total_scan }}x</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('products.show', $product->id) }}"
                                               class="btn btn-sm btn-outline-primary" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('labels.editor', $product->id) }}"
                                               class="btn btn-sm btn-outline-info" title="Label Editor">
                                                <i class="bi bi-qr-code"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Hapus unit ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($products->hasPages())
                        <div class="p-3 border-top">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@endsection