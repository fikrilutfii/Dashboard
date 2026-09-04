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
        $validated['payment_method'] = $request->payment_method ?? 'cash';
        $validated['due_date'] = $request->due_date;
        $validated['tenure'] = $request->tenure;
        $validated['payment_status'] = $validated['payment_method'] === 'cash' ? 'paid' : 'unpaid';

        if ($type === 'bahan') {
            $validated['quantity'] = $request->quantity ?? 1;
            $validated['unit_price'] = $request->unit_price ?? $validated['amount'];
            $validated['description'] = $request->description;
        }

        DB::transaction(function () use ($validated, $request) {
            $expense = Expense::create($validated);

            $desc = $expense->type === 'bahan' 
                ? "Belanja Bahan: {$expense->item_name} dari {$expense->supplier_name}"
                : "Pengeluaran ({$expense->category}): {$expense->description}";

            if ($expense->payment_method === 'cash') {
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
            } else {
                // If CREDIT, create a CompanyDebt instead of a Transaction
                $monthlyAmount = $request->monthly_amount;
                if (!$monthlyAmount && $expense->tenure > 0) {
                    $monthlyAmount = $expense->amount / $expense->tenure;
                }

                \App\Models\CompanyDebt::create([
                    'name' => $expense->type === 'bahan' ? $expense->supplier_name : $expense->category,
                    'description' => "(Dari Pengeluaran) " . $desc,
                    'amount' => $expense->amount,
                    'remaining_amount' => $expense->amount,
                    'monthly_amount' => $monthlyAmount,
                    'due_date' => $expense->due_date,
                    'status' => 'belum_lunas',
                    'type' => $expense->tenure > 1 ? 'credit' : 'cash', // 'credit' here usually means installment in your system
                    'division' => $expense->division,
                    'entity' => $expense->entity,
                ]);
            }
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
        if (auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Hanya admin yang dapat menghapus pengeluaran.');
        }

        DB::transaction(function () use ($expense) {
            Transaction::where('reference_type', Expense::class)
                ->where('reference_id', $expense->id)
                ->delete();

            $expense->delete();
        });

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
