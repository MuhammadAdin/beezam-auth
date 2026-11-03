<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ScanLogController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('customer.dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| VERIFIKASI PUBLIK (tanpa login)
|--------------------------------------------------------------------------
*/
// Dashboard konsumen — halaman publik (tanpa login)
Route::get('/customer', [CustomerController::class, 'dashboard'])
    ->name('customer.dashboard');
 
// Verifikasi manual via kode ketik dari modal scanner
// Menerima ?kode=... lalu redirect ke /verify/{kode_produk}
Route::get('/verify/manual', [CustomerController::class, 'verifyManual'])
    ->name('verify.manual');


Route::get('/verify', [VerifyController::class, 'index'])->name('verify.index');

Route::get('/verify/search', function(\Illuminate\Http\Request $request) {
    $kode = trim($request->query('kode', ''));
    if (!$kode) {
        return redirect()->route('verify.index')
                         ->with('error', 'Kode produk wajib diisi.');
    }
    return redirect()->route('verify.check', $kode);
})->name('verify.search');

Route::get('/verify/{kode_produk}', [VerifyController::class, 'verify'])
     ->name('verify.check');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Dilindungi AuthMiddleware)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth.manual'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');

    // ── CATEGORIES ────────────────────────────────────────────
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/{category}/generate-units',
        [CategoryController::class, 'generateUnits'])
        ->name('categories.generate-units');
    Route::delete('/categories/{category}/clear-units',
        [CategoryController::class, 'clearUnits'])
        ->name('categories.clear-units');
    // ⚠️ Statis di atas wildcard resource
    Route::get('/categories/{category}/export-pdf',
        [CategoryController::class, 'exportPdf'])
        ->name('categories.export-pdf');

    // ── PRODUK ────────────────────────────────────────────────
    Route::resource('products', ProductController::class)
         ->except(['create', 'store', 'edit', 'update']);
    Route::get('/products/{product}/regenerate-qr',
        [ProductController::class, 'regenerateQr'])
        ->name('products.regenerate-qr');

    // ── SCAN LOGS ─────────────────────────────────────────────
    Route::get('/scanlogs', [ScanLogController::class, 'index'])
         ->name('scanlogs.index');
    Route::get('/scanlogs/{product}', [ScanLogController::class, 'show'])
         ->name('scanlogs.show');

    // ── LABELS ────────────────────────────────────────────────
    Route::get('/labels', [LabelController::class, 'index'])
         ->name('labels.index');
    Route::post('/labels', [LabelController::class, 'store'])
         ->name('labels.store');

    // ⚠️ PENTING: route statis harus di ATAS wildcard {label}
    Route::get('/labels/print/{product}', [LabelController::class, 'print'])
         ->name('labels.print');
    Route::get('/labels/editor/{product}', [LabelController::class, 'editor'])
         ->name('labels.editor');

    // Simpan posisi QR ke template (AJAX)
    Route::post('/labels/{label}/save-position', [LabelController::class, 'savePosition'])
         ->name('labels.save-position');

    // Bulk Print Label per Kategori
    Route::get('/categories/{category}/bulk-print', [LabelController::class, 'bulkPrint'])
         ->name('labels.bulk-print');

    Route::delete('/labels/{label}', [LabelController::class, 'destroy'])
         ->name('labels.destroy');

});