<?php
$zip = new ZipArchive();
if ($zip->open(__DIR__ . '/Update_Log_Konveksi.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $files = [
        'resources/views/components/sidebar.blade.php',
        'resources/views/components/summary-card.blade.php',
        'resources/views/components/quick-action.blade.php',
        'resources/views/dashboard.blade.php',
        'app/Http/Controllers/InvoiceController.php',
        'app/Http/Controllers/CustomerBillingReportController.php',
        'app/Http/Controllers/CompanyReceivableController.php',
        'app/Http/Controllers/CompanyDebtController.php',
        'app/Http/Controllers/FinanceController.php',
        'app/Http/Controllers/PurchaseController.php',
        'app/Http/Controllers/PayrollController.php',
        'app/Http/Controllers/KasbonController.php',
        'app/Http/Controllers/ActivityLogController.php',
        'app/Http/Controllers/DashboardController.php',
        'app/Http/Middleware/RoleAccessMiddleware.php',
        'app/Models/Invoice.php',
        'app/Models/Product.php',
        'app/Models/Purchase.php',
        'app/Models/CompanyReceivable.php',
        'app/Models/CompanyDebt.php',
        'app/Models/KasbonRepayment.php',
        'app/Exports/CustomerBillingExport.php',
        'resources/views/division-selection.blade.php',
        'resources/views/activity-logs/index.blade.php',
        'resources/views/layouts/navigation.blade.php',
        'resources/views/purchases/index.blade.php',
        'resources/views/purchases/edit.blade.php',
        'resources/views/purchases/create.blade.php',
        'resources/views/company_debts/index.blade.php',
        'resources/views/company_receivables/index.blade.php',
        'resources/views/finance/transactions.blade.php',
        'resources/views/invoices/index.blade.php',
        'resources/views/invoices/create.blade.php',
        'resources/views/invoices/print.blade.php',
        'resources/views/reports/billing/pdf.blade.php',
        'resources/views/reports/billing/print.blade.php',
        'resources/views/reports/billing/index.blade.php',
        'resources/views/reports/billing/show.blade.php',
        'resources/views/kasbons/index.blade.php',
        'resources/views/kasbons/edit.blade.php',
        'resources/views/kasbons/pdf.blade.php',
        'resources/views/payrolls/index.blade.php',
        'resources/views/payrolls/edit.blade.php',
        'resources/views/payrolls/slip.blade.php',
        'routes/web.php',
    ];

    foreach ($files as $file) {
        $fullPath = __DIR__ . '/' . $file;
        if (file_exists($fullPath)) {
            $zip->addFile($fullPath, $file);
        }
    }

    if (file_exists(__DIR__ . '/public/images/logo.jpg')) {
        $zip->addFile(__DIR__ . '/public/images/logo.jpg', 'public/images/logo.jpg');
    }

    $zip->close();
    echo "Update_Log_Konveksi.zip successfully created with all updated files.\n";
}
