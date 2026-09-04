<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmTransportation;
use Illuminate\Http\Request;

class FarmTransportationController extends Controller
{
    public function index(Request $request)
    {
        $query = FarmTransportation::latest('transport_date');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('description', 'like', "%$s%")
                  ->orWhere('destination', 'like', "%$s%")
                  ->orWhere('driver', 'like', "%$s%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transport_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transport_date', '<=', $request->date_to);
        }

        $transportations = $query->paginate(15);

        $totalBulanIni = FarmTransportation::whereMonth('transport_date', now()->month)
            ->whereYear('transport_date', now()->year)->sum('amount');

        return view('farm.transportation.index', compact('transportations', 'totalBulanIni'));
    }

    public function create()
    {
        return view('farm.transportation.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'transport_date' => 'required|date',
            'type'           => 'required|in:masuk,keluar',
            'description'    => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0',
        ]);

        FarmTransportation::create($request->only([
            'transport_date', 'type', 'description', 'destination',
            'driver', 'vehicle_plate', 'amount', 'status', 'notes',
        ]));

        return redirect()->route('farm.transportation.index')->with('success', 'Data transportasi berhasil ditambahkan.');
    }

    public function edit(FarmTransportation $farmTransportation)
    {
        return view('farm.transportation.edit', ['transportation' => $farmTransportation]);
    }

    public function update(Request $request, FarmTransportation $farmTransportation)
    {
        $request->validate([
            'transport_date' => 'required|date',
            'type'           => 'required|in:masuk,keluar',
            'description'    => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0',
        ]);

        $farmTransportation->update($request->only([
            'transport_date', 'type', 'description', 'destination',
            'driver', 'vehicle_plate', 'amount', 'status', 'notes',
        ]));

        return redirect()->route('farm.transportation.index')->with('success', 'Data transportasi berhasil diperbarui.');
    }

    public function destroy(FarmTransportation $farmTransportation)
    {
        $farmTransportation->delete();
        return redirect()->route('farm.transportation.index')->with('success', 'Data transportasi berhasil dihapus.');
    }
}
