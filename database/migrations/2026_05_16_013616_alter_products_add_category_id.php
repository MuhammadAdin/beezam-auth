<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambah category_id setelah kolom id
            $table->foreignId('category_id')
                  ->after('id')
                  ->constrained('categories')
                  ->onDelete('cascade');

            // Hapus kolom yang dipindah ke categories
            $table->dropColumn(['batch_produk', 'tanggal_produksi']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->string('batch_produk')->nullable();
            $table->date('tanggal_produksi')->nullable();
        });
    }
};