<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\Illuminate\Support\Facades\DB::statement("UPDATE tb_barang SET created_at = NOW() - INTERVAL '30 days' WHERE id_barang < 6");
echo "Dates updated!\n";
