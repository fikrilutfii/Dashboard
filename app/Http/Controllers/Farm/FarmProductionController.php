<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmProduct;
use App\Models\FarmProductionBatch;
use App\Models\FarmProductionItem;
use App\Models\FarmSupplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmProductionController extends Controller
{
    public function index(Request $request)
    {
        $query = FarmProductionBatch::with(['supplier', 'items.product']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('production_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('supplier_id')) {
            $query->where('farm_supplier_id', $request->supplier_id);
        }

        $batches = $query->orderBy('production_date', 'desc')->orderBy('id', 'desc')->paginate(15);
        $suppliers = FarmSupplier::orderBy('name')->get();

        return view('farm.production.index', compact('batches', 'suppliers'));
    }

    public function create()
    {
        $suppliers = FarmSupplier::orderBy('name')->get();
        $products = FarmProduct::orderBy('category')->orderBy('name')->get();

        // Auto generate Batch Number
        $monthYear = Carbon::now()->format('Ym');
        $latest = FarmProductionBatch::where('batch_number', 'like', "BATCH-{$monthYear}-%")->latest('id')->first();
        $nextNumber = 1;
        if ($latest && preg_match('/BATCH-\d+-(\d+)/', $latest->batch_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $generatedBatchNumber = sprintf("BATCH-%s-%03d", $monthYear, $nextNumber);

        return view('farm.production.create', compact('suppliers', 'products', 'generatedBatchNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_number' => 'required|string|unique:farm_production_batches,batch_number',
            'production_date' => 'required|date',
            'farm_supplier_id' => 'nullable|exists:farm_suppliers,id',
            'live_birds_count' => 'required|integer|min:1',
            'live_birds_weight_kg' => 'required|numeric|min:0.1',
            'buy_price_per_kg' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.farm_product_id' => 'required|exists:farm_products,id',
            'items.*.weight_kg' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $liveWeight = floatval($request->live_birds_weight_kg);
            $buyPricePerKg = floatval($request->buy_price_per_kg);
            $totalBuyPrice = $liveWeight * $buyPricePerKg;

            // Calculate yield
            $totalYieldKg = 0;
            foreach ($request->items as $item) {
                $totalYieldKg += floatval($item['weight_kg'] ?? 0);
            }

            $shrinkageKg = max(0, $liveWeight - $totalYieldKg);
            $shrinkagePercentage = $liveWeight > 0 ? ($shrinkageKg / $liveWeight) * 100 : 0;

            $batch = FarmProductionBatch::create([
                'batch_number' => $request->batch_number,
                'production_date' => $request->production_date,
                'farm_supplier_id' => $request->farm_supplier_id ?: null,
                'live_birds_count' => intval($request->live_birds_count),
                'live_birds_weight_kg' => $liveWeight,
                'buy_price_per_kg' => $buyPricePerKg,
                'total_buy_price' => $totalBuyPrice,
                'supplier_payment_status' => $request->supplier_payment_status ?? 'belum_lunas',
                'doa_count' => intval($request->doa_count ?? 0),
                'doa_weight_kg' => floatval($request->doa_weight_kg ?? 0),
                'total_yield_weight_kg' => $totalYieldKg,
                'shrinkage_weight_kg' => $shrinkageKg,
                'shrinkage_percentage' => $shrinkagePercentage,
                'notes' => $request->notes,
            ]);

            // Save production items & increment product stock
            foreach ($request->items as $itemData) {
                $weightKg = floatval($itemData['weight_kg'] ?? 0);
                $qtyEkor = intval($itemData['qty_ekor'] ?? 0);

                if ($weightKg > 0 || $qtyEkor > 0) {
                    FarmProductionItem::create([
                        'farm_production_batch_id' => $batch->id,
                        'farm_product_id' => $itemData['farm_product_id'],
                        'qty_ekor' => $qtyEkor,
                        'weight_kg' => $weightKg,
                        'notes' => $itemData['notes'] ?? null,
                    ]);

                    // Add to Stock
                    $product = FarmProduct::find($itemData['farm_product_id']);
                    if ($product) {
                        $product->increment('current_stock_kg', $weightKg);
                        if ($qtyEkor > 0) {
                            $product->increment('current_stock_ekor', $qtyEkor);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('farm.production.show', $batch->id)->with('success', 'Batch Produksi & Penerimaan Ayam Hidup berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan batch produksi: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $batch = FarmProductionBatch::with(['supplier', 'items.product'])->findOrFail($id);
        return view('farm.production.show', compact('batch'));
    }

    public function destroy($id)
    {
        $batch = FarmProductionBatch::with('items')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Revert stock added by this batch
            foreach ($batch->items as $item) {
                $product = FarmProduct::find($item->farm_product_id);
                if ($product) {
                    $product->decrement('current_stock_kg', $item->weight_kg);
                    if ($item->qty_ekor > 0) {
                        $product->decrement('current_stock_ekor', $item->qty_ekor);
                    }
                }
            }

            $batch->delete();

            DB::commit();
            return redirect()->route('farm.production.index')->with('success', 'Batch Produksi berhasil dihapus dan stok telah disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus batch produksi: ' . $e->getMessage());
        }
    }
}
