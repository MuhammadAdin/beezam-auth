@extends('layouts.app')

@section('title', 'Print Label')
@section('page-title', 'Print Label Produk')

@push('styles')
<style>
    /* Style khusus untuk tampilan print */
    @media print {
        /* Sembunyikan semua kecuali area label */
        body * { visibility: hidden; }
        #labelPrintArea, #labelPrintArea * { visibility: visible; }
        #labelPrintArea {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
        }
        .no-print { display: none !important; }
    }

    /* Style label */
    .label-wrapper {
        position: relative;
        display: inline-block;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }

    .label-template-bg {
        display: block;
        max-width: 100%;
        height: auto;
    }

    /* Posisi & ukuran QR di template diatur JS (persen dari ukuran gambar template) */
    .label-qr-overlay {
        position: absolute;
        line-height: 0;
    }

    .label-qr-overlay img {
        display: block;
        aspect-ratio: 1 / 1;
        height: auto;
    }

    /* Label tanpa template */
    .label-default {
        width: 350px;
        min-height: 200px;
        background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
        border-radius: 12px;
        padding: 20px;
        color: white;
        position: relative;
    }
</style>
@endpush

@section('content')

<nav aria-label="breadcrumb" class="mb-3 no-print">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('products.index') }}">Data Produk</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('products.show', $product->id) }}">Detail</a>
        </li>
        <li class="breadcrumb-item active">Print Label</li>
    </ol>
</nav>

