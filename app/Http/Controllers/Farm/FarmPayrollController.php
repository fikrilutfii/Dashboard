<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmPayroll;
use Illuminate\Http\Request;

class FarmPayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = FarmPayroll::latest('period_start');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('employee_name', 'like', "%$s%")
                  ->orWhere('role', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->paginate(15);

        $totalBulanIni = FarmPayroll::where('status', 'dibayar')
            ->whereMonth('paid_at', now()->month)->sum('net_salary');

        $totalPending = FarmPayroll::where('status', 'pending')->sum('net_salary');

        return view('farm.payroll.index', compact('payrolls', 'totalBulanIni', 'totalPending'));
    }

    public function create()
    {
        return view('farm.payroll.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'period_start'  => 'required|date',
            'period_end'    => 'required|date|after_or_equal:period_start',
            'basic_salary'  => 'required|numeric|min:0',
        ]);

        $allowances  = (float)($request->allowances ?? 0);
        $deductions  = (float)($request->deductions ?? 0);
        $basicSalary = (float)$request->basic_salary;
        $netSalary   = $basicSalary + $allowances - $deductions;

        FarmPayroll::create([
            'employee_name' => $request->employee_name,
            'role'          => $request->role,
            'period_start'  => $request->period_start,
            'period_end'    => $request->period_end,
            'basic_salary'  => $basicSalary,
            'allowances'    => $allowances,
            'deductions'    => $deductions,
            'net_salary'    => $netSalary,
            'status'        => 'pending',
            'notes'         => $request->notes,
        ]);

        return redirect()->route('farm.payroll.index')->with('success', 'Data gaji berhasil ditambahkan.');
    }

    public function edit(FarmPayroll $farmPayroll)
    {
        return view('farm.payroll.edit', ['payroll' => $farmPayroll]);
    }

    public function update(Request $request, FarmPayroll $farmPayroll)
    {
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'period_start'  => 'required|date',
            'period_end'    => 'required|date|after_or_equal:period_start',
            'basic_salary'  => 'required|numeric|min:0',
        ]);

        $allowances  = (float)($request->allowances ?? 0);
        $deductions  = (float)($request->deductions ?? 0);
        $netSalary   = (float)$request->basic_salary + $allowances - $deductions;

        $farmPayroll->update([
            'employee_name' => $request->employee_name,
            'role'          => $request->role,
            'period_start'  => $request->period_start,
            'period_end'    => $request->period_end,
            'basic_salary'  => $request->basic_salary,
            'allowances'    => $allowances,
            'deductions'    => $deductions,
            'net_salary'    => $netSalary,
            'notes'         => $request->notes,
        ]);

        return redirect()->route('farm.payroll.index')->with('success', 'Data gaji berhasil diperbarui.');
    }

    public function markPaid(FarmPayroll $farmPayroll)
    {
        $farmPayroll->update([
            'status'  => 'dibayar',
            'paid_at' => now()->toDateString(),
        ]);
        return back()->with('success', 'Gaji ditandai sudah dibayar.');
    }

    public function destroy(FarmPayroll $farmPayroll)
    {
        $farmPayroll->delete();
        return redirect()->route('farm.payroll.index')->with('success', 'Data gaji berhasil dihapus.');
    }
}
