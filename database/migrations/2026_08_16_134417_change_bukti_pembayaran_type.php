<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE tb_pesanan ALTER COLUMN bukti_pembayaran TYPE TEXT');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE tb_pesanan ALTER COLUMN bukti_pembayaran TYPE VARCHAR(255)');
    }
};
