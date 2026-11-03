<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Export PDF - {{ $category->nama_kategori }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            background: #fff;
        }

        /* ── HEADER ─────────────────────────────────── */
        .header {
            background: #1a73e8;
            color: #fff;
            padding: 20px 24px 16px;
            margin-bottom: 0;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .brand {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .brand span {
            color: #ffd600;
        }

        .doc-meta {
            text-align: right;
            font-size: 9px;
            opacity: 0.85;
            line-height: 1.6;
        }

        .header-title {
            font-size: 14px;
            font-weight: 600;
            opacity: 0.95;
            border-top: 1px solid rgba(255,255,255,0.3);
            padding-top: 10px;
        }

        /* ── STAT CARDS ─────────────────────────────── */
        .stat-row {
            background: #f8f9ff;
            border-bottom: 2px solid #e8ecf5;
            padding: 12px 24px;
            display: flex;
            gap: 0;
        }

        .stat-card {
            flex: 1;
            text-align: center;
            padding: 8px 12px;
            border-right: 1px solid #dee2e6;
        }

        .stat-card:last-child {
            border-right: none;
        }

        .stat-num {
            font-size: 22px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 3px;
        }

        .stat-label {
            font-size: 9px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .num-total   { color: #1a73e8; }
        .num-asli    { color: #28a745; }
        .num-curiga  { color: #e67e22; }
        .num-baru    { color: #6c757d; }

        /* ── INFO KATEGORI ───────────────────────────── */
        .info-section {
            padding: 14px 24px 10px;
            border-bottom: 1px solid #e8ecf5;
        }

        .info-grid {
            display: flex;
            gap: 32px;
        }

        .info-item {
            flex: 1;
        }

        .info-label {
            font-size: 9px;
            color: #9ba5b4;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 11px;
            font-weight: 600;
            color: #1a1a2e;
        }

        /* ── TABEL ───────────────────────────────────── */
        .table-section {
            padding: 14px 24px 20px;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e8ecf5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #1a73e8;
            color: #fff;
        }

        thead th {
            padding: 7px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        tbody tr {
            border-bottom: 1px solid #f0f2f8;
        }

        tbody tr:nth-child(even) {
            background: #f8f9ff;
        }

        tbody td {
            padding: 6px 8px;
            font-size: 10px;
            color: #2c3e50;
            vertical-align: middle;
        }

        .kode {
            font-family: 'Courier New', monospace;
            font-size: 9.5px;
            background: #eef2ff;
            padding: 2px 5px;
            border-radius: 3px;
            color: #1a73e8;
            letter-spacing: 0.3px;
        }

        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-asli        { background: #d4edda; color: #155724; }
        .badge-mencurigakan { background: #fde8d0; color: #7a3200; }
        .badge-baru        { background: #e2e3e5; color: #383d41; }

        .scan-count {
            font-weight: 700;
            color: #1a73e8;
        }

        .no-col {
            color: #9ba5b4;
            font-size: 9px;
        }

        /* ── FOOTER ──────────────────────────────────── */
        .footer {
            border-top: 2px solid #e8ecf5;
            padding: 10px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #9ba5b4;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
        }

        .footer-brand {
            font-weight: 700;
            color: #1a73e8;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #9ba5b4;
            font-size: 12px;
        }
    </style>
</head>
<body>

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-top">
            <div class="brand">Beezam<span>.</span>Auth</div>
            <div class="doc-meta">
                Dicetak: {{ now()->format('d/m/Y H:i') }}<br>
                Dokumen: Laporan Unit Produk<br>
                Total Halaman: <span class="pagecount"></span>
            </div>
        </div>
        <div class="header-title">
            Laporan Unit Produk — {{ $category->nama_kategori }}
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-num num-total">{{ $products->count() }}</div>
            <div class="stat-label">Total Unit</div>
        </div>
        <div class="stat-card">
            <div class="stat-num num-asli">{{ $totalAsli }}</div>
            <div class="stat-label">Asli</div>
        </div>
        <div class="stat-card">
            <div class="stat-num num-curiga">{{ $totalMencurigakan }}</div>
            <div class="stat-label">Mencurigakan</div>
        </div>
        <div class="stat-card">
            <div class="stat-num num-baru">{{ $totalBaru }}</div>
            <div class="stat-label">Belum Scan</div>
        </div>
    </div>

    {{-- ── INFO KATEGORI ── --}}
    <div class="info-section">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nama Kategori</div>
                <div class="info-value">{{ $category->nama_kategori }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Batch Produk</div>
                <div class="info-value">{{ $category->batch_produk }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Tanggal Produksi</div>
                <div class="info-value">{{ $category->tanggal_produksi->format('d/m/Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Deskripsi</div>
                <div class="info-value">{{ $category->deskripsi ?: '-' }}</div>
            </div>
        </div>
    </div>

    {{-- ── TABEL UNIT ── --}}
    <div class="table-section">
        <div class="section-title">Daftar Unit Produk</div>

        @if($products->isEmpty())
            <div class="empty-state">Belum ada unit produk pada kategori ini.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th width="32%">Kode Produk</th>
                        <th width="14%">Status</th>
                        <th width="8%">Scan</th>
                        <th width="42%">Hash HMAC (SHA-256)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $i => $product)
                    <tr>
                        <td class="no-col">{{ $i + 1 }}</td>
                        <td><span class="kode">{{ $product->kode_produk }}</span></td>
                        <td>
                            @if($product->status_produk === 'ASLI')
                                <span class="badge badge-asli">Asli</span>
                            @elseif($product->status_produk === 'MENCURIGAKAN')
                                <span class="badge badge-mencurigakan">Mencurigakan</span>
                            @else
                                <span class="badge badge-baru">Baru</span>
                            @endif
                        </td>
                        <td class="scan-count">{{ $product->total_scan }}x</td>
                        <td>
                            <span style="font-family: 'Courier New', monospace;
                                         font-size: 8px; color: #6c757d;
                                         word-break: break-all;">
                                {{ $product->hash_hmac }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <span><span class="footer-brand">Beezam Auth</span> — Sistem Autentikasi Produk</span>
        <span>{{ $category->nama_kategori }} · Batch {{ $category->batch_produk }}</span>
        <span>Halaman <span class="page"></span></span>
    </div>

</body>
</html>