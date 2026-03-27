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
        Schema::table('customers', function (Blueprint $table) {
            $table->enum('division', ['percetakan', 'konfeksi'])->default('percetakan')->after('email');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->enum('division', ['percetakan', 'konfeksi'])->default('percetakan')->after('price');
        });

        // Check if suppliers table exists before adding column, as it might have been created differently
        if (Schema::hasTable('suppliers')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->enum('division', ['percetakan', 'konfeksi'])->default('percetakan')->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('division');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('division');
        });

        if (Schema::hasTable('suppliers')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->dropColumn('division');
            });
        }
    }
};
