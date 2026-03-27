<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InvoiceExportController extends Controller
{
    public function export($id)
    {
        $invoice = Invoice::with(['items', 'customer'])->findOrFail($id);
        
        $templatePath = storage_path('app/templates/excel/template.xlsx');
        
        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template file not found.');
        }

        try {
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Header Data
            // Date: J3
            $sheet->setCellValue('J3', $invoice->date->format('d M Y')); // Or use Excel date format
            
            // Customer: I5
            $sheet->setCellValue('I5', $invoice->customer ? $invoice->customer->name : 'N/A');

            // Invoice No: Assuming C8 (overwriting formula for now) or find a better place
            // $sheet->setCellValue('C8', $invoice->invoice_number); 

            // Items - Start Row 14
            $startRow = 14;
            $currentRow = $startRow;
            $items = $invoice->items;

            foreach ($items as $index => $item) {
                // Check if we exceed a reasonable limit (e.g. 15 items)
                if ($currentRow > 28) {
                    break; // Or handle pagination/insert rows (complex)
                }

                $sheet->setCellValue('A' . $currentRow, $index + 1);
                $sheet->setCellValue('B' . $currentRow, $item->product_name); // Assuming product_name is stored or relation
                $sheet->setCellValue('F' . $currentRow, $item->quantity);
                $sheet->setCellValue('H' . $currentRow, $item->unit ?? 'pcs'); 
                $sheet->setCellValue('K' . $currentRow, $item->price);
                
                $currentRow++;
            }

            // Totals
            // Assuming K28, K29, K30 based on labels I28, I29, I30
            // Calculate totals
            $subtotal = $items->sum(function($item) { return $item->quantity * $item->price; });
            $discount = 0; // Or from invoice if exists
            $total = $subtotal - $discount;

            $sheet->setCellValue('K28', $subtotal);
            $sheet->setCellValue('K29', $discount);
            $sheet->setCellValue('K30', $total);

            // Output
            $writer = new Xlsx($spreadsheet);
            
            $fileName = 'Invoice-' . $invoice->invoice_number . '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode($fileName) .'"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting invoice: ' . $e->getMessage());
        }
    }
}