<div class="row g-3">

    {{-- Panel Kiri: Pengaturan Label --}}
    <div class="col-lg-4 no-print">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-gear me-2 text-primary"></i>
                Pengaturan Label
            </div>
            <div class="card-body">

                {{-- Info Produk --}}
                <div class="p-3 rounded mb-3" style="background:#f0f4f8;">
                    <div style="font-size:11px; color:#999; font-weight:600;
                                text-transform:uppercase; margin-bottom:8px;">
                        Produk:
                    </div>
                    <div class="fw-bold" style="font-size:15px;">
                        {{ $product->category->nama_kategori }}
                    </div>
                    <div style="font-size:12px; color:#666;">
                        {{ $product->kode_produk }}
                    </div>
                    <div style="font-size:12px; color:#666;">
                        Batch: {{ $product->category->batch_produk }}
                    </div>
                </div>

                {{-- Pilih Template --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:14px;">
                        Pilih Template Label
                    </label>

                    {{-- Opsi: Tanpa Template (default) --}}
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio"
                               name="templatePilihan" id="templateDefault"
                               value="default" checked
                               onchange="gantiTemplate('default')">
                        <label class="form-check-label" for="templateDefault"
                               style="font-size:13px;">
                            <i class="bi bi-palette me-1"></i>
                            Label Default (tanpa template)
                        </label>
                    </div>

                    {{-- Opsi: Template yang diupload --}}
                    @if($templates->isEmpty())
                        <div class="text-muted p-2 rounded"
                             style="background:#f8f9fa; font-size:12px;">
                            <i class="bi bi-info-circle me-1"></i>
                            Belum ada template.
                            <a href="{{ route('labels.index') }}">Upload template</a>
                        </div>
                    @else
                        @foreach($templates as $template)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio"
                                   name="templatePilihan"
                                   id="template{{ $template->id }}"
                                   value="{{ $template->id }}"
                                   onchange="gantiTemplate('{{ $template->id }}')">
                            <label class="form-check-label d-flex align-items-center gap-2"
                                   for="template{{ $template->id }}"
                                   style="font-size:13px; cursor:pointer;">
                                <img src="{{ asset('storage/' . $template->file_template) }}"
                                     style="width:40px; height:30px;
                                            object-fit:cover; border-radius:4px;
                                            border:1px solid #dee2e6;">
                                {{ $template->nama_template }}
                            </label>
                        </div>
                        @endforeach
                    @endif
                </div>

                {{-- Pengaturan Ukuran QR --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:14px;">
                        Ukuran QR Code
                    </label>
                    <select class="form-select form-select-sm"
                            onchange="ubahUkuranQr(this.value)">
                        <option value="80">Kecil (80px)</option>
                        <option value="100" selected>Sedang (100px)</option>
                        <option value="130">Besar (130px)</option>
                        <option value="160">Sangat Besar (160px)</option>
                    </select>
                </div>

                {{-- Tombol Print --}}
                <button onclick="window.print()"
                        class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-printer me-1"></i> Print Label
                </button>

                <a href="{{ route('products.show', $product->id) }}"
                   class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>

            </div>
        </div>
    </div>

    {{-- Panel Kanan: Preview Label --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-eye me-2 text-primary"></i>
                    Preview Label
                </span>
                <span class="badge bg-secondary no-print">
                    Preview — Hasil print mungkin sedikit berbeda
                </span>
            </div>
            <div class="card-body text-center py-4" id="labelPrintArea">

                {{-- ===== LABEL DEFAULT ===== --}}
                <div id="labelDefault" class="label-wrapper">
                    <div class="label-default">

                        {{-- Header label --}}
                        <div style="border-bottom:1px solid rgba(255,255,255,0.3);
                                    padding-bottom:10px; margin-bottom:12px;">
                            <div style="font-size:10px; opacity:0.7;
                                        text-transform:uppercase; letter-spacing:1px;">
                                Autentikasi Produk
                            </div>
                            <div style="font-size:18px; font-weight:700; line-height:1.2;">
                                {{ $product->category->nama_kategori }}
                            </div>
                        </div>

                        {{-- Info produk --}}
                        <div style="font-size:12px; line-height:1.8; flex:1;">
                            <div>
                                <span style="opacity:0.7;">Batch:</span>
                                <strong>{{ $product->category->batch_produk }}</strong>
                            </div>
                            <div>
                                <span style="opacity:0.7;">Produksi:</span>
                                <strong>
                                    {{ $product->category->tanggal_produksi->format('d/m/Y') }}
                                </strong>
                            </div>
                            <div>
                                <span style="opacity:0.7;">Kode:</span>
                                <strong style="font-size:10px;">
                                    {{ $product->kode_produk }}
                                </strong>
                            </div>
                        </div>

                        {{-- QR Code pojok kanan bawah --}}
                        @if($product->qr_code && file_exists(public_path('storage/' . $product->qr_code)))
                            <div style="position:absolute; bottom:15px; right:15px;
                                        background:white; padding:5px;
                                        border-radius:6px;">
                                <img id="qrImg"
                                     src="{{ asset('storage/' . $product->qr_code) }}"
                                     style="width:100px; height:100px; display:block;">
                                <div style="font-size:8px; color:#333;
                                            text-align:center; margin-top:2px;">
                                    Scan untuk verifikasi
                                </div>
                            </div>
                            {{-- Padding kanan agar teks tidak tertutup QR --}}
                            <div style="padding-right:120px;"></div>
                        @else
                            <div style="position:absolute; bottom:15px; right:15px;
                                        background:rgba(255,255,255,0.2);
                                        border-radius:6px; padding:10px;
                                        font-size:11px; text-align:center;">
                                <i class="bi bi-qr-code"
                                   style="font-size:24px;"></i><br>
                                QR tidak tersedia
                            </div>
                        @endif

                        {{-- Footer --}}
                        <div style="font-size:9px; opacity:0.6; margin-top:8px;">
                            Produk ini dilindungi teknologi HMAC-SHA256
                        </div>

                    </div>
                </div>

                {{-- ===== LABEL DENGAN TEMPLATE ===== --}}
                <div id="labelTemplate"
                     class="label-wrapper"
                     style="display:none; max-width:100%;">
                    <img id="templateBg"
                         src=""
                         class="label-template-bg"
                         style="max-width:100%; height:auto;">
                    <div class="label-qr-overlay" id="qrOverlay">
                        @if($product->qr_code && file_exists(public_path('storage/' . $product->qr_code)))
                            <img id="qrImgTemplate"
                                 src="{{ asset('storage/' . $product->qr_code) }}">
                        @else
                            <div style="width:100px; height:100px;
                                        display:flex; align-items:center;
                                        justify-content:center;
                                        background:#f0f0f0; color:#999;
                                        font-size:10px; text-align:center;">
                                QR tidak tersedia
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Keterangan di bawah preview --}}
                <div class="mt-3 no-print">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Klik <strong>Print Label</strong> untuk mencetak.
                        QR Code akan langsung bisa discan konsumen.
                    </small>
                </div>

            </div>
        </div>

        {{-- Info QR & Verifikasi --}}
        <div class="card mt-3 no-print">
            <div class="card-body py-2">
                <div class="row text-center">
                    <div class="col-md-4 border-end">
                        <div style="font-size:11px; color:#999;">URL dalam QR Code</div>
                        <div style="font-size:12px; font-weight:600; color:#1a73e8;
                                    word-break:break-all;">
                            {{ url('/verify/' . $product->kode_produk) }}
                        </div>
                    </div>
                    <div class="col-md-4 border-end">
                        <div style="font-size:11px; color:#999;">Total Scan</div>
                        <div style="font-size:18px; font-weight:700; color:#1a73e8;">
                            {{ $product->total_scan }}x
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:11px; color:#999;">Status</div>
                        <div style="font-weight:700;">
                            @if($product->status_produk == 'ASLI')
                                <span class="text-success">✅ ASLI</span>
                            @elseif($product->status_produk == 'MENCURIGAKAN')
                                <span class="text-warning">⚠️ MENCURIGAKAN</span>
                            @else
                                <span class="text-secondary">— BELUM DISCAN</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    // Data template (termasuk posisi QR yang disimpan dari Label Editor)
    const TEMPLATES = {
        @foreach($templates as $t)
        "{{ $t->id }}": {
            url: @json(asset('storage/' . $t->file_template)),
            qr_x: {{ (int) ($t->qr_x ?? 20) }},
            qr_y: {{ (int) ($t->qr_y ?? 20) }},
            qr_size: {{ (int) ($t->qr_size ?? 150) }},
        },
        @endforeach
    };
    const CATEGORY_TEMPLATE_ID = "{{ $product->category->label_template_id ?? '' }}";

    let aktifId = null;

    function gantiTemplate(id) {
        const labelDefault  = document.getElementById('labelDefault');
        const labelTemplate = document.getElementById('labelTemplate');
        const templateBg    = document.getElementById('templateBg');

        if (id === 'default' || !TEMPLATES[id]) {
            aktifId = null;
            labelDefault.style.display  = 'inline-block';
            labelTemplate.style.display = 'none';
            return;
        }

        aktifId = id;
        const t = TEMPLATES[id];
        labelDefault.style.display  = 'none';
        labelTemplate.style.display = 'inline-block';

        templateBg.onload = terapkanPosisiQr;
        templateBg.src = t.url;
        if (templateBg.complete && templateBg.naturalWidth > 0) terapkanPosisiQr();
    }

    // Posisi & ukuran QR = persen dari ukuran asli gambar (sama dengan Editor & Cetak Massal)
    function terapkanPosisiQr(ukuranOverride) {
        if (!aktifId) return;
        const t    = TEMPLATES[aktifId];
        const bg   = document.getElementById('templateBg');
        const ov   = document.getElementById('qrOverlay');
        const img  = document.getElementById('qrImgTemplate');
        if (!ov || !img) return;

        const natW = bg.naturalWidth  || 800;
        const natH = bg.naturalHeight || 400;
        const size = (typeof ukuranOverride === 'number') ? ukuranOverride : t.qr_size;

        ov.style.left   = (t.qr_x / natW * 100).toFixed(4) + '%';
        ov.style.top    = (t.qr_y / natH * 100).toFixed(4) + '%';
        // lebar kotak QR = persen dari lebar label; gambar mengisi penuh kotaknya
        ov.style.width  = (size   / natW * 100).toFixed(4) + '%';
        img.style.width = '100%';
    }

    // Ukuran QR: label default dalam px; label template dalam px ukuran asli gambar
    function ubahUkuranQr(size) {
        size = parseInt(size);
        const qrImg = document.getElementById('qrImg');
        if (qrImg) {
            qrImg.style.width  = size + 'px';
            qrImg.style.height = size + 'px';
        }
        if (aktifId) {
            // skala dari 100px (pilihan "Sedang") terhadap ukuran tersimpan template
            terapkanPosisiQr(Math.round(TEMPLATES[aktifId].qr_size * size / 100));
        }
    }

    // Saat halaman dibuka: pakai template kategori (atau yang tercentang) lebih dulu
    document.addEventListener('DOMContentLoaded', function () {
        const dipilih = document.querySelector('input[name="templatePilihan"]:checked');
        let id = (dipilih && dipilih.value !== 'default') ? dipilih.value : CATEGORY_TEMPLATE_ID;
        if (id && TEMPLATES[id]) {
            const radio = document.getElementById('template' + id);
            if (radio) radio.checked = true;
            gantiTemplate(id);
        } else {
            gantiTemplate('default');
        }
    });
</script>
@endpush