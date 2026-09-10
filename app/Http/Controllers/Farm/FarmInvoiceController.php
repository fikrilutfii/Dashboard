<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmCustomer;
use App\Models\FarmCustomerPrice;
use App\Models\FarmInvoice;
use App\Models\FarmInvoiceItem;
use App\Models\FarmInvoicePayment;
use App\Models\FarmProduct;
use App\Models\FarmSender;
use App\Models\FarmTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FarmInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = FarmInvoice::with(['customer', 'sender', 'payments', 'items']);

        // Search by Invoice Number or Customer Name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($c) use ($search) {
                      $c->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Sender
        if ($request->filled('sender_id')) {
            $query->where('farm_sender_id', $request->sender_id);
        }

        // Filter by Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('invoice_date', [$request->start_date, $request->end_date]);
        }

        $invoices = $query->orderBy('invoice_date', 'desc')->orderBy('id', 'desc')->paginate(15);
        $senders = FarmSender::orderBy('name')->get();
        $customers = FarmCustomer::orderBy('name')->get();

        return view('farm.invoices.index', compact('invoices', 'senders', 'customers'));
    }

    public function create()
    {
        $senders = FarmSender::orderBy('is_default', 'desc')->orderBy('name')->get();
        $customers = FarmCustomer::orderBy('name')->get();
        $products = FarmProduct::orderBy('category')->orderBy('name')->get();

        // Auto generate Invoice Number (e.g. INV-RPA-202609-0001)
        $monthYear = Carbon::now()->format('Ym');
        $latest = FarmInvoice::where('invoice_number', 'like', "INV-RPA-{$monthYear}-%")->latest('id')->first();
        $nextNumber = 1;
        if ($latest && preg_match('/INV-RPA-\d+-(\d+)/', $latest->invoice_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $generatedInvoiceNumber = sprintf("INV-RPA-%s-%04d", $monthYear, $nextNumber);

        return view('farm.invoices.create', compact('senders', 'customers', 'products', 'generatedInvoiceNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:farm_invoices,invoice_number',
            'farm_sender_id' => 'required|exists:farm_senders,id',
            'farm_customer_id' => 'required|exists:farm_customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.weight_kg' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $qtyOrWeight = floatval($item['weight_kg']) > 0 ? floatval($item['weight_kg']) : floatval($item['qty_ekor'] ?? 1);
                $totalAmount += $qtyOrWeight * floatval($item['unit_price']);
            }

            $paidAmount = floatval($request->paid_amount ?? 0);
            $remainingAmount = max(0, $totalAmount - $paidAmount);
            $status = 'belum_lunas';
            if ($paidAmount >= $totalAmount && $totalAmount > 0) {
                $status = 'lunas';
            } elseif ($paidAmount > 0) {
                $status = 'sebagian';
            }

            $invoice = FarmInvoice::create([
                'invoice_number' => $request->invoice_number,
                'farm_sender_id' => $request->farm_sender_id,
                'farm_customer_id' => $request->farm_customer_id,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'status' => $status,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $itemData) {
                $weightKg = floatval($itemData['weight_kg'] ?? 0);
                $qtyEkor = intval($itemData['qty_ekor'] ?? 0);
                $unitPrice = floatval($itemData['unit_price']);
                $calcQty = $weightKg > 0 ? $weightKg : ($qtyEkor > 0 ? $qtyEkor : 1);
                $lineTotal = $calcQty * $unitPrice;

                $invoiceItem = FarmInvoiceItem::create([
                    'farm_invoice_id' => $invoice->id,
                    'farm_product_id' => $itemData['farm_product_id'] ?? null,
                    'product_code' => $itemData['product_code'] ?? null,
                    'item_name' => $itemData['item_name'],
                    'weight_kg' => $weightKg,
                    'qty_ekor' => $qtyEkor,
                    'unit' => $itemData['unit'] ?? 'kg',
                    'unit_price' => $unitPrice,
                    'total_price' => $lineTotal,
                ]);

                // Reduce Product Stock if linked
                if (!empty($itemData['farm_product_id'])) {
                    $product = FarmProduct::find($itemData['farm_product_id']);
                    if ($product) {
                        $product->decrement('current_stock_kg', $weightKg);
                        if ($qtyEkor > 0) {
                            $product->decrement('current_stock_ekor', $qtyEkor);
                        }
                    }
                }
            }

            // If initial payment exists, record it
            if ($paidAmount > 0) {
                FarmInvoicePayment::create([
                    'farm_invoice_id' => $invoice->id,
                    'payment_date' => $request->invoice_date,
                    'amount' => $paidAmount,
                    'payment_method' => $request->payment_method ?? 'tunai',
                    'notes' => 'Pembayaran awal saat pembuatan faktur',
                ]);

                FarmTransaction::create([
                    'type' => 'pemasukan',
                    'category' => 'penjualan_karkas',
                    'description' => "Pembayaran faktur {$invoice->invoice_number} ({$invoice->customer->name})",
                    'amount' => $paidAmount,
                    'transaction_date' => $request->invoice_date,
                    'reference_type' => FarmInvoice::class,
                    'reference_id' => $invoice->id,
                ]);
            }

            DB::commit();
            return redirect()->route('farm.invoices.show', $invoice->id)->with('success', 'Faktur Penjualan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat faktur: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $invoice = FarmInvoice::with(['customer', 'sender', 'items.product', 'payments', 'transportation'])->findOrFail($id);
        return view('farm.invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = FarmInvoice::with(['customer', 'sender', 'items'])->findOrFail($id);
        $senders = FarmSender::orderBy('is_default', 'desc')->orderBy('name')->get();
        $customers = FarmCustomer::orderBy('name')->get();
        $products = FarmProduct::orderBy('category')->orderBy('name')->get();

        return view('farm.invoices.edit', compact('invoice', 'senders', 'customers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $invoice = FarmInvoice::with('items')->findOrFail($id);

        $request->validate([
            'farm_sender_id' => 'required|exists:farm_senders,id',
            'farm_customer_id' => 'required|exists:farm_customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.weight_kg' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Restore previous stock
            foreach ($invoice->items as $oldItem) {
                if ($oldItem->farm_product_id) {
                    $prod = FarmProduct::find($oldItem->farm_product_id);
                    if ($prod) {
                        $prod->increment('current_stock_kg', $oldItem->weight_kg);
                        if ($oldItem->qty_ekor > 0) {
                            $prod->increment('current_stock_ekor', $oldItem->qty_ekor);
                        }
                    }
                }
            }

            // Delete old items
            $invoice->items()->delete();

            // Calculate new total
            $totalAmount = 0;
            foreach ($request->items as $itemData) {
                $weightKg = floatval($itemData['weight_kg'] ?? 0);
                $qtyEkor = intval($itemData['qty_ekor'] ?? 0);
                $unitPrice = floatval($itemData['unit_price']);
                $calcQty = $weightKg > 0 ? $weightKg : ($qtyEkor > 0 ? $qtyEkor : 1);
                $lineTotal = $calcQty * $unitPrice;
                $totalAmount += $lineTotal;

                FarmInvoiceItem::create([
                    'farm_invoice_id' => $invoice->id,
                    'farm_product_id' => $itemData['farm_product_id'] ?? null,
                    'product_code' => $itemData['product_code'] ?? null,
                    'item_name' => $itemData['item_name'],
                    'weight_kg' => $weightKg,
                    'qty_ekor' => $qtyEkor,
                    'unit' => $itemData['unit'] ?? 'kg',
                    'unit_price' => $unitPrice,
                    'total_price' => $lineTotal,
                ]);

                // Reduce new stock
                if (!empty($itemData['farm_product_id'])) {
                    $prod = FarmProduct::find($itemData['farm_product_id']);
                    if ($prod) {
                        $prod->decrement('current_stock_kg', $weightKg);
                        if ($qtyEkor > 0) {
                            $prod->decrement('current_stock_ekor', $qtyEkor);
                        }
                    }
                }
            }

            $invoice->update([
                'farm_sender_id' => $request->farm_sender_id,
                'farm_customer_id' => $request->farm_customer_id,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);

            $invoice->recalculatePayments();

            DB::commit();
            return redirect()->route('farm.invoices.show', $invoice->id)->with('success', 'Faktur Penjualan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui faktur: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $invoice = FarmInvoice::with('items')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Restore stock
            foreach ($invoice->items as $item) {
                if ($item->farm_product_id) {
                    $prod = FarmProduct::find($item->farm_product_id);
                    if ($prod) {
                        $prod->increment('current_stock_kg', $item->weight_kg);
                        if ($item->qty_ekor > 0) {
                            $prod->increment('current_stock_ekor', $item->qty_ekor);
                        }
                    }
                }
            }

            $invoice->delete();

            DB::commit();
            return redirect()->route('farm.invoices.index')->with('success', 'Faktur Penjualan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus faktur: ' . $e->getMessage());
        }
    }

    // Catat Pembayaran / Cicilan
    public function storePayment(Request $request, $id)
    {
        $invoice = FarmInvoice::findOrFail($id);

        $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $payment = FarmInvoicePayment::create([
                'farm_invoice_id' => $invoice->id,
                'payment_date' => $request->payment_date,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
            ]);

            $invoice->recalculatePayments();

            // Record transaction cash in
            FarmTransaction::create([
                'type' => 'pemasukan',
                'category' => 'cicilan_karkas',
                'description' => "Pembayaran cicilan {$invoice->invoice_number} ({$invoice->customer->name})",
                'amount' => $request->amount,
                'transaction_date' => $request->payment_date,
                'reference_type' => FarmInvoicePayment::class,
                'reference_id' => $payment->id,
            ]);

            DB::commit();
            return back()->with('success', 'Pembayaran cicilan berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    // Hapus Pembayaran Cicilan
    public function destroyPayment($id, $paymentId)
    {
        $invoice = FarmInvoice::findOrFail($id);
        $payment = FarmInvoicePayment::where('farm_invoice_id', $invoice->id)->findOrFail($paymentId);

        DB::beginTransaction();
        try {
            // Delete related transaction
            FarmTransaction::where('reference_type', FarmInvoicePayment::class)
                ->where('reference_id', $payment->id)
                ->delete();

            $payment->delete();
            $invoice->recalculatePayments();

            DB::commit();
            return back()->with('success', 'Riwayat pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pembayaran: ' . $e->getMessage());
        }
    }

    // Export Excel with dynamic sender info matching Percetakan template layout
    public function exportExcel($id)
    {
        $invoice = FarmInvoice::with(['items', 'customer', 'sender'])->findOrFail($id);

        $templatePath = storage_path('app/templates/excel/template.xlsx');

        // If template doesn't exist, generate a clean styled spreadsheet
        try {
            if (file_exists($templatePath)) {
                $spreadsheet = IOFactory::load($templatePath);
                $sheet = $spreadsheet->getActiveSheet();

                $senderName = $invoice->sender ? $invoice->sender->name : 'ASFOUR BROILER';
                $senderAddress = $invoice->sender ? $invoice->sender->address : '';
                $senderPhone = $invoice->sender ? $invoice->sender->phone : '';

                // Dynamically update sender kop if needed
                $sheet->setCellValue('A2', $senderName);
                $sheet->setCellValue('A3', $senderAddress . ($senderPhone ? ' Telp: ' . $senderPhone : ''));

                // Customer info
                $sheet->setCellValue('K1', $invoice->invoice_date->format('d F Y'));
                $sheet->setCellValue('K2', $invoice->customer ? $invoice->customer->name : 'N/A');
                $sheet->setCellValue('K3', $invoice->customer ? $invoice->customer->address : '');
                $sheet->setCellValue('H4', $invoice->invoice_number);

                // Surat jalan side
                $sheet->setCellValue('AB1', $invoice->invoice_date->format('d F Y'));
                $sheet->setCellValue('AB2', $invoice->customer ? $invoice->customer->name : 'N/A');
                $sheet->setCellValue('AB3', $invoice->customer ? $invoice->customer->address : '');
                $sheet->setCellValue('Y4', $invoice->invoice_number);

                $startRow = 15;
                $currentRow = $startRow;
                $subtotal = 0;

                foreach ($invoice->items as $index => $item) {
                    if ($currentRow > 24) break;

                    $subtotal += $item->total_price;

                    // Faktur side
                    $sheet->setCellValue('A' . $currentRow, $index + 1);
                    $sheet->setCellValue('B' . $currentRow, $item->product_code ?? '-');
                    $sheet->setCellValue('C' . $currentRow, $item->item_name);
                    $sheet->setCellValue('F' . $currentRow, $item->weight_kg > 0 ? $item->weight_kg : $item->qty_ekor);
                    $sheet->setCellValue('H' . $currentRow, strtoupper($item->unit));
                    $sheet->setCellValue('K' . $currentRow, $item->unit_price);
                    $sheet->setCellValue('P' . $currentRow, $item->total_price);

                    // Surat Jalan side
                    $sheet->setCellValue('R' . $currentRow, $index + 1);
                    $sheet->setCellValue('T' . $currentRow, $item->product_code ?? '-');
                    $sheet->setCellValue('U' . $currentRow, $item->item_name);
                    $sheet->setCellValue('AA' . $currentRow, $item->weight_kg > 0 ? $item->weight_kg : $item->qty_ekor);
                    $sheet->setCellValue('AB' . $currentRow, strtoupper($item->unit));

                    $currentRow++;
                }

                $sheet->setCellValue('P30', $subtotal);
            } else {
                // Fallback: Create spreadsheet from scratch with clean layout
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Faktur Penjualan');

                $senderName = $invoice->sender ? $invoice->sender->name : 'ASFOUR BROILER';
                $sheet->setCellValue('A1', $senderName);
                $sheet->setCellValue('A2', $invoice->sender ? $invoice->sender->address : '');
                $sheet->setCellValue('A3', 'Telp: ' . ($invoice->sender ? $invoice->sender->phone : ''));
                $sheet->setCellValue('A4', 'No Faktur: ' . $invoice->invoice_number);
                $sheet->setCellValue('A5', 'Tanggal: ' . $invoice->invoice_date->format('d/m/Y'));
                $sheet->setCellValue('A6', 'Kepada: ' . ($invoice->customer ? $invoice->customer->name : ''));

                $sheet->setCellValue('A8', 'No');
                $sheet->setCellValue('B8', 'Kode');
                $sheet->setCellValue('C8', 'Nama Produk');
                $sheet->setCellValue('D8', 'Berat (Kg) / Qty');
                $sheet->setCellValue('E8', 'Satuan');
                $sheet->setCellValue('F8', 'Harga Satuan');
                $sheet->setCellValue('G8', 'Total');

                $row = 9;
                foreach ($invoice->items as $idx => $item) {
                    $sheet->setCellValue('A' . $row, $idx + 1);
                    $sheet->setCellValue('B' . $row, $item->product_code ?? '-');
                    $sheet->setCellValue('C' . $row, $item->item_name);
                    $sheet->setCellValue('D' . $row, $item->weight_kg > 0 ? $item->weight_kg : $item->qty_ekor);
                    $sheet->setCellValue('E' . $row, $item->unit);
                    $sheet->setCellValue('F' . $row, $item->unit_price);
                    $sheet->setCellValue('G' . $row, $item->total_price);
                    $row++;
                }

                $sheet->setCellValue('F' . $row, 'TOTAL:');
                $sheet->setCellValue('G' . $row, $invoice->total_amount);
            }

            $writer = new Xlsx($spreadsheet);
            $fileName = 'Faktur-' . $invoice->invoice_number . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export Excel: ' . $e->getMessage());
        }
    }
}
