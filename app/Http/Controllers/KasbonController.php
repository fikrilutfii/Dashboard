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
    public function index(Request $request)
    {
        $division = session('division');
        $query = Kasbon::with(['employee', 'repayments']);
        
        if ($division) {
            $query->whereHas('employee', function($q) use ($division) {
                $q->where('division', $division);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Calculate totals based on the query before pagination
        $totalOutstanding = (clone $query)->sum('remaining_amount');
        $now = \Carbon\Carbon::now();
        $kasbonBulanIni = (clone $query)->whereMonth('date', $now->month)
                                        ->whereYear('date', $now->year)
                                        ->sum('amount');

        $kasbons = $query->latest()->paginate(10);
            
        return view('kasbons.index', compact('kasbons', 'totalOutstanding', 'kasbonBulanIni'));
    }

    public function printPdf(Request $request)
    {
        $division = session('division');
        $query = Kasbon::with('employee');
        
        if ($division) {
            $query->whereHas('employee', function($q) use ($division) {
                $q->where('division', $division);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $kasbons = $query->latest()->get();
        $totalOutstanding = $kasbons->sum('remaining_amount');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kasbons.pdf', compact('kasbons', 'totalOutstanding', 'division'));
        return $pdf->stream('Laporan_Kasbon.pdf');
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

        $kasbon = Kasbon::create([
            'employee_id' => $validated['employee_id'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'remaining_amount' => $validated['amount'], // Initial remaining
            'installment_amount' => $validated['installment_amount'] ?? 0,
            'date' => $validated['date'],
            'description' => $validated['description'],
            'status' => 'aktif',
        ]);

        $kasbon->load('employee');
        $kasbon->syncToReceivable();

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

            $kasbon->load('employee');
            $kasbon->syncToReceivable();

            // Record Transaction (Money In)
            \App\Models\Transaction::create([
                'type' => 'credit',
                'amount' => $validated['amount'],
                'category' => 'kasbon_repayment',
                'reference_type' => \App\Models\Kasbon::class,
                'reference_id' => $kasbon->id,
                'description' => 'Pelunasan Kasbon ' . $kasbon->employee->name . ' - ' . ($validated['description'] ?? 'Tgl ' . \Carbon\Carbon::parse($validated['date'])->format('d/m/Y')),
                'date' => $validated['date'],
                'division' => $kasbon->employee->division,
            ]);
        });

        return back()->with('success', 'Pembayaran cicilan berhasil.');
    }

    public function edit(Kasbon $kasbon)
    {
        if ($kasbon->status !== 'aktif' && $kasbon->status !== 'lunas') {
            return redirect()->back()->with('error', 'Hanya kasbon aktif atau lunas yang bisa diedit.');
        }

        $division = session('division');
        $employees = Employee::when($division, function($q) use ($division) {
            $q->where('division', $division);
        })->get();

        return view('kasbons.edit', compact('kasbon', 'employees'));
    }

    public function update(Request $request, Kasbon $kasbon)
    {
        if ($kasbon->status !== 'aktif' && $kasbon->status !== 'lunas') {
            return redirect()->back()->with('error', 'Hanya kasbon aktif atau lunas yang bisa diedit.');
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:staff_kasbon,personal_credit,personal_loan',
            'amount' => 'required|numeric|min:0',
            'installment_amount' => 'nullable|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Calculate paid amount before update
        $paid_amount = $kasbon->amount - $kasbon->remaining_amount;

        // If new amount is less than paid amount, it's an error
        if ($validated['amount'] < $paid_amount) {
            return redirect()->back()->with('error', 'Total pinjaman tidak boleh kurang dari jumlah yang sudah dibayar.');
        }

        $newRemaining = $validated['amount'] - $paid_amount;

        $kasbon->update([
            'employee_id' => $validated['employee_id'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'remaining_amount' => $newRemaining,
            'installment_amount' => $validated['installment_amount'] ?? 0,
            'date' => $validated['date'],
            'description' => $validated['description'],
            'status' => $newRemaining > 0 ? 'aktif' : 'lunas',
        ]);

        $kasbon->load('employee');
        $kasbon->syncToReceivable();

        return redirect()->route('kasbons.index')->with('success', 'Kasbon berhasil diupdate.');
    }

    public function destroy(Kasbon $kasbon)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Hanya admin yang dapat menghapus kasbon.');
        }

        if($kasbon->status !== 'aktif') {
             return back()->with('error', 'Hanya kasbon status Aktif yang bisa dihapus.');
        }
        $kasbon->receivable()->delete();
        $kasbon->delete();
        return back()->with('success', 'Kasbon dihapus.');
    }
}
