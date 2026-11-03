@extends('layouts.app')
@section('title', 'Unit Produk')
@section('page-title', 'Unit Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1 fw-bold">Semua Unit Produk</h5>
        <p class="text-muted mb-0" style="font-size:13px;">
            Total {{ $products->total() }} unit dari semua kategori
        </p>
    </div>
    <a href="{{ route('categories.index') }}" class="btn btn-primary">
        <i class="bi bi-tags me-1"></i> Kelola Kategori
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($products->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-box-seam" style="font-size:48px; color:#dee2e6;"></i>
                <h6 class="mt-3">Belum ada unit produk</h6>
                <p style="font-size:13px;">Buat kategori terlebih dahulu, lalu generate unit.</p>
                <a href="{{ route('categories.index') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-tags me-1"></i> Buat Kategori & Generate Unit
                </a>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Kode Produk</th>
                        <th>Total Scan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $i => $product)
                    <tr>
                        <td>{{ ($products->currentPage()-1) * $products->perPage() + $i + 1 }}</td>
                        <td>
                            <div class="fw-semibold">{{ $product->category->nama_kategori }}</div>
                            <div style="font-size:11px; color:#999;">
                                Batch: {{ $product->category->batch_produk }}
                            </div>
                        </td>
                        <td><code>{{ $product->kode_produk }}</code></td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $product->total_scan }}x
                            </span>
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
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
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
@endsection