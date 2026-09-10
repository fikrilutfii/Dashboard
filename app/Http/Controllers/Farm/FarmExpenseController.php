<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmExpense;
use App\Models\FarmTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = FarmExpense::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->orderBy('id', 'desc')->paginate(15);
        $totalExpense = $query->sum('amount');

        return view('farm.expenses.index', compact('expenses', 'totalExpense'));
    }

    public function create()
    {
        return view('farm.expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'category' => 'required|in:operasional_rpa,administrasi,armada_umum,lain',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $expense = FarmExpense::create([
                'expense_date' => $request->expense_date,
                'category' => $request->category,
                'subcategory' => $request->subcategory,
                'description' => $request->description,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            FarmTransaction::create([
                'type' => 'pengeluaran',
                'category' => $request->category,
                'description' => "Pengeluaran: {$request->description}",
                'amount' => $request->amount,
                'transaction_date' => $request->expense_date,
                'reference_type' => FarmExpense::class,
                'reference_id' => $expense->id,
            ]);

            DB::commit();
            return redirect()->route('farm.expenses.index')->with('success', 'Pengeluaran kas berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mencatat pengeluaran: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $expense = FarmExpense::findOrFail($id);
        return view('farm.expenses.edit', compact('expense'));
    }

    public function update(Request $request, $id)
    {
        $expense = FarmExpense::findOrFail($id);

        $request->validate([
            'expense_date' => 'required|date',
            'category' => 'required|in:operasional_rpa,administrasi,armada_umum,lain',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $expense->update([
                'expense_date' => $request->expense_date,
                'category' => $request->category,
                'subcategory' => $request->subcategory,
                'description' => $request->description,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            FarmTransaction::updateOrCreate(
                [
                    'reference_type' => FarmExpense::class,
                    'reference_id' => $expense->id,
                ],
                [
                    'type' => 'pengeluaran',
                    'category' => $request->category,
                    'description' => "Pengeluaran: {$request->description}",
                    'amount' => $request->amount,
                    'transaction_date' => $request->expense_date,
                ]
            );

            DB::commit();
            return redirect()->route('farm.expenses.index')->with('success', 'Pengeluaran kas berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui pengeluaran: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $expense = FarmExpense::findOrFail($id);

        DB::beginTransaction();
        try {
            FarmTransaction::where('reference_type', FarmExpense::class)
                ->where('reference_id', $expense->id)
                ->delete();

            $expense->delete();

            DB::commit();
            return redirect()->route('farm.expenses.index')->with('success', 'Pengeluaran kas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pengeluaran: ' . $e->getMessage());
        }
    }
}
