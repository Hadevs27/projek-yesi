<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_pesanan', function (Blueprint $table) {
            $table->string('id_pesanan')->primary();
            $table->string('nama_pesanan');
            $table->string('alamat_pesanan');
            $table->string('no_hp_pesanan');
            $table->string('email_pesanan');
            $table->string('total_harga_pesanan');
            $table->enum('status_pesanan', ['Menunggu Pembayaran', 'Diproses', 'Dikirim', 'Selesai', 'Ditolak', 'Dibatalkan'])->default('Menunggu Pembayaran');
            $table->date('tanggal_pesanan');
            $table->enum('jenis_pembayaran', ['COD', 'Transfer', 'QRIS']);
            $table->string('snap_token')->nullable();
            
            // New column for QR table connection
            $table->integer('id_meja')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pesanan');
    }
};
