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
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('overtime_rate', 15, 2)->default(0)->after('salary_base');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('overtime_hours', 8, 2)->default(0)->after('working_days_count');
            $table->decimal('overtime_rate', 15, 2)->default(0)->after('overtime_hours');
            $table->decimal('overtime_pay', 15, 2)->default(0)->after('overtime_rate');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('overtime_rate');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['overtime_hours', 'overtime_rate', 'overtime_pay']);
        });
    }
};
