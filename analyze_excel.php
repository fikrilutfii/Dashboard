<?php
ini_set('memory_limit', '1G');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ChunkReadFilter implements IReadFilter
{
    private $startRow = 0;
    private $endRow = 0;

    public function __construct($startRow, $endRow) {
        $this->startRow = $startRow;
        $this->endRow = $endRow;
    }

    public function readCell(string $column, int $row, string $worksheetName = ''): bool {
        // Calculate numeric column index (A=1) - simpler: check string length
        // Just read columns A to Z for now to be safe
        if (strlen($column) > 1) {
             return false; 
        }
        
        if ($row >= $this->startRow && $row <= $this->endRow) {
            return true;
        }
        return false;
    }
}

$inputFileName = 'storage/app/templates/excel/template.xlsx';

try {
    $reader = IOFactory::createReaderForFile($inputFileName);
    $reader->setReadDataOnly(true);
    
    // Create filter for rows 1-100
    $filter = new ChunkReadFilter(1, 100);
    $reader->setReadFilter($filter);
    
    // Load file
    $spreadsheet = $reader->load($inputFileName);
    $worksheet = $spreadsheet->getActiveSheet();
    
    echo "Sheet Name: " . $worksheet->getTitle() . PHP_EOL;
    echo "Highest Row (loaded): " . $worksheet->getHighestRow() . PHP_EOL;
    
    echo "\n--- Content Preview (Rows 1-50) ---\n";
    for ($row = 1; $row <= 50; $row++) {
        $hasData = false;
        $rowData = [];
        for ($col = 'A'; $col <= 'K'; $col++) {
            $value = $worksheet->getCell($col . $row)->getValue();
            if ($value) {
                $rowData[] = "$col$row: $value";
                $hasData = true;
            }
        }
        if ($hasData) {
            echo implode(" | ", $rowData) . PHP_EOL;
        }
    }
} catch (\Exception $e) {
    echo 'Error loading file: ' . $e->getMessage() . PHP_EOL;
}
