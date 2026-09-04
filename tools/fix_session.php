<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

try {
    if (!Schema::hasTable('sessions')) {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
        echo "Tabel sessions berhasil dibuat!<br>";
    } else {
        echo "Tabel sessions sudah ada.<br>";
    }
    
    // Also clear config cache just in case
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    
    echo "<br><b>Perbaikan sesi (419 Error) selesai! Silakan hapus cookies browser Anda, lalu coba login lagi.</b>";
} catch (\Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage();
}
