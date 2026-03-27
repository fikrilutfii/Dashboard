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
        // 1. Kasbon Statuses
        DB::table('kasbons')->where('status', 'open')->update(['status' => 'aktif']);
        DB::table('kasbons')->where('status', 'paid')->update(['status' => 'lunas']);
        DB::table('kasbons')->where('status', 'deducted')->update(['status' => 'lunas']);

        // 2. Payroll Statuses
        DB::table('payrolls')->where('status', 'unpaid')->update(['status' => 'belum_lunas']);
        DB::table('payrolls')->where('status', 'paid')->update(['status' => 'lunas']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Kasbon Statuses
        DB::table('kasbons')->where('status', 'aktif')->update(['status' => 'open']);
        DB::table('kasbons')->where('status', 'lunas')->update(['status' => 'paid']);

        // 2. Payroll Statuses
        DB::table('payrolls')->where('status', 'belum_lunas')->update(['status' => 'unpaid']);
        DB::table('payrolls')->where('status', 'lunas')->update(['status' => 'paid']);
    }
};
