<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Invoice;
use App\Models\CompanyDebt;

echo "INVOICES:\n";
$invoices = Invoice::select('invoice_date', 'total_amount', 'paid_amount')->get();
foreach ($invoices as $i) {
    echo "Date: {$i->invoice_date}, Total: {$i->total_amount}, Paid: {$i->paid_amount}\n";
}

echo "\nCOMPANY DEBTS:\n";
$debts = CompanyDebt::select('created_at', 'due_date', 'amount', 'remaining_amount')->get();
foreach ($debts as $d) {
    echo "Created: {$d->created_at}, Due: {$d->due_date}, Amount: {$d->amount}, Remaining: {$d->remaining_amount}\n";
}
