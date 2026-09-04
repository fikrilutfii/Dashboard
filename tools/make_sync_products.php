<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$products = DB::table('products')->get();

$sql = "";
$batch = [];
foreach ($products as $p) {
    $code = addslashes($p->code);
    $name = addslashes($p->name);
    $unit = addslashes($p->unit);
    $price = $p->price ?: 0;
    $stock = $p->stock ?: 0;
    $shared_stock_code = $p->shared_stock_code ? "'" . addslashes($p->shared_stock_code) . "'" : "NULL";
    $division = $p->division ? "'" . addslashes($p->division) . "'" : "NULL";
    $created_at = $p->created_at ? "'{$p->created_at}'" : "NULL";
    $updated_at = $p->updated_at ? "'{$p->updated_at}'" : "NULL";

    $batch[] = "('{$code}', '{$name}', '{$unit}', {$price}, {$stock}, {$shared_stock_code}, {$division}, {$created_at}, {$updated_at})";
    
    if (count($batch) >= 100) {
        $sql .= "INSERT IGNORE INTO `products` (`code`, `name`, `unit`, `price`, `stock`, `shared_stock_code`, `division`, `created_at`, `updated_at`) VALUES \n" . implode(",\n", $batch) . ";\n";
        $batch = [];
    }
}

if (count($batch) > 0) {
    $sql .= "INSERT IGNORE INTO `products` (`code`, `name`, `unit`, `price`, `stock`, `shared_stock_code`, `division`, `created_at`, `updated_at`) VALUES \n" . implode(",\n", $batch) . ";\n";
}

file_put_contents(__DIR__ . '/Sync_Products_Only.sql', $sql);

$zip = new ZipArchive();
if ($zip->open(__DIR__ . '/Sync_Products_Only.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $zip->addFile(__DIR__ . '/Sync_Products_Only.sql', 'Sync_Products_Only.sql');
    $zip->close();
    echo "Sync_Products_Only.zip created with " . count($products) . " products.\n";
}
