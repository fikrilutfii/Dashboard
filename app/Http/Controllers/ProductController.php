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
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    // API for fetching product details by code
    public function getByCode($code)
    {
        $product = Product::where('code', $code)->first();
        if ($product) {
            return response()->json([
                'success' => true,
                'data' => $product
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

        // Widget lists
        $outOfStockItems = (clone $query)->where('stock', '<=', 0)->get();
        $lowStockItems = (clone $query)->where('stock', '>', 0)->where('stock', '<=', 5)->orderBy('stock', 'asc')->get();

        // Main table
        $products = $query->orderBy('stock', 'asc')->paginate(20);

        return view('reports.stock', compact('products', 'outOfStockCount', 'lowStockCount', 'totalProducts', 'outOfStockItems', 'lowStockItems'));
    }
}
