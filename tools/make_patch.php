<?php
// Create patch.zip for code changes
$zip = new ZipArchive();
if ($zip->open(__DIR__ . '/Update_Fitur_Baru.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $zip->addFile(__DIR__ . '/resources/views/auth/login.blade.php', 'resources/views/auth/login.blade.php');
    $zip->addFile(__DIR__ . '/resources/views/invoices/index.blade.php', 'resources/views/invoices/index.blade.php');
    $zip->addFile(__DIR__ . '/app/Http/Controllers/InvoiceController.php', 'app/Http/Controllers/InvoiceController.php');
    $zip->close();
    echo "Update_Fitur_Baru.zip created.\n";
}

// Create ZIP for Database_Siap_Upload.sql to avoid phpMyAdmin timeout
$zipDb = new ZipArchive();
if ($zipDb->open(__DIR__ . '/Database_Siap_Upload.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $zipDb->addFile(__DIR__ . '/Database_Siap_Upload.sql', 'Database_Siap_Upload.sql');
    $zipDb->close();
    echo "Database_Siap_Upload.zip created.\n";
}
