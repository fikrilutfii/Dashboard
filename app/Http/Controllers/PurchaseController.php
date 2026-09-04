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
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('purchase_number', 'like', '%' . $search . '%')
                  ->orWhereHas('supplier', function($subQ) use ($search) {
                      $subQ->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('items', function($iq) use ($search) {
                      $iq->where('item_name', 'like', '%' . $search . '%')
                         ->orWhere('product_code', 'like', '%' . $search . '%');
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
        try {
            $indexes = DB::select("SHOW INDEX FROM purchases WHERE Key_name = 'purchases_purchase_number_unique'");
            if (count($indexes) > 0) {
                \Illuminate\Support\Facades\Schema::table('purchases', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->dropUnique('purchases_purchase_number_unique');
                });
                DB::statement('ALTER TABLE purchases MODIFY purchase_number VARCHAR(255) NULL');
            }
        } catch (\Exception $e) {}

        $this->normalizeItemNumbers($request);

        $validated = $request->validate([
            'purchase_number' => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,id', // Optional supplier
            'date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:date',
            'division' => 'required|in:percetakan,konfeksi',
            'payment_status' => 'required|in:cash,credit', // Logic decision
            'items' => 'required|array|min:1',
            'items.*.product_code' => 'nullable|string',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => ['required', 'numeric', 'min:0.001', 'regex:/^\d+(?:\.\d{1,3})?$/'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0', 'regex:/^\d+(?:\.\d{1,2})?$/'],
        ]);

        if (empty($validated['purchase_number'])) {
            $validated['purchase_number'] = 'PUR-' . date('YmdHis') . rand(10, 99);
        }

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
                        $product->syncStock($item['quantity'], 'in', 'Pembelian Bahan #' . $purchase->purchase_number, \App\Models\Purchase::class, $purchase->id);
                    }
                }
            }

            $purchase->update(['total_amount' => $totalAmount]);

            $purchase->load('supplier');
            $purchase->syncToDebt();

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

    public function edit(Purchase $purchase)
    {
        if ($purchase->status == 'lunas') {
            return redirect()->back()->with('error', 'Cannot edit paid purchase.');
        }

        $suppliers = Supplier::all();
        $purchase->load('items');
        return view('purchases.edit', compact('purchase', 'suppliers'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM purchases WHERE Key_name = 'purchases_purchase_number_unique'");
            if (count($indexes) > 0) {
                \Illuminate\Support\Facades\Schema::table('purchases', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->dropUnique('purchases_purchase_number_unique');
                });
                DB::statement('ALTER TABLE purchases MODIFY purchase_number VARCHAR(255) NULL');
            }
        } catch (\Exception $e) {}

        if ($purchase->status == 'lunas') {
             return redirect()->back()->with('error', 'Cannot edit paid purchase.');
        }

        $this->normalizeItemNumbers($request);

        $validated = $request->validate([
            'purchase_number' => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:date',
            'items' => 'required|array|min:1',
            'items.*.product_code' => 'nullable|string',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => ['required', 'numeric', 'min:0.001', 'regex:/^\d+(?:\.\d{1,3})?$/'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0', 'regex:/^\d+(?:\.\d{1,2})?$/'],
        ]);

        if (empty($validated['purchase_number'])) {
            $validated['purchase_number'] = $purchase->purchase_number ?: ('PUR-' . date('YmdHis') . rand(10, 99));
        }

        DB::transaction(function () use ($validated, $purchase, $request) {
            $purchase->update([
                'purchase_number' => $validated['purchase_number'],
                'supplier_id' => $validated['supplier_id'],
                'date' => $validated['date'],
                'due_date' => $validated['due_date'],
            ]);

            // Revert stock for old items before replacing
            foreach ($purchase->items as $oldItem) {
                if (!empty($oldItem->product_code)) {
                    $oldProduct = \App\Models\Product::where('code', $oldItem->product_code)->first();
                    if ($oldProduct) {
                        $oldProduct->syncStock(-$oldItem->quantity, 'correction', 'Revert sebelum update Purchase #' . $purchase->purchase_number, \App\Models\Purchase::class, $purchase->id);
                    }
                }
            }

            // Replace items
            $purchase->items()->delete();
            
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

                // Deduct stock for new items
                if (!empty($item['product_code'])) {
                    $product = \App\Models\Product::where('code', $item['product_code'])->first();
                    if ($product) {
                        $product->syncStock($item['quantity'], 'in', 'Pembelian Bahan (Update) #' . $purchase->purchase_number, \App\Models\Purchase::class, $purchase->id);
                    }
                }
            }

            $purchase->update(['total_amount' => $totalAmount]);
            $purchase->load('supplier');
            $purchase->syncToDebt();
        });

        return redirect()->route('purchases.index', ['division' => $purchase->division])
                         ->with('success', 'Pembelian berhasil diperbarui.');
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
            $purchase->load('supplier');
            $purchase->syncToDebt();

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
