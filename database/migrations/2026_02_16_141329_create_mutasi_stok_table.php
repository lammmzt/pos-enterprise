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
        Schema::create('mutasi_stok', function (Blueprint $table) {
            $table->id('id_mutasi');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk')->cascadeOnDelete();
            $table->foreignId('id_batch')->nullable()->constrained('batch_produk', 'id_batch')->nullOnDelete();
            $table->foreignId('id_user')->constrained('users', 'id_user'); // Admin/Kasir yang memproses
            $table->enum('tipe', ['masuk', 'keluar', 'penyesuaian', 'kedaluwarsa']);
            $table->integer('jumlah');
            $table->string('tipe_referensi')->nullable(); // Pesanan, Pembelian, dll
            $table->unsignedBigInteger('id_referensi')->nullable(); 
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok');
    }
};