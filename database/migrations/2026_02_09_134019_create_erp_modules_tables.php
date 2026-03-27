<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        // 2. Employees (Staff & Penjahit)
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role'); // 'staff', 'penjahit'
            $table->string('division')->default('konfeksi'); // 'percetakan', 'konfeksi'
            $table->decimal('salary_base', 15, 2)->default(0);
            $table->timestamps();
        });

        // 3. Purchases (Pembelian)
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_number')->unique();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date');
            $table->date('due_date')->nullable(); // For credit
            $table->string('status')->default('unpaid'); // unpaid, paid
            $table->string('division')->default('percetakan'); // percetakan, konfeksi
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // 4. Transactions (Cash Flow)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'debit' (pengeluaran), 'credit' (pemasukan)
            $table->decimal('amount', 15, 2);
            $table->string('category'); // invoice_payment, purchase_payment, salary, operational, etc.
            $table->string('reference_type')->nullable(); // Polymorphic
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->date('date');
            $table->string('division')->nullable(); // To track cash flow per division
            $table->timestamps();
        });

        // 5. Konfeksi Specific Modules (Kasbon & Payroll)
        Schema::create('kasbons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->string('status')->default('open'); // open, paid, deducted
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('bonus', 15, 2)->default(0);
            $table->decimal('kasbon_deduction', 15, 2)->default(0);
            $table->decimal('total_salary', 15, 2);
            $table->string('status')->default('draft'); // draft, paid
            $table->timestamps();
        });

        // 6. Modifications to Existing Tables
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('invoices', 'division')) {
                    $table->string('division')->default('percetakan')->after('status');
                }
                if (!Schema::hasColumn('invoices', 'surat_jalan_number')) {
                    $table->string('surat_jalan_number')->nullable()->after('invoice_number');
                }
                if (!Schema::hasColumn('invoices', 'paid_amount')) {
                    $table->decimal('paid_amount', 15, 2)->default(0)->after('total_amount');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('kasbons');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('suppliers');

        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn(['division', 'surat_jalan_number', 'paid_amount']);
            });
        }
    }
};
