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
        if (Schema::hasTable('activity_logs') && !Schema::hasColumn('activity_logs', 'subject_type')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->string('subject_type')->nullable()->after('description');
                $table->unsignedBigInteger('subject_id')->nullable()->after('subject_type');
                $table->json('properties')->nullable()->after('subject_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('activity_logs') && Schema::hasColumn('activity_logs', 'subject_type')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->dropColumn(['subject_type', 'subject_id', 'properties']);
            });
        }
    }
};
