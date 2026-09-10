<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmInvoice;
use App\Models\FarmTransportation;
use App\Models\FarmVehicle;
use Illuminate\Http\Request;

class FarmTransportationController extends Controller
{
    public function index(Request $request)
    {
        $query = FarmTransportation::with(['invoice.customer', 'vehicle']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transport_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $query->where('delivery_status', $request->status);
        }

        $transportations = $query->orderBy('transport_date', 'desc')->orderBy('id', 'desc')->paginate(15);
        $vehicles = FarmVehicle::orderBy('plate_number')->get();

        return view('farm.transportations.index', compact('transportations', 'vehicles'));
    }

    public function create()
    {
        $invoices = FarmInvoice::with('customer')->whereDoesntHave('transportation')->orderBy('id', 'desc')->limit(30)->get();
        $vehicles = FarmVehicle::orderBy('plate_number')->get();

        return view('farm.transportations.create', compact('invoices', 'vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transport_date' => 'required|date',
            'driver_name' => 'required|string',
            'delivery_status' => 'required|in:sedang_dikirim,baik,komplain_sebagian,komplain_penuh',
            'bbm_cost' => 'nullable|numeric|min:0',
            'toll_cost' => 'nullable|numeric|min:0',
            'other_cost' => 'nullable|numeric|min:0',
        ]);

        FarmTransportation::create([
            'transport_date' => $request->transport_date,
            'farm_invoice_id' => $request->farm_invoice_id ?: null,
            'farm_vehicle_id' => $request->farm_vehicle_id ?: null,
            'driver_name' => $request->driver_name,
            'destination' => $request->destination,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'delivery_status' => $request->delivery_status,
            'bbm_cost' => $request->bbm_cost ?? 0,
            'toll_cost' => $request->toll_cost ?? 0,
            'other_cost' => $request->other_cost ?? 0,
            'notes' => $request->notes,
        ]);

        return redirect()->route('farm.transportations.index')->with('success', 'Log Transportasi berhasil dicatat.');
    }

    public function edit($id)
    {
        $transportation = FarmTransportation::findOrFail($id);
        $invoices = FarmInvoice::with('customer')->orderBy('id', 'desc')->limit(50)->get();
        $vehicles = FarmVehicle::orderBy('plate_number')->get();

        return view('farm.transportations.edit', compact('transportation', 'invoices', 'vehicles'));
    }

    public function update(Request $request, $id)
    {
        $transportation = FarmTransportation::findOrFail($id);

        $request->validate([
            'transport_date' => 'required|date',
            'driver_name' => 'required|string',
            'delivery_status' => 'required|in:sedang_dikirim,baik,komplain_sebagian,komplain_penuh',
            'bbm_cost' => 'nullable|numeric|min:0',
            'toll_cost' => 'nullable|numeric|min:0',
            'other_cost' => 'nullable|numeric|min:0',
        ]);

        $transportation->update([
            'transport_date' => $request->transport_date,
            'farm_invoice_id' => $request->farm_invoice_id ?: null,
            'farm_vehicle_id' => $request->farm_vehicle_id ?: null,
            'driver_name' => $request->driver_name,
            'destination' => $request->destination,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'delivery_status' => $request->delivery_status,
            'bbm_cost' => $request->bbm_cost ?? 0,
            'toll_cost' => $request->toll_cost ?? 0,
            'other_cost' => $request->other_cost ?? 0,
            'notes' => $request->notes,
        ]);

        return redirect()->route('farm.transportations.index')->with('success', 'Log Transportasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $transportation = FarmTransportation::findOrFail($id);
        $transportation->delete();

        return redirect()->route('farm.transportations.index')->with('success', 'Log Transportasi berhasil dihapus.');
    }
}
