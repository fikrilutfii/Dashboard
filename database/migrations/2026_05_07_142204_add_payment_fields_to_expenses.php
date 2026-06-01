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
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('payment_method')->default('cash')->after('amount'); // cash, credit
            $table->string('payment_status')->default('paid')->after('payment_method'); // paid, unpaid
            $table->integer('tenure')->nullable()->after('payment_status'); // duration in months
            $table->date('due_date')->nullable()->after('tenure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status', 'tenure', 'due_date']);
        });
    }
};
