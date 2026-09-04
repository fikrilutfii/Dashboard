<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

try {
    DB::table('users')
        ->where('username', 'admin_3')
        ->update(['role' => 'admin3']);

    Artisan::call('optimize:clear');

    echo '<h1>Akses admin_3 Berhasil Diperbarui</h1>';
    echo '<p>Akun <strong>admin_3</strong> sekarang dapat membuka Laporan Tagihan Klien.</p>';
    echo '<p>Silakan logout lalu login kembali sebagai admin_3. Setelah itu hapus file <code>update_admin_3_billing_access.php</code> dari hosting demi keamanan.</p>';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h1>Update Gagal</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
}
