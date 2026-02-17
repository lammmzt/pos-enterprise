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
        Schema::create('batch_produk', function (Blueprint $table) {
            $table->id('id_batch');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk')->cascadeOnDelete();
            $table->foreignId('id_pembelian')->nullable()->constrained('pembelian', 'id_pembelian')->nullOnDelete();
            $table->integer('jumlah');
            $table->decimal('harga_beli', 15, 2); // Harga spesifik saat batch ini masuk
            $table->date('tanggal_kedaluwarsa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_produk');
    }
};