<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama
        DB::statement('TRUNCATE TABLE tb_detail_pesanan CASCADE');
        DB::statement('TRUNCATE TABLE tb_pesanan CASCADE');
        DB::statement('TRUNCATE TABLE tb_barang CASCADE');
        DB::statement('TRUNCATE TABLE tb_kategori CASCADE');
        DB::statement('TRUNCATE TABLE tb_meja CASCADE');

        // Reset sequence (auto increment)
        DB::statement('ALTER SEQUENCE tb_kategori_id_kategori_seq RESTART WITH 1');
        DB::statement('ALTER SEQUENCE tb_barang_id_barang_seq RESTART WITH 1');
        DB::statement('ALTER SEQUENCE tb_meja_id_meja_seq RESTART WITH 1');

        // 1. Kategori
        $kategori_ice_cream = DB::table('tb_kategori')->insertGetId([
            'nama_kategori' => 'Ice Cream'
        ], 'id_kategori');

        $kategori_milk_tea = DB::table('tb_kategori')->insertGetId([
            'nama_kategori' => 'Milk Tea'
        ], 'id_kategori');

        $kategori_fruit_tea = DB::table('tb_kategori')->insertGetId([
            'nama_kategori' => 'Fresh Fruit Tea'
        ], 'id_kategori');

        // 2. Barang (Menu Ai-CHA)
        DB::table('tb_barang')->insert([
            [
                'id_kategori' => $kategori_ice_cream,
                'nama_barang' => 'Boba Sundae',
                'deskripsi_barang' => 'Es krim vanilla lembut dengan saus brown sugar dan topping boba kenyal.',
                'harga_barang' => '16000',
                'stok_barang' => 100,
                'foto_barang' => 'default.png' // Pastikan gambar default.png ada atau ubah nanti
            ],
            [
                'id_kategori' => $kategori_fruit_tea,
                'nama_barang' => 'Mango Oats Jasmine Tea',
                'deskripsi_barang' => 'Paduan teh jasmine segar dengan potongan mangga manis dan oats yang sehat.',
                'harga_barang' => '16000',
                'stok_barang' => 50,
                'foto_barang' => 'default.png'
            ],
            [
                'id_kategori' => $kategori_milk_tea,
                'nama_barang' => 'Supreme Mixed Milk Tea',
                'deskripsi_barang' => 'Milk tea klasik dengan campuran berbagai topping lezat seperti boba, jelly, dan oats.',
                'harga_barang' => '22000',
                'stok_barang' => 75,
                'foto_barang' => 'default.png'
            ],
            [
                'id_kategori' => $kategori_ice_cream,
                'nama_barang' => 'Mi-Shake Strawberry',
                'deskripsi_barang' => 'Minuman segar hasil perpaduan es krim vanilla dan selai strawberry asli.',
                'harga_barang' => '16000',
                'stok_barang' => 120,
                'foto_barang' => 'default.png'
            ]
        ]);

        // 3. Meja
        DB::table('tb_meja')->insert([
            [
                'kode_meja' => 'MEJA-01',
                'nama_meja' => 'Meja Nomor 01',
                'token_qr' => 'TOKEN-MEJA-01',
                'status' => 'ACTIVE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_meja' => 'MEJA-02',
                'nama_meja' => 'Meja Nomor 02',
                'token_qr' => 'TOKEN-MEJA-02',
                'status' => 'ACTIVE',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 4. Admin Default (jika belum ada)
        $adminExists = DB::table('tb_admin')->where('username_admin', 'admin')->exists();
        if (!$adminExists) {
            DB::table('tb_admin')->insert([
                'username_admin' => 'admin',
                'password_admin' => md5('admin') // Sesuai dengan format lama (MD5)
            ]);
        }
    }
}
