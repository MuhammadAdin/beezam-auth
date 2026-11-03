@extends('layouts.app')

@section('title', 'Bulk Print Label - ' . $category->nama_kategori)
@section('page-title', 'Bulk Print Label QR')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════
   PRINT
   Strategi: semua elemen layout disembunyikan display:none
   kecuali #printOnly yang membungkus #printArea.
   #printArea tetap in-flow (tidak fixed/absolute) sehingga
   gambar template ter-render dengan ukuran yang sama
   dan posisi QR (dalam %) tidak meleset.
══════════════════════════════════════════════════════ */
@media print {

    /* Reset margin/padding halaman */
    @page { margin: 6mm; }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
    }

    /* Sembunyikan semua elemen langsung di body */
    body > * { display: none !important; }

    /* Kecuali #printOnly — wrapper khusus print */
    body > #printOnly { display: block !important; }

    /* printArea: grid in-flow, lebar 100% halaman
       --print-cols di-set di #printOnly oleh JS sebelum print */
    #printOnly #printArea {
        display: grid !important;
        grid-template-columns: repeat(var(--print-cols, 3), 1fr) !important;
        gap: 4mm !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        box-sizing: border-box !important;
    }

    .label-cell {
        break-inside: avoid !important;
        page-break-inside: avoid !important;
        width: 100% !important;
    }

    /* Label item: in-flow, biarkan gambar atur tinggi */
    .label-item {
        position: relative !important;
        max-height: none !important;
        height: auto !important;
        width: 100% !important;
        overflow: hidden !important;
        border-radius: 3px !important;
        display: block !important;
    }

    .label-item img.template-bg {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        object-fit: fill !important;
    }

    .label-default-item {
        width: 100% !important;
        display: block !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* QR overlay — posisi sudah dalam %, tetap akurat */
    .qr-on-label {
        position: absolute !important;
        line-height: 0 !important;
    }
    .qr-on-label img { display: block !important; }

    /* Sembunyikan checkbox & teks di dalam printArea */
    .no-print,
    .unit-checkbox,
    .cell-label { display: none !important; }
}

/* ══════════════════════════════════════
   #printOnly — hanya tampil saat print
══════════════════════════════════════ */
#printOnly { display: none; }

/* ══════════════════════════════════════
   PREVIEW LAYAR
══════════════════════════════════════ */
.label-item {
    position: relative;
    display: block;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    max-height: 220px;
}
.label-item img.template-bg {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: fill;
}
.qr-on-label { position: absolute; line-height: 0; }

.label-default-item {
    width: 100%;
    min-height: 140px;
    background: linear-gradient(135deg, #1a73e8, #0d47a1);
    border-radius: 8px;
    padding: 12px;
    color: white;
    position: relative;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}

#printArea {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    padding: 10px;
}

/* ── Template selector ── */
.template-option {
    cursor: pointer;
    border: 2px solid transparent;
    border-radius: 8px;
    overflow: hidden;
    transition: border-color 0.2s;
    margin-bottom: 8px;
}
.template-option.active { border-color: #1a73e8; }
.template-option img { width: 100%; height: 70px; object-fit: cover; display: block; }
.template-option .opt-label {
    font-size: 11px; text-align: center;
    padding: 3px; color: #444; background: #f8f9fa;
}
.template-option .opt-badge {
    font-size: 9px; text-align: center;
    padding: 2px; color: #28a745; background: #f8f9fa;
}

/* ══════════════════════════════════════
   MOBILE RESPONSIVE
══════════════════════════════════════ */
@media (max-width: 991.98px) {
    .bulk-left {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 8px;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        flex-wrap: nowrap;
        align-items: flex-start;
    }
    .bulk-left::-webkit-scrollbar { display: none; }
    .bulk-left .card {
        flex: 0 0 190px;
        scroll-snap-align: start;
        margin-bottom: 0 !important;
        min-width: 150px;
    }
    .bulk-left .card-info { flex: 0 0 200px; }
    .bulk-left .card-body.p-2 { max-height: 160px; overflow-y: auto; }
    #mobileActionBar { display: flex !important; }
    #printArea { grid-template-columns: repeat(2, 1fr) !important; gap: 8px; padding: 8px; }
    .label-item { max-height: 160px; }
    .label-default-item { min-height: 100px; }
}

@media (max-width: 575.98px) {
    #printArea { gap: 6px; padding: 6px; }
    .label-item { max-height: 130px; }
    .label-default-item { min-height: 90px; padding: 8px; }
}

/* ── Mobile Action Bar ── */
#mobileActionBar {
    display: none;
    position: fixed;
    bottom: 0; left: 0; right: 0;
    z-index: 1020;
    background: #fff;
    border-top: 1px solid #dee2e6;
    padding: 10px 16px max(10px, env(safe-area-inset-bottom));
    gap: 10px;
    box-shadow: 0 -4px 16px rgba(0,0,0,0.1);
}

