<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Farm Senders (Master Identitas Pengirim Faktur / Multi-Entity)
        Schema::create('farm_senders', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Asfour Broiler", "CV Berkah Mandiri"
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->text('invoice_footer_notes')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // 2. Farm Customers (Master Pelanggan)
        Schema::create('farm_customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Farm Suppliers (Master Supplier Ayam Hidup)
        Schema::create('farm_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('farm_location')->nullable(); // Lokasi peternakan / kandang asal
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Farm Products (Master Produk / Karkas / Parting & Realtime Stok)
        Schema::create('farm_products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. "AYM-UTUH", "FLT-DADA", "PHA-BWH", "CKR"
            $table->string('name'); // e.g. "Ayam Karkas Utuh", "Fillet Dada", "Paha Bawah", "Ceker", "Jeroan"
            $table->enum('category', ['karkas_utuh', 'parting', 'sampingan', 'lain'])->default('karkas_utuh');
            $table->string('unit')->default('kg'); // kg, ekor, pack
            $table->decimal('standard_price', 15, 2)->default(0); // Harga standar per unit
            $table->decimal('current_stock_kg', 12, 2)->default(0); // Stok realtime kg
            $table->integer('current_stock_ekor')->default(0); // Stok realtime ekor (jika karkas)
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 5. Farm Customer Special Prices (Master Harga Khusus per Customer)
        Schema::create('farm_customer_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_customer_id')->constrained('farm_customers')->cascadeOnDelete();
            $table->foreignId('farm_product_id')->constrained('farm_products')->cascadeOnDelete();
            $table->decimal('custom_price', 15, 2)->default(0);
            $table->timestamps();
            $table->unique(['farm_customer_id', 'farm_product_id']);
        });

        // 6. Farm Employees (Master Karyawan RPA)
        Schema::create('farm_employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable(); // e.g. "Pemotong", "Pembersih Bulu", "Parting / Fillet", "Packing", "Supir"
            $table->enum('salary_type', ['harian', 'borongan', 'bulanan'])->default('borongan');
            $table->decimal('daily_rate', 15, 2)->default(0); // Tarif harian
            $table->decimal('piece_rate_per_kg', 15, 2)->default(0); // Tarif borongan per kg
            $table->decimal('piece_rate_per_ekor', 15, 2)->default(0); // Tarif borongan per ekor
            $table->decimal('monthly_salary', 15, 2)->default(0); // Gaji pokok bulanan
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 7. Farm Vehicles (Master Armada & Supir)
        Schema::create('farm_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number'); // e.g. "B 1234 CD"
            $table->string('vehicle_type')->nullable(); // e.g. "Gran Max Box Pendingin", "Pick Up", "Engkel"
            $table->string('default_driver')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 8. Farm Production Batches (Penerimaan Ayam Hidup & Produksi RPA)
        Schema::create('farm_production_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique(); // e.g. "BATCH-202609-001"
            $table->date('production_date');
            $table->foreignId('farm_supplier_id')->nullable()->constrained('farm_suppliers')->nullOnDelete();
            
            // Intake Data (Bahan Baku Ayam Hidup)
            $table->integer('live_birds_count')->default(0); // Ekor ayam hidup diterima
            $table->decimal('live_birds_weight_kg', 10, 2)->default(0); // Total berat timbang hidup (kg)
            $table->decimal('buy_price_per_kg', 15, 2)->default(0); // Harga beli per kg dari supplier
            $table->decimal('total_buy_price', 15, 2)->default(0); // Total HPP pembelian ayam hidup
            $table->enum('supplier_payment_status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            
            // DOA / Reject Data
            $table->integer('doa_count')->default(0); // Ekor mati di jalan (DOA)
            $table->decimal('doa_weight_kg', 10, 2)->default(0); // Berat ayam mati (kg)
            
            // Output Summary
            $table->decimal('total_yield_weight_kg', 10, 2)->default(0); // Total berat karkas & parting hasil potong (kg)
            $table->decimal('shrinkage_weight_kg', 10, 2)->default(0); // Susut berat (kg) = Live Kg - Yield Kg
            $table->decimal('shrinkage_percentage', 5, 2)->default(0); // Persentase susut (%)
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 9. Farm Production Items (Breakdown Hasil Parting per Batch -> Menambah Stok)
        Schema::create('farm_production_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_production_batch_id')->constrained('farm_production_batches')->cascadeOnDelete();
            $table->foreignId('farm_product_id')->constrained('farm_products')->cascadeOnDelete();
            $table->integer('qty_ekor')->default(0);
            $table->decimal('weight_kg', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 10. Farm Invoices (Faktur Penjualan)
        Schema::create('farm_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // e.g. "INV-AB-202609-0001"
            $table->foreignId('farm_sender_id')->nullable()->constrained('farm_senders')->nullOnDelete();
            $table->foreignId('farm_customer_id')->constrained('farm_customers');
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);
            $table->enum('status', ['belum_lunas', 'sebagian', 'lunas'])->default('belum_lunas');
            $table->string('payment_method')->nullable(); // Transfer BCA, Tunai, dll
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 11. Farm Invoice Items (Rincian Faktur Penjualan -> Mengurangi Stok)
        Schema::create('farm_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_invoice_id')->constrained('farm_invoices')->cascadeOnDelete();
            $table->foreignId('farm_product_id')->nullable()->constrained('farm_products')->nullOnDelete();
            $table->string('product_code')->nullable();
            $table->string('item_name');
            $table->decimal('weight_kg', 10, 2)->default(0);
            $table->integer('qty_ekor')->default(0);
            $table->string('unit')->default('kg');
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->timestamps();
        });

        // 12. Farm Invoice Payments (Histori Pembayaran Cicilan / Bertahap)
        Schema::create('farm_invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_invoice_id')->constrained('farm_invoices')->cascadeOnDelete();
            $table->date('payment_date');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('payment_method')->default('transfer'); // Transfer / Tunai
            $table->string('reference_number')->nullable(); // No Ref Transfer
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 13. Farm Transportations (Log Pengiriman & SLA Kesegaran)
        Schema::create('farm_transportations', function (Blueprint $table) {
            $table->id();
            $table->date('transport_date');
            $table->foreignId('farm_invoice_id')->nullable()->constrained('farm_invoices')->nullOnDelete();
            $table->foreignId('farm_vehicle_id')->nullable()->constrained('farm_vehicles')->nullOnDelete();
            $table->string('driver_name')->nullable();
            $table->string('destination')->nullable();
            $table->time('departure_time')->nullable(); // Jam berangkat
            $table->time('arrival_time')->nullable(); // Jam tiba
            $table->enum('delivery_status', ['sedang_dikirim', 'baik', 'komplain_sebagian', 'komplain_penuh'])->default('baik');
            $table->decimal('bbm_cost', 15, 2)->default(0); // Biaya BBM
            $table->decimal('toll_cost', 15, 2)->default(0); // Biaya Tol/Parkir
            $table->decimal('other_cost', 15, 2)->default(0); // Biaya lain
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 14. Farm Expenses (Pencatatan Pengeluaran Kas RPA)
        Schema::create('farm_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('expense_date');
            $table->enum('category', [
                'operasional_rpa', // Es balok, plastik, gas scalder, kaporit, upah kuli bongkar
                'administrasi',    // ATK, pulsa, konsumsi kantor
                'armada_umum',     // Servis/pajak kendaraan (di luar BBM/Tol harian)
                'lain'
            ])->default('operasional_rpa');
            $table->string('subcategory')->nullable(); // e.g. "Es Balok", "Plastik Packing", "Gas Scalder", "Kuli Bongkar"
            $table->string('description');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('payment_method')->default('tunai');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 15. Farm Payrolls (Penggajian Fleksibel)
        Schema::create('farm_payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_employee_id')->nullable()->constrained('farm_employees')->nullOnDelete();
            $table->string('employee_name');
            $table->string('role')->nullable();
            $table->enum('salary_type', ['harian', 'borongan', 'bulanan'])->default('borongan');
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('work_days')->default(0);
            $table->decimal('total_kg_produced', 10, 2)->default(0); // Untuk borongan per kg
            $table->integer('total_ekor_produced')->default(0); // Untuk borongan per ekor
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('overtime_pay', 15, 2)->default(0);
            $table->decimal('allowances', 15, 2)->default(0);
            $table->decimal('deductions', 15, 2)->default(0); // Kasbon/potongan
            $table->decimal('net_salary', 15, 2)->default(0);
            $table->enum('status', ['pending', 'dibayar'])->default('pending');
            $table->date('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 16. Farm Transactions (Kas Buku Masuk & Keluar RPA)
        Schema::create('farm_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['pemasukan', 'pengeluaran']);
            $table->string('category')->nullable();
            $table->string('description');
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('transaction_date');
            $table->string('reference_type')->nullable(); // Model name (e.g. FarmInvoicePayment, FarmExpense, etc.)
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farm_transactions');
        Schema::dropIfExists('farm_payrolls');
        Schema::dropIfExists('farm_expenses');
        Schema::dropIfExists('farm_transportations');
        Schema::dropIfExists('farm_invoice_payments');
        Schema::dropIfExists('farm_invoice_items');
        Schema::dropIfExists('farm_invoices');
        Schema::dropIfExists('farm_production_items');
        Schema::dropIfExists('farm_production_batches');
        Schema::dropIfExists('farm_vehicles');
        Schema::dropIfExists('farm_employees');
        Schema::dropIfExists('farm_customer_prices');
        Schema::dropIfExists('farm_products');
        Schema::dropIfExists('farm_suppliers');
        Schema::dropIfExists('farm_customers');
        Schema::dropIfExists('farm_senders');
    }
};
