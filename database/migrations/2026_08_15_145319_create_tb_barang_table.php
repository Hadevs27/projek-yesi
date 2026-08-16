<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_barang', function (Blueprint $table) {
            $table->increments('id_barang');
            $table->integer('id_kategori');
            $table->string('nama_barang');
            $table->string('deskripsi_barang');
            $table->string('harga_barang');
            $table->integer('stok_barang');
            $table->string('foto_barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_barang');
    }
};
