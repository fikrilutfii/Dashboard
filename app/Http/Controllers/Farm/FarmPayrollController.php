<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmEmployee;
use App\Models\FarmPayroll;
use App\Models\FarmTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmPayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = FarmPayroll::with('employee');

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereYear('period_start', $request->year)
                  ->whereMonth('period_start', $request->month);
        }

        if ($request->filled('salary_type')) {
            $query->where('salary_type', $request->salary_type);
        }

        $payrolls = $query->orderBy('period_start', 'desc')->orderBy('id', 'desc')->paginate(15);
        $employees = FarmEmployee::where('is_active', true)->orderBy('name')->get();

        return view('farm.payroll.index', compact('payrolls', 'employees'));
    }

    public function create()
    {
        $employees = FarmEmployee::where('is_active', true)->orderBy('name')->get();
        return view('farm.payroll.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'farm_employee_id' => 'required|exists:farm_employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date',
            'salary_type' => 'required|in:harian,borongan,bulanan',
            'basic_salary' => 'required|numeric|min:0',
        ]);

        $employee = FarmEmployee::findOrFail($request->farm_employee_id);

        $basicSalary = floatval($request->basic_salary);
        $overtimePay = floatval($request->overtime_pay ?? 0);
        $allowances = floatval($request->allowances ?? 0);
        $deductions = floatval($request->deductions ?? 0);
        $netSalary = max(0, ($basicSalary + $overtimePay + $allowances) - $deductions);

        DB::beginTransaction();
        try {
            $payroll = FarmPayroll::create([
                'farm_employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'role' => $employee->role,
                'salary_type' => $request->salary_type,
                'period_start' => $request->period_start,
                'period_end' => $request->period_end,
                'work_days' => intval($request->work_days ?? 0),
                'total_kg_produced' => floatval($request->total_kg_produced ?? 0),
                'total_ekor_produced' => intval($request->total_ekor_produced ?? 0),
                'basic_salary' => $basicSalary,
                'overtime_pay' => $overtimePay,
                'allowances' => $allowances,
                'deductions' => $deductions,
                'net_salary' => $netSalary,
                'status' => $request->status ?? 'pending',
                'paid_at' => $request->status === 'dibayar' ? Carbon::today() : null,
                'notes' => $request->notes,
            ]);

            if ($request->status === 'dibayar') {
                FarmTransaction::create([
                    'type' => 'pengeluaran',
                    'category' => 'gaji_karyawan_rpa',
                    'description' => "Gaji {$employee->name} ({$payroll->salary_type}) periode {$payroll->period_start->format('d/m/Y')}",
                    'amount' => $netSalary,
                    'transaction_date' => Carbon::today(),
                    'reference_type' => FarmPayroll::class,
                    'reference_id' => $payroll->id,
                ]);
            }

            DB::commit();
            return redirect()->route('farm.payroll.index')->with('success', 'Data penggajian berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat data gaji: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $payroll = FarmPayroll::with('employee')->findOrFail($id);
        return view('farm.payroll.slip', compact('payroll'));
    }

    public function markAsPaid($id)
    {
        $payroll = FarmPayroll::findOrFail($id);

        if ($payroll->status !== 'dibayar') {
            DB::beginTransaction();
            try {
                $payroll->update([
                    'status' => 'dibayar',
                    'paid_at' => Carbon::today(),
                ]);

                FarmTransaction::create([
                    'type' => 'pengeluaran',
                    'category' => 'gaji_karyawan_rpa',
                    'description' => "Gaji {$payroll->employee_name} ({$payroll->salary_type})",
                    'amount' => $payroll->net_salary,
                    'transaction_date' => Carbon::today(),
                    'reference_type' => FarmPayroll::class,
                    'reference_id' => $payroll->id,
                ]);

                DB::commit();
                return back()->with('success', 'Status gaji berhasil diubah menjadi Dibayar.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
            }
        }

        return back();
    }

    public function destroy($id)
    {
        $payroll = FarmPayroll::findOrFail($id);

        DB::beginTransaction();
        try {
            FarmTransaction::where('reference_type', FarmPayroll::class)
                ->where('reference_id', $payroll->id)
                ->delete();

            $payroll->delete();

            DB::commit();
            return redirect()->route('farm.payroll.index')->with('success', 'Data penggajian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data gaji: ' . $e->getMessage());
        }
    }
}
