<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Services\ProductSecurityService;
use Illuminate\Support\Facades\Storage;
use App\Support\PublicFile;

class ProductController extends Controller
{
    protected $security;

    public function __construct(ProductSecurityService $security)
    {
        $this->security = $security;
    }

    /**
     * Daftar semua unit produk dari semua kategori.
     */
    public function index()
    {
        $products = Product::with('category')
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);

        return view('products.index', compact('products'));
    }

    /**
     * Detail unit produk.
     */
    public function show(Product $product)
    {
        $product->load('category');

        $scanLogs = $product->scanLogs()
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        $hashValid = $this->security->verifyHash(
            $product->kode_produk,
            $product->category->nama_kategori,
            $product->category->batch_produk,
            $product->category->tanggal_produksi->format('Y-m-d'),
            $product->secret_key,
            $product->hash_hmac
        );

        return view('products.show', compact('product', 'scanLogs', 'hashValid'));
    }

    /**
     * Hapus unit produk + hapus file QR.
     */
    public function destroy(Product $product)
    {
        $categoryId = $product->category_id;

        PublicFile::delete($product->qr_code);

        $product->delete();

        return redirect()->route('categories.show', $categoryId)
                         ->with('success', 'Unit produk berhasil dihapus!');
    }

    /**
     * Regenerate QR Code.
     */
    public function regenerateQr(Product $product)
    {
        $qrCodePath = $this->security->generateQrCode($product->kode_produk);
        $product->update(['qr_code' => $qrCodePath]);

        return redirect()->route('products.show', $product->id)
                         ->with('success', 'QR Code berhasil di-regenerate!');
    }
}