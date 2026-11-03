<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ScanLog;
use App\Services\ProductSecurityService;

class VerifyController extends Controller
{
    protected $security;

    public function __construct(ProductSecurityService $security)
    {
        $this->security = $security;
    }

    /**
     * Halaman landing verifikasi publik.
     * Konsumen bisa input kode produk manual di sini.
     * Atau langsung scan QR → otomatis ke verify().
     */
    public function index()
    {
        return view('verify.index');
    }

    /**
     * =========================================================
     * PROSES VERIFIKASI UTAMA
     * =========================================================
     *
     * Flowchart sistem:
     * 1. Konsumen scan QR → buka URL /verify/{kode_produk}
     * 2. Sistem ambil data produk dari database
     * 3. Sistem re-hash data + secret key
     * 4. Bandingkan hash baru vs hash database
     * 5. Jika hash TIDAK sama → TIDAK TERVERIFIKASI
     * 6. Jika hash sama → cek jumlah scan
     * 7. Scan <= 3 → PRODUK ASLI
     * 8. Scan > 3  → MENCURIGAKAN
     * 9. Catat log scan ke database
     */
    public function verify(Request $request, string $kodeProduk)
{
    // ===== STEP 1: Cari produk di database =====
    $product = Product::with('category')->where('kode_produk', $kodeProduk)->first();

    // Jika produk tidak ditemukan
    if (!$product) {
        return view('verify.result', [
            'status'     => 'NOT_FOUND',
            'product'    => null,
            'kodeProduk' => $kodeProduk,
            'message'    => 'Produk dengan kode ini tidak ditemukan dalam database kami.',
        ]);
    }

    // ===== STEP 2: RE-HASHING (verifikasi integritas data) =====
    $hashValid = $this->security->verifyHash(
        $product->kode_produk,
        $product->category->nama_kategori,
        $product->category->batch_produk,
        $product->category->tanggal_produksi->format('Y-m-d'),
        $product->secret_key,
        $product->hash_hmac
    );

    // ===== STEP 3: Jika hash TIDAK cocok → TIDAK TERVERIFIKASI =====
    if (!$hashValid) {
        $this->catatScan($product, $request);

        return view('verify.result', [
            'status'     => 'TIDAK_TERVERIFIKASI',
            'product'    => $product,
            'kodeProduk' => $kodeProduk,
            'message'    => 'Hash tidak cocok. Produk ini kemungkinan PALSU atau data telah dimanipulasi.',
        ]);
    }

    // ===== STEP 4: Hash cocok → catat scan =====
    $this->catatScan($product, $request);

    $product->increment('total_scan');
    $product->refresh();

    // ===== STEP 5: Tentukan status =====
    if ($product->total_scan <= 3) {
        $status  = 'ASLI';
        $message = 'Produk ini telah diverifikasi dan terbukti ASLI.';
    } else {
        $status  = 'MENCURIGAKAN';
        $message = 'Produk ini telah discan ' . $product->total_scan . ' kali. '
                 . 'Melebihi batas normal (3x). Kemungkinan produk dipalsukan.';
    }

    // ===== STEP 6: Update status produk =====
    $product->update(['status_produk' => $status]);

    return view('verify.result', [
        'status'     => $status,
        'product'    => $product,
        'kodeProduk' => $kodeProduk,
        'message'    => $message,
    ]);
}

    /**
     * =========================================================
     * CATAT LOG SCAN
     * =========================================================
     * Simpan: IP address, user agent, waktu scan
     */
    private function catatScan(Product $product, Request $request): void
    {
        ScanLog::create([
            'product_id' => $product->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'scanned_at' => now(),
        ]);
    }
}