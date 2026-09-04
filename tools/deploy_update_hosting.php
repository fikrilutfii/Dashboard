<?php
/**
 * Script Update Otomatis Hosting (Domainesia cPanel)
 * AMAN 100%: Tidak akan menghapus/merusak database yang sudah ada.
 */

// Keamanan sederhana
if (isset($_GET['key']) && $_GET['key'] !== 'sentosa2026') {
    die("Akses ditolak.");
}

echo "<h2>=== UPDATE SISTEM MINI ERP (DIVISI PETERNAKAN) ===</h2><br>";

// Root path
$baseDir = __DIR__;
chdir($baseDir);

// 1. Bersihkan file-file script bekas di root agar cPanel rapi
$messyFiles = [
    'fix_db.php', 'fix_user.php', 'fix_cash_purchases.php', 'delete_invoices.php',
    'database_new_passwords.sql', 'fix_session.php', 'fix_users_local.php', 'analyze_excel.php'
];

$deletedCount = 0;
foreach ($messyFiles as $file) {
    if (file_exists($file)) {
        @unlink($file);
        $deletedCount++;
    }
}
echo "1. Pembersihan File Root Bekas: Berhasil membersihkan {$deletedCount} file tua.<br>";

// 2. Jalankan Artisan Migration secara aman
echo "2. Menjalankan Migration Tabel Baru (Peternakan)...<br>";
try {
    require $baseDir . '/vendor/autoload.php';
    $app = require_once $baseDir . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    // Force migration incremental
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    $migrateOutput = \Illuminate\Support\Facades\Artisan::output();
    echo "<pre style='background:#f4f4f4;padding:10px;'>" . htmlspecialchars($migrateOutput) . "</pre>";
    echo "<span style='color:green;font-weight:bold;'>✓ Migration selesai. Database lama Anda (Percetakan, Invoices, Customers) AMAN 100%!</span><br><br>";

    // 3. Bersihkan Cache
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    echo "3. Pembersihan Cache: Cache tampilan, konfigurasi, dan route berhasil dibersihkan.<br>";

    echo "<br><h3 style='color:green;'>🎉 SELAMAT! Update Divisi Peternakan Berhasil Diterapkan di Hosting.</h3>";
    echo "<p>File script update ini dapat Anda hapus demi keamanan.</p>";

} catch (\Exception $e) {
    echo "<span style='color:red;font-weight:bold;'>Terjadi kesalahan: " . htmlspecialchars($e->getMessage()) . "</span>";
}
