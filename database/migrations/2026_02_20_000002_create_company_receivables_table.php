<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_receivables', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama debitur
            $table->text('description')->nullable();
            $table->decimal('total_amount', 15, 2);
            $table->decimal('remaining_amount', 15, 2);
            $table->date('due_date')->nullable();
            $table->enum('type', ['cash', 'installment'])->default('cash'); // cash / cicilan
            $table->enum('status', ['belum_lunas', 'sebagian', 'lunas'])->default('belum_lunas');
            $table->string('division')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_receivables');
    }
};
