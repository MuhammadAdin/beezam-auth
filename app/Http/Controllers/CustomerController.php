<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ScanLog;

class CustomerController extends Controller
{
    /**
     * Halaman utama dashboard konsumen.
     * Tidak memerlukan login — halaman publik.
     */
    public function dashboard()
    {
        // Ambil semua kategori beserta jumlah unit produknya
        $kategoris = Category::withCount('products')
            ->orderBy('nama_kategori', 'asc')
            ->get();

        // Statistik untuk hero section
        $totalProduk   = Product::count();
        $totalKategori = Category::count();
        $totalScan     = ScanLog::count();

        return view('customer.dashboard', compact(
            'kategoris',
            'totalProduk',
            'totalKategori',
            'totalScan'
        ));
    }

    /**
     * Verifikasi manual via kode (tanpa hash dari QR).
     * Konsumen bisa ketik kode_unik langsung.
     *
     * Route: GET /verify/manual?kode=BZM-2026-A4X9
     */
    public function verifyManual(Request $request)
    {
        $kode = $request->get('kode');

        if (!$kode) {
            return redirect()->route('customer.dashboard')
                ->with('error', 'Kode produk tidak boleh kosong.');
        }

        // Cari produk berdasarkan kode_produk (sesuai migration)
        $produk = Product::where('kode_produk', $kode)->first();

        if (!$produk) {
            return redirect()->route('customer.dashboard')
                ->with('error', 'Produk dengan kode "' . $kode . '" tidak ditemukan.');
        }

        // Redirect ke halaman verify yang sudah ada
        // VerifyController akan handle status ASLI/MENCURIGAKAN
        return redirect()->route('verify', [
            'kode' => $produk->kode_produk,
            'hash' => $produk->hash_hmac,
        ]);
    }
}