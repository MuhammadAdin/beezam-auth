<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom posisi QR ke label_templates
        Schema::table('label_templates', function (Blueprint $table) {
            $table->integer('qr_x')->default(20)->after('file_template');
            $table->integer('qr_y')->default(20)->after('qr_x');
            $table->integer('qr_size')->default(150)->after('qr_y');
        });

        // Tambah kolom label_template_id ke categories
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('label_template_id')
                  ->nullable()
                  ->after('tanggal_produksi')
                  ->constrained('label_templates')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['label_template_id']);
            $table->dropColumn('label_template_id');
        });

        Schema::table('label_templates', function (Blueprint $table) {
            $table->dropColumn(['qr_x', 'qr_y', 'qr_size']);
        });
    }
};