<?php

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\InvoiceItem;

$customer1 = Customer::firstOrCreate(
    ['name' => 'PT. HARMONI'],
    ['email' => 'harmoni@example.com', 'phone' => '081234567890', 'address' => 'Bandung']
);

$customer2 = Customer::firstOrCreate(
    ['name' => 'TOKO ANDI'],
    ['email' => 'andi@example.com', 'phone' => '081298765432', 'address' => 'Bandung']
);

$customer3 = Customer::firstOrCreate(
    ['name' => 'UMUM'],
    ['email' => 'umum@example.com', 'phone' => '00000000', 'address' => 'Bandung']
);

// Helper to add invoice
function addInvoice($customer, $division, $totalAmount) {
    $fakturNumber = Invoice::generateFakturNumber($division);
    
    // Ensure invoice number is unique
    $invoiceNumber = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
    
    $invoice = Invoice::create([
        'invoice_number' => $invoiceNumber,
        'faktur_number' => $fakturNumber,
        'customer_id' => $customer->id,
        'invoice_date' => now()->subDays(rand(1, 30)),
        'due_date' => now()->addDays(rand(1, 30)),
        'total_amount' => $totalAmount,
        'paid_amount' => 0,
        'status' => 'belum_lunas',
        'division' => $division,
        'payment_method' => 'cash',
        'tenure' => 0,
        'is_printed' => false,
    ]);

    InvoiceItem::create([
        'invoice_id' => $invoice->id,
        'item_name' => 'Barang Contoh ' . rand(1, 100),
        'quantity' => 1,
        'unit_price' => $totalAmount,
        'subtotal' => $totalAmount,
    ]);
    
    // Sync to receivable
    $invoice->syncToReceivable();
    
    return $invoice;
}

// Add some invoices
addInvoice($customer1, 'TOKO ANDI', 930000);
addInvoice($customer2, 'TOKO ANDI', 1500000);
addInvoice($customer3, 'UMUM', 500000);
addInvoice($customer3, 'UMUM', 750000);

echo "Invoices seeded successfully.\n";
