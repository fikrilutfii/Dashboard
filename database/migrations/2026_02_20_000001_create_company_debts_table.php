<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_debts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama kreditur
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('due_date')->nullable();
            $table->enum('type', ['cash', 'credit'])->default('cash');
            $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->string('division')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_debts');
    }
};
