<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Exports\CustomerBillingExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class CustomerBillingReportController extends Controller
{
    private function applyInvoiceFilter($query, $division, $itemSearch, $startDate, $endDate)
    {
        $query->where('status', '!=', 'lunas');
        
        if ($division) {
            $query->where('division', $division);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('invoice_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('invoice_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('invoice_date', '<=', $endDate);
        }

        if ($itemSearch) {
            $query->where(function($subQ) use ($itemSearch) {
                $subQ->where('invoice_number', 'like', '%' . $itemSearch . '%')
                     ->orWhere('faktur_number', 'like', '%' . $itemSearch . '%')
                     ->orWhereHas('items', function ($iq) use ($itemSearch) {
                         $iq->where('item_name', 'like', '%' . $itemSearch . '%')
                            ->orWhere('product_code', 'like', '%' . $itemSearch . '%');
                     });
            });
        }
    }

    public function index(Request $request)
    {
        $division = session('division');
        $search = $request->input('search');
        $itemSearch = $request->input('item_search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Customer::query();

        if ($division) {
            $query->where('division', $division);
        }

        $invoiceFilter = function ($q) use ($division, $itemSearch, $startDate, $endDate) {
            $this->applyInvoiceFilter($q, $division, $itemSearch, $startDate, $endDate);
        };

        $query->whereHas('invoices', $invoiceFilter);

        if ($search) {
            $searchStr = trim($search);
            $query->where(function($sq) use ($searchStr) {
                $sq->where('name', 'like', '%' . $searchStr . '%')
                   ->orWhereHas('invoices', function($iq) use ($searchStr) {
                       $iq->where('faktur_number', 'like', '%' . $searchStr . '%')
                          ->orWhere('invoice_number', 'like', '%' . $searchStr . '%')
                          ->orWhereHas('items', function($itemQ) use ($searchStr) {
                              $itemQ->where('item_name', 'like', '%' . $searchStr . '%')
                                    ->orWhere('product_code', 'like', '%' . $searchStr . '%');
                          });
                   });
            });
        }

        $customers = $query->with(['invoices' => $invoiceFilter])->latest()->paginate(15);

        $metricsQuery = Invoice::query();
        $this->applyInvoiceFilter($metricsQuery, $division, $itemSearch, $startDate, $endDate);

        $totalOutstanding = $metricsQuery->sum(DB::raw('total_amount - paid_amount'));
        $unpaidInvoicesCount = $metricsQuery->count();
        $unpaidCustomersCount = $query->count();

        return view('reports.billing.index', compact(
            'customers',
            'totalOutstanding',
            'unpaidInvoicesCount',
            'unpaidCustomersCount',
            'division',
            'search',
            'itemSearch',
            'startDate',
            'endDate'
        ));
    }

    public function show(Request $request, Customer $customer)
    {
        $division = session('division');
        $itemSearch = $request->input('item_search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = $customer->invoices();
        $this->applyInvoiceFilter($query, $division, $itemSearch, $startDate, $endDate);
        $query->with('items');
        
        $invoices = $query->orderBy('invoice_date', 'asc')->get();
        
        $totalOutstanding = $invoices->sum(function ($inv) {
            return $inv->total_amount - $inv->paid_amount;
        });

        return view('reports.billing.show', compact('customer', 'invoices', 'totalOutstanding', 'division', 'itemSearch', 'startDate', 'endDate'));
    }

    public function print(Request $request, Customer $customer)
    {
        $division = session('division');
        $itemSearch = $request->input('item_search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = $customer->invoices();
        $this->applyInvoiceFilter($query, $division, $itemSearch, $startDate, $endDate);
        $query->with('items');
        
        $invoices = $query->orderBy('invoice_date', 'asc')->get();
        
        $totalOutstanding = $invoices->sum(function ($inv) {
            return $inv->total_amount - $inv->paid_amount;
        });

        return view('reports.billing.print', compact('customer', 'invoices', 'totalOutstanding', 'division'));
    }

    public function exportPdf(Request $request, Customer $customer)
    {
        $division = session('division');
        $itemSearch = $request->input('item_search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = $customer->invoices();
        $this->applyInvoiceFilter($query, $division, $itemSearch, $startDate, $endDate);
        $query->with('items');
        
        $invoices = $query->orderBy('invoice_date', 'asc')->get();
        
        $totalOutstanding = $invoices->sum(function ($inv) {
            return $inv->total_amount - $inv->paid_amount;
        });

        $pdf = Pdf::loadView('reports.billing.pdf', compact('customer', 'invoices', 'totalOutstanding', 'division'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('tagihan-' . Str::slug($customer->name) . '-' . now()->format('Ymd') . '.pdf');
    }

    public function exportExcel(Request $request, Customer $customer)
    {
        $division = session('division');
        $itemSearch = $request->input('item_search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = $customer->invoices();
        $this->applyInvoiceFilter($query, $division, $itemSearch, $startDate, $endDate);
        $query->with('items');
        
        $invoices = $query->orderBy('invoice_date', 'asc')->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'SURAT PERNYATAAN TAGIHAN (STATEMENT OF ACCOUNT)');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A2', 'Klien:');
        $sheet->setCellValue('B2', $customer->name);
        
        $sheet->setCellValue('A3', 'Telepon:');
        $sheet->setCellValue('B3', $customer->phone ?? '-');
        
        $sheet->setCellValue('A4', 'Email:');
        $sheet->setCellValue('B4', $customer->email ?? '-');
        
        $sheet->setCellValue('A5', 'Alamat:');
        $sheet->setCellValue('B5', $customer->address ?? '-');
        
        $sheet->setCellValue('A6', 'Tanggal Cetak:');
        $sheet->setCellValue('B6', Carbon::now()->format('d-m-Y H:i'));

        // Headers
        $headers = [
            'Nomor Invoice',
            'Nomor Faktur',
            'Tanggal Faktur',
            'Jatuh Tempo',
            'Total Tagihan (Rp)',
            'Sudah Dibayar (Rp)',
            'Sisa Tagihan (Rp)'
        ];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '8', $header);
            $sheet->getStyle($col . '8')->getFont()->setBold(true);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        $row = 9;
        $totalAmount = 0;
        $totalPaid = 0;
        $totalRemaining = 0;

        foreach ($invoices as $invoice) {
            $remaining = $invoice->total_amount - $invoice->paid_amount;
            $totalAmount += $invoice->total_amount;
            $totalPaid += $invoice->paid_amount;
            $totalRemaining += $remaining;

            $sheet->setCellValue('A' . $row, $invoice->invoice_number);
            $sheet->setCellValue('B' . $row, $invoice->faktur_number ?? '-');
            $sheet->setCellValue('C' . $row, $invoice->invoice_date ? Carbon::parse($invoice->invoice_date)->format('d/m/Y') : '-');
            $sheet->setCellValue('D' . $row, $invoice->due_date ? Carbon::parse($invoice->due_date)->format('d/m/Y') : '-');
            $sheet->setCellValue('E' . $row, (double)$invoice->total_amount);
            $sheet->setCellValue('F' . $row, (double)$invoice->paid_amount);
            $sheet->setCellValue('G' . $row, (double)$remaining);
            $row++;
        }

        $row++;
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->getStyle('A' . $row . ':G' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('E' . $row, (double)$totalAmount);
        $sheet->setCellValue('F' . $row, (double)$totalPaid);
        $sheet->setCellValue('G' . $row, (double)$totalRemaining);

        $filename = 'tagihan-' . Str::slug($customer->name) . '-' . now()->format('Ymd') . '.xlsx';
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
