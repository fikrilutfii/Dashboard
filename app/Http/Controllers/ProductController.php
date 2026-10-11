<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        
        $division = session('division');
        if ($division) {
            $query->where('division', $division);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }
        
        $products = $query->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:products,code',
            'name' => 'required|string',
            'unit' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'shared_stock_code' => 'nullable|string',
        ]);

        if (!empty($validated['shared_stock_code'])) {
            $existing = Product::where('shared_stock_code', $validated['shared_stock_code'])->first();
            if ($existing) {
                $validated['stock'] = $existing->stock;
            }
        }

        $validated['division'] = session('division', 'percetakan');

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:products,code,' . $product->id,
            'name' => 'required|string',
            'unit' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'shared_stock_code' => 'nullable|string',
        ]);

        $product->update($validated);

        if (!empty($product->shared_stock_code) && isset($validated['stock'])) {
            Product::where('shared_stock_code', $product->shared_stock_code)
                   ->update(['stock' => $validated['stock']]);
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Hanya admin yang dapat menghapus produk.');
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    // API for live product searching (autocomplete & search modal) with order history details
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 1) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $division = session('division');
        $query = Product::query();
        if ($division) {
            $query->where(function ($sq) use ($division) {
                $sq->where('division', $division)->orWhereNull('division');
            });
        }

        $products = $query->where(function ($subQ) use ($q) {
                $subQ->where('code', 'like', "%{$q}%")
                     ->orWhere('name', 'like', "%{$q}%");
            })
            ->limit(15)
            ->get(['id', 'code', 'name', 'unit', 'price', 'stock']);

        $codes = $products->pluck('code')->filter()->unique()->values();
        $names = $products->pluck('name')->filter()->unique()->values();

        // Also search past invoice items to catch items not in master products table
        $additionalItems = \App\Models\InvoiceItem::whereHas('invoice', function ($invQ) {
                $invQ->whereNull('deleted_at');
            })
            ->where(function ($subQ) use ($q) {
                $subQ->where('product_code', 'like', "%{$q}%")
                     ->orWhere('item_name', 'like', "%{$q}%");
            })
            ->with(['invoice.customer'])
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        // Query invoice items for the found master products
        $masterHistoryItems = collect();
        if ($codes->isNotEmpty() || $names->isNotEmpty()) {
            $masterHistoryItems = \App\Models\InvoiceItem::whereHas('invoice', function ($invQ) {
                    $invQ->whereNull('deleted_at');
                })
                ->where(function ($sq) use ($codes, $names) {
                    if ($codes->isNotEmpty()) {
                        $sq->whereIn('product_code', $codes);
                    }
                    if ($names->isNotEmpty()) {
                        $sq->orWhereIn('item_name', $names);
                    }
                })
                ->with(['invoice.customer'])
                ->orderBy('id', 'desc')
                ->get();
        }

        $allHistoryItems = $masterHistoryItems->concat($additionalItems);
        $itemsByCode = $allHistoryItems->groupBy('product_code');
        $itemsByName = $allHistoryItems->groupBy('item_name');

        $resultList = collect();
        $seenKeys = [];

        foreach ($products as $p) {
            $history = $itemsByCode->get($p->code) ?? $itemsByName->get($p->name) ?? collect();
            $lastItem = $history->first();

            $unitPrice = $lastItem ? (float)$lastItem->unit_price : (float)($p->price ?? 0);
            $lastQty = $lastItem ? (float)$lastItem->quantity : null;
            $customerName = ($lastItem && $lastItem->invoice && $lastItem->invoice->customer) ? $lastItem->invoice->customer->name : null;
            $fakturNumber = ($lastItem && $lastItem->invoice) ? ($lastItem->invoice->faktur_number ?: $lastItem->invoice->invoice_number) : null;
            $invoiceDate = ($lastItem && $lastItem->invoice && $lastItem->invoice->invoice_date) ? $lastItem->invoice->invoice_date->format('d/m/Y') : null;

            $key = strtoupper(trim($p->code ?: $p->name));
            $seenKeys[$key] = true;

            $resultList->push([
                'id' => $p->id,
                'code' => $p->code ?: '',
                'name' => $p->name ?: '',
                'unit' => $p->unit ?: 'Pcs',
                'stock' => (float)($p->stock ?? 0),
                'master_price' => (float)($p->price ?? 0),
                'price' => $unitPrice,
                'price_formatted' => number_format($unitPrice, 0, ',', '.'),
                'last_qty' => $lastQty,
                'last_qty_formatted' => $lastQty !== null ? (number_format($lastQty, 0, ',', '.') == $lastQty ? number_format($lastQty, 0, ',', '.') : number_format($lastQty, 2, ',', '.')) : null,
                'last_customer' => $customerName,
                'last_faktur' => $fakturNumber,
                'last_date' => $invoiceDate,
                'has_history' => $lastItem ? true : false,
            ]);
        }

        // Add additional items from history if not already present
        foreach ($additionalItems as $add) {
            $code = trim($add->product_code ?? '');
            $name = trim($add->item_name ?? '');
            $key = strtoupper($code ?: $name);
            if ($key === '' || isset($seenKeys[$key])) {
                continue;
            }
            $seenKeys[$key] = true;

            $unitPrice = (float)$add->unit_price;
            $lastQty = (float)$add->quantity;
            $customerName = ($add->invoice && $add->invoice->customer) ? $add->invoice->customer->name : null;
            $fakturNumber = $add->invoice ? ($add->invoice->faktur_number ?: $add->invoice->invoice_number) : null;
            $invoiceDate = ($add->invoice && $add->invoice->invoice_date) ? $add->invoice->invoice_date->format('d/m/Y') : null;

            $resultList->push([
                'id' => null,
                'code' => $code,
                'name' => $name,
                'unit' => 'Pcs',
                'stock' => 0,
                'master_price' => $unitPrice,
                'price' => $unitPrice,
                'price_formatted' => number_format($unitPrice, 0, ',', '.'),
                'last_qty' => $lastQty,
                'last_qty_formatted' => number_format($lastQty, 0, ',', '.') == $lastQty ? number_format($lastQty, 0, ',', '.') : number_format($lastQty, 2, ',', '.'),
                'last_customer' => $customerName,
                'last_faktur' => $fakturNumber,
                'last_date' => $invoiceDate,
                'has_history' => true,
            ]);

            if ($resultList->count() >= 20) {
                break;
            }
        }

        return response()->json([
            'success' => true,
            'data' => $resultList->values()
        ]);
    }

    // API for fetching product details by code along with invoice history (prices, invoices, qty)
    public function getByCode($code)
    {
        $code = trim(urldecode($code));
        $product = Product::where('code', $code)->first();

        // Query invoice items matching this product code or product name
        $items = \App\Models\InvoiceItem::whereHas('invoice', function ($invQ) {
                $invQ->whereNull('deleted_at');
            })
            ->with(['invoice.customer'])
            ->where(function ($q) use ($code, $product) {
                $q->where('product_code', $code);
                if ($product && !empty($product->code) && $product->code !== $code) {
                    $q->orWhere('product_code', $product->code);
                }
                if ($product && !empty($product->name)) {
                    $q->orWhere('item_name', $product->name);
                }
            })
            ->get();

        // Sort items by invoice date desc, then invoice id desc
        $sortedItems = $items->sortByDesc(function ($item) {
            $date = ($item->invoice && $item->invoice->invoice_date) 
                ? $item->invoice->invoice_date->timestamp 
                : 0;
            return [$date, $item->id];
        })->take(15)->values();

        $history = $sortedItems->map(function ($item) {
            $invoice = $item->invoice;
            $customerName = ($invoice && $invoice->customer) ? $invoice->customer->name : 'Pelanggan Umum';

            $fakturNumber = ($invoice && !empty($invoice->faktur_number)) ? $invoice->faktur_number : null;
            $opNumber = ($invoice && !empty($invoice->invoice_number)) ? $invoice->invoice_number : null;

            $displayNumber = $fakturNumber ?: ($opNumber ?: 'Faktur #' . $item->invoice_id);
            if ($fakturNumber && $opNumber && $fakturNumber !== $opNumber && !str_starts_with($opNumber, 'NO-PO-')) {
                $displayNumber .= ' (' . $opNumber . ')';
            }

            $dateFormatted = ($invoice && $invoice->invoice_date) 
                ? $invoice->invoice_date->format('d/m/Y') 
                : '-';

            $qty = (float) $item->quantity;
            $unitPrice = (float) $item->unit_price;
            $subtotal = (float) $item->subtotal;

            return [
                'invoice_id' => $item->invoice_id,
                'faktur_number' => $fakturNumber ?: ($opNumber ?: '-'),
                'op_number' => $opNumber ?: '-',
                'display_number' => $displayNumber,
                'invoice_date' => $dateFormatted,
                'customer_name' => $customerName,
                'quantity' => $qty,
                'formatted_quantity' => $item->formatted_quantity ?? (number_format($qty, 0, ',', '.') == $qty ? number_format($qty, 0, ',', '.') : number_format($qty, 2, ',', '.')),
                'unit_price' => $unitPrice,
                'unit_price_formatted' => number_format($unitPrice, 2, ',', '.'),
                'unit_price_display' => 'Rp ' . (number_format($unitPrice, 0, ',', '.') == number_format($unitPrice, 2, ',', '.') ? number_format($unitPrice, 0, ',', '.') : number_format($unitPrice, 2, ',', '.')),
                'subtotal' => $subtotal,
                'subtotal_formatted' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
                'status' => $invoice ? $invoice->status : 'belum_lunas',
            ];
        });

        $lastInvoice = $history->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'data' => $product,
                'history' => $history,
                'last_price' => $lastInvoice ? $lastInvoice['unit_price'] : (float)$product->price,
                'last_invoice' => $lastInvoice,
            ]);
        }

        // Fallback: If not in products master table, but exists in invoice history
        if ($history->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => null,
                    'code' => $code,
                    'name' => $items->first()->item_name ?? $code,
                    'price' => $lastInvoice ? $lastInvoice['unit_price'] : 0,
                    'stock' => 0,
                    'unit' => 'Pcs',
                ],
                'history' => $history,
                'last_price' => $lastInvoice ? $lastInvoice['unit_price'] : 0,
                'last_invoice' => $lastInvoice,
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    // Import from CSV (data master.csv in project root)
    public function importCsv(\Illuminate\Http\Request $request)
    {
        $csvPath = base_path('data master.csv');

        if (!file_exists($csvPath)) {
            return back()->with('error', 'File "data master.csv" tidak ditemukan di root project.');
        }

        $division = session('division', 'percetakan');
        $handle   = fopen($csvPath, 'r');
        $header   = fgetcsv($handle); // skip header row
        $imported = 0;
        $skipped  = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) continue;

            $code = trim((string)$row[0]);
            $name = trim($row[1]);

            if (empty($code) || empty($name)) {
                $skipped++;
                continue;
            }

            // Pad kode if numeric (e.g., scientific notation from Excel)
            if (is_numeric($code)) {
                $code = number_format((float)$code, 0, '', '');
            }

            $unit  = isset($row[2]) ? trim($row[2]) : 'Pcs';
            $price = isset($row[3]) ? (float)str_replace(['.', ','], ['', '.'], $row[3]) : 0;

            Product::updateOrCreate(
                ['code' => $code],
                [
                    'name'     => $name,
                    'unit'     => $unit,
                    'price'    => $price,
                    'division' => $division,
                ]
            );
            $imported++;
        }

        fclose($handle);

        return back()->with('success', "Import selesai: {$imported} barang berhasil diimport, {$skipped} baris dilewati.");
    }

    // Stock Report Dashboard
    public function stockReport(Request $request)
    {
        $division = session('division');
        $query = Product::query();

        if ($division) {
            $query->where('division', $division);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        // Metrics
        $totalProducts = (clone $query)->count();
        $outOfStockCount = (clone $query)->where('stock', '<=', 0)->count();
        $lowStockCount = (clone $query)->where('stock', '>', 0)->where('stock', '<=', 5)->count();
        $totalAssetValue = (clone $query)->selectRaw('SUM(stock * price) as total')->value('total') ?? 0;

        // Widget lists
        $outOfStockItems = (clone $query)->where('stock', '<=', 0)->get();
        $lowStockItems = (clone $query)->where('stock', '>', 0)->where('stock', '<=', 5)->orderBy('stock', 'asc')->get();

        // Stock Logs (Recent Movements)
        $recentLogs = \App\Models\StockLog::with(['product', 'user'])
            ->whereHas('product', function($q) use ($division) {
                if ($division) {
                    $q->where('division', $division);
                }
            })
            ->latest()
            ->take(10)
            ->get();

        // Main table
        $products = $query->orderBy('stock', 'asc')->paginate(20);

        return view('reports.stock', compact('products', 'outOfStockCount', 'lowStockCount', 'totalProducts', 'totalAssetValue', 'outOfStockItems', 'lowStockItems', 'recentLogs'));
    }

    // Manual Stock Adjustment
    public function adjustStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'adjustment_type' => 'required|in:add,subtract',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $quantity = $validated['adjustment_type'] === 'add' ? $validated['quantity'] : -$validated['quantity'];

        // Prevent negative stock if subtracting
        if ($quantity < 0 && $product->stock < abs($quantity)) {
            return back()->with('error', 'Stok tidak cukup untuk dikurangi.');
        }

        $product->syncStock($quantity, 'adjustment', $validated['reason'], null, null);

        return back()->with('success', 'Stok berhasil disesuaikan.');
    }
}
