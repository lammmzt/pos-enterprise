<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_pembelian', function (Blueprint $table) {
            $table->id('id_detail_pembelian');
            // Relasi ke tabel pembelian utama
            $table->foreignId('id_pembelian')->constrained('pembelian', 'id_pembelian')->cascadeOnDelete();
            // Relasi ke produk yang disuplai
            $table->foreignId('id_produk')->constrained('produk', 'id_produk')->cascadeOnDelete();
            
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2); // Harga beli dari supplier per item
            $table->decimal('subtotal', 15, 2); // jumlah * harga_satuan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian');
    }
};