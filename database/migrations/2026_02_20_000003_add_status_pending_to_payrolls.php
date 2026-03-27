<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Change payroll status enum to include 'belum_lunas'
        // We need to alter the column - for MySQL we re-create the enum
        Schema::table('payrolls', function (Blueprint $table) {
            $table->enum('status', ['belum_lunas', 'lunas', 'paid', 'unpaid'])->default('belum_lunas')->change();
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->enum('status', ['paid', 'unpaid'])->default('paid')->change();
        });
    }
};
