<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceLog;
use App\Models\InvoicePayment;
use App\Models\Transaction;
use App\Models\Product; // Import Product
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

class InvoiceController extends Controller
{
    /**
     * Display a listing - Filters & Metrics
     */
    public function index(Request $request)
    {
        $division = $request->input('division', session('division', 'percetakan'));
        $hasPaymentsTable = Schema::hasTable('invoice_payments');
        
        $withRelations = ['customer', 'items'];
        if ($hasPaymentsTable) {
            $withRelations[] = 'payments';
        }

        $query = Invoice::with($withRelations)->latest('invoice_date');

        // Division Filter
        if ($division) {
            $query->where('division', $division);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($subQ) use ($search) {
                $subQ->where('faktur_number', 'like', '%' . $search . '%')
                     ->orWhere('invoice_number', 'like', '%' . $search . '%')
                     ->orWhereHas('customer', function($cq) use ($search) {
                         $cq->where('name', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%');
                     })
                     ->orWhereHas('items', function($iq) use ($search) {
                         $iq->where('item_name', 'like', '%' . $search . '%')
                            ->orWhere('product_code', 'like', '%' . $search . '%');
                     });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $filterDateBy = $request->input('filter_date_by', 'invoice_date');

        if ($startDate || $endDate) {
            if ($filterDateBy === 'payment_date' && $hasPaymentsTable) {
                $query->whereHas('payments', function ($pq) use ($startDate, $endDate) {
                    if ($startDate && $endDate) {
                        $pq->whereBetween('payment_date', [$startDate, $endDate]);
                    } elseif ($startDate) {
                        $pq->whereDate('payment_date', '>=', $startDate);
                    } elseif ($endDate) {
                        $pq->whereDate('payment_date', '<=', $endDate);
                    }
                });
            } else {
                if ($startDate && $endDate) {
                    $query->whereBetween('invoice_date', [$startDate, $endDate]);
                } elseif ($startDate) {
                    $query->whereDate('invoice_date', '>=', $startDate);
                } elseif ($endDate) {
                    $query->whereDate('invoice_date', '<=', $endDate);
                }
            }
        } elseif ($request->filled('date_filter')) {
            $query->whereDate('invoice_date', $request->date_filter);
        }

        // Calculate Metrics across the filtered scope
        $metricsQuery = clone $query;
        $allInvoices = $metricsQuery->get();

        $totalPenjualan = $allInvoices->sum('total_amount');
        $totalPelunasan = $allInvoices->sum(function($inv) use ($startDate, $endDate, $filterDateBy, $hasPaymentsTable) {
            if ($filterDateBy === 'payment_date' && ($startDate || $endDate) && $hasPaymentsTable && $inv->relationLoaded('payments')) {
                return $inv->payments->filter(function($p) use ($startDate, $endDate) {
                    $d = $p->payment_date ? $p->payment_date->format('Y-m-d') : null;
                    if (!$d) return false;
                    if ($startDate && $endDate) return $d >= $startDate && $d <= $endDate;
                    if ($startDate) return $d >= $startDate;
                    return $d <= $endDate;
                })->sum('amount');
            }
            return (float) $inv->paid_amount;
        });
        $totalSisaTagihan = $allInvoices->sum(function($inv) {
            return max(0, (float)$inv->total_amount - (float)$inv->paid_amount);
        });
        $countLunas = $allInvoices->where('status', 'lunas')->count();
        $countBelumLunas = $allInvoices->where('status', '!=', 'lunas')->count();

        $invoices = $query->paginate(15)->withQueryString();

        return view('invoices.index', compact(
            'invoices',
            'division',
            'totalPenjualan',
            'totalPelunasan',
            'totalSisaTagihan',
            'countLunas',
            'countBelumLunas',
            'startDate',
            'endDate',
            'filterDateBy'
        ));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all(); 
        return view('invoices.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM invoices WHERE Key_name = 'invoices_invoice_number_unique'");
            if (count($indexes) > 0) {
                \Illuminate\Support\Facades\Schema::table('invoices', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->dropUnique('invoices_invoice_number_unique');
                });
                DB::statement('ALTER TABLE invoices MODIFY invoice_number VARCHAR(255) NULL');
            }
        } catch (\Exception $e) {}

        $this->normalizeItemNumbers($request);

        $validated = $request->validate([
            'invoice_number' => 'nullable|string|max:100',
            'division' => 'required|in:percetakan,konfeksi',
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'payment_method' => 'required|in:cash,credit',
            'tenure' => 'nullable|integer|min:1',
            'items' => 'required|array|min:1',
            'items.*.product_code' => 'nullable|string',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => ['required', 'numeric', 'min:0.001', 'regex:/^\d+(?:\.\d{1,3})?$/'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0', 'regex:/^\d+(?:\.\d{1,2})?$/'],
        ]);

        $invoice = null;
        \Illuminate\Support\Facades\Log::info('Invoice Store: Before transaction');
        DB::transaction(function () use ($validated, &$invoice, $request) {
            \Illuminate\Support\Facades\Log::info('Invoice Store: Starting transaction');
            
            $fakturNumber = Invoice::generateFakturNumber($validated['division']);
            
            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number ?: 'NO-PO-' . $fakturNumber,
                'faktur_number'  => $fakturNumber,
                'division'       => $validated['division'],
                'customer_id'    => $validated['customer_id'],
                'invoice_date'   => $validated['invoice_date'],
                'due_date'       => $validated['due_date'],
                'status'         => 'belum_lunas',
                'total_amount'   => 0,
                'payment_method' => $validated['payment_method'],
                'tenure'         => $validated['tenure'],
            ]);
            \Illuminate\Support\Facades\Log::info('Invoice Store: Invoice created ID: ' . $invoice->id);

            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $code = trim($item['product_code'] ?? '');
                \Illuminate\Support\Facades\Log::info('Invoice Store: Processing item code: ' . $code);
                $product = $code !== '' ? Product::where('code', $code)->first() : null;
                $unitPrice = $item['unit_price']; 
                $subtotal = $item['quantity'] * $unitPrice;
                $totalAmount += $subtotal;

                InvoiceItem::create([
                    'invoice_id'   => $invoice->id,
                    'product_code' => $product ? $product->code : $code,
                    'item_name'    => $item['product_name'] ?? ($product ? $product->name : ''),
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $unitPrice,
                    'subtotal'     => $subtotal,
                ]);

                if ($product) {
                    \Illuminate\Support\Facades\Log::info('Invoice Store: Syncing stock for product code: ' . $product->code);
                    $product->syncStock(-$item['quantity'], 'out', 'Penjualan Invoice #' . $invoice->invoice_number, \App\Models\Invoice::class, $invoice->id);
                    \Illuminate\Support\Facades\Log::info('Invoice Store: Stock synced');
                }
            }

            \Illuminate\Support\Facades\Log::info('Invoice Store: Updating total amount');
            $invoice->update(['total_amount' => $totalAmount]);
            
            \Illuminate\Support\Facades\Log::info('Invoice Store: Loading customer');
            $invoice->load('customer');
            
            \Illuminate\Support\Facades\Log::info('Invoice Store: Syncing to receivable');
            $invoice->syncToReceivable();
            \Illuminate\Support\Facades\Log::info('Invoice Store: Synced to receivable');

            InvoiceLog::create([
                'invoice_id'  => $invoice->id,
                'user_id'     => Auth::id(),
                'action'      => 'Created',
                'description' => 'Invoice created with total: ' . number_format($totalAmount, 0, ',', '.') . ' (' . strtoupper($invoice->payment_method) . ')',
            ]);
        });
        \Illuminate\Support\Facades\Log::info('Invoice Store: Transaction completed');

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }


