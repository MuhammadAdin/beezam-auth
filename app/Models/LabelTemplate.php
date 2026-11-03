<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelTemplate extends Model
{
    use HasFactory;

    protected $table = 'label_templates';

    protected $fillable = [
        'nama_template',
        'file_template',
        'qr_x',
        'qr_y',
        'qr_size',
    ];

    /**
     * Kategori yang memakai template ini
     */
    public function categories()
    {
        return $this->hasMany(Category::class, 'label_template_id');
    }

    /**
     * Cek apakah posisi QR sudah pernah disimpan
     */
    public function hasSavedPosition(): bool
    {
        return $this->qr_x !== 20 || $this->qr_y !== 20 || $this->qr_size !== 150;
    }
}