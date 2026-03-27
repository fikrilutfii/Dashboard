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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('division');
            $table->string('entity')->nullable(); // Konveksi/Percetakan entity
            $table->enum('type', ['manual', 'bahan'])->default('manual');
            $table->string('category')->nullable(); // Transport, Listrik, etc.
            $table->text('description')->nullable();
            
            // For 'bahan' type (optional details, if we want to store them)
            $table->string('supplier_name')->nullable();
            $table->string('item_name')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('unit_price', 15, 2)->nullable();
            
            $table->decimal('amount', 15, 2); // Total expense amount
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
