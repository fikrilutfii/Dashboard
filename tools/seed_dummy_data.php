<?php
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Transaction;
use App\Models\CompanyDebt;
use App\Models\CompanyReceivable;
use App\Models\Payroll;
use App\Models\Kasbon;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Str;

$now = Carbon::now();

// 1. Create Customers
$customer1 = Customer::firstOrCreate(['email' => 'budidaya@example.com'], ['name' => 'PT Budi Daya', 'phone' => '08123456789', 'address' => 'Jl. Merdeka No 1', 'division' => 'percetakan']);
$customer2 = Customer::firstOrCreate(['email' => 'maju@example.com'], ['name' => 'Toko Maju Jaya', 'phone' => '08987654321', 'address' => 'Jl. Sudirman No 2', 'division' => 'konfeksi']);

// 2. Create Suppliers
$supplier1 = Supplier::firstOrCreate(['email' => 'tintaemas@example.com'], ['name' => 'Supplier Tinta Emas', 'phone' => '08111222333', 'address' => 'Jl. Tinta 1', 'division' => 'percetakan']);
$supplier2 = Supplier::firstOrCreate(['email' => 'kainmakmur@example.com'], ['name' => 'Toko Kain Makmur', 'phone' => '08222333444', 'address' => 'Jl. Kain 2', 'division' => 'konfeksi']);

// 3. Create Employees
$employee1 = Employee::firstOrCreate(['name' => 'Agus Karyawan'], ['role' => 'Operator Jahit', 'salary_base' => 3000000, 'overtime_rate' => 20000, 'division' => 'konfeksi']);
$employee2 = Employee::firstOrCreate(['name' => 'Budi Staf'], ['role' => 'Admin', 'salary_base' => 2500000, 'overtime_rate' => 15000, 'division' => 'percetakan']);

// 4. Create Products
$product1 = Product::firstOrCreate(['code' => 'PRC-001'], ['name' => 'Cetak Brosur A4', 'unit' => 'rim', 'price' => 150000, 'division' => 'percetakan']);
$product2 = Product::firstOrCreate(['code' => 'KNF-001'], ['name' => 'Kaos Polos Cotton Combed', 'unit' => 'pcs', 'price' => 45000, 'division' => 'konfeksi']);

// 5. Create Invoices & Receivables (Percetakan) - LUNAS
$invoice1 = Invoice::create([
    'invoice_number' => 'INV-PRC-' . time() . '1',
    'customer_id' => $customer1->id,
    'invoice_date' => $now->copy()->subDays(5),
    'due_date' => $now->copy()->addDays(25),
    'payment_method' => 'cash',
    'total_amount' => 750000,
    'paid_amount' => 750000,
    'status' => 'lunas',
    'division' => 'percetakan'
]);
InvoiceItem::create(['invoice_id' => $invoice1->id, 'product_code' => $product1->code, 'item_name' => $product1->name, 'quantity' => 5, 'unit_price' => 150000, 'subtotal' => 750000]);
Transaction::create([
    'type' => 'credit',
    'amount' => 750000,
    'category' => 'penjualan',
    'date' => $now->copy()->subDays(5),
    'description' => 'Pembayaran Lunas Invoice ' . $invoice1->invoice_number,
    'division' => 'percetakan',
    'entity' => 'percetakan',
    'reference_type' => Invoice::class,
    'reference_id' => $invoice1->id
]);

// 6. Create Invoices & Receivables (Konfeksi) - BELUM LUNAS (Kredit)
$invoice2 = Invoice::create([
    'invoice_number' => 'INV-KNF-' . time() . '2',
    'customer_id' => $customer2->id,
    'invoice_date' => $now->copy()->subDays(2),
    'due_date' => $now->copy()->addDays(28),
    'payment_method' => 'credit',
    'tenure' => 3,
    'total_amount' => 2250000,
    'paid_amount' => 0,
    'status' => 'belum_lunas',
    'division' => 'konfeksi'
]);
InvoiceItem::create(['invoice_id' => $invoice2->id, 'product_code' => $product2->code, 'item_name' => $product2->name, 'quantity' => 50, 'unit_price' => 45000, 'subtotal' => 2250000]);
CompanyReceivable::create([
    'name' => 'Tagihan ' . $customer2->name,
    'total_amount' => 2250000,
    'remaining_amount' => 2250000,
    'monthly_amount' => 2250000 / 3,
    'due_date' => $now->copy()->addDays(28),
    'status' => 'belum_lunas',
    'type' => 'installment',
    'description' => 'Penjualan Konveksi ' . $invoice2->invoice_number,
    'division' => 'konfeksi',
    'entity' => 'konfeksi',
    'invoice_id' => $invoice2->id
]);

