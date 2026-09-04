<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('username', 'admin_3')
            ->update(['role' => 'admin3']);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('username', 'admin_3')
            ->where('role', 'admin3')
            ->update(['role' => 'limited_invoice']);
    }
};
