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
        // Update Invoices status column to allow new values (enum change)
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('status')->default('belum_lunas')->change();
        });

        // Update existing data for invoices
        DB::table('invoices')->where('status', 'unpaid')->update(['status' => 'belum_lunas']);
        DB::table('invoices')->where('status', 'paid')->update(['status' => 'lunas']);

        // Update existing data for purchases
        DB::table('purchases')->where('status', 'unpaid')->update(['status' => 'belum_lunas']);
        DB::table('purchases')->where('status', 'paid')->update(['status' => 'lunas']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data
        DB::table('invoices')->where('status', 'belum_lunas')->update(['status' => 'unpaid']);
        DB::table('invoices')->where('status', 'lunas')->update(['status' => 'paid']);

        DB::table('purchases')->where('status', 'belum_lunas')->update(['status' => 'unpaid']);
        DB::table('purchases')->where('status', 'lunas')->update(['status' => 'paid']);

        Schema::table('invoices', function (Blueprint $table) {
            $table->string('status')->default('unpaid')->change();
        });
    }
};
