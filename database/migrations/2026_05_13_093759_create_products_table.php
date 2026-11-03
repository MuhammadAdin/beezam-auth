<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('batch_produk');
            $table->date('tanggal_produksi');
            $table->string('kode_produk')->unique();
            $table->string('secret_key');
            $table->string('hash_hmac');
            $table->text('qr_code')->nullable();
            $table->integer('total_scan')->default(0);
            $table->enum('status_produk', ['ASLI', 'MENCURIGAKAN', 'BARU'])->default('BARU');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};