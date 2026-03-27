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
        Schema::create('company_loans', function (Blueprint $table) {
            $table->id();
            $table->string('creditor_name')->nullable()->comment('Pemberi Pinjaman');
            $table->decimal('amount', 15, 2);
            $table->decimal('remaining_amount', 15, 2);
            $table->string('type'); // cash, credit
            $table->text('description')->nullable();
            $table->string('status')->default('open'); // open, paid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_loans');
    }
};
