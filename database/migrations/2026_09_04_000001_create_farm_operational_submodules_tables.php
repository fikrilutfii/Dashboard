<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Batch / Siklus Populasi Kandang
        Schema::create('farm_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_coop_id')->constrained('farm_coops')->cascadeOnDelete();
            $table->string('batch_code')->unique(); // e.g. BATCH-202609-001
            $table->enum('type', ['broiler', 'layer'])->default('broiler');
            $table->date('entry_date');
            $table->integer('initial_population')->default(0);
            $table->integer('current_population')->default(0);
            $table->date('target_harvest_date')->nullable();
            $table->enum('status', ['aktif', 'panen_selesai', 'non_aktif'])->default('aktif');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Log Pemberian Pakan Harian
        Schema::create('farm_feed_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_batch_id')->constrained('farm_batches')->cascadeOnDelete();
            $table->foreignId('farm_coop_id')->constrained('farm_coops')->cascadeOnDelete();
            $table->date('log_date');
            $table->string('feed_type'); // e.g. Starter, Grower, Finisher, Layer Feed
            $table->decimal('quantity_kg', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3a. Jadwal Vaksin / Obat
        Schema::create('farm_vaccine_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_batch_id')->constrained('farm_batches')->cascadeOnDelete();
            $table->foreignId('farm_coop_id')->constrained('farm_coops')->cascadeOnDelete();
            $table->string('vaccine_name');
            $table->date('scheduled_date');
            $table->integer('recurring_days')->nullable(); // berkala setiap X hari (opsional)
            $table->enum('status', ['pending', 'selesai', 'terlewat'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3b. Log Kesehatan & Mortalitas Harian
        Schema::create('farm_health_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_batch_id')->constrained('farm_batches')->cascadeOnDelete();
            $table->foreignId('farm_coop_id')->constrained('farm_coops')->cascadeOnDelete();
            $table->date('log_date');
            $table->integer('mortality_count')->default(0);
            $table->integer('cull_count')->default(0);
            $table->string('cause')->nullable();
            $table->text('treatment_notes')->nullable();
            $table->timestamps();
        });

        // 4. Log Produksi (Broiler Weight Sampling & Layer Egg Production)
        Schema::create('farm_production_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_batch_id')->constrained('farm_batches')->cascadeOnDelete();
            $table->foreignId('farm_coop_id')->constrained('farm_coops')->cascadeOnDelete();
            $table->date('log_date');
            // Broiler
            $table->decimal('avg_weight_kg', 8, 3)->nullable();
            // Layer
            $table->integer('egg_count_a')->default(0);
            $table->integer('egg_count_b')->default(0);
            $table->integer('egg_count_c')->default(0);
            $table->integer('total_egg_count')->default(0);
            $table->decimal('total_egg_weight_kg', 10, 2)->default(0);
            $table->decimal('egg_production_rate', 5, 2)->default(0); // Egg Rate %
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 5. Log Panen Broiler / Afkir Layer (Murni Operasional)
        Schema::create('farm_harvest_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_batch_id')->constrained('farm_batches')->cascadeOnDelete();
            $table->foreignId('farm_coop_id')->constrained('farm_coops')->cascadeOnDelete();
            $table->date('harvest_date');
            $table->enum('type', ['panen_broiler', 'afkir_layer'])->default('panen_broiler');
            $table->integer('chicken_count')->default(0);
            $table->decimal('total_weight_kg', 10, 2)->default(0);
            $table->decimal('avg_weight_kg', 8, 3)->default(0);
            $table->decimal('reference_price_per_kg', 15, 2)->default(0); // Harga acuan opsional
            $table->enum('status_penjualan', ['tersedia', 'terjual_sebagian', 'terjual_lunas'])->default('tersedia');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Pivot Panen ke Faktur Penjualan (Opsional saat ada transaksi)
        Schema::create('farm_harvest_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_harvest_log_id')->constrained('farm_harvest_logs')->cascadeOnDelete();
            $table->foreignId('farm_invoice_id')->constrained('farm_invoices')->cascadeOnDelete();
            $table->decimal('sold_weight_kg', 10, 2)->default(0);
            $table->integer('sold_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farm_harvest_sales');
        Schema::dropIfExists('farm_harvest_logs');
        Schema::dropIfExists('farm_production_logs');
        Schema::dropIfExists('farm_health_logs');
        Schema::dropIfExists('farm_vaccine_schedules');
        Schema::dropIfExists('farm_feed_logs');
        Schema::dropIfExists('farm_batches');
    }
};
