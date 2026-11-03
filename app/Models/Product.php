<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'kode_produk',
        'secret_key',
        'hash_hmac',
        'qr_code',
        'total_scan',
        'status_produk',
    ];

    /**
     * Setiap unit produk milik satu kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Satu produk punya banyak scan log
     */
    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class, 'product_id');
    }
}