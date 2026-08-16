<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_detail_pesanan', function (Blueprint $table) {
            $table->increments('id_detail');
            $table->string('id_pesanan');
            $table->integer('id_barang');
            $table->integer('jumlah_pesanan');
            $table->string('subtotal_harga');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_detail_pesanan');
    }
};
