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
        Schema::create('mangkuk_pesanan', function (Blueprint $table) {
            $table->id('id_mangkuk');
            $table->unsignedBigInteger('id_pesanan');
            $table->string('nama_pemesan'); // Contoh: Budi, Sisca
            $table->string('tipe_kuah');
            $table->integer('level_pedas'); // 0-5
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mangkuk_pesanans');
    }
};