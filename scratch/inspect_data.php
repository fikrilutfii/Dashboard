<?php
ini_set('memory_limit', '2G');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class LimitReadFilter implements IReadFilter
{
    private $maxRow;
    private $maxColIndex;

    public function __construct($maxRow = 100, $maxCol = 'L') {
        $this->maxRow = $maxRow;
        $this->maxColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($maxCol);
    }

    public function readCell(string $columnAddress, int $row, string $worksheetName = ''): bool {
        if ($row > $this->maxRow) {
            return false;
        }
        $colIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columnAddress);
        if ($colIndex > $this->maxColIndex) {
            return false;
        }
        return true;
    }
}

$file = 'FAKTUR TOKO ANDI 2026.xlsx';
$sheets = ['AMNA FOAM', 'FERDI', 'LAP. PROFIT', 'REKAP HARMONI'];

foreach ($sheets as $sheetName) {
    echo "=== INSPECTING SHEET: $sheetName ===\n";
    $reader = IOFactory::createReaderForFile($file);
    $reader->setReadDataOnly(true);
    $reader->setReadFilter(new LimitReadFilter(15, 'N'));
    $reader->setLoadSheetsOnly($sheetName);
    try {
        $spreadsheet = $reader->load($file);
        $sheet = $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray();
        for ($i = 0; $i < min(15, count($rows)); $i++) {
            $row = $rows[$i];
            $trimmedRow = $row;
            while (!empty($trimmedRow) && end($trimmedRow) === null) {
                array_pop($trimmedRow);
            }
            if (!empty($trimmedRow)) {
                echo "Row " . ($i + 1) . ": " . json_encode($trimmedRow) . "\n";
            }
        }
    } catch (\Exception $e) {
        echo "Error loading $sheetName: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
