<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    use HasFactory;

    protected $table = 'scan_logs';

    protected $fillable = [
        'product_id',
        'ip_address',
        'user_agent',
        'scanned_at',
    ];

    // Relasi: scan log milik satu produk
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}