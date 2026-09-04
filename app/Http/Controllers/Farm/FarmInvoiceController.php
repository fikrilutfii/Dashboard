<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmInvoice;
use App\Models\FarmInvoiceItem;
use App\Models\FarmCustomer;
use App\Models\FarmCoop;
use App\Models\FarmHarvestLog;
use App\Models\FarmHarvestSale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = FarmInvoice::with('customer', 'coop')->latest();

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('invoice_number', 'like', "%$s%")
                  ->orWhereHas('customer', fn($cq) => $cq->where('name', 'like', "%$s%"))
                  ->orWhereHas('items', fn($iq) => $iq->where('description', 'like', "%$s%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->paginate(15);

        $stats = [
            'total'       => FarmInvoice::count(),
            'belum_lunas' => FarmInvoice::whereIn('status', ['belum_lunas', 'sebagian'])->count(),
            'lunas'       => FarmInvoice::where('status', 'lunas')->count(),
            'outstanding' => FarmInvoice::whereIn('status', ['belum_lunas', 'sebagian'])->sum(DB::raw('total_amount - paid_amount')),
        ];

        return view('farm.invoices.index', compact('invoices', 'stats'));
    }

    public function create()
    {
        $customers = FarmCustomer::orderBy('name')->get();
        $coops     = FarmCoop::orderBy('name')->get();
        $availableHarvests = FarmHarvestLog::with(['coop', 'batch'])
            ->whereIn('status_penjualan', ['tersedia', 'terjual_sebagian'])
            ->latest('harvest_date')
            ->get();

        $invoiceNumber = 'FAP-' . now()->format('ym') . '-' . str_pad(FarmInvoice::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count() + 1, 3, '0', STR_PAD_LEFT);
        return view('farm.invoices.create', compact('customers', 'coops', 'availableHarvests', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'farm_customer_id' => 'required|exists:farm_customers,id',
            'invoice_date'     => 'required|date',
            'items'            => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.qty'         => 'required|numeric|min:0',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        DB::transaction(function() use ($request) {
            $total = 0;
            foreach ($request->items as $item) {
                $total += ($item['qty'] ?? 0) * ($item['unit_price'] ?? 0);
            }

            $invoice = FarmInvoice::create([
                'invoice_number'   => $request->invoice_number,
                'farm_customer_id' => $request->farm_customer_id,
                'farm_coop_id'     => $request->farm_coop_id ?: null,
                'invoice_date'     => $request->invoice_date,
                'due_date'         => $request->due_date ?: null,
                'total_amount'     => $total,
                'paid_amount'      => 0,
                'status'           => 'belum_lunas',
                'payment_method'   => $request->payment_method,
                'notes'            => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $itemTotal = ($item['qty'] ?? 0) * ($item['unit_price'] ?? 0);
                FarmInvoiceItem::create([
                    'farm_invoice_id' => $invoice->id,
                    'description'     => $item['description'],
                    'qty'             => $item['qty'],
                    'unit'            => $item['unit'] ?? 'kg',
                    'unit_price'      => $item['unit_price'],
                    'total_price'     => $itemTotal,
                ]);
            }

            // Jika faktur terhubung ke Stok Panen
            if ($request->filled('farm_harvest_log_id')) {
                $harvest = FarmHarvestLog::find($request->farm_harvest_log_id);
                if ($harvest) {
                    FarmHarvestSale::create([
                        'farm_harvest_log_id' => $harvest->id,
                        'farm_invoice_id'     => $invoice->id,
                        'sold_weight_kg'      => $harvest->total_weight_kg,
                        'sold_count'          => $harvest->chicken_count,
                    ]);

                    $harvest->update(['status_penjualan' => 'terjual_lunas']);
                }
            }
        });

        return redirect()->route('farm.invoices.index')->with('success', 'Faktur berhasil dibuat.');
    }

    public function show(FarmInvoice $farmInvoice)
    {
        $farmInvoice->load('customer', 'coop', 'items', 'harvestSales.harvestLog');
        return view('farm.invoices.show', ['invoice' => $farmInvoice]);
    }

    public function edit(FarmInvoice $farmInvoice)
    {
        $farmInvoice->load('items');
        $customers = FarmCustomer::orderBy('name')->get();
        $coops     = FarmCoop::orderBy('name')->get();
        return view('farm.invoices.edit', ['invoice' => $farmInvoice, 'customers' => $customers, 'coops' => $coops]);
    }

    public function update(Request $request, FarmInvoice $farmInvoice)
    {
        $request->validate([
            'farm_customer_id' => 'required|exists:farm_customers,id',
            'invoice_date'     => 'required|date',
            'items'            => 'required|array|min:1',
        ]);

        DB::transaction(function() use ($request, $farmInvoice) {
            $total = 0;
            foreach ($request->items as $item) {
                $total += ($item['qty'] ?? 0) * ($item['unit_price'] ?? 0);
            }

            $farmInvoice->update([
                'invoice_number'   => $request->invoice_number,
                'farm_customer_id' => $request->farm_customer_id,
                'farm_coop_id'     => $request->farm_coop_id ?: null,
                'invoice_date'     => $request->invoice_date,
                'due_date'         => $request->due_date ?: null,
                'total_amount'     => $total,
                'payment_method'   => $request->payment_method,
                'notes'            => $request->notes,
            ]);

            $farmInvoice->items()->delete();

            foreach ($request->items as $item) {
                $itemTotal = ($item['qty'] ?? 0) * ($item['unit_price'] ?? 0);
                FarmInvoiceItem::create([
                    'farm_invoice_id' => $farmInvoice->id,
                    'description'     => $item['description'],
                    'qty'             => $item['qty'],
                    'unit'            => $item['unit'] ?? 'kg',
                    'unit_price'      => $item['unit_price'],
                    'total_price'     => $itemTotal,
                ]);
            }
        });

        return redirect()->route('farm.invoices.index')->with('success', 'Faktur berhasil diperbarui.');
    }

    public function destroy(FarmInvoice $farmInvoice)
    {
        $farmInvoice->delete();
        return redirect()->route('farm.invoices.index')->with('success', 'Faktur berhasil dihapus.');
    }

    public function recordPayment(Request $request, FarmInvoice $farmInvoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $newPaid = $farmInvoice->paid_amount + $request->amount;
        $status  = $newPaid >= $farmInvoice->total_amount ? 'lunas' : 'sebagian';

        $farmInvoice->update([
            'paid_amount' => min($newPaid, $farmInvoice->total_amount),
            'status'      => $status,
        ]);

        return redirect()->back()->with('success', 'Pembayaran faktur berhasil dicatat.');
    }

    public function print(FarmInvoice $farmInvoice)
    {
        $farmInvoice->load('customer', 'coop', 'items');
        return view('farm.invoices.print', ['invoice' => $farmInvoice]);
    }

    public function billing(Request $request)
    {
        $query = FarmCustomer::whereHas('invoices', function($q) {
            $q->whereIn('status', ['belum_lunas', 'sebagian']);
        })->with(['invoices' => function($q) {
            $q->whereIn('status', ['belum_lunas', 'sebagian'])->with('coop');
        }]);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . trim($request->search) . '%');
        }

        $customers = $query->get();

        $totalOutstanding = FarmInvoice::whereIn('status', ['belum_lunas', 'sebagian'])
            ->sum(DB::raw('total_amount - paid_amount'));

        return view('farm.billing.index', compact('customers', 'totalOutstanding'));
    }
}