@media (max-width: 991.98px) {
    .bulk-page-wrap { padding-bottom: 80px !important; }
}
</style>
@endpush

@section('content')

<script>
const TEMPLATES = {
    @foreach($templates as $tmpl)
    "{{ $tmpl->id }}": {
        id: "{{ $tmpl->id }}",
        nama: "{{ $tmpl->nama_template }}",
        url: "{{ asset('storage/' . $tmpl->file_template) }}",
        qr_x: {{ $tmpl->qr_x ?? 20 }},
        qr_y: {{ $tmpl->qr_y ?? 20 }},
        qr_size: {{ $tmpl->qr_size ?? 150 }},
    },
    @endforeach
};
const CATEGORY_TEMPLATE_ID = "{{ $category->label_template_id ?? '' }}";
</script>

<div class="bulk-page-wrap">

<nav aria-label="breadcrumb" class="mb-3 no-print">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Kategori Produk</a></li>
        <li class="breadcrumb-item"><a href="{{ route('categories.show', $category->id) }}">{{ $category->nama_kategori }}</a></li>
        <li class="breadcrumb-item active">Bulk Print Label</li>
    </ol>
</nav>

<div class="row g-3">

    {{-- Panel Kiri --}}
    <div class="col-lg-3 no-print">
        <div class="bulk-left">

            <div class="card mb-3 card-info">
                <div class="card-header bg-primary text-white" style="font-size:13px;">
                    <i class="bi bi-tags me-1"></i> Info Kategori
                </div>
                <div class="card-body" style="font-size:13px;">
                    @if($category->foto_produk)
                        <img src="{{ asset('storage/' . $category->foto_produk) }}"
                             style="width:100%;height:80px;object-fit:cover;border-radius:8px;margin-bottom:8px;">
                    @endif
                    <div class="fw-bold">{{ $category->nama_kategori }}</div>
                    <div class="text-muted small">Batch: {{ $category->batch_produk }}</div>
                    <div class="text-muted small">Produksi: {{ $category->tanggal_produksi->format('d/m/Y') }}</div>
                    <div class="mt-2">
                        <span class="badge bg-primary">{{ $products->count() }} unit dengan QR</span>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header" style="font-size:13px;">
                    <i class="bi bi-image me-1 text-primary"></i> Template Label
                </div>
                <div class="card-body p-2">
                    <div class="template-option" id="opt-default" onclick="setTemplate('default')">
                        <div style="height:44px;background:linear-gradient(135deg,#1a73e8,#0d47a1);
                                    display:flex;align-items:center;justify-content:center;
                                    color:white;font-size:12px;border-radius:6px 6px 0 0;">
                            Label Default
                        </div>
                        <div class="opt-label">Tanpa template gambar</div>
                    </div>
                    @if($templates->isEmpty())
                        <div class="text-muted text-center mt-2" style="font-size:12px;">
                            Belum ada template. <a href="{{ route('labels.index') }}">Upload dulu</a>
                        </div>
                    @else
                        @foreach($templates as $tmpl)
                        <div class="template-option" id="opt-{{ $tmpl->id }}"
                             onclick="setTemplate('{{ $tmpl->id }}')">
                            <img src="{{ asset('storage/' . $tmpl->file_template) }}" alt="{{ $tmpl->nama_template }}">
                            <div class="opt-label">{{ $tmpl->nama_template }}</div>
                            @if($tmpl->hasSavedPosition())
                            <div class="opt-badge">
                                <i class="bi bi-pin-fill"></i>
                                QR: {{ $tmpl->qr_size }}px · x:{{ $tmpl->qr_x }} y:{{ $tmpl->qr_y }}
                            </div>
                            @else
                            <div style="font-size:9px;text-align:center;color:#e67e22;background:#fff3cd;padding:2px;">
                                <i class="bi bi-exclamation-triangle"></i> Posisi QR belum diatur
                            </div>
                            @endif
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="card mb-3" id="qrSizeCard">
                <div class="card-header" style="font-size:13px;">
                    <i class="bi bi-qr-code me-1 text-primary"></i> Ukuran QR
                </div>
                <div class="card-body">
                    <input type="range" class="form-range" id="qrSizeRange"
                           min="40" max="200" value="80"
                           oninput="updateQrSizeDefault(this.value)">
                    <div class="text-center">
                        <span class="fw-bold text-primary" id="qrSizeLabel">80px</span>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header" style="font-size:13px;">
                    <i class="bi bi-check2-square me-1 text-primary"></i> Pilih Unit
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 mb-2">
                        <button class="btn btn-outline-primary btn-sm flex-fill" onclick="selectAll()">Pilih Semua</button>
                        <button class="btn btn-outline-secondary btn-sm flex-fill" onclick="deselectAll()">Hapus</button>
                    </div>
                    <div class="text-center" style="font-size:12px;color:#666;">
                        <span id="selectedCount">{{ $products->count() }}</span> / {{ $products->count() }} dipilih
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body py-2">
                    <label style="font-size:12px;" class="fw-semibold mb-1 d-block">Label per baris:</label>
                    <select class="form-select form-select-sm" id="colsSelect" onchange="updateColumns(this.value)">
                        <option value="2">2</option>
                        <option value="3" selected>3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
            </div>

            <div class="card d-none d-lg-block">
                <div class="card-body">
                    <button onclick="doPrint()" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-printer me-1"></i> Print Label
                    </button>
                    <a href="{{ route('categories.show', $category->id) }}"
                       class="btn btn-outline-secondary w-100 btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- Panel Kanan --}}
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center no-print">
                <span style="font-size:14px;">
                    <i class="bi bi-grid me-2 text-primary"></i>
                    Preview Label ({{ $products->count() }} unit)
                </span>
                <span class="badge bg-secondary" id="templateBadge">Label Default</span>
            </div>
            <div class="card-body p-2">
                @if($products->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-qr-code" style="font-size:48px;color:#dee2e6;"></i>
                        <h6 class="mt-3">Tidak ada unit dengan QR Code</h6>
                        <a href="{{ route('categories.show', $category->id) }}"
                           class="btn btn-primary btn-sm mt-2">Kembali ke Kategori</a>
                    </div>
                @else
                    <div id="printArea">
                        @foreach($products as $i => $product)
                        <div class="label-cell" id="cell-{{ $product->id }}">
                            <div class="no-print mb-1 d-flex align-items-center gap-1">
                                <input type="checkbox"
                                       class="unit-checkbox form-check-input"
                                       data-id="{{ $product->id }}"
                                       checked
                                       onchange="toggleUnit({{ $product->id }}, this.checked)">
                                <label class="cell-label" style="font-size:10px;color:#666;">
                                    #{{ $i + 1 }} {{ Str::limit($product->kode_produk, 16) }}
                                </label>
                            </div>
                            <div class="label-item" id="label-{{ $product->id }}">
                                <div class="label-default-item" id="default-{{ $product->id }}">
                                    <div style="border-bottom:1px solid rgba(255,255,255,0.3);padding-bottom:6px;margin-bottom:6px;">
                                        <div style="font-size:8px;opacity:0.7;text-transform:uppercase;">Autentikasi Produk</div>
                                        <div style="font-size:12px;font-weight:700;">{{ $category->nama_kategori }}</div>
                                    </div>
                                    <div style="font-size:9px;line-height:1.7;padding-right:88px;">
                                        <div><span style="opacity:0.7;">Batch:</span> <strong>{{ $category->batch_produk }}</strong></div>
                                        <div><span style="opacity:0.7;">Produksi:</span> <strong>{{ $category->tanggal_produksi->format('d/m/Y') }}</strong></div>
                                        <div><span style="opacity:0.7;">Kode:</span> <strong style="font-size:7px;">{{ $product->kode_produk }}</strong></div>
                                    </div>
                                    <div style="position:absolute;right:8px;bottom:8px;background:white;padding:3px;border-radius:4px;">
                                        <img src="{{ asset('storage/' . $product->qr_code) }}"
                                             class="qr-default-img"
                                             style="width:80px;height:80px;display:block;">
                                    </div>
                                    <div style="font-size:7px;opacity:0.5;margin-top:4px;">HMAC-SHA256 Protected</div>
                                </div>
                                <img class="template-bg"
                                     id="tmplimg-{{ $product->id }}"
                                     src="" alt=""
                                     style="display:none;width:100%;height:auto;">
                                <div class="qr-on-label" id="qr-{{ $product->id }}" style="display:none;">
                                    <img src="{{ asset('storage/' . $product->qr_code) }}"
                                         class="qr-tmpl-img" style="display:block;">
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
</div>

{{-- Mobile Action Bar --}}
<div id="mobileActionBar" class="no-print" style="display:none;">
    <a href="{{ route('categories.show', $category->id) }}"
       class="btn btn-outline-secondary flex-shrink-0">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div class="text-center flex-grow-1" style="font-size:12px;color:#666;line-height:1.3;">
        <div><span id="selectedCountMobile">{{ $products->count() }}</span>/{{ $products->count() }} dipilih</div>
        <div style="font-size:10px;" id="templateBadgeMobile">Label Default</div>
    </div>
    <button onclick="doPrint()" class="btn btn-primary flex-shrink-0">
        <i class="bi bi-printer me-1"></i> Print
    </button>
</div>

@endsection

@push('scripts')
<script>
let currentTemplateId = 'default';
let defaultQrSize     = 80;
let currentColumns    = 3;

window.addEventListener('DOMContentLoaded', () => {
    // Buat #printOnly dan append langsung ke document.body
    // agar selector CSS "body > #printOnly" selalu match
    const printOnly = document.createElement('div');
    printOnly.id = 'printOnly';
    document.body.appendChild(printOnly);

    if (window.innerWidth < 992) {
        currentColumns = 2;
        document.getElementById('colsSelect').value = '2';
    }
    updateColumns(currentColumns);
    if (CATEGORY_TEMPLATE_ID && TEMPLATES[CATEGORY_TEMPLATE_ID]) {
        setTemplate(CATEGORY_TEMPLATE_ID);
    } else {
        setTemplate('default');
    }
    updateSelectedCount();
});

function setTemplate(id) {
    currentTemplateId = id;
    document.querySelectorAll('.template-option').forEach(el => el.classList.remove('active'));
    const optEl = document.getElementById('opt-' + id);
    if (optEl) optEl.classList.add('active');

    const badge       = document.getElementById('templateBadge');
    const badgeMobile = document.getElementById('templateBadgeMobile');

    if (id === 'default') {
        badge.textContent = 'Label Default';
        badge.className   = 'badge bg-secondary';
        if (badgeMobile) badgeMobile.textContent = 'Label Default';
        document.getElementById('qrSizeCard').style.display = '';
        document.querySelectorAll('[id^="label-"]').forEach(wrap => {
            showDefault(wrap.id.replace('label-', ''));
        });
    } else {
        const tmpl = TEMPLATES[id];
        if (!tmpl) return;
        badge.textContent = tmpl.nama + ' · QR ' + tmpl.qr_size + 'px';
        badge.className   = 'badge bg-success';
        if (badgeMobile) badgeMobile.textContent = tmpl.nama;
        document.getElementById('qrSizeCard').style.display = 'none';
        document.querySelectorAll('[id^="label-"]').forEach(wrap => {
            showTemplate(wrap.id.replace('label-', ''), tmpl);
        });
    }
}

function showDefault(pid) {
    document.getElementById('default-' + pid).style.display = 'block';
    document.getElementById('tmplimg-' + pid).style.display = 'none';
    const qrEl = document.getElementById('qr-' + pid);
    if (qrEl) qrEl.style.display = 'none';
}

function showTemplate(pid, tmpl) {
    document.getElementById('default-' + pid).style.display = 'none';
    const tmplImg = document.getElementById('tmplimg-' + pid);
    tmplImg.style.display = 'block';
    const qrEl  = document.getElementById('qr-' + pid);
    const qrImg = qrEl.querySelector('.qr-tmpl-img');
    qrEl.style.display  = 'block';
    qrEl.style.position = 'absolute';

    function applyQrPercent() {
        const natW = tmplImg.naturalWidth  || 800;
        const natH = tmplImg.naturalHeight || 400;
        qrEl.style.left         = (tmpl.qr_x    / natW * 100).toFixed(4) + '%';
        qrEl.style.top          = (tmpl.qr_y    / natH * 100).toFixed(4) + '%';
        qrImg.style.width       = (tmpl.qr_size / natW * 100).toFixed(4) + '%';
        qrImg.style.height      = 'auto';
        qrImg.style.aspectRatio = '1 / 1';
    }

    if (tmplImg.naturalWidth > 0) { applyQrPercent(); }
    else { tmplImg.src = tmpl.url; tmplImg.onload = applyQrPercent; }
}

function reapplyAllQrPositions() {
    if (currentTemplateId === 'default') return;
    const tmpl = TEMPLATES[currentTemplateId];
    if (!tmpl) return;
    document.querySelectorAll('[id^="tmplimg-"]').forEach(tmplImg => {
        if (tmplImg.style.display === 'none') return;
        showTemplate(tmplImg.id.replace('tmplimg-', ''), tmpl);
    });
}

function updateColumns(val) {
    currentColumns = parseInt(val);
    const printArea = document.getElementById('printArea');
    printArea.style.gridTemplateColumns = 'repeat(' + currentColumns + ', 1fr)';
    printArea.style.setProperty('--print-cols', currentColumns);
    reapplyAllQrPositions();
}

function updateQrSizeDefault(val) {
    defaultQrSize = parseInt(val);
    document.getElementById('qrSizeLabel').textContent = val + 'px';
    document.querySelectorAll('.qr-default-img').forEach(img => {
        img.style.width = val + 'px';
        img.style.height = val + 'px';
    });
}

function selectAll() {
    document.querySelectorAll('.unit-checkbox').forEach(cb => {
        cb.checked = true;
        document.getElementById('cell-' + cb.dataset.id).style.display = '';
    });
    updateSelectedCount();
}

function deselectAll() {
    document.querySelectorAll('.unit-checkbox').forEach(cb => {
        cb.checked = false;
        document.getElementById('cell-' + cb.dataset.id).style.display = 'none';
    });
    updateSelectedCount();
}

function toggleUnit(productId, checked) {
    const cell = document.getElementById('cell-' + productId);
    if (cell) cell.style.display = checked ? '' : 'none';
    updateSelectedCount();
}

function updateSelectedCount() {
    const n = document.querySelectorAll('.unit-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = n;
    const mob = document.getElementById('selectedCountMobile');
    if (mob) mob.textContent = n;
}

// ── Print ─────────────────────────────────────────────────────────────────
function doPrint() {
    // Kumpulkan unit yang dipilih
    const selectedIds = [];
    document.querySelectorAll('.unit-checkbox:checked').forEach(cb => {
        selectedIds.push(cb.dataset.id);
    });

    const isTemplate = currentTemplateId !== 'default';
    const tmpl = isTemplate ? TEMPLATES[currentTemplateId] : null;

    // Bangun HTML print dari data — tidak bergantung pada DOM state/cloneNode
    // Sehingga display setiap elemen 100% terkontrol
    let html = `<div id="printArea" style="
        display:grid;
        grid-template-columns:repeat(${currentColumns},1fr);
        gap:4mm;
        width:100%;
        padding:0;
        margin:0;
        box-sizing:border-box;
    ">`;

    selectedIds.forEach(pid => {
        const labelEl   = document.getElementById('label-' + pid);
        if (!labelEl) return;

        html += `<div class="label-cell" style="break-inside:avoid;page-break-inside:avoid;width:100%;">`;
        html += `<div class="label-item" style="position:relative;display:block;width:100%;overflow:hidden;border-radius:3px;max-height:none;">`;

        if (!isTemplate) {
            // Mode default — ambil innerHTML label-default-item dari DOM asli
            const defEl = document.getElementById('default-' + pid);
            if (defEl) {
                html += `<div class="label-default-item" style="display:block;width:100%;-webkit-print-color-adjust:exact;print-color-adjust:exact;">`;
                html += defEl.innerHTML;
                html += `</div>`;
            }
        } else {
            // Mode template — gambar + QR overlay dengan posisi %
            const tmplImgEl = document.getElementById('tmplimg-' + pid);
            const qrEl      = document.getElementById('qr-'      + pid);
            const qrImgEl   = qrEl ? qrEl.querySelector('.qr-tmpl-img') : null;

            const imgSrc = tmplImgEl ? tmplImgEl.src : '';

            // Hitung posisi % dari data template (bukan dari DOM)
            const natW = (tmplImgEl && tmplImgEl.naturalWidth)  || 800;
            const natH = (tmplImgEl && tmplImgEl.naturalHeight) || 400;
            const left = (tmpl.qr_x    / natW * 100).toFixed(4) + '%';
            const top  = (tmpl.qr_y    / natH * 100).toFixed(4) + '%';
            const size = (tmpl.qr_size / natW * 100).toFixed(4) + '%';

            // QR src — ambil dari elemen asli
            const qrSrc = qrImgEl ? qrImgEl.src : '';

            html += `<img class="template-bg" src="${imgSrc}" style="display:block;width:100%;height:auto;object-fit:fill;">`;
            html += `<div class="qr-on-label" style="position:absolute;line-height:0;left:${left};top:${top};display:block;">`;
            html += `<img class="qr-tmpl-img" src="${qrSrc}" style="display:block;width:${size};height:auto;aspect-ratio:1/1;">`;
            html += `</div>`;
        }

        html += `</div>`; // .label-item
        html += `</div>`; // .label-cell
    });

    html += `</div>`; // #printArea

    // Pasang ke #printOnly (sudah jadi child langsung body)
    const printOnly = document.getElementById('printOnly');
    printOnly.innerHTML = html;
    printOnly.style.setProperty('--print-cols', String(currentColumns));

    // Tunggu 2 frame agar browser render gambar sebelum print
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            window.print();

            // Restore setelah print
            setTimeout(() => {
                printOnly.innerHTML = '';
                printOnly.style.removeProperty('--print-cols');
            }, 1500);
        });
    });
}
</script>
@endpush