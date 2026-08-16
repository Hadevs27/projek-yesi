<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_meja', function (Blueprint $table) {
            $table->increments('id_meja');
            $table->string('kode_meja')->unique();
            $table->string('nama_meja');
            $table->string('token_qr')->unique();
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'MAINTENANCE'])->default('ACTIVE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_meja');
    }
};
