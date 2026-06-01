<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL for PostgreSQL-compatible enum-like constraint change.
        // Laravel's enum()->change() generates invalid CHECK syntax on Postgres.
        DB::statement("ALTER TABLE payrolls DROP CONSTRAINT IF EXISTS payrolls_status_check");
        DB::statement("ALTER TABLE payrolls ALTER COLUMN status TYPE VARCHAR(255)");
        DB::statement("ALTER TABLE payrolls ALTER COLUMN status SET DEFAULT 'belum_lunas'");
        DB::statement("ALTER TABLE payrolls ADD CONSTRAINT payrolls_status_check CHECK (status IN ('belum_lunas', 'lunas', 'paid', 'unpaid'))");

        // Migrate any existing rows that used the old default so they satisfy the new constraint
        DB::statement("UPDATE payrolls SET status = 'belum_lunas' WHERE status NOT IN ('belum_lunas', 'lunas', 'paid', 'unpaid')");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE payrolls DROP CONSTRAINT IF EXISTS payrolls_status_check");
        DB::statement("ALTER TABLE payrolls ALTER COLUMN status TYPE VARCHAR(255)");
        DB::statement("ALTER TABLE payrolls ALTER COLUMN status SET DEFAULT 'paid'");
        DB::statement("ALTER TABLE payrolls ADD CONSTRAINT payrolls_status_check CHECK (status IN ('paid', 'unpaid'))");

        // Revert rows that no longer satisfy the original constraint
        DB::statement("UPDATE payrolls SET status = 'paid' WHERE status NOT IN ('paid', 'unpaid')");
    }
};
