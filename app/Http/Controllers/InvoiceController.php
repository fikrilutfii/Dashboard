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
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0', // Ensure price is validated
        ]);

        DB::transaction(function () use ($validated) {
            $invoice = Invoice::create([
                'invoice_number' => $validated['invoice_number'],
                'division' => $validated['division'],
                'customer_id' => $validated['customer_id'],
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'status' => 'belum_lunas',
                'total_amount' => 0, 
            ]);

            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                // Fetch product for name/code, but trust manual price if needed or validate
                $product = Product::where('code', $item['product_code'])->first();
                
                // Allow manual override of price or use product price? 
                // Previous code used product price. 
                // Requirement says "Input Faktur". Often price can vary. 
                // Let's use the input price associated with the validated data.
                $unitPrice = $item['unit_price']; 
                
                $subtotal = $item['quantity'] * $unitPrice;
                $totalAmount += $subtotal;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_code' => $product->code,
                    'item_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);
            }

            $invoice->update(['total_amount' => $totalAmount]);

            InvoiceLog::create([
                'invoice_id' => $invoice->id,
                'user_id' => Auth::id(),
                'action' => 'Created',
                'description' => 'Invoice created with total: ' . number_format($totalAmount, 0, ',', '.'),
            ]);
        });

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
             'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $invoice) {
             $invoice->update([
                 'customer_id' => $validated['customer_id'],
                 'invoice_date' => $validated['invoice_date'],
                 'due_date' => $validated['due_date'],
             ]);

             // Replace tokens
             $invoice->items()->delete();
             
             $totalAmount = 0;
             foreach ($validated['items'] as $item) {
                 $product = Product::where('code', $item['product_code'])->first();
                 
                 $unitPrice = $product->price;
                 $subtotal = $item['quantity'] * $unitPrice;
                 $totalAmount += $subtotal;
 
                 InvoiceItem::create([
                     'invoice_id' => $invoice->id,
                     'product_code' => $product->code,
                     'item_name' => $product->name,
                     'specification' => null,
                     'quantity' => $item['quantity'],
                     'unit_price' => $unitPrice,
                     'subtotal' => $subtotal,
                 ]);
             }
 
             $invoice->update(['total_amount' => $totalAmount]);

             InvoiceLog::create([
                 'invoice_id' => $invoice->id,
                 'user_id' => Auth::id(),
                 'action' => 'Updated',
                 'description' => 'Invoice updated. New total: ' . number_format($totalAmount, 0, ',', '.'),
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

            if ($status == 'lunas') {
                $invoice->update(['paid_amount' => $invoice->total_amount]); // Assume full payment for toggle
                
                // Record Transaction
                \App\Models\Transaction::create([
                    'type' => 'credit',
                    'amount' => $invoice->total_amount,
                    'category' => 'invoice_payment',
                    'reference_type' => Invoice::class,
                    'reference_id' => $invoice->id,
                    'description' => 'Payment for Invoice ' . $invoice->invoice_number,
                    'date' => now(),
                    'division' => $invoice->division,
                ]);
            } else {
                $invoice->update(['paid_amount' => 0]);
                // Optional: Delete/Reverse transaction if set back to unpaid?
                // For now, let's keep it simple or maybe reverse it with a debit? 
                // Simplicity: Delete the transaction linked to this invoice to correct the balance.
                \App\Models\Transaction::where('reference_type', Invoice::class)
                    ->where('reference_id', $invoice->id)
                    ->delete();
            }

            InvoiceLog::create([
                'invoice_id' => $invoice->id,
                'user_id' => Auth::id(),
                'action' => 'Status Changed',
                'description' => 'Status changed to ' . ($status == 'lunas' ? 'Lunas' : 'Belum Lunas'),
            ]);
        });

        return back()->with('success', 'Invoice status updated.');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status == 'lunas') {
            return redirect()->back()->with('error', 'Cannot delete paid invoice.');
        }

        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function print(Invoice $invoice)
    {
        $invoice->load(['items', 'customer']);
        return view('invoices.print', compact('invoice'));
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
