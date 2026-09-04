<?php
ini_set('memory_limit', '4G');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileType = IOFactory::identify('FAKTUR TOKO ANDI 2026.xlsx');
$reader = IOFactory::createReader($inputFileType);
$reader->setReadDataOnly(true);

$sheetsToRead = ['KODE BRG', 'FAKTUR TOKO ANDI'];

foreach ($sheetsToRead as $sheetName) {
    echo "=== Sheet: $sheetName ===\n";
    $reader->setLoadSheetsOnly($sheetName);
    $spreadsheet = $reader->load('FAKTUR TOKO ANDI 2026.xlsx');
    $sheet = $spreadsheet->getActiveSheet();
    
    $rows = $sheet->toArray();
    for ($i = 0; $i < min(5, count($rows)); $i++) {
        echo json_encode($rows[$i]) . "\n";
    }
    echo "\n";
}
