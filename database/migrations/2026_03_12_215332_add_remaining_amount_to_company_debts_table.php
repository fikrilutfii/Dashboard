<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('company_debts', function (Blueprint $table) {
            $table->decimal('remaining_amount', 15, 2)->after('amount')->nullable();
        });

        // Initialize remaining_amount with amount for existing records
        DB::table('company_debts')->update([
            'remaining_amount' => DB::raw('amount')
        ]);
    }

    public function down(): void
    {
        Schema::table('company_debts', function (Blueprint $table) {
            $table->dropColumn('remaining_amount');
        });
    }
};
