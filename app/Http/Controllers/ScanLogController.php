<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScanLog;
use App\Models\Product;

class ScanLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ScanLog::with('product.category')
                        ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('status_produk', $request->status);
            });
        }

        if ($request->filled('kode')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('kode_produk', 'like', '%' . $request->kode . '%');
            });
        }

        $scanLogs = $query->paginate(15)->withQueryString();

        $totalScan          = ScanLog::count();
        $produkMencurigakan = Product::where('status_produk', 'MENCURIGAKAN')->count();
        $produkAsli         = Product::where('status_produk', 'ASLI')->count();
        $scanHariIni        = ScanLog::whereDate('created_at', today())->count();

        $topScanProduk = Product::with('category')
                                ->orderBy('total_scan', 'desc')
                                ->where('total_scan', '>', 0)
                                ->limit(5)
                                ->get();

        return view('scanlogs.index', compact(
            'scanLogs',
            'totalScan',
            'produkMencurigakan',
            'produkAsli',
            'scanHariIni',
            'topScanProduk'
        ));
    }

    public function show(Product $product)
    {
        $product->load('category');

        $scanLogs = ScanLog::where('product_id', $product->id)
                           ->orderBy('created_at', 'desc')
                           ->paginate(15);

        $scanPerHari = ScanLog::where('product_id', $product->id)
                              ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah')
                              ->groupBy('tanggal')
                              ->orderBy('tanggal', 'desc')
                              ->limit(7)
                              ->get();

        $topIpAddress = ScanLog::where('product_id', $product->id)
                               ->selectRaw('ip_address, COUNT(*) as jumlah')
                               ->groupBy('ip_address')
                               ->orderBy('jumlah', 'desc')
                               ->limit(5)
                               ->get();

        $scanCepat    = ScanLog::where('product_id', $product->id)
                               ->where('created_at', '>=', now()->subMinute())
                               ->count();
        $adaScanCepat = $scanCepat > 1;

        return view('scanlogs.show', compact(
            'product',
            'scanLogs',
            'scanPerHari',
            'topIpAddress',
            'adaScanCepat'
        ));
    }
}