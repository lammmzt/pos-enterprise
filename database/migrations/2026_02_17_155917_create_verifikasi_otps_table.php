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
        Schema::create('verifikasi_otp', function (Blueprint $table) {
            $table->id('id_otp');
            $table->foreignId('id_user')->constrained('users', 'id_user')->cascadeOnDelete();
            $table->string('kode_otp', 10); // Misal: 6 digit angka
            $table->enum('tipe', ['registrasi', 'reset_password', 'login', 'ganti_pin']);
            $table->timestamp('waktu_kedaluwarsa'); // Batas waktu OTP (misal 5 menit)
            $table->boolean('status_terpakai')->default(false); // Cegah OTP dipakai 2x
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_otps');
    }
};