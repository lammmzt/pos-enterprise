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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->foreignId('id_user')->constrained('users', 'id_user'); // Pelanggan
            $table->foreignId('id_kasir')->nullable()->constrained('users', 'id_user'); // Kasir jika offline
            $table->string('nomor_invoice')->unique();
            $table->decimal('total_harga', 15, 2);
            $table->enum('status_pembayaran', ['belum_bayar', 'lunas', 'gagal', 'refund'])->default('belum_bayar');
            $table->string('metode_pembayaran')->nullable(); // midtrans, tunai, transfer
            $table->enum('tipe_pesanan', ['online', 'pos']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};