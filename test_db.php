<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pesanan = \App\Models\Pesanan::whereNotNull('bukti_pembayaran')->orderBy('tanggal_pesanan', 'desc')->first();
if ($pesanan) {
    echo "ID: " . $pesanan->id_pesanan . "\n";
    echo "Length of bukti: " . strlen($pesanan->bukti_pembayaran) . "\n";
    echo "Prefix: " . substr($pesanan->bukti_pembayaran, 0, 50) . "\n";
} else {
    echo "No records found.\n";
}
