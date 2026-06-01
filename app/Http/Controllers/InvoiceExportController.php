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

            // Header Kiri (Faktur)
            $sheet->setCellValue('K1', $invoice->invoice_date->format('d F Y'));
            $sheet->setCellValue('K2', $invoice->customer ? $invoice->customer->name : 'N/A');
            $sheet->setCellValue('K3', $invoice->customer ? $invoice->customer->address : '');
            $sheet->setCellValue('H4', $invoice->invoice_number);

            // Header Kanan (Surat Jalan)
            $sheet->setCellValue('AB1', $invoice->invoice_date->format('d F Y'));
            $sheet->setCellValue('AB2', $invoice->customer ? $invoice->customer->name : 'N/A');
            $sheet->setCellValue('AB3', $invoice->customer ? $invoice->customer->address : '');
            $sheet->setCellValue('Y4', $invoice->invoice_number);

            // Items - Start Row 15
            $startRow = 15;
            $currentRow = $startRow;
            $items = $invoice->items;

            $subtotal = 0;

            foreach ($items as $index => $item) {
                if ($currentRow > 24) {
                    break;
                }
                
                $itemSubtotal = $item->quantity * $item->unit_price;
                $subtotal += $itemSubtotal;

                // Sisi Kiri (Faktur)
                $sheet->setCellValue('A' . $currentRow, $index + 1);
                $sheet->setCellValue('B' . $currentRow, $item->product_code);
                $sheet->setCellValue('C' . $currentRow, $item->item_name);
                $sheet->setCellValue('F' . $currentRow, $item->quantity);
                $sheet->setCellValue('H' . $currentRow, 'PCS'); 
                $sheet->setCellValue('K' . $currentRow, $item->unit_price);
                $sheet->setCellValue('P' . $currentRow, $itemSubtotal);
                
                // Sisi Kanan (Surat Jalan)
                $sheet->setCellValue('R' . $currentRow, $index + 1);
                $sheet->setCellValue('T' . $currentRow, $item->product_code);
                $sheet->setCellValue('U' . $currentRow, $item->item_name);
                $sheet->setCellValue('AA' . $currentRow, $item->quantity);
                $sheet->setCellValue('AB' . $currentRow, 'PCS');

                $currentRow++;
            }

            // Totals
            $sheet->setCellValue('P30', $subtotal);

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
