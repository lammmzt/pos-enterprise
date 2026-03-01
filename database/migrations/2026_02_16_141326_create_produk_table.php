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
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            // Relasi ke tabel kategori
            $table->foreignId('id_kategori')->constrained('kategori', 'id_kategori')->cascadeOnDelete();
            $table->string('nama');
            $table->string('sku')->unique()->nullable(); // Stock Keeping Unit 
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_dasar', 15, 2)->default(0); // HPP
            $table->decimal('harga_jual', 15, 2);
            $table->integer('stok')->default(0);
            $table->string('gambar')->nullable();
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};