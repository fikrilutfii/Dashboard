<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE invoice_items MODIFY quantity DECIMAL(15,3) NOT NULL');
        DB::statement('ALTER TABLE purchase_items MODIFY quantity DECIMAL(15,3) NOT NULL');
        DB::statement('ALTER TABLE products MODIFY stock DECIMAL(15,3) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE stock_logs MODIFY quantity DECIMAL(15,3) NOT NULL');
        DB::statement('ALTER TABLE stock_logs MODIFY new_stock DECIMAL(15,3) NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE invoice_items MODIFY quantity INT NOT NULL');
        DB::statement('ALTER TABLE purchase_items MODIFY quantity INT NOT NULL');
        DB::statement('ALTER TABLE products MODIFY stock INT NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE stock_logs MODIFY quantity INT NOT NULL');
        DB::statement('ALTER TABLE stock_logs MODIFY new_stock INT NOT NULL');
    }
};
