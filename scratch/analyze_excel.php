<?php
require 'vendor/autoload.php';
// Load template
$file = 'Book1.xlsx';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();

echo "Highest Column: " . $sheet->getHighestColumn() . "\n";
echo "Highest Row: " . $sheet->getHighestRow() . "\n\n";

$data = $sheet->toArray(null, true, true, true);
$letters = range('A', 'Z');
$letters = array_merge($letters, array_map(function($c){ return 'A'.$c; }, range('A', 'Z')));

foreach ($data as $rowIndex => $row) {
    if ($rowIndex > 50) break;
    $rowValues = [];
    foreach ($row as $col => $val) {
        if (!empty($val)) {
            $rowValues[] = "$col: $val";
        }
    }
    if (!empty($rowValues)) {
        echo "Row $rowIndex: " . implode(', ', $rowValues) . "\n";
    }
}
