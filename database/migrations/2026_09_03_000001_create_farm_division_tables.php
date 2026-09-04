<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Farm Customers
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

        // Farm Suppliers
        Schema::create('farm_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['doc', 'pakan', 'obat', 'alat', 'lain'])->default('lain');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Farm Coops (Kandang)
        Schema::create('farm_coops', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Kandang A", "Kandang 1"
            $table->integer('capacity')->default(0); // kapasitas ekor
            $table->string('location')->nullable();
            $table->string('status')->default('aktif');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Farm Invoices
        Schema::create('farm_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->foreignId('farm_customer_id')->constrained('farm_customers');
            $table->foreignId('farm_coop_id')->nullable()->constrained('farm_coops')->nullOnDelete();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->enum('status', ['belum_lunas', 'sebagian', 'lunas'])->default('belum_lunas');
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Farm Invoice Items
        Schema::create('farm_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_invoice_id')->constrained('farm_invoices')->cascadeOnDelete();
            $table->string('description');
            $table->decimal('qty', 10, 2)->default(1);
            $table->string('unit')->default('kg');
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->timestamps();
        });

        // Farm Operational Logs (Catatan Harian)
        Schema::create('farm_operational_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_coop_id')->constrained('farm_coops');
            $table->date('log_date');
            $table->integer('population')->default(0); // populasi saat itu
            $table->integer('mortality')->default(0);  // ayam mati hari ini
            $table->decimal('feed_kg', 10, 2)->default(0); // pakan (kg)
            $table->decimal('avg_weight', 8, 3)->nullable(); // berat rata-rata (kg)
            $table->integer('age_days')->nullable(); // umur (hari)
            $table->text('vaccine_notes')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Farm Transportations
        Schema::create('farm_transportations', function (Blueprint $table) {
            $table->id();
            $table->date('transport_date');
            $table->enum('type', ['masuk', 'keluar'])->default('keluar'); // masuk = supply in, keluar = delivery
            $table->string('description');
            $table->string('destination')->nullable();
            $table->string('driver')->nullable();
            $table->string('vehicle_plate')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('status')->default('selesai');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Farm Expenses (Pengeluaran)
        Schema::create('farm_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('expense_date');
            $table->enum('category', ['doc', 'pakan', 'obat', 'listrik', 'air', 'alat', 'transportasi', 'gaji', 'lain'])->default('lain');
            $table->string('description');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('payment_method')->default('tunai');
            $table->foreignId('farm_supplier_id')->nullable()->constrained('farm_suppliers')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Farm Payrolls (Penggajian)
        Schema::create('farm_payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->string('role')->nullable(); // jabatan/pekerjaan
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('allowances', 15, 2)->default(0); // tunjangan
            $table->decimal('deductions', 15, 2)->default(0); // potongan
            $table->decimal('net_salary', 15, 2)->default(0);
            $table->enum('status', ['pending', 'dibayar'])->default('pending');
            $table->date('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Farm Transactions (Kas Peternakan)
        Schema::create('farm_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['pemasukan', 'pengeluaran']);
            $table->string('category')->nullable();
            $table->string('description');
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('transaction_date');
            $table->string('reference_type')->nullable(); // model name
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
        Schema::dropIfExists('farm_operational_logs');
        Schema::dropIfExists('farm_invoice_items');
        Schema::dropIfExists('farm_invoices');
        Schema::dropIfExists('farm_coops');
        Schema::dropIfExists('farm_suppliers');
        Schema::dropIfExists('farm_customers');
    }
};
