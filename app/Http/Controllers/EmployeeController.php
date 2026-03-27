<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::latest();

        // Filter by Session Division
        if ($request->session()->has('division')) {
            $query->where('division', $request->session()->get('division'));
        }

        $employees = $query->paginate(10);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string', // staff, penjahit
            'salary_base' => 'required|numeric|min:0',
            'overtime_rate' => 'nullable|numeric|min:0',
        ]);

        // Auto-assign division from session
        $validated['division'] = $request->session()->get('division', 'percetakan'); // Default to percetakan if somehow missing

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string',
            'division' => 'required|in:percetakan,konfeksi',
            'salary_base' => 'required|numeric|min:0',
            'overtime_rate' => 'nullable|numeric|min:0',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diupdate.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
