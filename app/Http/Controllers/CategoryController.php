<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\LabelTemplate;
use App\Services\ProductSecurityService;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CategoryController extends Controller
{
    protected $security;

    public function __construct(ProductSecurityService $security)
    {
        $this->security = $security;
    }

    public function index()
    {
        $categories = Category::withCount('products')
                              ->orderBy('created_at', 'desc')
                              ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string|max:500',
            'batch_produk'     => 'required|string|max:100',
            'tanggal_produksi' => 'required|date',
            'foto_produk'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama_kategori.required'    => 'Nama kategori wajib diisi.',
            'batch_produk.required'     => 'Batch produk wajib diisi.',
            'tanggal_produksi.required' => 'Tanggal produksi wajib diisi.',
            'foto_produk.image'         => 'File harus berupa gambar.',
            'foto_produk.max'           => 'Ukuran foto maksimal 2MB.',
        ]);

        $data = $request->only([
            'nama_kategori', 'deskripsi', 'batch_produk', 'tanggal_produksi',
        ]);

        if ($request->hasFile('foto_produk')) {
            $data['foto_produk'] = $request->file('foto_produk')
                                           ->store('foto_kategori', 'public_uploads');
        }

        Category::create($data);

        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function show(Category $category)
    {
        $products = $category->products()
                             ->orderBy('created_at', 'desc')
                             ->paginate(20);

        // Load relasi template
        $category->load('labelTemplate');

        // Kirim daftar template untuk dropdown generate
        $templates = LabelTemplate::orderBy('created_at', 'desc')->get();

        return view('categories.show', compact('category', 'products', 'templates'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama_kategori'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string|max:500',
            'batch_produk'     => 'required|string|max:100',
            'tanggal_produksi' => 'required|date',
            'foto_produk'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'nama_kategori', 'deskripsi', 'batch_produk', 'tanggal_produksi',
        ]);

        if ($request->hasFile('foto_produk')) {
            if ($category->foto_produk &&
                Storage::disk('public_uploads')->exists($category->foto_produk)) {
                Storage::disk('public_uploads')->delete($category->foto_produk);
            }
            $data['foto_produk'] = $request->file('foto_produk')
                                           ->store('foto_kategori', 'public_uploads');
        }

        if ($request->has('hapus_foto') && !$request->hasFile('foto_produk')) {
            if ($category->foto_produk &&
                Storage::disk('public_uploads')->exists($category->foto_produk)) {
                Storage::disk('public_uploads')->delete($category->foto_produk);
            }
            $data['foto_produk'] = null;
        }

        $category->update($data);

        return redirect()->route('categories.show', $category)
                         ->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy(Category $category)
    {
        if ($category->foto_produk &&
            Storage::disk('public_uploads')->exists($category->foto_produk)) {
            Storage::disk('public_uploads')->delete($category->foto_produk);
        }

        foreach ($category->products as $product) {
            if ($product->qr_code &&
                Storage::disk('public_uploads')->exists($product->qr_code)) {
                Storage::disk('public_uploads')->delete($product->qr_code);
            }
        }

        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Kategori dan semua unit produk berhasil dihapus!');
    }

    public function generateUnits(Request $request, Category $category)
    {
        $request->validate([
            'jumlah'            => 'required|integer|min:1|max:500',
            'label_template_id' => 'nullable|exists:label_templates,id',
        ], [
            'jumlah.required' => 'Jumlah unit wajib diisi.',
            'jumlah.min'      => 'Minimal generate 1 unit.',
            'jumlah.max'      => 'Maksimal generate 500 unit sekaligus.',
        ]);

        // Simpan pilihan template ke kategori ini
        if ($request->filled('label_template_id')) {
            $category->update(['label_template_id' => $request->label_template_id]);
        }

        // Generate 500 unit + QR bisa lebih lama dari batas default 30 detik
        @set_time_limit(300);

        $jumlah    = (int) $request->jumlah;
        $generated = 0;
        $errors    = [];

        for ($i = 0; $i < $jumlah; $i++) {
            $qrCode = null;
            try {
                $kodeProduk = $this->security->generateKodeProduk();
                $secretKey  = $this->security->generateSecretKey();
                $hash       = $this->security->generateHash(
                    $kodeProduk,
                    $category->nama_kategori,
                    $category->batch_produk,
                    $category->tanggal_produksi->format('Y-m-d'),
                    $secretKey
                );
                $qrCode = $this->security->generateQrCode($kodeProduk);

                Product::create([
                    'category_id'   => $category->id,
                    'kode_produk'   => $kodeProduk,
                    'secret_key'    => $secretKey,
                    'hash_hmac'     => $hash,
                    'qr_code'       => $qrCode,
                    'total_scan'    => 0,
                    'status_produk' => 'BARU',
                ]);

                $generated++;
                usleep(1000);

            } catch (\Throwable $e) {
                // Detail teknis ke log saja, jangan ditampilkan ke pengguna
                report($e);
                $errors[] = 'Unit ke-' . ($i + 1) . ' gagal dibuat (lihat storage/logs/laravel.log).';

                // Bersihkan file QR yatim bila produk gagal tersimpan
                if (!empty($qrCode) && Storage::disk('public_uploads')->exists($qrCode)) {
                    Storage::disk('public_uploads')->delete($qrCode);
                }
            }
        }

        $message = "Berhasil generate {$generated} unit produk";

        if (!empty($errors)) {
            $message .= ' (' . ($jumlah - $generated) . ' gagal)';
            return redirect()->route('categories.show', $category)
                             ->with('warning', $message)
                             ->with('errors_generate', $errors);
        }

        return redirect()->route('categories.show', $category)
                         ->with('success', $message . '!');
    }

    public function clearUnits(Category $category)
    {
        foreach ($category->products as $product) {
            if ($product->qr_code &&
                Storage::disk('public_uploads')->exists($product->qr_code)) {
                Storage::disk('public_uploads')->delete($product->qr_code);
            }
        }

        $category->products()->delete();

        return redirect()->route('categories.show', $category)
                         ->with('success', 'Semua unit produk berhasil dihapus!');
    }

    // =========================================================
    // Export PDF per Kategori
    // =========================================================

    public function exportPdf(Category $category)
    {
        $products = $category->products()
                             ->orderBy('created_at', 'asc')
                             ->get();

        $totalAsli         = $products->where('status_produk', 'ASLI')->count();
        $totalMencurigakan = $products->where('status_produk', 'MENCURIGAKAN')->count();
        $totalBaru         = $products->where('status_produk', 'BARU')->count();

        $pdf = Pdf::loadView('categories.export-pdf', compact(
            'category',
            'products',
            'totalAsli',
            'totalMencurigakan',
            'totalBaru'
        ))->setPaper('a4', 'portrait');

        $filename = 'Beezam-' . str()->slug($category->nama_kategori) . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}