<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Purchase;

try {
    $purchases = Purchase::where('status', 'lunas')->get();
    $count = 0;

    foreach ($purchases as $purchase) {
        if (!$purchase->debt) {
            $purchase->syncToDebt();
            $count++;
        }
    }

    echo "<h1>Berhasil!</h1>";
    echo "<p>Sebanyak <strong>$count</strong> transaksi Belanja Tunai (Cash) telah berhasil disinkronkan ke Daftar Pembayaran.</p>";
    echo "<p>Sekarang nilai 'Total Pembayaran' di Dashboard Anda sudah akurat.</p>";
    echo '<a href="/">Kembali ke Beranda</a>';
} catch (\Exception $e) {
    echo "<h1>Gagal!</h1>";
    echo "<p>Terjadi kesalahan: " . $e->getMessage() . "</p>";
}
