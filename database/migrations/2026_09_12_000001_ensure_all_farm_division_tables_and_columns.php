<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Farm Senders (Identitas Pengirim)
        if (!Schema::hasTable('farm_senders')) {
            Schema::create('farm_senders', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('bank_account_number')->nullable();
                $table->string('bank_account_name')->nullable();
                $table->text('invoice_footer_notes')->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }

        // 2. Farm Customers
        if (!Schema::hasTable('farm_customers')) {
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
        }

        // 3. Farm Suppliers
        if (!Schema::hasTable('farm_suppliers')) {
            Schema::create('farm_suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('farm_location')->nullable();
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->string('contact_person')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('farm_suppliers', function (Blueprint $table) {
                if (!Schema::hasColumn('farm_suppliers', 'farm_location')) {
                    $table->string('farm_location')->nullable()->after('name');
                }
            });
        }

        // 4. Farm Products
        if (!Schema::hasTable('farm_products')) {
            Schema::create('farm_products', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->enum('category', ['karkas_utuh', 'parting', 'sampingan', 'lain'])->default('karkas_utuh');
                $table->string('unit')->default('kg');
                $table->decimal('standard_price', 15, 2)->default(0);
                $table->decimal('current_stock_kg', 12, 2)->default(0);
                $table->integer('current_stock_ekor')->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 5. Farm Customer Prices
        if (!Schema::hasTable('farm_customer_prices')) {
            Schema::create('farm_customer_prices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('farm_customer_id')->constrained('farm_customers')->cascadeOnDelete();
                $table->foreignId('farm_product_id')->constrained('farm_products')->cascadeOnDelete();
                $table->decimal('custom_price', 15, 2)->default(0);
                $table->timestamps();
                $table->unique(['farm_customer_id', 'farm_product_id']);
            });
        }

        // 6. Farm Employees
        if (!Schema::hasTable('farm_employees')) {
            Schema::create('farm_employees', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('role')->nullable();
                $table->enum('salary_type', ['harian', 'borongan', 'bulanan'])->default('borongan');
                $table->decimal('daily_rate', 15, 2)->default(0);
                $table->decimal('piece_rate_per_kg', 15, 2)->default(0);
                $table->decimal('piece_rate_per_ekor', 15, 2)->default(0);
                $table->decimal('monthly_salary', 15, 2)->default(0);
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 7. Farm Vehicles
        if (!Schema::hasTable('farm_vehicles')) {
            Schema::create('farm_vehicles', function (Blueprint $table) {
                $table->id();
                $table->string('plate_number');
                $table->string('vehicle_type')->nullable();
                $table->string('default_driver')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 8. Farm Production Batches
        if (!Schema::hasTable('farm_production_batches')) {
            Schema::create('farm_production_batches', function (Blueprint $table) {
                $table->id();
                $table->string('batch_number')->unique();
                $table->date('production_date');
                $table->foreignId('farm_supplier_id')->nullable()->constrained('farm_suppliers')->nullOnDelete();
                $table->integer('live_birds_count')->default(0);
                $table->decimal('live_birds_weight_kg', 10, 2)->default(0);
                $table->decimal('buy_price_per_kg', 15, 2)->default(0);
                $table->decimal('total_buy_price', 15, 2)->default(0);
                $table->enum('supplier_payment_status', ['belum_lunas', 'lunas'])->default('belum_lunas');
                $table->integer('doa_count')->default(0);
                $table->decimal('doa_weight_kg', 10, 2)->default(0);
                $table->decimal('total_yield_weight_kg', 10, 2)->default(0);
                $table->decimal('shrinkage_weight_kg', 10, 2)->default(0);
                $table->decimal('shrinkage_percentage', 5, 2)->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 9. Farm Production Items
        if (!Schema::hasTable('farm_production_items')) {
            Schema::create('farm_production_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('farm_production_batch_id')->constrained('farm_production_batches')->cascadeOnDelete();
                $table->foreignId('farm_product_id')->constrained('farm_products')->cascadeOnDelete();
                $table->integer('qty_ekor')->default(0);
                $table->decimal('weight_kg', 10, 2)->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 10. Farm Invoices (Update/Add remaining_amount & farm_sender_id)
        if (!Schema::hasTable('farm_invoices')) {
            Schema::create('farm_invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number')->unique();
                $table->foreignId('farm_sender_id')->nullable()->constrained('farm_senders')->nullOnDelete();
                $table->foreignId('farm_customer_id')->constrained('farm_customers');
                $table->date('invoice_date');
                $table->date('due_date')->nullable();
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->decimal('remaining_amount', 15, 2)->default(0);
                $table->enum('status', ['belum_lunas', 'sebagian', 'lunas'])->default('belum_lunas');
                $table->string('payment_method')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('farm_invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('farm_invoices', 'farm_sender_id')) {
                    $table->foreignId('farm_sender_id')->nullable()->after('invoice_number');
                }
                if (!Schema::hasColumn('farm_invoices', 'remaining_amount')) {
                    $table->decimal('remaining_amount', 15, 2)->default(0)->after('paid_amount');
                }
            });
        }

        // 11. Farm Invoice Items
        if (!Schema::hasTable('farm_invoice_items')) {
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
        } else {
            Schema::table('farm_invoice_items', function (Blueprint $table) {
                if (!Schema::hasColumn('farm_invoice_items', 'farm_product_id')) {
                    $table->foreignId('farm_product_id')->nullable()->after('farm_invoice_id');
                }
                if (!Schema::hasColumn('farm_invoice_items', 'product_code')) {
                    $table->string('product_code')->nullable()->after('farm_product_id');
                }
                if (!Schema::hasColumn('farm_invoice_items', 'weight_kg')) {
                    $table->decimal('weight_kg', 10, 2)->default(0)->after('item_name');
                }
                if (!Schema::hasColumn('farm_invoice_items', 'qty_ekor')) {
                    $table->integer('qty_ekor')->default(0)->after('weight_kg');
                }
            });
        }

        // 12. Farm Invoice Payments
        if (!Schema::hasTable('farm_invoice_payments')) {
            Schema::create('farm_invoice_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('farm_invoice_id')->constrained('farm_invoices')->cascadeOnDelete();
                $table->date('payment_date');
                $table->decimal('amount', 15, 2)->default(0);
                $table->string('payment_method')->default('transfer');
                $table->string('reference_number')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 13. Farm Transportations
        if (!Schema::hasTable('farm_transportations')) {
            Schema::create('farm_transportations', function (Blueprint $table) {
                $table->id();
                $table->date('transport_date');
                $table->foreignId('farm_invoice_id')->nullable()->constrained('farm_invoices')->nullOnDelete();
                $table->foreignId('farm_vehicle_id')->nullable()->constrained('farm_vehicles')->nullOnDelete();
                $table->string('driver_name')->nullable();
                $table->string('destination')->nullable();
                $table->time('departure_time')->nullable();
                $table->time('arrival_time')->nullable();
                $table->enum('delivery_status', ['sedang_dikirim', 'baik', 'komplain_sebagian', 'komplain_penuh'])->default('baik');
                $table->decimal('bbm_cost', 15, 2)->default(0);
                $table->decimal('toll_cost', 15, 2)->default(0);
                $table->decimal('other_cost', 15, 2)->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 14. Farm Expenses
        if (!Schema::hasTable('farm_expenses')) {
            Schema::create('farm_expenses', function (Blueprint $table) {
                $table->id();
                $table->date('expense_date');
                $table->enum('category', ['operasional_rpa', 'administrasi', 'armada_umum', 'lain'])->default('operasional_rpa');
                $table->string('subcategory')->nullable();
                $table->string('description');
                $table->decimal('amount', 15, 2)->default(0);
                $table->string('payment_method')->default('tunai');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 15. Farm Payrolls
        if (!Schema::hasTable('farm_payrolls')) {
            Schema::create('farm_payrolls', function (Blueprint $table) {
                $table->id();
                $table->foreignId('farm_employee_id')->nullable()->constrained('farm_employees')->nullOnDelete();
                $table->string('employee_name');
                $table->string('role')->nullable();
                $table->enum('salary_type', ['harian', 'borongan', 'bulanan'])->default('borongan');
                $table->date('period_start');
                $table->date('period_end');
                $table->integer('work_days')->default(0);
                $table->decimal('total_kg_produced', 10, 2)->default(0);
                $table->integer('total_ekor_produced')->default(0);
                $table->decimal('basic_salary', 15, 2)->default(0);
                $table->decimal('overtime_pay', 15, 2)->default(0);
                $table->decimal('allowances', 15, 2)->default(0);
                $table->decimal('deductions', 15, 2)->default(0);
                $table->decimal('net_salary', 15, 2)->default(0);
                $table->enum('status', ['pending', 'dibayar'])->default('pending');
                $table->date('paid_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 16. Farm Transactions
        if (!Schema::hasTable('farm_transactions')) {
            Schema::create('farm_transactions', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['pemasukan', 'pengeluaran']);
                $table->string('category')->nullable();
                $table->string('description');
                $table->decimal('amount', 15, 2)->default(0);
                $table->date('transaction_date');
                $table->string('reference_type')->nullable();
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Safe down
    }
};
