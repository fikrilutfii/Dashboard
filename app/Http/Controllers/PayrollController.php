<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayrollController extends Controller
{
    // ─── LIST ────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $division = session('division');
        $query = Payroll::with('employee');

        if ($division) {
            $query->whereHas('employee', fn($q) => $q->where('division', $division));
        }

        if ($request->filled('search')) {
            $query->whereHas('employee', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        if ($request->filled('date_start')) {
            $query->whereDate('period_end', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->whereDate('period_end', '<=', $request->date_end);
        }

        $payrolls = $query->latest('period_end')->paginate(20)->withQueryString();

        return view('payrolls.index', compact('payrolls'));
    }

    // ─── RECAP (Auto-calculate from attendance) ──────────────────────────
    public function recap(Request $request)
    {
        $division  = session('division');
        $employees = Employee::when($division, fn($q) => $q->where('division', $division))->orderBy('name')->get();

        $periodStart = $request->input('period_start', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $periodEnd   = $request->input('period_end',   Carbon::now()->format('Y-m-d'));

        // Enrich employees with attendance data
        foreach ($employees as $employee) {
            $workingDays = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$periodStart, $periodEnd])
                ->where('status', 'masuk')
                ->count();

            $employee->working_days_count    = $workingDays;
            $employee->daily_rate            = $employee->salary_base;
            $employee->subtotal_salary       = $employee->salary_base * $workingDays;
            $employee->overtime_rate         = $employee->overtime_rate ?: 0;
            $employee->current_kasbon        = $employee->kasbons()->where('remaining_amount', '>', 0)->sum('remaining_amount');
            $employee->recommended_kasbon_deduction = 0;

            $kasbons = $employee->kasbons()->where('remaining_amount', '>', 0)->get();
            foreach ($kasbons as $k) {
                $employee->recommended_kasbon_deduction += $k->installment_amount > 0
                    ? min($k->installment_amount, $k->remaining_amount)
                    : 0;
            }
        }

        return view('payrolls.recap', compact('employees', 'periodStart', 'periodEnd'));
    }

    // ─── PROCESS WEEKLY PAYMENT ──────────────────────────────────────────
    public function storeRecap(Request $request)
    {
        $request->validate([
            'period_start'              => 'required|date',
            'period_end'                => 'required|date|after_or_equal:period_start',
            'payrolls'                  => 'required|array',
            'payrolls.*.employee_id'    => 'required|exists:employees,id',
            'payrolls.*.working_days'   => 'required|integer|min:0',
            'payrolls.*.overtime_hours' => 'nullable|numeric|min:0',
            'payrolls.*.bonus'          => 'nullable|numeric|min:0',
            'payrolls.*.deduction'      => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->payrolls as $data) {
                $workingDays   = (int)($data['working_days'] ?? 0);
                $overtimeHours = (float)($data['overtime_hours'] ?? 0);
                $bonus         = (float)($data['bonus']       ?? 0);
                $deduction     = (float)($data['deduction']   ?? 0);

                if ($workingDays <= 0 && $bonus <= 0 && $overtimeHours <= 0) continue;

                $employee     = Employee::findOrFail($data['employee_id']);
                $dailyRate    = $employee->salary_base;
                $overtimeRate = $employee->overtime_rate;
                $basicSalary  = $dailyRate * $workingDays;
                $overtimePay  = $overtimeRate * $overtimeHours;
                $totalSalary  = $basicSalary + $overtimePay + $bonus - $deduction;

                // Deduct Kasbon (FIFO)
                if ($deduction > 0) {
                    $remaining = $deduction;
                    foreach ($employee->kasbons()->where('remaining_amount', '>', 0)->orderBy('date')->get() as $kasbon) {
                        if ($remaining <= 0) break;
                        $amount = min($remaining, $kasbon->remaining_amount);
                        \App\Models\KasbonRepayment::create([
                            'kasbon_id'   => $kasbon->id,
                            'amount'      => $amount,
                            'date'        => now(),
                            'method'      => 'payroll_deduction',
                            'description' => 'Potong Gaji Minggu ' . $request->period_end,
                        ]);
                        $kasbon->decrement('remaining_amount', $amount);
                        $remaining -= $amount;
                        if ($kasbon->fresh()->remaining_amount <= 0) {
                            $kasbon->update(['status' => 'lunas']);
                        }
                    }
                }

                $payroll = Payroll::create([
                    'employee_id'       => $employee->id,
                    'period_start'      => $request->period_start,
                    'period_end'        => $request->period_end,
                    'daily_rate'        => $dailyRate,
                    'working_days'      => $workingDays,
                    'working_days_count'=> $workingDays,
                    'overtime_hours'    => $overtimeHours,
                    'overtime_rate'     => $overtimeRate,
                    'overtime_pay'      => $overtimePay,
                    'basic_salary'      => $basicSalary,
                    'bonus'             => $bonus,
                    'kasbon_deduction'  => $deduction,
                    'total_salary'      => $totalSalary,
                    'status'            => 'belum_lunas',
                ]);

                // Auto-create Finance Entry (Pengeluaran → Penggajian)
                Transaction::create([
                    'type'           => 'debit',
                    'amount'         => $totalSalary,
                    'category'       => 'penggajian',
                    'reference_type' => Payroll::class,
                    'reference_id'   => $payroll->id,
                    'description'    => 'Pembayaran Gaji ' . $employee->name . ' (' . $workingDays . ' hari, ' . $request->period_start . ' – ' . $request->period_end . ')',
                    'date'           => now(),
                    'division'       => $employee->division,
                ]);
            }
        });

        return redirect()->route('payrolls.index')->with('success', 'Rekap Gaji Mingguan berhasil diproses. Status: Belum Lunas.');
    }

    // ─── MANUAL SINGLE ENTRY ─────────────────────────────────────────────
    public function create()
    {
        $division  = session('division');
        $employees = Employee::when($division, fn($q) => $q->where('division', $division))->get();
        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'period_start'  => 'required|date',
            'period_end'    => 'required|date|after_or_equal:period_start',
            'bonus'          => 'nullable|numeric|min:0',
            'deduction'      => 'nullable|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $employee = Employee::findOrFail($validated['employee_id']);
            
            // Count attendance if exists
            $workingDays  = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$validated['period_start'], $validated['period_end']])
                ->where('status', 'masuk')->count();
            
            $dailyRate     = $employee->salary_base;
            $overtimeRate  = $employee->overtime_rate;
            $overtimeHours = (float)($validated['overtime_hours'] ?? 0);
            $basicSalary   = $dailyRate * max($workingDays, 1);
            $overtimePay   = $overtimeRate * $overtimeHours;
            $bonus         = (float)($validated['bonus']     ?? 0);
            $deduction     = (float)($validated['deduction'] ?? 0);
            $totalSalary   = $basicSalary + $overtimePay + $bonus - $deduction;

            $payroll = Payroll::create([
                'employee_id'        => $employee->id,
                'period_start'       => $validated['period_start'],
                'period_end'         => $validated['period_end'],
                'daily_rate'         => $dailyRate,
                'working_days'       => $workingDays,
                'working_days_count' => $workingDays,
                'overtime_hours'     => $overtimeHours,
                'overtime_rate'      => $overtimeRate,
                'overtime_pay'       => $overtimePay,
                'basic_salary'       => $basicSalary,
                'bonus'              => $bonus,
                'kasbon_deduction'   => $deduction,
                'total_salary'       => $totalSalary,
                'status'             => 'belum_lunas',
            ]);

            Transaction::create([
                'type'           => 'debit',
                'amount'         => $totalSalary,
                'category'       => 'penggajian',
                'reference_type' => Payroll::class,
                'reference_id'   => $payroll->id,
                'description'    => 'Pembayaran Gaji ' . $employee->name,
                'date'           => now(),
                'division'       => $employee->division,
            ]);
        });

        return redirect()->route('payrolls.index')->with('success', 'Data penggajian berhasil disimpan.');
    }

    // ─── EDIT / UPDATE ───────────────────────────────────────────────────
    public function edit(Payroll $payroll)
    {
        return view('payrolls.edit', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $validated = $request->validate([
            'bonus'            => 'nullable|numeric|min:0',
            'kasbon_deduction' => 'nullable|numeric|min:0',
            'overtime_hours'   => 'nullable|numeric|min:0',
            'working_days'     => 'required|integer|min:0',
            'status'           => 'required|in:belum_lunas,lunas',
        ]);

        $dailyRate     = $payroll->daily_rate ?: $payroll->employee->salary_base;
        $overtimeRate  = $payroll->overtime_rate ?: $payroll->employee->overtime_rate;
        $overtimeHours = (float)($validated['overtime_hours'] ?? 0);
        $basicSalary   = $dailyRate * (int)$validated['working_days'];
        $overtimePay   = $overtimeRate * $overtimeHours;
        $totalSalary   = $basicSalary + $overtimePay + (float)($validated['bonus'] ?? 0) - (float)($validated['kasbon_deduction'] ?? 0);

        $payroll->update([
            'working_days'      => $validated['working_days'],
            'working_days_count'=> $validated['working_days'],
            'overtime_hours'    => $overtimeHours,
            'overtime_rate'     => $overtimeRate,
            'overtime_pay'      => $overtimePay,
            'basic_salary'      => $basicSalary,
            'bonus'             => $validated['bonus'] ?? 0,
            'kasbon_deduction'  => $validated['kasbon_deduction'] ?? 0,
            'total_salary'      => $totalSalary,
            'status'            => $validated['status'],
        ]);

        // Sync transaction amount
        Transaction::where('reference_type', Payroll::class)
            ->where('reference_id', $payroll->id)
            ->update(['amount' => $totalSalary]);

        return redirect()->route('payrolls.index')->with('success', 'Data penggajian berhasil diperbarui.');
    }

    // ─── SOFT DELETE ─────────────────────────────────────────────────────
    public function destroy(Payroll $payroll)
    {
        // Soft delete associated transaction
        Transaction::where('reference_type', Payroll::class)
            ->where('reference_id', $payroll->id)
            ->delete();

        $payroll->delete();
        return back()->with('success', 'Data penggajian dihapus.');
    }

    // ─── MARK LUNAS ──────────────────────────────────────────────────────
    public function markLunas(Payroll $payroll)
    {
        $payroll->update(['status' => 'lunas']);

        // Update transaction record to reflect payment
        Transaction::where('reference_type', Payroll::class)
            ->where('reference_id', $payroll->id)
            ->update(['description' => 'Pembayaran Gaji ' . $payroll->employee->name . ' [LUNAS] ' . now()->format('d/m/Y')]);

        return back()->with('success', 'Gaji ' . $payroll->employee->name . ' ditandai Lunas.');
    }

    // ─── PRINT ───────────────────────────────────────────────────────────
    public function print(Request $request)
    {
        $division    = session('division');
        $periodStart = $request->input('period_start');
        $periodEnd   = $request->input('period_end');

        $query = Payroll::with('employee')
            ->when($division, fn($q) => $q->whereHas('employee', fn($e) => $e->where('division', $division)))
            ->when($periodStart, fn($q) => $q->whereDate('period_start', '>=', $periodStart))
            ->when($periodEnd, fn($q) => $q->whereDate('period_end', '<=', $periodEnd))
            ->orderBy('period_end');

        $payrolls = $query->get();

        return view('payrolls.print', compact('payrolls', 'periodStart', 'periodEnd'));
    }

    // ─── PRINT SLIP GAJI ─────────────────────────────────────────────────
    public function printSlip(Payroll $payroll)
    {
        $payroll->load('employee');
        return view('payrolls.slip', compact('payroll'));
    }

    // ─── AJAX Helper ─────────────────────────────────────────────────────
    public function getEmployeeData(Employee $employee)
    {
        $kasbons             = $employee->kasbons()->where('remaining_amount', '>', 0)->get();
        $totalRemaining      = $kasbons->sum('remaining_amount');
        $recommendedDeduction = $kasbons->sum(function($k) {
            return $k->installment_amount > 0 ? min($k->installment_amount, $k->remaining_amount) : 0;
        });

        return response()->json([
            'salary_base'          => $employee->salary_base,
            'overtime_rate'        => $employee->overtime_rate,
            'open_kasbon'          => $totalRemaining,
            'recommended_deduction'=> $recommendedDeduction,
        ]);
    }
}
