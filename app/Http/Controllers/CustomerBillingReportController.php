<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\InvoiceLog;
use App\Models\Transaction;
use App\Exports\CustomerBillingExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
            return max(0, $inv->total_amount - $inv->paid_amount);
        });

        // Recent payments for this customer
        $recentPayments = InvoicePayment::whereIn('invoice_id', function($q) use ($customer, $division) {
            $q->select('id')->from('invoices')->where('customer_id', $customer->id)
              ->when($division, fn($d) => $d->where('division', $division));
        })->with('invoice')->latest('payment_date')->take(10)->get();

        return view('reports.billing.show', compact('customer', 'invoices', 'totalOutstanding', 'division', 'itemSearch', 'startDate', 'endDate', 'recentPayments'));
    }

    public function batchSettle(Request $request, Customer $customer)
    {
        $division = session('division');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $itemSearch = $request->input('item_search');

        $validated = $request->validate([
            'payment_date'     => 'required|date',
            'payment_method'   => 'required|string',
            'amount'           => 'required|numeric|min:1',
            'reference_number' => 'nullable|string|max:100',
            'notes'            => 'nullable|string|max:500',
        ]);

        $query = $customer->invoices();
        $this->applyInvoiceFilter($query, $division, $itemSearch, $startDate, $endDate);
        $invoices = $query->orderBy('invoice_date', 'asc')->orderBy('id', 'asc')->get();

        if ($invoices->isEmpty()) {
            return back()->with('error', 'Tidak ada faktur yang belum lunas pada periode yang dipilih.');
        }

        $totalOutstanding = $invoices->sum(function ($inv) {
            return max(0, (float)$inv->total_amount - (float)$inv->paid_amount);
        });

        if ($validated['amount'] > $totalOutstanding) {
            return back()->with('error', 'Jumlah pelunasan (Rp ' . number_format($validated['amount'], 0, ',', '.') . ') melebihi total sisa tagihan periode ini (Rp ' . number_format($totalOutstanding, 0, ',', '.') . ').');
        }

        $remainingToPay = (float)$validated['amount'];
        $settledCount = 0;
        $totalAllocated = 0;

        DB::transaction(function () use ($invoices, $validated, &$remainingToPay, &$settledCount, &$totalAllocated) {
            foreach ($invoices as $inv) {
                if ($remainingToPay <= 0) {
                    break;
                }

                $invRemaining = max(0, (float)$inv->total_amount - (float)$inv->paid_amount);
                if ($invRemaining <= 0) {
                    continue;
                }

                $allocAmount = min($remainingToPay, $invRemaining);

                $payment = InvoicePayment::create([
                    'invoice_id'       => $inv->id,
                    'payment_date'     => $validated['payment_date'],
                    'amount'           => $allocAmount,
                    'payment_method'   => $validated['payment_method'],
                    'reference_number' => $validated['reference_number'] ?? null,
                    'notes'            => 'Pelunasan Tagihan Klien Periode' . (!empty($validated['notes']) ? ': ' . $validated['notes'] : ''),
                ]);

                $inv->recalculatePayments();

                Transaction::create([
                    'type'           => 'credit',
                    'amount'         => $allocAmount,
                    'category'       => 'pelunasan_faktur',
                    'reference_type' => InvoicePayment::class,
                    'reference_id'   => $payment->id,
                    'description'    => 'Pelunasan Tagihan Periode Faktur #' . ($inv->faktur_number ?? $inv->invoice_number) . ' - ' . ($inv->customer->name ?? 'Customer') . (!empty($validated['notes']) ? ' (' . $validated['notes'] . ')' : ''),
                    'date'           => $validated['payment_date'],
                    'division'       => $inv->division,
                    'entity'         => $inv->entity ?? $inv->division,
                ]);

                InvoiceLog::create([
                    'invoice_id'  => $inv->id,
                    'user_id'     => Auth::id(),
                    'action'      => 'Periodic Settlement Payment',
                    'description' => 'Pelunasan tagihan periode sebesar Rp ' . number_format($allocAmount, 0, ',', '.') . ' dicatat (' . $validated['payment_method'] . ') pada tanggal ' . Carbon::parse($validated['payment_date'])->format('d/m/Y'),
                ]);

                $remainingToPay -= $allocAmount;
                $totalAllocated += $allocAmount;
                $settledCount++;
            }
        });

        return back()->with('success', 'Pelunasan tagihan klien per periode berhasil diproses sebesar Rp ' . number_format($totalAllocated, 0, ',', '.') . ' untuk ' . $settledCount . ' faktur.');
    }

    public function bulkSettle(Request $request)
    {
        $division = session('division');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $itemSearch = $request->input('item_search');

        $validated = $request->validate([
            'customer_ids'     => 'required|array|min:1',
            'customer_ids.*'   => 'exists:customers,id',
            'payment_date'     => 'required|date',
            'payment_method'   => 'required|string',
            'reference_number' => 'nullable|string|max:100',
            'notes'            => 'nullable|string|max:500',
        ]);

        $customers = Customer::whereIn('id', $validated['customer_ids'])->get();
        if ($customers->isEmpty()) {
            return back()->with('error', 'Tidak ada klien yang dipilih.');
        }

        $totalAllocated = 0;
        $settledInvoicesCount = 0;
        $settledCustomersCount = 0;

        DB::transaction(function () use ($customers, $division, $itemSearch, $startDate, $endDate, $validated, &$totalAllocated, &$settledInvoicesCount, &$settledCustomersCount) {
            foreach ($customers as $customer) {
                $query = $customer->invoices();
                $this->applyInvoiceFilter($query, $division, $itemSearch, $startDate, $endDate);
                $invoices = $query->orderBy('invoice_date', 'asc')->orderBy('id', 'asc')->get();

                if ($invoices->isEmpty()) {
                    continue;
                }

                $customerPaidSomething = false;

                foreach ($invoices as $inv) {
                    $invRemaining = max(0, (float)$inv->total_amount - (float)$inv->paid_amount);
                    if ($invRemaining <= 0) {
                        continue;
                    }

                    $payment = InvoicePayment::create([
                        'invoice_id'       => $inv->id,
                        'payment_date'     => $validated['payment_date'],
                        'amount'           => $invRemaining,
                        'payment_method'   => $validated['payment_method'],
                        'reference_number' => $validated['reference_number'] ?? null,
                        'notes'            => 'Pelunasan Masal Tagihan Periode' . (!empty($validated['notes']) ? ': ' . $validated['notes'] : ''),
                    ]);

                    $inv->recalculatePayments();

                    Transaction::create([
                        'type'           => 'credit',
                        'amount'         => $invRemaining,
                        'category'       => 'pelunasan_faktur',
                        'reference_type' => InvoicePayment::class,
                        'reference_id'   => $payment->id,
                        'description'    => 'Pelunasan Masal Periode Faktur #' . ($inv->faktur_number ?? $inv->invoice_number) . ' - ' . ($customer->name ?? 'Customer') . (!empty($validated['notes']) ? ' (' . $validated['notes'] . ')' : ''),
                        'date'           => $validated['payment_date'],
                        'division'       => $inv->division,
                        'entity'         => $inv->entity ?? $inv->division,
                    ]);

                    InvoiceLog::create([
                        'invoice_id'  => $inv->id,
                        'user_id'     => Auth::id(),
                        'action'      => 'Bulk Settlement Payment',
                        'description' => 'Pelunasan masal sebesar Rp ' . number_format($invRemaining, 0, ',', '.') . ' dicatat (' . $validated['payment_method'] . ') pada tanggal ' . Carbon::parse($validated['payment_date'])->format('d/m/Y'),
                    ]);

                    $totalAllocated += $invRemaining;
                    $settledInvoicesCount++;
                    $customerPaidSomething = true;
                }

                if ($customerPaidSomething) {
                    $settledCustomersCount++;
                }
            }
        });

        if ($settledInvoicesCount === 0) {
            return back()->with('error', 'Tidak ada faktur yang belum lunas pada klien dan periode yang dipilih.');
        }

        return back()->with('success', 'Pelunasan masal periode berhasil diproses untuk ' . $settledCustomersCount . ' klien (' . $settledInvoicesCount . ' faktur) dengan total Rp ' . number_format($totalAllocated, 0, ',', '.') . '.');
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
