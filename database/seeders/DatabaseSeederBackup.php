<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSeederBackup extends Seeder
{
    /**
     * Seed the MySQL database from PostgreSQL.
     */
    public function run(): void
    {
        $tables = [
            'tb_admin',
            'tb_kategori',
            'tb_barang',
            'tb_pesanan',
            'tb_detail_pesanan',
            'tb_meja'
        ];

        foreach ($tables as $table) {
            $records = DB::connection('pgsql')->table($table)->get();
            foreach ($records as $record) {
                DB::connection('mysql')->table($table)->insert((array)$record);
            }
        }

        Log::info('Database backup from Neon PostgreSQL to MySQL completed successfully.');
    }
}
