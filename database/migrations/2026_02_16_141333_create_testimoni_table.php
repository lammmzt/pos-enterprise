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
        Schema::create('testimonis', function (Blueprint $table) {
            $table->id('id_testimoni');
            // Relasi ke pelanggan yang memberikan ulasan
            $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan')->cascadeOnDelete();
            
            $table->integer('rating')->default(5); // Bintang 1 sampai 5
            $table->text('ulasan')->nullable();
            $table->boolean('status_tampil')->default(false); // Admin bisa filter mana yang mau ditampilkan di Frontend
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonis');
    }
};