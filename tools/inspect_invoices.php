<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Invoice;

$invoices = Invoice::orderBy('id', 'desc')->limit(5)->get();
foreach($invoices as $inv) {
    echo "ID: {$inv->id}, Faktur: {$inv->faktur_number}, Amount: {$inv->total_amount}, Date: {$inv->created_at}\n";
}
