<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'batch_produk',
        'tanggal_produksi',
        'foto_produk',
        'label_template_id',
    ];

    protected $casts = [
        'tanggal_produksi' => 'date',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Template label yang dipakai kategori ini
     */
    public function labelTemplate()
    {
        return $this->belongsTo(LabelTemplate::class, 'label_template_id');
    }

    public function unitMencurigakan()
    {
        return $this->products()->where('status_produk', 'MENCURIGAKAN')->count();
    }
}