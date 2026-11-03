<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('nama_produk');
    });
}

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('nama_produk')->nullable();
            $table->string('batch_produk')->nullable();
            $table->date('tanggal_produksi')->nullable();
        });
    }
};