<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin
        $admins = DB::connection('mysql')->table('tb_admin')->get();
        foreach ($admins as $admin) {
            DB::table('tb_admin')->insert((array)$admin);
        }

        // 2. Kategori
        $kategoris = DB::connection('mysql')->table('tb_kategori')->get();
        foreach ($kategoris as $kategori) {
            DB::table('tb_kategori')->insert((array)$kategori);
        }

        // 3. Barang
        $barangs = DB::connection('mysql')->table('tb_barang')->get();
        foreach ($barangs as $barang) {
            DB::table('tb_barang')->insert((array)$barang);
        }

        // 4. Pesanan
        $pesanans = DB::connection('mysql')->table('tb_pesanan')->get();
        foreach ($pesanans as $pesanan) {
            DB::table('tb_pesanan')->insert((array)$pesanan);
        }

        // 5. Detail Pesanan
        $detail_pesanans = DB::connection('mysql')->table('tb_detail_pesanan')->get();
        foreach ($detail_pesanans as $detail) {
            DB::table('tb_detail_pesanan')->insert((array)$detail);
        }

        Log::info('Database migration from MySQL to Neon PostgreSQL completed successfully.');
    }
}
