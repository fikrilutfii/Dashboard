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
        Schema::table('kasbons', function (Blueprint $table) {
            $table->string('type')->default('staff_kasbon')->after('employee_id'); // staff_kasbon, personal_credit, personal_loan
            $table->decimal('remaining_amount', 15, 2)->after('amount')->default(0);
            $table->decimal('installment_amount', 15, 2)->after('remaining_amount')->default(0); // Recommended installment
        });

        // Initialize remaining amount for existing records
        DB::table('kasbons')->where('status', 'open')->update([
            'remaining_amount' => DB::raw('amount')
        ]);
        DB::table('kasbons')->where('status', '!=', 'open')->update([
            'remaining_amount' => 0
        ]);

        Schema::create('kasbon_repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasbon_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->string('method'); // payroll_deduction, cash, transfer
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasbon_repayments');
        Schema::table('kasbons', function (Blueprint $table) {
            $table->dropColumn(['type', 'remaining_amount', 'installment_amount']);
        });
    }
};
