<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    Illuminate\Support\Facades\Artisan::call('optimize:clear');

    echo '<h1>Update Berhasil</h1>';
    echo '<p>Logika tagihan berdasarkan jatuh tempo dan Manajemen Akses akun tracker sudah aktif.</p>';
    echo '<p>Hapus file <code>update_billing_and_access.php</code> dari hosting setelah halaman ini berhasil dibuka.</p>';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h1>Update Gagal</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
}