    public function edit(Invoice $invoice)
    {
        if ($invoice->status == 'lunas') {
            return redirect()->back()->with('error', 'Cannot edit paid invoice.');
        }

        $customers = Customer::all();
        $products = Product::all();
        return view('invoices.edit', compact('invoice', 'customers', 'products'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM invoices WHERE Key_name = 'invoices_invoice_number_unique'");
            if (count($indexes) > 0) {
                \Illuminate\Support\Facades\Schema::table('invoices', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->dropUnique('invoices_invoice_number_unique');
                });
                DB::statement('ALTER TABLE invoices MODIFY invoice_number VARCHAR(255) NULL');
            }
        } catch (\Exception $e) {}

        if ($invoice->status == 'lunas') {
             return redirect()->back()->with('error', 'Cannot edit paid invoice.');
        }

        $this->normalizeItemNumbers($request);

        $validated = $request->validate([
             'invoice_number' => 'nullable|string|max:100',
             'customer_id' => 'required|exists:customers,id',
             'invoice_date' => 'required|date',
             'due_date' => 'nullable|date|after_or_equal:invoice_date',
             'payment_method' => 'required|in:cash,credit',
             'tenure' => 'nullable|integer|min:1',
             'items' => 'required|array|min:1',
             'items.*.product_code' => 'nullable|string',
             'items.*.product_name' => 'required|string',
             'items.*.quantity' => ['required', 'numeric', 'min:0.001', 'regex:/^\d+(?:\.\d{1,3})?$/'],
             'items.*.unit_price' => ['required', 'numeric', 'min:0', 'regex:/^\d+(?:\.\d{1,2})?$/'],
        ]);

        DB::transaction(function () use ($validated, $invoice, $request) {
            $invoice->update([
                'invoice_number' => $request->invoice_number ?: 'NO-PO-' . $invoice->faktur_number,
                'customer_id'    => $validated['customer_id'],
                'invoice_date'   => $validated['invoice_date'],
                'due_date'       => $validated['due_date'],
                'payment_method' => $validated['payment_method'],
                'tenure'         => $validated['tenure'] ?? $invoice->tenure,
            ]);

            // Revert stock for old items before replacing
            foreach ($invoice->items as $oldItem) {
                $oldCode = trim($oldItem->product_code ?? '');
                if ($oldCode !== '') {
                    $oldProduct = Product::where('code', $oldCode)->first();
                    if ($oldProduct) {
                        $oldProduct->syncStock($oldItem->quantity, 'correction', 'Revert sebelum update Invoice #' . $invoice->invoice_number, \App\Models\Invoice::class, $invoice->id);
                    }
                }
            }

            // Replace items
            $invoice->items()->delete();
            
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $code = trim($item['product_code'] ?? '');
                $product = $code !== '' ? Product::where('code', $code)->first() : null;
                
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $subtotal;

                InvoiceItem::create([
                    'invoice_id'   => $invoice->id,
                    'product_code' => $product ? $product->code : $code,
                    'item_name'    => $item['product_name'] ?? ($product ? $product->name : ''),
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                    'subtotal'     => $subtotal,
                ]);

                // Deduct stock for new items
                if ($product) {
                    $product->syncStock(-$item['quantity'], 'out', 'Penjualan (Update) Invoice #' . $invoice->invoice_number, \App\Models\Invoice::class, $invoice->id);
                }
            }

            $invoice->update(['total_amount' => $totalAmount]);
            $invoice->load('customer');
            $invoice->syncToReceivable();

            InvoiceLog::create([
                'invoice_id'  => $invoice->id,
                'user_id'     => Auth::id(),
                'action'      => 'Updated',
                'description' => 'Invoice updated. New total: ' . number_format($totalAmount, 0, ',', '.') . ' (' . strtoupper($invoice->payment_method) . ')',
            ]);
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function show(Invoice $invoice)
    {
        $relations = ['items', 'logs', 'customer'];
        if (Schema::hasTable('invoice_payments')) {
            $relations[] = 'payments';
        }
        $invoice->load($relations);
        return view('invoices.show', compact('invoice'));
    }

    // Toggle Status Method
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $status = $request->status; // 'lunas' or 'belum_lunas'
        
        if (!in_array($status, ['lunas', 'belum_lunas'])) {
            return back()->with('error', 'Invalid status');
        }

        DB::transaction(function() use ($invoice, $status) {
            if ($status === 'lunas') {
                $remaining = max(0, (float)$invoice->total_amount - (float)$invoice->paid_amount);
                if ($remaining > 0) {
                    $payment = InvoicePayment::create([
                        'invoice_id'       => $invoice->id,
                        'payment_date'     => now()->toDateString(),
                        'amount'           => $remaining,
                        'payment_method'   => $invoice->payment_method === 'credit' ? 'Transfer Bank' : 'Tunai',
                        'notes'            => 'Pelunasan langsung dari tombol status',
                    ]);

                    Transaction::create([
                        'type'           => 'credit',
                        'amount'         => $remaining,
                        'category'       => 'pelunasan_faktur',
                        'reference_type' => InvoicePayment::class,
                        'reference_id'   => $payment->id,
                        'description'    => 'Pelunasan Faktur #' . ($invoice->faktur_number ?? $invoice->invoice_number) . ' - ' . ($invoice->customer->name ?? 'Customer'),
                        'date'           => now()->toDateString(),
                        'division'       => $invoice->division,
                        'entity'         => $invoice->entity ?? $invoice->division,
                    ]);
                }
                $invoice->recalculatePayments();
            } else {
                foreach ($invoice->payments as $pay) {
                    Transaction::where('reference_type', InvoicePayment::class)
                        ->where('reference_id', $pay->id)
                        ->delete();
                    $pay->delete();
                }
                $invoice->update([
                    'paid_amount' => 0,
                    'status'      => 'belum_lunas',
                ]);
                $invoice->syncToReceivable();
            }

            InvoiceLog::create([
                'invoice_id'  => $invoice->id,
                'user_id'     => Auth::id(),
                'action'      => 'Status Changed',
                'description' => 'Status changed to ' . ($status == 'lunas' ? 'Lunas' : 'Belum Lunas'),
            ]);
        });

        return back()->with('success', 'Status faktur berhasil diperbarui dan disinkronkan ke keuangan.');
    }

    public function storePayment(Request $request, Invoice $invoice)
    {
        $remaining = max(0, (float)$invoice->total_amount - (float)$invoice->paid_amount);

        $validated = $request->validate([
            'payment_date'     => 'required|date',
            'amount'           => 'required|numeric|min:1|max:' . ($remaining > 0 ? $remaining : $invoice->total_amount),
            'payment_method'   => 'required|string',
            'reference_number' => 'nullable|string|max:100',
            'notes'            => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($invoice, $validated) {
            $payment = InvoicePayment::create([
                'invoice_id'       => $invoice->id,
                'payment_date'     => $validated['payment_date'],
                'amount'           => $validated['amount'],
                'payment_method'   => $validated['payment_method'],
                'reference_number' => $validated['reference_number'] ?? null,
                'notes'            => $validated['notes'] ?? null,
            ]);

            $invoice->recalculatePayments();

            Transaction::create([
                'type'           => 'credit',
                'amount'         => $validated['amount'],
                'category'       => 'pelunasan_faktur',
                'reference_type' => InvoicePayment::class,
                'reference_id'   => $payment->id,
                'description'    => 'Pelunasan Faktur #' . ($invoice->faktur_number ?? $invoice->invoice_number) . ' - ' . ($invoice->customer->name ?? 'Customer') . (!empty($validated['notes']) ? ' (' . $validated['notes'] . ')' : ''),
                'date'           => $validated['payment_date'],
                'division'       => $invoice->division,
                'entity'         => $invoice->entity ?? $invoice->division,
            ]);

            InvoiceLog::create([
                'invoice_id'  => $invoice->id,
                'user_id'     => Auth::id(),
                'action'      => 'Payment Recorded',
                'description' => 'Pelunasan sebesar Rp ' . number_format($validated['amount'], 0, ',', '.') . ' dicatat (' . $validated['payment_method'] . ') pada tanggal ' . Carbon::parse($validated['payment_date'])->format('d/m/Y'),
            ]);
        });

        return back()->with('success', 'Pelunasan faktur berhasil dicatat dan masuk ke Laporan Keuangan.');
    }

    public function destroyPayment(Invoice $invoice, InvoicePayment $payment)
    {
        if ($payment->invoice_id !== $invoice->id) {
            abort(404);
        }

        DB::transaction(function () use ($invoice, $payment) {
            Transaction::where('reference_type', InvoicePayment::class)
                ->where('reference_id', $payment->id)
                ->delete();

            $amount = $payment->amount;
            $payment->delete();

            $invoice->recalculatePayments();

            InvoiceLog::create([
                'invoice_id'  => $invoice->id,
                'user_id'     => Auth::id(),
                'action'      => 'Payment Deleted',
                'description' => 'Pembayaran pelunasan sebesar Rp ' . number_format($amount, 0, ',', '.') . ' dibatalkan/dihapus.',
            ]);
        });

        return back()->with('success', 'Riwayat pembayaran berhasil dihapus.');
    }

    public function destroy(Invoice $invoice)
    {
        DB::transaction(function() use ($invoice) {
            // Delete associated transactions & payments
            foreach ($invoice->payments as $pay) {
                Transaction::where('reference_type', InvoicePayment::class)
                    ->where('reference_id', $pay->id)
                    ->delete();
                $pay->delete();
            }

            // Revert stock
            foreach ($invoice->items as $oldItem) {
                $oldProduct = Product::where('code', $oldItem->product_code)->first();
                if ($oldProduct) {
                    $oldProduct->syncStock($oldItem->quantity, 'correction', 'Pembatalan Faktur #' . $invoice->faktur_number, \App\Models\Invoice::class, $invoice->id);
                }
            }
            $invoice->items()->delete();
            $invoice->delete();
        });

        return redirect()->back()->with('success', 'Faktur berhasil dihapus beserta pengembalian stok barang.');
    }

    public function print(Invoice $invoice)
    {
        $invoice->load(['items', 'customer']);
        return view('invoices.print', compact('invoice'));
    }

    public function printExcel(Invoice $invoice)
    {
        $invoice->load(['customer', 'items']);
        
        $templatePath = storage_path('app/templates/Book1.xlsx');
        
        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template file not found.');
        }

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);
            
            // WAJIB: disable calculation engine agar tidak resolve external references
            $spreadsheet->getCalculationEngine()->disableCalculationCache();
            \PhpOffice\PhpSpreadsheet\Calculation\Calculation::getInstance($spreadsheet)
                ->disableCalculationCache();

            $sheet = $spreadsheet->getActiveSheet();

            // Set Header values
            $sheet->setCellValue('H1', "Bandung, " . $invoice->invoice_date->translatedFormat('d F Y'));
            $sheet->setCellValue('K1', null);
            $sheet->setCellValue('AB1', "Bandung, " . $invoice->invoice_date->translatedFormat('d F Y'));
            $sheet->setCellValue('AD1', null);

            $sheet->setCellValue('H2', "Kepada Yth.");
            $sheet->setCellValue('AB2', "Kepada Yth.");

            $sheet->setCellValue('H3', $invoice->customer->name ?? '');
            $sheet->setCellValue('AB3', $invoice->customer->name ?? '');

            $sheet->setCellValueExplicit('H5', $invoice->invoice_number ?? '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('AB5', $invoice->invoice_number ?? '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            
            $sheet->setCellValueExplicit('C6', $invoice->faktur_number, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('U6', $invoice->faktur_number, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            // Line Items
            $items = $invoice->items->take(15);
            foreach ($items as $i => $item) {
                $row = 15 + $i;
                
                // Faktur (kiri)
                $sheet->setCellValue("A{$row}", $i + 1);
                $sheet->setCellValue("B{$row}", $item->product_code);
                $sheet->setCellValue("C{$row}", $item->item_name);
                $sheet->setCellValue("F{$row}", $item->quantity);
                $sheet->setCellValue("H{$row}", "PCS");
                $sheet->setCellValue("K{$row}", $item->unit_price);
                $sheet->setCellValue("P{$row}", $item->quantity * $item->unit_price);

                // Surat Jalan (kanan)
                $sheet->setCellValue("R{$row}", $i + 1);
                $sheet->setCellValue("T{$row}", $item->product_code);
                $sheet->setCellValue("U{$row}", $item->item_name);
                $sheet->setCellValue("AA{$row}", $item->quantity);
                $sheet->setCellValue("AB{$row}", "PCS");
            }

            for ($r = 15 + count($items); $r <= 29; $r++) {
                $sheet->setCellValue('A'.$r, null);
                $sheet->setCellValue('B'.$r, null);
                $sheet->setCellValue('C'.$r, null);
                $sheet->setCellValue('F'.$r, null);
                $sheet->setCellValue('H'.$r, null);
                $sheet->setCellValue('K'.$r, null);
                $sheet->setCellValue('P'.$r, null);
                $sheet->setCellValue('R'.$r, null);
                $sheet->setCellValue('T'.$r, null);
                $sheet->setCellValue('U'.$r, null);
                $sheet->setCellValue('AA'.$r, null);
                $sheet->setCellValue('AB'.$r, null);
            }

            // Total
            $sheet->setCellValue('P30', $invoice->total_amount);

            \PhpOffice\PhpSpreadsheet\Calculation\Calculation::getInstance($spreadsheet)->clearCalculationCache();

            $filename = 'INV-' . preg_replace('/[^A-Za-z0-9\-_]/', '_', $invoice->faktur_number ?? $invoice->invoice_number) . '.xlsx';

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->setPreCalculateFormulas(false); // WAJIB: jangan recalculate

            $invoice->update(['is_printed' => true]);

            return response()->stream(function () use ($writer) {
                $writer->save('php://output');
            }, 200, [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control'       => 'max-age=0',
                'Pragma'              => 'no-cache',
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting excel: ' . $e->getMessage());
        }
    }

    public function printCombined(Invoice $invoice)
    {
        $invoice->load(['items', 'customer']);
        return view('invoices.print_combined', compact('invoice'));
    }

    public function printReport(Request $request)
    {
        $query = Invoice::with('customer', 'items')->latest();

        if ($request->has('division') && $request->division != '') {
            $query->where('division', $request->division);
        }
        if ($request->has('search') && $request->search != '') {
            $query->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('date_filter') && $request->date_filter != '') {
            $query->whereDate('invoice_date', $request->date_filter);
        }

        $invoices = $query->get();
        $filters  = $request->only(['search', 'status', 'date_filter', 'division']);
        return view('invoices.print_report', compact('invoices', 'filters'));
    }

    private function normalizeItemNumbers(Request $request): void
    {
        $items = $request->input('items', []);

        if (!is_array($items)) {
            return;
        }

        foreach ($items as $index => $item) {
            if (!is_array($item)) {
                continue;
            }

            foreach (['quantity', 'unit_price'] as $field) {
                if (array_key_exists($field, $item)) {
                    $items[$index][$field] = str_replace(',', '.', trim((string) $item[$field]));
                }
            }
        }

        $request->merge(['items' => $items]);
    }
}
