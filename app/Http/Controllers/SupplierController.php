<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
// Request is already imported by base Controller or implicit, but let's keep one generic if needed, or remove if unused. 
// Actually Controller doesn't import Request by default in bare class, but here it was imported twice.
// The previous block showed `use Illuminate\Http\Request;` then `use App\Models...` then `use Illuminate\Http\Request;`. 
// I will keep the one at the top.

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $division = session('division');
        $query = Supplier::query();
        
        if ($division) {
            $query->where('division', $division);
        }

        $suppliers = $query->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $validated['division'] = session('division', 'percetakan');

        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diupdate.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier berkasil dihapus.');
    }
}
