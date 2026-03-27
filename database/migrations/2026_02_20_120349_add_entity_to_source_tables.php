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
        $tables = ['invoices', 'purchases', 'company_debts', 'company_receivables'];
        
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('entity')->nullable()->after('division');
            });

            DB::table($table)->whereNull('entity')->update([
                'entity' => DB::raw('division')
            ]);
        }
    }

    public function down(): void
    {
        $tables = ['invoices', 'purchases', 'company_debts', 'company_receivables'];
        
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('entity');
            });
        }
    }
};
