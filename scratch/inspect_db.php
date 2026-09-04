<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "FarmInvoices: " . \App\Models\FarmInvoice::count() . "\n";
echo "FarmCoops: " . \App\Models\FarmCoop::count() . "\n";
echo "FarmOperationalLogs: " . \App\Models\FarmOperationalLog::count() . "\n";
echo "FarmTransportations: " . \App\Models\FarmTransportation::count() . "\n";
echo "FarmExpenses: " . \App\Models\FarmExpense::count() . "\n";
echo "FarmPayrolls: " . \App\Models\FarmPayroll::count() . "\n";
echo "FarmCustomers: " . \App\Models\FarmCustomer::count() . "\n";
echo "FarmSuppliers: " . \App\Models\FarmSupplier::count() . "\n";
