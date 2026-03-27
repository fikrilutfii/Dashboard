<?php

namespace App\Http\Controllers;

use App\Models\Kasbon;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KasbonRepayment;
use App\Models\Transaction;

class KasbonController extends Controller
{
    public function index()
    {
        $division = session('division');
        $kasbons = Kasbon::with('employee')
            ->whereHas('employee', function($q) use ($division) {
                if ($division) {
                    $q->where('division', $division);
                }
            })
            ->latest()
            ->paginate(10);
            
        return view('kasbons.index', compact('kasbons'));
    }

    public function create(Request $request)
    {
        $division = session('division');
        $employees = Employee::when($division, function($q) use ($division) {
            $q->where('division', $division);
        })->get();
        
        $selected_employee = $request->employee_id;
        return view('kasbons.create', compact('employees', 'selected_employee'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:staff_kasbon,personal_credit,personal_loan',
            'amount' => 'required|numeric|min:0',
            'installment_amount' => 'nullable|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Kasbon::create([
            'employee_id' => $validated['employee_id'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'remaining_amount' => $validated['amount'], // Initial remaining
            'installment_amount' => $validated['installment_amount'] ?? 0,
            'date' => $validated['date'],
            'description' => $validated['description'],
            'status' => 'aktif',
        ]);

        return redirect()->route('kasbons.index')->with('success', 'Pinjaman berhasil dicatat.');
    }

    public function repay(Request $request, Kasbon $kasbon)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $kasbon->remaining_amount,
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($kasbon, $validated) {
            // Create Repayment Record
            \App\Models\KasbonRepayment::create([
                'kasbon_id' => $kasbon->id,
                'amount' => $validated['amount'],
                'date' => $validated['date'],
                'method' => 'cash', // Manual repayment implies cash/transfer
                'description' => $validated['description'] ?? 'Pembayaran Manual',
            ]);

            // Update Remaining Amount
            $kasbon->decrement('remaining_amount', $validated['amount']);

            // Update Status if paid off
            if ($kasbon->fresh()->remaining_amount <= 0) {
                $kasbon->update(['status' => 'lunas']);
            }

            // Record Transaction (Money In)
            \App\Models\Transaction::create([
                'type' => 'credit',
                'amount' => $validated['amount'],
                'category' => 'kasbon_repayment',
                'reference_type' => \App\Models\Kasbon::class,
                'reference_id' => $kasbon->id,
                'description' => 'Pelunasan Kasbon ' . $kasbon->employee->name,
                'date' => $validated['date'],
                'division' => $kasbon->employee->division,
            ]);
        });

        return back()->with('success', 'Pembayaran cicilan berhasil.');
    }

    public function destroy(Kasbon $kasbon)
    {
        if($kasbon->status !== 'aktif') {
             return back()->with('error', 'Hanya kasbon status Aktif yang bisa dihapus.');
        }
        $kasbon->delete();
        return back()->with('success', 'Kasbon dihapus.');
    }
}
