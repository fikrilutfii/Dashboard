<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            // PostgreSQL: alter column using raw SQL
            DB::statement("ALTER TABLE payrolls DROP CONSTRAINT IF EXISTS payrolls_status_check");
            DB::statement("ALTER TABLE payrolls ALTER COLUMN status TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE payrolls ALTER COLUMN status SET DEFAULT 'belum_lunas'");
            DB::statement("ALTER TABLE payrolls ADD CONSTRAINT payrolls_status_check CHECK (status IN ('belum_lunas', 'lunas', 'paid', 'unpaid'))");
            DB::statement("UPDATE payrolls SET status = 'belum_lunas' WHERE status NOT IN ('belum_lunas', 'lunas', 'paid', 'unpaid')");
        } else {
            // MySQL: re-create enum column
            Schema::table('payrolls', function (Blueprint $table) {
                $table->enum('status', ['belum_lunas', 'lunas', 'paid', 'unpaid'])->default('belum_lunas')->change();
            });
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE payrolls DROP CONSTRAINT IF EXISTS payrolls_status_check");
            DB::statement("ALTER TABLE payrolls ALTER COLUMN status TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE payrolls ALTER COLUMN status SET DEFAULT 'paid'");
            DB::statement("ALTER TABLE payrolls ADD CONSTRAINT payrolls_status_check CHECK (status IN ('paid', 'unpaid'))");
        } else {
            Schema::table('payrolls', function (Blueprint $table) {
                $table->enum('status', ['paid', 'unpaid'])->default('paid')->change();
            });
        }
    }
};
