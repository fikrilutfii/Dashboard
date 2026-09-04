<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use App\Models\Customer;
use App\Models\Product;

class LimitReadFilter implements IReadFilter
{
    private $maxRow;
    private $maxColIndex;

    public function __construct($maxRow, $maxCol) {
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

class ImportExcelDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-excel-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import customers and product master data from FAKTUR TOKO ANDI 2026.xlsx';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '2G');
        
        $filePath = base_path('FAKTUR TOKO ANDI 2026.xlsx');
        if (!file_exists($filePath)) {
            $this->error("File Excel tidak ditemukan di: $filePath");
            return 1;
        }

        $this->info("Memulai proses impor data...");

        // 1. Impor Customers dari sheet 'PERUSAHAAN'
        $this->info("\n[1/2] Membaca sheet 'PERUSAHAAN'...");
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter(new LimitReadFilter(150, 'L'));
        $reader->setLoadSheetsOnly('PERUSAHAAN');
        
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        
        $customerCount = 0;
        // Loncat baris pertama (header)
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $name = isset($row[0]) ? trim((string)$row[0]) : '';
            
            if (empty($name) || strtolower($name) === 'nama perusahaan') {
                continue;
            }

            Customer::updateOrCreate(
                ['name' => $name],
                ['division' => 'percetakan']
            );
            $customerCount++;
        }
        $this->info("Selesai mengimpor $customerCount customer.");

        // Clean memory
        unset($spreadsheet);
        gc_collect_cycles();

        // 2. Impor Products dari sheet 'KODE BRG'
        $this->info("\n[2/2] Membaca sheet 'KODE BRG'...");
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter(new LimitReadFilter(3000, 'L'));
        $reader->setLoadSheetsOnly('KODE BRG');
        
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $productCount = 0;
        $bar = $this->output->createProgressBar(count($rows) - 1);
        $bar->start();

        // Loncat baris pertama (header)
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            
            $codeRawB = isset($row[1]) ? trim((string)$row[1]) : '';
            $codeRawC = isset($row[2]) ? trim((string)$row[2]) : '';
            $name = isset($row[3]) ? trim((string)$row[3]) : '';
            $unit = isset($row[4]) ? trim((string)$row[4]) : 'pcs';
            $stockRaw = isset($row[9]) ? $row[9] : 0;
            $priceRaw = isset($row[10]) ? $row[10] : 0;

            // Tentukan kode barang (B prioritaskan, C cadangan)
            $codeRaw = !empty($codeRawB) ? $codeRawB : $codeRawC;
            $code = $this->cleanCode($codeRaw);

            if (empty($code) || empty($name) || strtolower($name) === 'nama barang' || strtolower($code) === 'kode barang') {
                $bar->advance();
                continue;
            }

            // Bersihkan unit
            if (empty($unit)) {
                $unit = 'pcs';
            }

            // Bersihkan stock
            $stock = is_numeric($stockRaw) ? (int)$stockRaw : 0;

            // Bersihkan price
            $price = is_numeric($priceRaw) ? (float)$priceRaw : 0;

            Product::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'unit' => strtolower($unit),
                    'price' => $price,
                    'stock' => $stock,
                    'division' => 'percetakan'
                ]
            );

            $productCount++;
            $bar->advance();
        }
        $bar->finish();
        
        $this->info("\n\nSelesai mengimpor $productCount produk.");
        $this->info("Semua data berhasil diimpor!");

        return 0;
    }

    /**
     * Membersihkan kode barang dari karakter-karakter tidak penting atau scientific notation.
     */
    private function cleanCode($code)
    {
        if ($code === null || $code === '') return '';
        $code = trim((string)$code);
        
        // Memperbaiki scientific notation jika ada
        if (stripos($code, 'E+') !== false) {
            $code = number_format((float)$code, 0, '', '');
        }
        
        // Menghapus karakter di akhir seperti koma, titik, ellipsis, dll.
        $code = rtrim($code, '.,… ');
        return $code;
    }
}
