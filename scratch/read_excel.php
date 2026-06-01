<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$file = 'Book1.xlsx';
$spreadsheet = IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();
$data = $sheet->toArray(null, true, true, true);

$rowCount = 0;
foreach ($data as $rowIndex => $row) {
    echo "Row $rowIndex: " . implode(' | ', $row) . PHP_EOL;
    $rowCount++;
    if ($rowCount >= 20) break;
}
