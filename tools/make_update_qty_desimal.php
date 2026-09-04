<?php

$files = [
    'app/Http/Controllers/PurchaseController.php',
    'app/Http/Controllers/InvoiceController.php',
    'app/Http/Middleware/RoleAccessMiddleware.php',
    'app/Http/Controllers/UserAccessController.php',
    'app/Models/Product.php',
    'resources/views/purchases/create.blade.php',
    'resources/views/purchases/edit.blade.php',
    'resources/views/invoices/create.blade.php',
    'resources/views/invoices/edit.blade.php',
    'resources/views/user-access/index.blade.php',
    'resources/views/components/sidebar.blade.php',
    'app/Http/Controllers/CompanyReceivableController.php',
    'app/Http/Controllers/CustomerBillingReportController.php',
    'app/Http/Controllers/DashboardController.php',
    'routes/web.php',
    'database/migrations/2026_08_03_000000_allow_decimal_purchase_quantities.php',
    'database/migrations/2026_08_03_000001_grant_admin_3_billing_report_access.php',
    'update_qty_desimal.php',
    'update_admin_3_billing_access.php',
    'update_billing_and_access.php',
    'UPDATE_QTY_DESIMAL.txt',
    'UPDATE_AKSES_ADMIN_3.txt',
    'UPDATE_TAGIHAN_DAN_AKSES.txt',
];

$zip = new ZipArchive();
$zipPath = __DIR__ . '/Update_Log_Konveksi.zip';

if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    throw new RuntimeException('Gagal membuat file ZIP.');
}

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (!is_file($path)) {
        throw new RuntimeException("File tidak ditemukan: {$file}");
    }

    $zip->addFile($path, $file);
}

$zip->close();

echo "Update_Log_Konveksi.zip berhasil dibuat.\n";
