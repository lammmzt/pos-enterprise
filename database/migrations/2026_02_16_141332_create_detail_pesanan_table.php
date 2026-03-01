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
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_mangkuk'); // Berubah dari id_pesanan
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_batch')->nullable();
            
            $table->integer('jumlah');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            
            $table->timestamps();

            $table->foreign('id_mangkuk')->references('id_mangkuk')->on('mangkuk_pesanan')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
            $table->foreign('id_batch')->references('id_batch')->on('batch_produk')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};