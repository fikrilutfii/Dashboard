<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::statement('SET FOREIGN_KEY_CHECKS=0;');
DB::table('company_receivables')->truncate();
DB::table('invoice_items')->truncate();
DB::table('invoices')->truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "Faktur test berhasil dihapus secara permanen. Faktur berikutnya otomatis 936001.\n";
