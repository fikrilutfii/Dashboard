<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileType = IOFactory::identify('FAKTUR TOKO ANDI 2026.xlsx');
$reader = IOFactory::createReader($inputFileType);

// Only read the structure, not the data yet to save memory
$sheetNames = $reader->listWorksheetNames('FAKTUR TOKO ANDI 2026.xlsx');

echo "Sheets:\n";
foreach ($sheetNames as $sheetName) {
    echo "- $sheetName\n";
}
