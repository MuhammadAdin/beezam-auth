@extends('layouts.app')

@section('title', 'Kategori Produk')
@section('page-title', 'Kategori Produk')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1 fw-bold">Daftar Kategori Produk</h5>
        <p class="text-muted mb-0" style="font-size:13px;">
            Total {{ $categories->total() }} kategori terdaftar
        </p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
    </a>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body p-0">
        @if($categories->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-tags" style="font-size:48px; color:#dee2e6;"></i>
                <h6 class="mt-3">Belum ada kategori</h6>
                <p style="font-size:13px;">Tambah kategori produk untuk mulai generate unit QR Code.</p>
                <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus"></i> Tambah Kategori Pertama
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Kategori</th>
                            <th>Batch</th>
                            <th>Tgl Produksi</th>
                            <th>Total Unit</th>
                            <th>Mencurigakan</th>
                            <th width="18%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $i => $category)
                        <tr>
                            <td class="text-muted">
                                {{ ($categories->currentPage() - 1) * $categories->perPage() + $i + 1 }}
                            </td>
                            <td>
                                <div class="fw-semibold" style="font-size:14px;">
                                    {{ $category->nama_kategori }}
                                </div>
                                @if($category->deskripsi)
                                <div style="font-size:11px; color:#999;">
                                    {{ Str::limit($category->deskripsi, 50) }}
                                </div>
                                @endif
                            </td>
                            <td style="font-size:13px;">{{ $category->batch_produk }}</td>
                            <td style="font-size:13px;">
                                {{ \Carbon\Carbon::parse($category->tanggal_produksi)->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="badge bg-primary rounded-pill px-3">
                                    {{ $category->products_count }} unit
                                </span>
                            </td>
                            <td>
                                @php $mencurigakan = $category->unitMencurigakan(); @endphp
                                @if($mencurigakan > 0)
                                    <span class="badge badge-mencurigakan rounded-pill px-3">
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $mencurigakan }}
                                    </span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3">0</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('categories.show', $category->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="Detail & Generate">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('categories.edit', $category->id) }}"
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus kategori ini beserta SEMUA unit produknya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
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

            @if($categories->hasPages())
                <div class="p-3 border-top">
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
</div>

@endsection