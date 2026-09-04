<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmCustomer;
use App\Models\FarmSupplier;
use App\Models\FarmCoop;
use Illuminate\Http\Request;

class FarmMasterDataController extends Controller
{
    // ==================== CUSTOMERS ====================

    public function customersIndex(Request $request)
    {
        $query = FarmCustomer::withCount('invoices');
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('phone', 'like', "%$s%")
                  ->orWhere('city', 'like', "%$s%");
            });
        }
        $customers = $query->orderBy('name')->paginate(20);
        return view('farm.master.customers.index', compact('customers'));
    }

    public function customersCreate()
    {
        return view('farm.master.customers.create');
    }

    public function customersStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        FarmCustomer::create($request->only(['name', 'phone', 'address', 'city', 'contact_person', 'notes']));
        return redirect()->route('farm.master.customers.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    public function customersEdit(FarmCustomer $farmCustomer)
    {
        return view('farm.master.customers.edit', ['customer' => $farmCustomer]);
    }

    public function customersUpdate(Request $request, FarmCustomer $farmCustomer)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $farmCustomer->update($request->only(['name', 'phone', 'address', 'city', 'contact_person', 'notes']));
        return redirect()->route('farm.master.customers.index')->with('success', 'Customer berhasil diperbarui.');
    }

    public function customersDestroy(FarmCustomer $farmCustomer)
    {
        $farmCustomer->delete();
        return redirect()->route('farm.master.customers.index')->with('success', 'Customer berhasil dihapus.');
    }

    // ==================== SUPPLIERS ====================

    public function suppliersIndex(Request $request)
    {
        $query = FarmSupplier::query();
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('type', 'like', "%$s%");
            });
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        $suppliers = $query->orderBy('name')->paginate(20);
        return view('farm.master.suppliers.index', compact('suppliers'));
    }

    public function suppliersCreate()
    {
        return view('farm.master.suppliers.create');
    }

    public function suppliersStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:doc,pakan,obat,alat,lain',
        ]);
        FarmSupplier::create($request->only(['name', 'type', 'phone', 'address', 'contact_person', 'notes']));
        return redirect()->route('farm.master.suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function suppliersEdit(FarmSupplier $farmSupplier)
    {
        return view('farm.master.suppliers.edit', ['supplier' => $farmSupplier]);
    }

    public function suppliersUpdate(Request $request, FarmSupplier $farmSupplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:doc,pakan,obat,alat,lain',
        ]);
        $farmSupplier->update($request->only(['name', 'type', 'phone', 'address', 'contact_person', 'notes']));
        return redirect()->route('farm.master.suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function suppliersDestroy(FarmSupplier $farmSupplier)
    {
        $farmSupplier->delete();
        return redirect()->route('farm.master.suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }

    // ==================== COOPS (KANDANG) ====================

    public function coopsIndex(Request $request)
    {
        $query = FarmCoop::withCount(['operationalLogs']);
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('location', 'like', "%$s%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $coops = $query->orderBy('name')->paginate(20);
        return view('farm.master.coops.index', compact('coops'));
    }

    public function coopsCreate()
    {
        return view('farm.master.coops.create');
    }

    public function coopsStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);
        FarmCoop::create($request->only(['name', 'capacity', 'location', 'status', 'notes']));
        return redirect()->route('farm.master.coops.index')->with('success', 'Kandang berhasil ditambahkan.');
    }

    public function coopsEdit(FarmCoop $farmCoop)
    {
        return view('farm.master.coops.edit', ['coop' => $farmCoop]);
    }

    public function coopsUpdate(Request $request, FarmCoop $farmCoop)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);
        $farmCoop->update($request->only(['name', 'capacity', 'location', 'status', 'notes']));
        return redirect()->route('farm.master.coops.index')->with('success', 'Kandang berhasil diperbarui.');
    }

    public function coopsDestroy(FarmCoop $farmCoop)
    {
        $farmCoop->delete();
        return redirect()->route('farm.master.coops.index')->with('success', 'Kandang berhasil dihapus.');
    }
}
