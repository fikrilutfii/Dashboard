<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $division = session('division');
        $query = Expense::when($division, fn($q) => $q->where('division', $division));

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $expenses = $query->latest('date')->latest('id')->paginate(15);
        $totalExpenses = $query->sum('amount');

        return view('expenses.index', compact('expenses', 'totalExpenses'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'manual'); // 'manual' or 'bahan'
        return view('expenses.create', compact('type'));
    }

    public function store(Request $request)
    {
        $type = $request->type ?? 'manual';
        $rules = [
            'date' => 'required|date',
            'type' => 'required|in:manual,bahan',
            'entity' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ];

        if ($type === 'manual') {
            $rules['category'] = 'required|string';
            $rules['description'] = 'required|string';
        } else {
            $rules['supplier_name'] = 'required|string';
            $rules['item_name'] = 'required|string';
        }

        $validated = $request->validate($rules);
        $validated['division'] = session('division', 'Percetakan');

        if ($type === 'bahan') {
            $validated['quantity'] = $request->quantity ?? 1;
            $validated['unit_price'] = $request->unit_price ?? $validated['amount'];
            $validated['description'] = $request->description;
        }

        DB::transaction(function () use ($validated) {
            $expense = Expense::create($validated);

            $desc = $expense->type === 'bahan' 
                ? "Belanja Bahan: {$expense->item_name} dari {$expense->supplier_name}"
                : "Pengeluaran ({$expense->category}): {$expense->description}";

            Transaction::create([
                'type' => 'debit',
                'amount' => $expense->amount,
                'description' => $desc,
                'reference_id' => $expense->id,
                'reference_type' => Expense::class,
                'date' => $expense->date,
                'division' => $expense->division,
                'entity' => $expense->entity,
            ]);
        });

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $type = $expense->type;
        $rules = [
            'date' => 'required|date',
            'entity' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ];

        if ($type === 'manual') {
            $rules['category'] = 'required|string';
            $rules['description'] = 'required|string';
        } else {
            $rules['supplier_name'] = 'required|string';
            $rules['item_name'] = 'required|string';
        }

        $validated = $request->validate($rules);

        if ($type === 'bahan') {
            $validated['quantity'] = $request->quantity ?? 1;
            $validated['unit_price'] = $request->unit_price ?? $validated['amount'];
            $validated['description'] = $request->description;
        }

        DB::transaction(function () use ($validated, $expense) {
            $expense->update($validated);

            $desc = $expense->type === 'bahan' 
                ? "Belanja Bahan: {$expense->item_name} dari {$expense->supplier_name}"
                : "Pengeluaran ({$expense->category}): {$expense->description}";

            $transaction = Transaction::where('reference_type', Expense::class)
                ->where('reference_id', $expense->id)
                ->first();

            if ($transaction) {
                $transaction->update([
                    'amount' => $expense->amount,
                    'description' => $desc,
                    'date' => $expense->date,
                    'entity' => $expense->entity,
                ]);
            }
        });

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $expense)
    {
        DB::transaction(function () use ($expense) {
            Transaction::where('reference_type', Expense::class)
                ->where('reference_id', $expense->id)
                ->delete();

            $expense->delete();
        });

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
