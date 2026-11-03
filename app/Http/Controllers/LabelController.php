<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabelTemplate;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class LabelController extends Controller
{
    public function index()
    {
        $templates = LabelTemplate::orderBy('created_at', 'desc')->get();
        return view('labels.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_template' => 'required|string|max:100',
            'file_template' => 'required|image|mimes:png,jpg,jpeg|max:5120',
        ], [
            'nama_template.required' => 'Nama template wajib diisi.',
            'file_template.required' => 'File template wajib diupload.',
            'file_template.image'    => 'File harus berupa gambar.',
            'file_template.mimes'    => 'Format file: PNG, JPG, JPEG.',
            'file_template.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $file     = $request->file('file_template');
        // Nama file dibersihkan (slug) agar aman dari karakter aneh/spasi
        $fileName = time() . '_'
                  . \Illuminate\Support\Str::slug(
                        pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                    )
                  . '.' . $file->extension();
        $path     = $file->storeAs('labels', $fileName, 'public_uploads');

        LabelTemplate::create([
            'nama_template' => $request->nama_template,
            'file_template' => $path,
            'qr_x'          => 20,
            'qr_y'          => 20,
            'qr_size'       => 150,
        ]);

        return redirect()->route('labels.index')
                         ->with('success', 'Template label berhasil diupload!');
    }

    public function destroy(LabelTemplate $label)
    {
        if (Storage::disk('public_uploads')->exists($label->file_template)) {
            Storage::disk('public_uploads')->delete($label->file_template);
        }

        $label->delete();

        return redirect()->route('labels.index')
                         ->with('success', 'Template berhasil dihapus!');
    }

    public function print(Product $product)
    {
        $product->load('category');
        $templates = LabelTemplate::orderBy('created_at', 'desc')->get();
        return view('labels.print', compact('product', 'templates'));
    }

    public function editor(Product $product)
    {
        $product->load('category');
        $labels = LabelTemplate::orderBy('created_at', 'desc')->get();
        return view('labels.editor', compact('product', 'labels'));
    }

    public function bulkPrint(Category $category)
    {
        $products  = $category->products()->whereNotNull('qr_code')->get();
        $templates = LabelTemplate::orderBy('created_at', 'desc')->get();

        return view('labels.bulk-print', compact('category', 'products', 'templates'));
    }

    // =========================================================
    // FITUR BARU: Simpan posisi QR ke template
    // =========================================================

    /**
     * Simpan posisi QR (x, y, size) ke template label
     * Dipanggil via AJAX dari editor.blade.php
     */
    public function savePosition(Request $request, LabelTemplate $label)
    {
        $request->validate([
            'qr_x'    => 'required|integer|min:0',
            'qr_y'    => 'required|integer|min:0',
            'qr_size' => 'required|integer|min:60|max:400',
        ]);

        $label->update([
            'qr_x'    => $request->qr_x,
            'qr_y'    => $request->qr_y,
            'qr_size' => $request->qr_size,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Posisi QR berhasil disimpan!',
            'data'    => [
                'qr_x'    => $label->qr_x,
                'qr_y'    => $label->qr_y,
                'qr_size' => $label->qr_size,
            ],
        ]);
    }
}