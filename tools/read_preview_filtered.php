<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ChunkReadFilter implements IReadFilter
{
    private $startRow = 0;
    private $endRow = 0;

    public function setRows($startRow, $chunkSize) {
        $this->startRow = $startRow;
        $this->endRow = $startRow + $chunkSize;
    }

    public function readCell(string $columnAddress, int $row, string $worksheetName = ''): bool {
        if ($row >= $this->startRow && $row < $this->endRow) {
            return true;
        }
        return false;
    }
}

$inputFileType = IOFactory::identify('FAKTUR TOKO ANDI 2026.xlsx');
$reader = IOFactory::createReader($inputFileType);
$reader->setReadDataOnly(true);

$chunkFilter = new ChunkReadFilter();
$chunkFilter->setRows(1, 5);
$reader->setReadFilter($chunkFilter);

$sheetsToRead = ['PERUSAHAAN', 'KODE BRG', 'FAKTUR TOKO ANDI'];

foreach ($sheetsToRead as $sheetName) {
    echo "=== Sheet: $sheetName ===\n";
    $reader->setLoadSheetsOnly($sheetName);
    
    $spreadsheet = $reader->load('FAKTUR TOKO ANDI 2026.xlsx');
    $sheet = $spreadsheet->getActiveSheet();
    
    $rows = $sheet->toArray();
    foreach ($rows as $index => $row) {
        $isEmpty = true;
        foreach($row as $cell) {
            if ($cell !== null) { $isEmpty = false; break; }
        }
        if (!$isEmpty) {
            echo json_encode($row) . "\n";
        }
    }
    echo "\n";
}
