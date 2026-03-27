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
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('daily_salary', 15, 2)->after('employee_id')->default(0)->comment('Gaji Perhari');
            $table->integer('working_days')->after('daily_salary')->default(0)->comment('Jumlah Hari Kerja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['daily_salary', 'working_days']);
        });
    }
};
