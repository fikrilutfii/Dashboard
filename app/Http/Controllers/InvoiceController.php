<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceLog;
use App\Models\Product; // Import Product
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing - Simplified Filters
     */
    public function index(Request $request)
    {
        $query = Invoice::with('customer')->latest();

        // Division Filter
        if ($request->has('division') && $request->division != '') {
            $query->where('division', $request->division);
        }

        if ($request->has('search') && $request->search != '') {
             $query->where('invoice_number', 'like', '%' . $request->search . '%')
                   ->orWhereHas('customer', function($q) use ($request) {
                       $q->where('name', 'like', '%' . $request->search . '%');
                   });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_filter') && $request->date_filter != '') {
             $query->whereDate('invoice_date', $request->date_filter);
        }

        $invoices = $query->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all(); 
        return view('invoices.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'division' => 'required|in:percetakan,konfeksi',
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.product_code' => 'required|exists:products,code',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0', // Ensure price is validated
        ]);

        $invoice = null;
        \Illuminate\Support\Facades\Log::info('Invoice Store: Before transaction');
        DB::transaction(function () use ($validated, &$invoice, $request) {
            \Illuminate\Support\Facades\Log::info('Invoice Store: Starting transaction');
            $invoice = Invoice::create([
                'invoice_number' => $validated['invoice_number'],
                'division'       => $validated['division'],
                'customer_id'    => $validated['customer_id'],
                'invoice_date'   => $validated['invoice_date'],
                'due_date'       => $validated['due_date'],
                'status'         => 'belum_lunas',
                'total_amount'   => 0,
                'payment_method' => $request->payment_method ?? 'cash',
                'tenure'         => $request->tenure,
            ]);
            \Illuminate\Support\Facades\Log::info('Invoice Store: Invoice created ID: ' . $invoice->id);

            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                \Illuminate\Support\Facades\Log::info('Invoice Store: Processing item: ' . $item['product_code']);
                $product = Product::where('code', $item['product_code'])->first();
                $unitPrice = $item['unit_price']; 
                $subtotal = $item['quantity'] * $unitPrice;
                $totalAmount += $subtotal;

                InvoiceItem::create([
                    'invoice_id'   => $invoice->id,
                    'product_code' => $product->code,
                    'item_name'    => $item['product_name'] ?? $product->name,
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $unitPrice,
                    'subtotal'     => $subtotal,
                ]);

                if ($product) {
                    \Illuminate\Support\Facades\Log::info('Invoice Store: Syncing stock for product: ' . $product->code);
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

    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'logs', 'customer']);
        return view('invoices.show', compact('invoice'));
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
        if ($invoice->status == 'lunas') {
             return redirect()->back()->with('error', 'Cannot edit paid invoice.');
        }

        $validated = $request->validate([
             'customer_id' => 'required|exists:customers,id',
             'invoice_date' => 'required|date',
             'due_date' => 'nullable|date|after_or_equal:invoice_date',
             'items' => 'required|array|min:1',
             'items.*.product_code' => 'required|exists:products,code',
             'items.*.product_name' => 'required|string',
             'items.*.quantity' => 'required|integer|min:1',
             'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $invoice, $request) {
            $invoice->update([
                'customer_id'    => $validated['customer_id'],
                'invoice_date'   => $validated['invoice_date'],
                'due_date'       => $validated['due_date'],
                'payment_method' => $request->payment_method ?? $invoice->payment_method,
                'tenure'         => $request->tenure ?? $invoice->tenure,
            ]);

            // Revert stock for old items before replacing
            foreach ($invoice->items as $oldItem) {
                $oldProduct = Product::where('code', $oldItem->product_code)->first();
                if ($oldProduct) {
                    $oldProduct->syncStock($oldItem->quantity, 'correction', 'Revert sebelum update Invoice #' . $invoice->invoice_number, \App\Models\Invoice::class, $invoice->id);
                }
            }

            // Replace items
            $invoice->items()->delete();
            
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $product = Product::where('code', $item['product_code'])->first();
                
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $subtotal;

                InvoiceItem::create([
                    'invoice_id'   => $invoice->id,
                    'product_code' => $product->code,
                    'item_name'    => $item['product_name'] ?? $product->name,
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

    // Toggle Status Method
    // Toggle Status Method
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $status = $request->status; // 'lunas' or 'belum_lunas'
        
        if (!in_array($status, ['lunas', 'belum_lunas'])) {
            return back()->with('error', 'Invalid status');
        }

        DB::transaction(function() use ($invoice, $status) {
            $invoice->update(['status' => $status]);

            InvoiceLog::create([
                'invoice_id' => $invoice->id,
                'user_id' => Auth::id(),
                'action' => 'Status Changed',
                'description' => 'Status changed to ' . ($status == 'lunas' ? 'Lunas' : 'Belum Lunas'),
            ]);

            $invoice->load('customer');
            $invoice->syncToReceivable();
        });

        return back()->with('success', 'Invoice status updated.');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status == 'lunas') {
            return redirect()->back()->with('error', 'Cannot delete paid invoice.');
        }

        // Revert stock before deleting
        foreach ($invoice->items as $item) {
            $product = Product::where('code', $item->product_code)->first();
            if ($product) {
                $product->syncStock($item->quantity, 'correction', 'Revert dari penghapusan Invoice #' . $invoice->invoice_number, \App\Models\Invoice::class, $invoice->id);
            }
        }

        $invoice->receivable()->delete();
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function print(Invoice $invoice)
    {
        $invoice->load(['items', 'customer']);
        return view('invoices.print', compact('invoice'));
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
}
