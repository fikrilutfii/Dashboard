<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['masuk', 'tidak_masuk'])->default('masuk');
            $table->string('keterangan')->nullable();
            $table->decimal('daily_rate_snapshot', 15, 2)->default(0); // rates at time of entry
            $table->string('division')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'date']); // one entry per employee per day
        });

        // Add soft deletes and daily_rate_snapshot to payrolls
        Schema::table('payrolls', function (Blueprint $table) {
            $table->softDeletes();
            $table->decimal('daily_rate', 15, 2)->default(0)->after('basic_salary');
            $table->integer('working_days_count')->default(0)->after('daily_rate');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['daily_rate', 'working_days_count']);
        });
        Schema::dropIfExists('attendances');
    }
};
