@extends('layouts.app')

@section('title', 'Label Editor')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            <i class="bi bi-pencil-square me-2"></i>Label Editor
            <small class="text-muted fs-6">— {{ $product->kode_produk }}</small>
        </h4>
        <a href="{{ route('labels.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    {{-- Alert simpan posisi --}}
    <div id="alertSimpan" class="alert alert-success alert-dismissible d-none mb-3">
        <i class="bi bi-check-circle me-2"></i>
        <span id="alertMsg">Posisi QR berhasil disimpan ke template!</span>
        <button type="button" class="btn-close" onclick="this.parentElement.classList.add('d-none')"></button>
    </div>
    <div id="alertError" class="alert alert-danger alert-dismissible d-none mb-3">
        <i class="bi bi-x-circle me-2"></i>
        <span id="alertErrMsg">Gagal menyimpan posisi.</span>
        <button type="button" class="btn-close" onclick="this.parentElement.classList.add('d-none')"></button>
    </div>

    <div class="row g-3">

        {{-- Panel Kiri: Kontrol --}}
        <div class="col-md-3">

            {{-- Pilih Template Label --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white py-2">
                    <i class="bi bi-image me-1"></i> Pilih Template Label
                </div>
                <div class="card-body p-2">
                    @if($labels->isEmpty())
                        <p class="text-muted small mb-2">Belum ada template.</p>
                        <a href="{{ route('labels.index') }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="bi bi-upload me-1"></i>Upload Template
                        </a>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($labels as $label)
                            <button type="button"
                                class="list-group-item list-group-item-action py-2 px-2 label-picker"
                                data-id="{{ $label->id }}"
                                data-url="{{ asset('storage/' . $label->file_template) }}"
                                data-qr-x="{{ $label->qr_x }}"
                                data-qr-y="{{ $label->qr_y }}"
                                data-qr-size="{{ $label->qr_size }}"
                                data-save-url="{{ route('labels.save-position', $label->id) }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span style="font-size:13px;">
                                        <i class="bi bi-file-image me-1"></i>{{ $label->nama_template }}
                                    </span>
                                    @if($label->hasSavedPosition())
                                        <span class="badge bg-success" style="font-size:9px;" title="Posisi QR sudah disimpan">
                                            <i class="bi bi-pin-fill"></i>
                                        </span>
                                    @endif
                                </div>
                                @if($label->hasSavedPosition())
                                <div style="font-size:10px; color:#6c757d; margin-top:2px;">
                                    QR: {{ $label->qr_size }}px · x:{{ $label->qr_x }} y:{{ $label->qr_y }}
                                </div>
                                @endif
                            </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kontrol QR --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white py-2">
                    <i class="bi bi-qr-code me-1"></i> Kontrol QR Code
                </div>
                <div class="card-body">
                    <label class="form-label small fw-bold">Ukuran QR</label>
                    <input type="range" class="form-range" id="qrSize" min="60" max="400" value="150">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">60px</small>
                        <small class="fw-bold text-primary" id="qrSizeLabel">150px</small>
                        <small class="text-muted">400px</small>
                    </div>

                    <label class="form-label small fw-bold">Posisi X (px)</label>
                    <input type="number" class="form-control form-control-sm mb-2" id="qrX" value="20" min="0">

                    <label class="form-label small fw-bold">Posisi Y (px)</label>
                    <input type="number" class="form-control form-control-sm mb-2" id="qrY" value="20" min="0">

                    <button class="btn btn-outline-secondary btn-sm w-100 mt-1" id="btnCenter">
                        <i class="bi bi-align-center me-1"></i>Tengahkan QR
                    </button>
                </div>
            </div>

            {{-- Simpan Posisi ke Template --}}
            <div class="card shadow-sm mb-3 border-success">
                <div class="card-body">
                    <button class="btn btn-success w-100 mb-2" id="btnSimpanPosisi" disabled>
                        <i class="bi bi-pin-map me-1"></i>Simpan Posisi ke Template
                    </button>
                    <p class="text-muted mb-0" style="font-size:11px; text-align:center;">
                        Posisi ini akan dipakai otomatis saat generate unit dari kategori yang memakai template ini.
                    </p>
                </div>
            </div>

            {{-- Download --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <button class="btn btn-outline-primary w-100 mb-2" id="btnDownload">
                        <i class="bi bi-download me-1"></i>Download PNG
                    </button>
                    <p class="text-muted small mb-0 text-center">Hasil gabungan label + QR</p>
                </div>
            </div>
        </div>

        {{-- Panel Kanan: Canvas Editor --}}
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-light py-2 d-flex align-items-center gap-2">
                    <i class="bi bi-arrows-move text-primary"></i>
                    <span class="small">Drag QR Code untuk mengatur posisi</span>
                    <span class="badge bg-secondary ms-auto" id="posInfo">x: 20, y: 20</span>
                    <span class="badge bg-light text-dark border ms-1" id="templateInfo">Belum ada template dipilih</span>
                </div>
                <div class="card-body p-2 text-center bg-secondary bg-opacity-10">
                    <div style="position:relative; display:inline-block; cursor:default;" id="canvasWrapper">
                        <canvas id="labelCanvas" style="display:block; border:2px dashed #ccc; border-radius:4px; max-width:100%;"></canvas>
                        <div id="qrOverlay" style="
                            position:absolute;
                            top:20px; left:20px;
                            width:150px; height:150px;
                            cursor:move;
                            border:2px dashed #1a73e8;
                            box-sizing:border-box;
                            background: rgba(255,255,255,0.1);
                        ">
                            <img id="qrPreview" src="" style="width:100%;height:100%;pointer-events:none;" />
                            <div style="position:absolute;top:-1px;right:-1px;width:10px;height:10px;background:#1a73e8;border-radius:50%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const QR_SVG_URL      = "{{ asset('storage/' . $product->qr_code) }}";
    const PRODUCT_NAME    = "{{ $product->kode_produk }}";
    const CSRF_TOKEN      = "{{ csrf_token() }}";
    const AUTO_TEMPLATE   = "{{ request('template') }}";
</script>

<script>
(function () {
    const canvas      = document.getElementById('labelCanvas');
    const ctx         = canvas.getContext('2d');
    const overlay     = document.getElementById('qrOverlay');
    const qrPreview   = document.getElementById('qrPreview');
    const wrapper     = document.getElementById('canvasWrapper');
    const qrSizeEl    = document.getElementById('qrSize');
    const qrSizeLbl   = document.getElementById('qrSizeLabel');
    const qrXEl       = document.getElementById('qrX');
    const qrYEl       = document.getElementById('qrY');
    const posInfo     = document.getElementById('posInfo');
    const templateInfo= document.getElementById('templateInfo');
    const btnDownload = document.getElementById('btnDownload');
    const btnCenter   = document.getElementById('btnCenter');
    const btnSimpan   = document.getElementById('btnSimpanPosisi');

    let labelImg    = null;
    let qrImg       = null;
    let qrSize      = 150;
    let qrX         = 20;
    let qrY         = 20;
    let isDragging  = false;
    let dragStartX, dragStartY, dragStartQrX, dragStartQrY;
    let activeTemplateId  = null;
    let activeSaveUrl     = null;
    let activeTemplateName= null;

    // Load QR
    qrPreview.src = QR_SVG_URL;
    const qrImgEl = new Image();
    qrImgEl.onload = () => { qrImg = qrImgEl; drawCanvas(); };
    qrImgEl.src = QR_SVG_URL;

    // Load template dari ?template=id atau template pertama
    let targetPicker = null;
    if (AUTO_TEMPLATE) {
        targetPicker = document.querySelector(`.label-picker[data-id="${AUTO_TEMPLATE}"]`);
    }
    if (!targetPicker) targetPicker = document.querySelector(".label-picker");
    if (targetPicker) {
        targetPicker.classList.add("active");
        loadTemplate(targetPicker);
    }

    function loadTemplate(btn) {
        activeTemplateId   = btn.dataset.id;
        activeSaveUrl      = btn.dataset.saveUrl;
        activeTemplateName = btn.querySelector('span').textContent.trim();
        templateInfo.textContent = activeTemplateName;

        // Load posisi tersimpan dari template
        qrX    = parseInt(btn.dataset.qrX)    || 20;
        qrY    = parseInt(btn.dataset.qrY)    || 20;
        qrSize = parseInt(btn.dataset.qrSize) || 150;

        qrSizeEl.value         = qrSize;
        qrSizeLbl.textContent  = qrSize + 'px';
        qrXEl.value            = qrX;
        qrYEl.value            = qrY;

        btnSimpan.disabled = false;

        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = () => {
            labelImg      = img;
            canvas.width  = img.naturalWidth;
            canvas.height = img.naturalHeight;
            drawCanvas();
            syncOverlay();
        };
        img.src = btn.dataset.url;
    }

    function drawCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        if (labelImg) {
            ctx.drawImage(labelImg, 0, 0, canvas.width, canvas.height);
        } else {
            ctx.fillStyle = '#f8f9fa';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = '#aaa';
            ctx.font = '20px Arial';
            ctx.textAlign = 'center';
            ctx.fillText('Pilih template label di panel kiri', canvas.width / 2, canvas.height / 2);
        }
        if (qrImg) ctx.drawImage(qrImg, qrX, qrY, qrSize, qrSize);
    }

    function syncOverlay() {
        const rect   = canvas.getBoundingClientRect();
        const scaleX = rect.width  / canvas.width;
        const scaleY = rect.height / canvas.height;

        overlay.style.left   = (qrX * scaleX) + 'px';
        overlay.style.top    = (qrY * scaleY) + 'px';
        overlay.style.width  = (qrSize * scaleX) + 'px';
        overlay.style.height = (qrSize * scaleY) + 'px';

        qrXEl.value          = qrX;
        qrYEl.value          = qrY;
        posInfo.textContent  = `x: ${qrX}, y: ${qrY}`;
    }

    // ===== DRAG =====
    overlay.addEventListener('mousedown', e => {
        isDragging   = true;
        dragStartX   = e.clientX;
        dragStartY   = e.clientY;
        dragStartQrX = qrX;
        dragStartQrY = qrY;
        overlay.style.opacity = '0.7';
        e.preventDefault();
    });

    document.addEventListener('mousemove', e => {
        if (!isDragging) return;
        const rect   = canvas.getBoundingClientRect();
        const scaleX = canvas.width  / rect.width;
        const scaleY = canvas.height / rect.height;
        qrX = Math.round(dragStartQrX + (e.clientX - dragStartX) * scaleX);
        qrY = Math.round(dragStartQrY + (e.clientY - dragStartY) * scaleY);
        qrX = Math.max(0, Math.min(canvas.width  - qrSize, qrX));
        qrY = Math.max(0, Math.min(canvas.height - qrSize, qrY));
        drawCanvas();
        syncOverlay();
    });

    document.addEventListener('mouseup', () => {
        if (!isDragging) return;
        isDragging = false;
        overlay.style.opacity = '1';
    });

    // Touch support
    overlay.addEventListener('touchstart', e => {
        const t      = e.touches[0];
        isDragging   = true;
        dragStartX   = t.clientX;
        dragStartY   = t.clientY;
        dragStartQrX = qrX;
        dragStartQrY = qrY;
        e.preventDefault();
    }, { passive: false });

    document.addEventListener('touchmove', e => {
        if (!isDragging) return;
        const t      = e.touches[0];
        const rect   = canvas.getBoundingClientRect();
        const scaleX = canvas.width  / rect.width;
        const scaleY = canvas.height / rect.height;
        qrX = Math.round(dragStartQrX + (t.clientX - dragStartX) * scaleX);
        qrY = Math.round(dragStartQrY + (t.clientY - dragStartY) * scaleY);
        qrX = Math.max(0, Math.min(canvas.width  - qrSize, qrX));
        qrY = Math.max(0, Math.min(canvas.height - qrSize, qrY));
        drawCanvas();
        syncOverlay();
        e.preventDefault();
    }, { passive: false });

    document.addEventListener('touchend', () => { isDragging = false; });

    // ===== KONTROL =====
    qrSizeEl.addEventListener('input', () => {
        qrSize = parseInt(qrSizeEl.value);
        qrSizeLbl.textContent = qrSize + 'px';
        drawCanvas();
        syncOverlay();
    });

    qrXEl.addEventListener('input', () => {
        qrX = Math.max(0, parseInt(qrXEl.value) || 0);
        drawCanvas();
        syncOverlay();
    });

    qrYEl.addEventListener('input', () => {
        qrY = Math.max(0, parseInt(qrYEl.value) || 0);
        drawCanvas();
        syncOverlay();
    });

    btnCenter.addEventListener('click', () => {
        qrX = Math.round((canvas.width  - qrSize) / 2);
        qrY = Math.round((canvas.height - qrSize) / 2);
        drawCanvas();
        syncOverlay();
    });

    // ===== SIMPAN POSISI KE TEMPLATE =====
    btnSimpan.addEventListener('click', async () => {
        if (!activeSaveUrl) return;

        btnSimpan.disabled = true;
        btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        try {
            const res = await fetch(activeSaveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ qr_x: qrX, qr_y: qrY, qr_size: qrSize }),
            });

            const data = await res.json();

            if (data.success) {
                // Update data-* pada button template yang aktif
                const activeBtn = document.querySelector('.label-picker.active');
                if (activeBtn) {
                    activeBtn.dataset.qrX    = qrX;
                    activeBtn.dataset.qrY    = qrY;
                    activeBtn.dataset.qrSize = qrSize;

                    // Tampilkan badge hijau pada template
                    let badge = activeBtn.querySelector('.badge');
                    if (!badge) {
                        const span = activeBtn.querySelector('div');
                        span.insertAdjacentHTML('beforeend',
                            `<span class="badge bg-success" style="font-size:9px;" title="Posisi QR sudah disimpan"><i class="bi bi-pin-fill"></i></span>`
                        );
                    }

                    // Update info size/pos di bawah nama
                    let infoDiv = activeBtn.querySelector('div:last-child');
                    if (infoDiv && !infoDiv.querySelector('.badge')) {
                        infoDiv.textContent = `QR: ${qrSize}px · x:${qrX} y:${qrY}`;
                    } else {
                        activeBtn.insertAdjacentHTML('beforeend',
                            `<div style="font-size:10px;color:#6c757d;margin-top:2px;">QR: ${qrSize}px · x:${qrX} y:${qrY}</div>`
                        );
                    }
                }

                document.getElementById('alertMsg').textContent =
                    `Posisi QR berhasil disimpan ke template "${activeTemplateName}"!`;
                document.getElementById('alertSimpan').classList.remove('d-none');
                document.getElementById('alertError').classList.add('d-none');
            } else {
                throw new Error('Gagal');
            }
        } catch (err) {
            document.getElementById('alertErrMsg').textContent = 'Gagal menyimpan posisi. Coba lagi.';
            document.getElementById('alertError').classList.remove('d-none');
        } finally {
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = '<i class="bi bi-pin-map me-1"></i>Simpan Posisi ke Template';
        }
    });

    // ===== DOWNLOAD =====
    btnDownload.addEventListener('click', () => {
        const tmpCanvas  = document.createElement('canvas');
        tmpCanvas.width  = canvas.width;
        tmpCanvas.height = canvas.height;
        const tmpCtx     = tmpCanvas.getContext('2d');
        if (labelImg) tmpCtx.drawImage(labelImg, 0, 0, canvas.width, canvas.height);
        if (qrImg)    tmpCtx.drawImage(qrImg, qrX, qrY, qrSize, qrSize);

        const link    = document.createElement('a');
        link.download = 'label-' + PRODUCT_NAME + '.png';
        link.href     = tmpCanvas.toDataURL('image/png');
        link.click();
    });

    // ===== PILIH TEMPLATE =====
    document.querySelectorAll('.label-picker').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.label-picker').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            loadTemplate(this);
        });
    });

    // Init overlay
    syncOverlay();
})();
</script>
@endsection