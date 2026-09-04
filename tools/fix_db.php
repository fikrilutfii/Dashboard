<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

try {
    // Check if the unique constraint exists by trying to drop it
    // MySQL way to check index
    $indexes = DB::select("SHOW INDEX FROM invoices WHERE Key_name = 'invoices_invoice_number_unique'");
    if (count($indexes) > 0) {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropUnique('invoices_invoice_number_unique');
        });
        echo "Berhasil menghapus aturan unik pada nomor invoice.<br>";
    } else {
        echo "Aturan unik sudah tidak ada.<br>";
    }

    // Modify column to be nullable
    DB::statement('ALTER TABLE invoices MODIFY invoice_number VARCHAR(255) NULL');
    echo "Berhasil mengubah kolom invoice_number menjadi opsional (nullable).<br>";

    // Drop unique index for purchase_number on purchases table if exists
    $purchaseIndexes = DB::select("SHOW INDEX FROM purchases WHERE Key_name = 'purchases_purchase_number_unique'");
    if (count($purchaseIndexes) > 0) {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropUnique('purchases_purchase_number_unique');
        });
        echo "Berhasil menghapus aturan unik pada nomor pembelian.<br>";
    }
    DB::statement('ALTER TABLE purchases MODIFY purchase_number VARCHAR(255) NULL');
    echo "Berhasil mengubah kolom purchase_number menjadi opsional (nullable).<br>";

    // Update existing empty strings to NULL
    DB::table('invoices')->where('invoice_number', '')->update(['invoice_number' => null]);
    DB::table('purchases')->where('purchase_number', '')->update(['purchase_number' => null]);
    echo "Berhasil merapikan data lama.<br>";

    echo "<br><b>Selesai! Anda sudah bisa menutup halaman ini dan kembali membuat faktur.</b>";
} catch (\Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage();
}
