<?php
require 'vendor/autoload.php';

$templatePath = 'storage/app/templates/Book1.xlsx';
if (!file_exists($templatePath)) {
    die("Template not found!\n");
}

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);
$sheet = $spreadsheet->getActiveSheet();

echo "--- CELLS ROWS 1-10 ---\n";
for ($row = 1; $row <= 10; $row++) {
    for ($col = 'A'; $col != 'AJ'; $col++) {
        $cell = $col . $row;
        $val = $sheet->getCell($cell)->getValue();
        if ($val !== null && $val !== '') {
            echo "$cell: '$val'\n";
        }
    }
}
