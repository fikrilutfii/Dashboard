<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with('supplier')->latest();

        // Division Filter (Default to 'percetakan' if not specified, or show all?) 
        // Better: if no division, show all? Or force choice? 
        // Design says separate menus. So usually division is passed.
        if ($request->has('division') && $request->division != '') {
            $query->where('division', $request->division);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('purchase_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('supplier', function($subQ) use ($request) {
                      $subQ->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->has('date_start') && $request->date_start != '') {
            $query->whereDate('date', '>=', $request->date_start);
        }
        if ($request->has('date_end') && $request->date_end != '') {
            $query->whereDate('date', '<=', $request->date_end);
        }

        $purchases = $query->paginate(10);
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('purchases.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_number' => 'required|string|unique:purchases,purchase_number',
            'supplier_id' => 'nullable|exists:suppliers,id', // Optional supplier
            'date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:date',
            'division' => 'required|in:percetakan,konfeksi',
            'payment_status' => 'required|in:cash,credit', // Logic decision
            'items' => 'required|array|min:1',
            'items.*.product_code' => 'nullable|string',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $status = ($validated['payment_status'] === 'cash') ? 'lunas' : 'belum_lunas';
            
            $purchase = Purchase::create([
                'purchase_number' => $validated['purchase_number'],
                'supplier_id' => $validated['supplier_id'],
                'date' => $validated['date'],
                'due_date' => $validated['due_date'],
                'division' => $validated['division'],
                'status' => $status,
                'total_amount' => 0,
                'description' => 'Purchase ' . $validated['purchase_number'],
            ]);

            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $subtotal;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);

                if (!empty($item['product_code'])) {
                    $product = \App\Models\Product::where('code', $item['product_code'])->first();
                    if ($product) {
                        $product->syncStock($item['quantity']);
                    }
                }
            }

            $purchase->update(['total_amount' => $totalAmount]);

            // If Cash, Record Transaction immediately (Debit)
            if ($status === 'lunas') {
                Transaction::create([
                    'type' => 'debit', // Money Out
                    'amount' => $totalAmount,
                    'category' => 'purchase_payment',
                    'reference_type' => Purchase::class,
                    'reference_id' => $purchase->id,
                    'description' => 'Cash Purchase ' . $purchase->purchase_number,
                    'date' => $validated['date'],
                    'division' => $validated['division'],
                ]);
            }
        });

        return redirect()->route('purchases.index', ['division' => $validated['division']])
                         ->with('success', 'Pembelian berhasil disimpan.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['items', 'supplier', 'transactions']);
        return view('purchases.show', compact('purchase'));
    }

    // Pay off Credit (Hutang)
    public function updateStatus(Request $request, Purchase $purchase)
    {
        // Only allow paying if unpaid
        if ($purchase->status === 'lunas') {
            return back()->with('error', 'Purchase already paid.');
        }

        DB::transaction(function () use ($purchase) {
            $purchase->update(['status' => 'lunas']);

            // Record Transaction (Debit)
            Transaction::create([
                'type' => 'debit',
                'amount' => $purchase->total_amount,
                'category' => 'purchase_payment',
                'reference_type' => Purchase::class,
                'reference_id' => $purchase->id,
                'description' => 'Pelunasan Hutang ' . $purchase->purchase_number,
                'date' => now(),
                'division' => $purchase->division,
            ]);
        });

        return back()->with('success', 'Hutang lunas.');
    }
}
