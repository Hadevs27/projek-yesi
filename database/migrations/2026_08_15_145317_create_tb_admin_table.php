<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_admin', function (Blueprint $table) {
            $table->increments('id_admin');
            $table->string('username_admin');
            $table->string('password_admin');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_admin');
    }
};
