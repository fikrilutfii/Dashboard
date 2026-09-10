<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmCustomer;
use App\Models\FarmCustomerPrice;
use App\Models\FarmEmployee;
use App\Models\FarmProduct;
use App\Models\FarmSender;
use App\Models\FarmSupplier;
use App\Models\FarmVehicle;
use Illuminate\Http\Request;

class FarmMasterDataController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'senders');

        $senders = FarmSender::orderBy('is_default', 'desc')->orderBy('name')->get();
        $customers = FarmCustomer::with('customPrices.product')->orderBy('name')->get();
        $suppliers = FarmSupplier::orderBy('name')->get();
        $products = FarmProduct::orderBy('category')->orderBy('name')->get();
        $employees = FarmEmployee::orderBy('name')->get();
        $vehicles = FarmVehicle::orderBy('plate_number')->get();

        return view('farm.master_data.index', compact(
            'activeTab',
            'senders',
            'customers',
            'suppliers',
            'products',
            'employees',
            'vehicles'
        ));
    }

    // Senders CRUD
    public function storeSender(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($request->has('is_default')) {
            FarmSender::query()->update(['is_default' => false]);
        }

        FarmSender::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_name' => $request->bank_account_name,
            'invoice_footer_notes' => $request->invoice_footer_notes,
            'is_default' => $request->has('is_default'),
        ]);

        return redirect()->route('farm.master_data.index', ['tab' => 'senders'])->with('success', 'Identitas Pengirim berhasil ditambahkan.');
    }

    public function updateSender(Request $request, $id)
    {
        $sender = FarmSender::findOrFail($id);
        $request->validate(['name' => 'required|string|max:255']);

        if ($request->has('is_default')) {
            FarmSender::where('id', '!=', $id)->update(['is_default' => false]);
        }

        $sender->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_name' => $request->bank_account_name,
            'invoice_footer_notes' => $request->invoice_footer_notes,
            'is_default' => $request->has('is_default'),
        ]);

        return redirect()->route('farm.master_data.index', ['tab' => 'senders'])->with('success', 'Identitas Pengirim berhasil diperbarui.');
    }

    public function destroySender($id)
    {
        $sender = FarmSender::findOrFail($id);
        $sender->delete();
        return redirect()->route('farm.master_data.index', ['tab' => 'senders'])->with('success', 'Identitas Pengirim berhasil dihapus.');
    }

    // Customers CRUD & Custom Prices
    public function storeCustomer(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $customer = FarmCustomer::create($request->only(['name', 'phone', 'address', 'city', 'contact_person', 'notes']));

        // Handle custom prices if submitted
        if ($request->has('prices') && is_array($request->prices)) {
            foreach ($request->prices as $productId => $price) {
                if (floatval($price) > 0) {
                    FarmCustomerPrice::create([
                        'farm_customer_id' => $customer->id,
                        'farm_product_id' => $productId,
                        'custom_price' => $price,
                    ]);
                }
            }
        }

        return redirect()->route('farm.master_data.index', ['tab' => 'customers'])->with('success', 'Customer berhasil ditambahkan.');
    }

    public function updateCustomer(Request $request, $id)
    {
        $customer = FarmCustomer::findOrFail($id);
        $request->validate(['name' => 'required|string|max:255']);
        $customer->update($request->only(['name', 'phone', 'address', 'city', 'contact_person', 'notes']));

        // Update custom prices
        FarmCustomerPrice::where('farm_customer_id', $customer->id)->delete();
        if ($request->has('prices') && is_array($request->prices)) {
            foreach ($request->prices as $productId => $price) {
                if (floatval($price) > 0) {
                    FarmCustomerPrice::create([
                        'farm_customer_id' => $customer->id,
                        'farm_product_id' => $productId,
                        'custom_price' => $price,
                    ]);
                }
            }
        }

        return redirect()->route('farm.master_data.index', ['tab' => 'customers'])->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroyCustomer($id)
    {
        $customer = FarmCustomer::findOrFail($id);
        $customer->delete();
        return redirect()->route('farm.master_data.index', ['tab' => 'customers'])->with('success', 'Customer berhasil dihapus.');
    }

    // Suppliers CRUD
    public function storeSupplier(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        FarmSupplier::create($request->only(['name', 'farm_location', 'phone', 'address', 'contact_person', 'notes']));
        return redirect()->route('farm.master_data.index', ['tab' => 'suppliers'])->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function updateSupplier(Request $request, $id)
    {
        $supplier = FarmSupplier::findOrFail($id);
        $request->validate(['name' => 'required|string|max:255']);
        $supplier->update($request->only(['name', 'farm_location', 'phone', 'address', 'contact_person', 'notes']));
        return redirect()->route('farm.master_data.index', ['tab' => 'suppliers'])->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroySupplier($id)
    {
        $supplier = FarmSupplier::findOrFail($id);
        $supplier->delete();
        return redirect()->route('farm.master_data.index', ['tab' => 'suppliers'])->with('success', 'Supplier berhasil dihapus.');
    }

    // Products CRUD & Stock Adjustment
    public function storeProduct(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:farm_products,code',
            'name' => 'required|string|max:255',
            'category' => 'required|in:karkas_utuh,parting,sampingan,lain',
            'standard_price' => 'required|numeric|min:0',
        ]);

        FarmProduct::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'category' => $request->category,
            'unit' => $request->unit ?? 'kg',
            'standard_price' => $request->standard_price,
            'current_stock_kg' => $request->current_stock_kg ?? 0,
            'current_stock_ekor' => $request->current_stock_ekor ?? 0,
            'notes' => $request->notes,
        ]);

        return redirect()->route('farm.master_data.index', ['tab' => 'products'])->with('success', 'Produk berhasil ditambahkan.');
    }

    public function updateProduct(Request $request, $id)
    {
        $product = FarmProduct::findOrFail($id);
        $request->validate([
            'code' => "required|string|unique:farm_products,code,{$id}",
            'name' => 'required|string|max:255',
            'category' => 'required|in:karkas_utuh,parting,sampingan,lain',
            'standard_price' => 'required|numeric|min:0',
        ]);

        $product->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'category' => $request->category,
            'unit' => $request->unit ?? 'kg',
            'standard_price' => $request->standard_price,
            'current_stock_kg' => $request->current_stock_kg ?? $product->current_stock_kg,
            'current_stock_ekor' => $request->current_stock_ekor ?? $product->current_stock_ekor,
            'notes' => $request->notes,
        ]);

        return redirect()->route('farm.master_data.index', ['tab' => 'products'])->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroyProduct($id)
    {
        $product = FarmProduct::findOrFail($id);
        $product->delete();
        return redirect()->route('farm.master_data.index', ['tab' => 'products'])->with('success', 'Produk berhasil dihapus.');
    }

    // Employees CRUD
    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'salary_type' => 'required|in:harian,borongan,bulanan',
        ]);

        FarmEmployee::create([
            'name' => $request->name,
            'role' => $request->role,
            'salary_type' => $request->salary_type,
            'daily_rate' => $request->daily_rate ?? 0,
            'piece_rate_per_kg' => $request->piece_rate_per_kg ?? 0,
            'piece_rate_per_ekor' => $request->piece_rate_per_ekor ?? 0,
            'monthly_salary' => $request->monthly_salary ?? 0,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('farm.master_data.index', ['tab' => 'employees'])->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function updateEmployee(Request $request, $id)
    {
        $employee = FarmEmployee::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'salary_type' => 'required|in:harian,borongan,bulanan',
        ]);

        $employee->update([
            'name' => $request->name,
            'role' => $request->role,
            'salary_type' => $request->salary_type,
            'daily_rate' => $request->daily_rate ?? 0,
            'piece_rate_per_kg' => $request->piece_rate_per_kg ?? 0,
            'piece_rate_per_ekor' => $request->piece_rate_per_ekor ?? 0,
            'monthly_salary' => $request->monthly_salary ?? 0,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('farm.master_data.index', ['tab' => 'employees'])->with('success', 'Karyawan berhasil diperbarui.');
    }

    public function destroyEmployee($id)
    {
        $employee = FarmEmployee::findOrFail($id);
        $employee->delete();
        return redirect()->route('farm.master_data.index', ['tab' => 'employees'])->with('success', 'Karyawan berhasil dihapus.');
    }

    // Vehicles CRUD
    public function storeVehicle(Request $request)
    {
        $request->validate(['plate_number' => 'required|string|max:255']);
        FarmVehicle::create($request->only(['plate_number', 'vehicle_type', 'default_driver', 'notes']));
        return redirect()->route('farm.master_data.index', ['tab' => 'vehicles'])->with('success', 'Kendaraan armada berhasil ditambahkan.');
    }

    public function updateVehicle(Request $request, $id)
    {
        $vehicle = FarmVehicle::findOrFail($id);
        $request->validate(['plate_number' => 'required|string|max:255']);
        $vehicle->update($request->only(['plate_number', 'vehicle_type', 'default_driver', 'notes']));
        return redirect()->route('farm.master_data.index', ['tab' => 'vehicles'])->with('success', 'Kendaraan armada berhasil diperbarui.');
    }

    public function destroyVehicle($id)
    {
        $vehicle = FarmVehicle::findOrFail($id);
        $vehicle->delete();
        return redirect()->route('farm.master_data.index', ['tab' => 'vehicles'])->with('success', 'Kendaraan armada berhasil dihapus.');
    }
}