// 7. Create Purchases (Belanja Bahan) - Percetakan (Cash)
$purchase1 = Purchase::create([
    'purchase_number' => 'PO-PRC-' . time() . '1',
    'supplier_id' => $supplier1->id,
    'date' => $now->copy()->subDays(10),
    'due_date' => $now->copy()->addDays(20),
    'total_amount' => 300000,
    'status' => 'lunas',
    'division' => 'percetakan'
]);
PurchaseItem::create(['purchase_id' => $purchase1->id, 'item_name' => 'Tinta Kertas', 'quantity' => 2, 'unit_price' => 150000, 'subtotal' => 300000]);
Transaction::create([
    'type' => 'debit',
    'amount' => 300000,
    'category' => 'belanja_bahan',
    'date' => $now->copy()->subDays(10),
    'description' => 'Pembayaran Lunas PO ' . $purchase1->purchase_number,
    'division' => 'percetakan',
    'entity' => 'percetakan',
    'reference_type' => Purchase::class,
    'reference_id' => $purchase1->id
]);

// 8. Create Purchases (Belanja Bahan) - Konfeksi (Kredit)
$purchase2 = Purchase::create([
    'purchase_number' => 'PO-KNF-' . time() . '2',
    'supplier_id' => $supplier2->id,
    'date' => $now->copy()->subDays(3),
    'due_date' => $now->copy()->addDays(27),
    'total_amount' => 1000000,
    'status' => 'belum_lunas',
    'division' => 'konfeksi'
]);
PurchaseItem::create(['purchase_id' => $purchase2->id, 'item_name' => 'Kain Katun', 'quantity' => 10, 'unit_price' => 100000, 'subtotal' => 1000000]);
CompanyDebt::create([
    'name' => 'Hutang ' . $supplier2->name,
    'amount' => 1000000,
    'remaining_amount' => 1000000,
    'monthly_amount' => 0,
    'due_date' => $now->copy()->addDays(27),
    'status' => 'belum_lunas',
    'type' => 'cash',
    'description' => 'Pembelian Kain Konveksi',
    'division' => 'konfeksi',
    'entity' => 'konfeksi',
    'purchase_id' => $purchase2->id
]);

// 9. Create Payroll (Konfeksi) - LUNAS
$payroll1 = Payroll::create([
    'employee_id' => $employee1->id,
    'period_start' => $now->copy()->startOfMonth(),
    'period_end' => $now->copy()->startOfMonth()->addDays(14),
    'basic_salary' => 1500000,
    'bonus' => 200000,
    'total_salary' => 1700000,
    'status' => 'lunas'
]);
Transaction::create([
    'type' => 'debit',
    'amount' => 1700000,
    'category' => 'gaji_karyawan',
    'date' => $now->copy()->subDays(1),
    'description' => 'Pembayaran Gaji Karyawan: ' . $employee1->name,
    'division' => 'konfeksi',
    'entity' => 'konfeksi',
    'reference_type' => Payroll::class,
    'reference_id' => $payroll1->id
]);

// 10. Manual Expenses (Biaya Operasional)
Transaction::create([
    'type' => 'debit',
    'amount' => 150000,
    'category' => 'operasional',
    'date' => $now->copy()->subDays(4),
    'description' => 'Bayar Listrik & Air Bulan Ini',
    'division' => 'percetakan',
    'entity' => 'percetakan',
]);
Transaction::create([
    'type' => 'debit',
    'amount' => 50000,
    'category' => 'operasional',
    'date' => $now->copy()->subDays(2),
    'description' => 'Beli Kopi & Gula',
    'division' => 'konfeksi',
    'entity' => 'konfeksi',
]);

echo "Dummy data for current month generated successfully!\n";
