<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmExpense;
use App\Models\FarmSupplier;
use Illuminate\Http\Request;

class FarmExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = FarmExpense::with('supplier')->latest('expense_date');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('description', 'like', "%$s%")
                  ->orWhere('category', 'like', "%$s%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        $expenses = $query->paginate(15);

        $totalBulanIni = FarmExpense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)->sum('amount');

        $byCategory = FarmExpense::whereMonth('expense_date', now()->month)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return view('farm.expenses.index', compact('expenses', 'totalBulanIni', 'byCategory'));
    }

    public function create()
    {
        $suppliers = FarmSupplier::orderBy('name')->get();
        return view('farm.expenses.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'category'     => 'required|string',
            'description'  => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
        ]);

        FarmExpense::create($request->only([
            'expense_date', 'category', 'description',
            'amount', 'payment_method', 'farm_supplier_id', 'notes',
        ]));

        return redirect()->route('farm.expenses.index')->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function edit(FarmExpense $farmExpense)
    {
        $suppliers = FarmSupplier::orderBy('name')->get();
        return view('farm.expenses.edit', ['expense' => $farmExpense, 'suppliers' => $suppliers]);
    }

    public function update(Request $request, FarmExpense $farmExpense)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'category'     => 'required|string',
            'description'  => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
        ]);

        $farmExpense->update($request->only([
            'expense_date', 'category', 'description',
            'amount', 'payment_method', 'farm_supplier_id', 'notes',
        ]));

        return redirect()->route('farm.expenses.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(FarmExpense $farmExpense)
    {
        $farmExpense->delete();
        return redirect()->route('farm.expenses.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
