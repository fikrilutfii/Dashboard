<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

try {
    DB::statement('ALTER TABLE invoice_items MODIFY quantity DECIMAL(15,3) NOT NULL');
    DB::statement('ALTER TABLE purchase_items MODIFY quantity DECIMAL(15,3) NOT NULL');
    DB::statement('ALTER TABLE products MODIFY stock DECIMAL(15,3) NOT NULL DEFAULT 0');
    DB::statement('ALTER TABLE stock_logs MODIFY quantity DECIMAL(15,3) NOT NULL');
    DB::statement('ALTER TABLE stock_logs MODIFY new_stock DECIMAL(15,3) NOT NULL');

    Artisan::call('optimize:clear');

    echo '<h1>Update Qty Desimal Berhasil</h1>';
    echo '<p>Qty dan Harga Pembelian Bahan sekarang menerima angka desimal seperti <strong>1,5</strong>, <strong>1.5</strong>, <strong>507,20</strong>, atau <strong>507.20</strong>.</p>';
    echo '<p>Cache aplikasi sudah dibersihkan. Silakan tutup halaman ini, lalu hapus file <code>update_qty_desimal.php</code> dari hosting demi keamanan.</p>';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h1>Update Gagal</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
}
