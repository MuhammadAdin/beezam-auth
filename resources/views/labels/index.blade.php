@extends('layouts.app')

@section('title', 'Template Label')
@section('page-title', 'Template Label')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3">

    {{-- Form Upload Template --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-upload me-2 text-primary"></i>
                Upload Template Label
            </div>
            <div class="card-body">

                <div class="alert alert-info py-2 mb-3" style="font-size:12px;">
                    <i class="bi bi-info-circle me-1"></i>
                    Upload gambar desain label produk (PNG/JPG).
                    Setelah upload, klik <strong>Edit Posisi QR</strong> untuk atur posisi QR Code.
                </div>

                <form action="{{ route('labels.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:14px;">
                            Nama Template
                        </label>
                        <input type="text"
                            name="nama_template"
                            class="form-control @error('nama_template') is-invalid @enderror"
                            placeholder="Contoh: Label Madu Trigona"
                            value="{{ old('nama_template') }}">
                        @error('nama_template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:14px;">
                            File Template (PNG/JPG)
                        </label>

                        <div id="previewArea"
                             style="border:2px dashed #dee2e6; border-radius:8px;
                                    padding:20px; text-align:center; margin-bottom:10px;
                                    cursor:pointer; background:#fafafa;"
                             onclick="document.getElementById('fileInput').click()">
                            <div id="previewPlaceholder">
                                <i class="bi bi-image" style="font-size:32px; color:#dee2e6;"></i>
                                <p class="mb-0 mt-2" style="font-size:13px; color:#999;">
                                    Klik untuk pilih gambar
                                </p>
                                <p style="font-size:11px; color:#bbb;">PNG, JPG maks 5MB</p>
                            </div>
                            <img id="previewImg" src=""
                                 style="display:none; max-width:100%; max-height:150px; border-radius:6px;">
                        </div>

                        <input type="file"
                            name="file_template"
                            id="fileInput"
                            class="form-control @error('file_template') is-invalid @enderror"
                            accept="image/png,image/jpg,image/jpeg"
                            style="display:none"
                            onchange="previewImage(this)">
                        <small class="text-muted">Klik area di atas untuk memilih file</small>
                        @error('file_template')
                            <div class="text-danger mt-1" style="font-size:13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-upload me-1"></i> Upload Template
                    </button>
                </form>
            </div>
        </div>

        {{-- Panduan singkat --}}
        <div class="card mt-3 border-0 bg-light">
            <div class="card-body py-3">
                <p class="fw-semibold mb-2" style="font-size:13px;">
                    <i class="bi bi-info-circle text-primary me-1"></i>Cara pakai:
                </p>
                <ol style="font-size:12px; color:#6c757d; padding-left:16px; margin:0;">
                    <li class="mb-1">Upload template label (gambar PNG/JPG)</li>
                    <li class="mb-1">Klik <strong>Edit Posisi QR</strong> pada template</li>
                    <li class="mb-1">Drag QR Code ke posisi yang diinginkan</li>
                    <li class="mb-1">Klik <strong>Simpan Posisi ke Template</strong></li>
                    <li>Pilih template ini saat generate unit di halaman kategori</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- Daftar Template --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-collection me-2 text-primary"></i>
                    Daftar Template
                </span>
                <span class="badge bg-primary">{{ $templates->count() }} template</span>
            </div>
            <div class="card-body">
                @if($templates->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-image" style="font-size:48px; color:#dee2e6;"></i>
                        <h6 class="mt-3">Belum ada template</h6>
                        <p style="font-size:13px;">
                            Upload template label produk di form sebelah kiri.
                        </p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($templates as $template)
                        <div class="col-md-6">
                            <div class="card border h-100" style="border-radius:10px; overflow:hidden;">

                                {{-- Preview Gambar --}}
                                <div style="height:160px; overflow:hidden; background:#f8f9fa; position:relative;">
                                    <img src="{{ asset('storage/' . $template->file_template) }}"
                                         alt="{{ $template->nama_template }}"
                                         style="width:100%; height:100%; object-fit:cover;"
                                         onerror="this.parentElement.innerHTML='<div style=\'text-align:center;padding:40px;color:#ccc;\'><i class=\'bi bi-image\' style=\'font-size:32px;\'></i><br>Preview tidak tersedia</div>'">

                                    {{-- Badge nomor --}}
                                    <span class="badge bg-dark"
                                          style="position:absolute; top:8px; left:8px; font-size:10px;">
                                        #{{ $loop->iteration }}
                                    </span>

                                    {{-- Badge posisi tersimpan --}}
                                    @if($template->qr_x != 20 || $template->qr_y != 20 || $template->qr_size != 150)
                                        <span class="badge bg-success"
                                              style="position:absolute; top:8px; right:8px; font-size:10px;">
                                            <i class="bi bi-pin-fill me-1"></i>Posisi Tersimpan
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark"
                                              style="position:absolute; top:8px; right:8px; font-size:10px;">
                                            <i class="bi bi-exclamation me-1"></i>Belum diatur
                                        </span>
                                    @endif
                                </div>

                                <div class="card-body py-2 px-3">
                                    <div class="fw-semibold" style="font-size:14px;">
                                        {{ $template->nama_template }}
                                    </div>
                                    <div style="font-size:11px; color:#999;">
                                        {{ $template->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    @if($template->qr_x != 20 || $template->qr_y != 20 || $template->qr_size != 150)
                                    <div style="font-size:11px; color:#28a745; margin-top:3px;">
                                        <i class="bi bi-qr-code me-1"></i>
                                        QR: {{ $template->qr_size }}px · x:{{ $template->qr_x }} y:{{ $template->qr_y }}
                                    </div>
                                    @endif
                                </div>

                                <div class="card-footer bg-white py-2 px-3">
                                    <div class="d-flex gap-2">
                                        {{-- ✅ TOMBOL EDIT POSISI QR --}}
                                        @php
                                            // Ambil produk pertama yang ada untuk editor
                                            $anyProduct = \App\Models\Product::whereNotNull('qr_code')->first();
                                        @endphp
                                        @if($anyProduct)
                                            <a href="{{ route('labels.editor', $anyProduct->id) }}?template={{ $template->id }}"
                                               class="btn btn-primary btn-sm flex-fill">
                                                <i class="bi bi-pencil-square me-1"></i>Edit Posisi QR
                                            </a>
                                        @else
                                            <span class="btn btn-secondary btn-sm flex-fill disabled"
                                                  title="Generate unit produk dulu">
                                                <i class="bi bi-pencil-square me-1"></i>Edit Posisi QR
                                            </span>
                                        @endif

                                        <a href="{{ asset('storage/' . $template->file_template) }}"
                                           target="_blank"
                                           class="btn btn-outline-secondary btn-sm"
                                           title="Lihat template">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <form action="{{ route('labels.destroy', $template->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus template ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger btn-sm"
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewPlaceholder').style.display = 'none';
                const img = document.getElementById('previewImg');
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush