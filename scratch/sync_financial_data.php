<?php

use App\Models\Invoice;
use App\Models\Kasbon;
use App\Models\Payroll;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Starting data migration...\n";

DB::transaction(function() {
    // 1. Sync Invoices
    $invoices = Invoice::where('status', '!=', 'lunas')->get();
    echo "Syncing " . $invoices->count() . " unpaid invoices...\n";
    foreach ($invoices as $invoice) {
        $invoice->syncToReceivable();
    }

    // 2. Sync Kasbon
    $kasbons = Kasbon::where('status', 'aktif')->get();
    echo "Syncing " . $kasbons->count() . " active kasbons...\n";
    foreach ($kasbons as $kasbon) {
        $kasbon->syncToReceivable();
    }

    // 3. Sync Payroll
    $payrolls = Payroll::whereNotIn('status', ['lunas', 'paid'])->get();
    echo "Syncing " . $payrolls->count() . " unpaid payrolls...\n";
    foreach ($payrolls as $payroll) {
        $payroll->syncToDebt();
    }

    // 4. Sync Purchases
    $purchases = Purchase::where('status', '!=', 'lunas')->get();
    echo "Syncing " . $purchases->count() . " unpaid purchases...\n";
    foreach ($purchases as $purchase) {
        $purchase->syncToDebt();
    }
});

echo "Data migration completed successfully.\n";
