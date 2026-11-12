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
    Schema::create('pesanans', function (Blueprint $table) {
        $table->id();
        $table->string('kode_transaksi')->unique();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->integer('total_harga');
        $table->text('alamat_pengiriman');
        $table->string('no_hp');
        $table->string('foto_resep')->nullable();
        $table->enum('status', ['Menunggu Verifikasi Resep', 'Diproses', 'Dikirim', 'Selesai', 'Ditolak'])->default('Diproses');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
