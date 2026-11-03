<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ScanLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Data lama (tidak diubah) ─────────────────────────────────────
        $totalProduk        = Product::count();
        $totalScan          = ScanLog::count();
        $produkAsli         = Product::where('status_produk', 'ASLI')->count();
        $produkMencurigakan = Product::where('status_produk', 'MENCURIGAKAN')->count();

        $riwayatScan = ScanLog::with('product.category')
                              ->orderBy('created_at', 'desc')
                              ->limit(10)
                              ->get();

        $produkTerbaru = Product::with('category')
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();

        // ── BARU: Grafik line — scan per hari (7 hari terakhir) ──────────
        $scanPerHari = collect(range(6, 0))->map(function ($daysAgo) {
            $tanggal = Carbon::today()->subDays($daysAgo);
            return [
                'label' => $tanggal->locale('id')->isoFormat('ddd'),  // Sen, Sel, …
                'total' => ScanLog::whereDate('created_at', $tanggal)->count(),
            ];
        });

        $labelHari = $scanPerHari->pluck('label')->toJson();
        $dataScan  = $scanPerHari->pluck('total')->toJson();

        // ── BARU: Grafik donut — distribusi status produk ────────────────
        $statusCounts = Product::select('status_produk', DB::raw('count(*) as total'))
                               ->groupBy('status_produk')
                               ->pluck('total', 'status_produk');

        $statusBaru         = $statusCounts->get('BARU', 0);
        $statusAsli         = $statusCounts->get('ASLI', 0);
        $statusMencurigakan = $statusCounts->get('MENCURIGAKAN', 0);

        return view('dashboard.index', compact(
            // lama
            'totalProduk',
            'totalScan',
            'produkAsli',
            'produkMencurigakan',
            'riwayatScan',
            'produkTerbaru',
            // baru
            'labelHari',
            'dataScan',
            'statusBaru',
            'statusAsli',
            'statusMencurigakan',
        ));
    }
}