<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


use App\Http\Controllers\CustomerBillingReportController;
use App\Http\Controllers\UserAccessController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\KasbonController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\CompanyDebtController;
use App\Http\Controllers\CompanyReceivableController;
use App\Http\Controllers\ExpenseController;

// Farm Division Controllers
use App\Http\Controllers\Farm\FarmDashboardController;
use App\Http\Controllers\Farm\FarmInvoiceController;
use App\Http\Controllers\Farm\FarmTransportationController;
use App\Http\Controllers\Farm\FarmOperationalController;
use App\Http\Controllers\Farm\FarmExpenseController;
use App\Http\Controllers\Farm\FarmPayrollController;
use App\Http\Controllers\Farm\FarmMasterDataController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/set-division', [App\Http\Controllers\DashboardController::class, 'setDivision'])->name('division.set');
    Route::post('/switch-division', [App\Http\Controllers\DashboardController::class, 'switchDivision'])->name('division.switch');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/user-access', [UserAccessController::class, 'index'])->name('user-access.index');
    Route::put('/user-access/{user}', [UserAccessController::class, 'update'])->name('user-access.update');
    
    // Customer Billing Reports
    Route::get('/reports/billing', [CustomerBillingReportController::class, 'index'])->name('reports.billing');
    Route::get('/reports/billing/{customer}', [CustomerBillingReportController::class, 'show'])->name('reports.billing.show');
    Route::get('/reports/billing/{customer}/print', [CustomerBillingReportController::class, 'print'])->name('reports.billing.print');
    Route::get('/reports/billing/{customer}/export-pdf', [CustomerBillingReportController::class, 'exportPdf'])->name('reports.billing.pdf');
    Route::get('/reports/billing/{customer}/export-excel', [CustomerBillingReportController::class, 'exportExcel'])->name('reports.billing.excel');

    Route::get('/invoices/{invoice}/export', [InvoiceController::class, 'printExcel'])->name('invoices.export');
    Route::resource('invoices', InvoiceController::class);
    
    // Master Data
    Route::resource('customers', CustomerController::class);
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);

    // Stock Report (Dashboard) & Adjustment
    Route::get('/reports/stock', [ProductController::class, 'stockReport'])->name('reports.stock');
    Route::post('/products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');

    // API for Product Lookup
    Route::get('/api/products/{code}', [ProductController::class, 'getByCode'])->name('products.lookup');
    Route::post('/products/import-csv', [ProductController::class, 'importCsv'])->name('products.import-csv');

    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::get('invoices/{invoice}/print-excel', [InvoiceController::class, 'printExcel'])->name('invoices.print-excel');
    Route::get('invoices/{invoice}/print-combined', [InvoiceController::class, 'printCombined'])->name('invoices.print-combined');
    Route::get('invoices/report/print', [InvoiceController::class, 'printReport'])->name('invoices.printReport');
    Route::put('invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.update-status');

    Route::resource('purchases', PurchaseController::class);
    Route::put('purchases/{purchase}/status', [PurchaseController::class, 'updateStatus'])->name('purchases.update-status');
    
    // Expenses
    Route::resource('expenses', ExpenseController::class);

    // Finance
    Route::get('/finance', [App\Http\Controllers\FinanceReportController::class, 'index'])->name('finance.index');
    Route::get('/finance/export/pdf', [App\Http\Controllers\FinanceReportController::class, 'exportPDF'])->name('finance.export.pdf');
    Route::get('/finance/export/excel', [App\Http\Controllers\FinanceReportController::class, 'exportExcel'])->name('finance.export.excel');
    Route::get('/finance/transactions', [FinanceController::class, 'transactions'])->name('finance.transactions');
    Route::post('/finance/transactions', [FinanceController::class, 'storeTransaction'])->name('finance.storeTransaction');
    Route::post('/finance/loan', [FinanceController::class, 'storeLoan'])->name('finance.storeLoan');

    // Hutang & Piutang Perusahaan
    Route::resource('company-debts', CompanyDebtController::class);
    Route::post('/company-debts/{companyDebt}/mark-lunas', [CompanyDebtController::class, 'markLunas'])->name('company-debts.mark-lunas');
    Route::post('/company-debts/{companyDebt}/payment', [CompanyDebtController::class, 'recordPayment'])->name('company-debts.payment');
    
    Route::resource('company-receivables', CompanyReceivableController::class);
    Route::post('/company-receivables/{companyReceivable}/mark-lunas', [CompanyReceivableController::class, 'markLunas'])->name('company-receivables.mark-lunas');
    Route::post('/company-receivables/{companyReceivable}/record-payment', [CompanyReceivableController::class, 'recordPayment'])->name('company-receivables.record-payment');

    // Employees, Absensi & Penggajian (Konfeksi)
    Route::resource('employees', EmployeeController::class);
    Route::post('/kasbons/{kasbon}/repay', [KasbonController::class, 'repay'])->name('kasbons.repay');
    // Kasbons (For Konfeksi/Employees)
    Route::get('/kasbons/print-pdf', [KasbonController::class, 'printPdf'])->name('kasbons.print_pdf');
    Route::resource('kasbons', KasbonController::class)->except(['show']);


    // Penggajian
    Route::get('/payrolls/recap', [PayrollController::class, 'recap'])->name('payrolls.recap');
    Route::post('/payrolls/recap', [PayrollController::class, 'storeRecap'])->name('payrolls.storeRecap');
    Route::get('/payrolls/print', [PayrollController::class, 'print'])->name('payrolls.print');
    Route::get('/payrolls/{payroll}/slip', [PayrollController::class, 'printSlip'])->name('payrolls.slip');
    Route::post('/payrolls/{payroll}/mark-lunas', [PayrollController::class, 'markLunas'])->name('payrolls.mark-lunas');
    Route::resource('payrolls', PayrollController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // API for Payroll Calculation
    Route::get('/api/employees/{employee}/data', [PayrollController::class, 'getEmployeeData'])->name('api.employees.data');

    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // ===================== FARM DIVISION ROUTES =====================
    Route::prefix('farm')->name('farm.')->group(function () {

        // Dashboard Peternakan
        Route::get('/dashboard', [FarmDashboardController::class, 'index'])->name('dashboard');

        // Faktur Penjualan
        Route::resource('invoices', FarmInvoiceController::class);
        Route::post('/invoices/{farmInvoice}/payment', [FarmInvoiceController::class, 'recordPayment'])->name('invoices.payment');
        Route::get('/invoices/{farmInvoice}/print', [FarmInvoiceController::class, 'print'])->name('invoices.print');

        // Laporan Tagihan Klien
        Route::get('/billing', [FarmInvoiceController::class, 'billing'])->name('billing.index');

        // Transportasi
        Route::resource('transportation', FarmTransportationController::class)->parameters(['transportation' => 'farmTransportation']);

        // Operasional
        Route::resource('operational', FarmOperationalController::class)->parameters(['operational' => 'farmOperationalLog']);
        Route::post('/operational/batch', [FarmOperationalController::class, 'storeBatch'])->name('operational.batch.store');
        Route::post('/operational/batch/{farmBatch}/close', [FarmOperationalController::class, 'closeBatch'])->name('operational.batch.close');
        
        Route::post('/operational/feed', [FarmOperationalController::class, 'storeFeed'])->name('operational.feed.store');
        Route::delete('/operational/feed/{farmFeedLog}', [FarmOperationalController::class, 'destroyFeed'])->name('operational.feed.destroy');
        
        Route::post('/operational/health', [FarmOperationalController::class, 'storeHealth'])->name('operational.health.store');
        Route::delete('/operational/health/{farmHealthLog}', [FarmOperationalController::class, 'destroyHealth'])->name('operational.health.destroy');

        Route::post('/operational/vaccine', [FarmOperationalController::class, 'storeVaccine'])->name('operational.vaccine.store');
        Route::post('/operational/vaccine/{farmVaccineSchedule}/complete', [FarmOperationalController::class, 'completeVaccine'])->name('operational.vaccine.complete');
        Route::delete('/operational/vaccine/{farmVaccineSchedule}', [FarmOperationalController::class, 'destroyVaccine'])->name('operational.vaccine.destroy');

        Route::post('/operational/production', [FarmOperationalController::class, 'storeProduction'])->name('operational.production.store');
        Route::delete('/operational/production/{farmProductionLog}', [FarmOperationalController::class, 'destroyProduction'])->name('operational.production.destroy');

        Route::post('/operational/harvest', [FarmOperationalController::class, 'storeHarvest'])->name('operational.harvest.store');
        Route::delete('/operational/harvest/{farmHarvestLog}', [FarmOperationalController::class, 'destroyHarvest'])->name('operational.harvest.destroy');

        // Pengeluaran
        Route::resource('expenses', FarmExpenseController::class)->parameters(['expenses' => 'farmExpense']);

        // Penggajian
        Route::resource('payroll', FarmPayrollController::class)->parameters(['payroll' => 'farmPayroll'])->except(['show']);
        Route::post('/payroll/{farmPayroll}/mark-paid', [FarmPayrollController::class, 'markPaid'])->name('payroll.mark-paid');

        // Master Data — Customers
        Route::prefix('master')->name('master.')->group(function () {
            Route::get('/customers', [FarmMasterDataController::class, 'customersIndex'])->name('customers.index');
            Route::get('/customers/create', [FarmMasterDataController::class, 'customersCreate'])->name('customers.create');
            Route::post('/customers', [FarmMasterDataController::class, 'customersStore'])->name('customers.store');
            Route::get('/customers/{farmCustomer}/edit', [FarmMasterDataController::class, 'customersEdit'])->name('customers.edit');
            Route::put('/customers/{farmCustomer}', [FarmMasterDataController::class, 'customersUpdate'])->name('customers.update');
            Route::delete('/customers/{farmCustomer}', [FarmMasterDataController::class, 'customersDestroy'])->name('customers.destroy');

            // Suppliers
            Route::get('/suppliers', [FarmMasterDataController::class, 'suppliersIndex'])->name('suppliers.index');
            Route::get('/suppliers/create', [FarmMasterDataController::class, 'suppliersCreate'])->name('suppliers.create');
            Route::post('/suppliers', [FarmMasterDataController::class, 'suppliersStore'])->name('suppliers.store');
            Route::get('/suppliers/{farmSupplier}/edit', [FarmMasterDataController::class, 'suppliersEdit'])->name('suppliers.edit');
            Route::put('/suppliers/{farmSupplier}', [FarmMasterDataController::class, 'suppliersUpdate'])->name('suppliers.update');
            Route::delete('/suppliers/{farmSupplier}', [FarmMasterDataController::class, 'suppliersDestroy'])->name('suppliers.destroy');

            // Coops / Kandang
            Route::get('/coops', [FarmMasterDataController::class, 'coopsIndex'])->name('coops.index');
            Route::get('/coops/create', [FarmMasterDataController::class, 'coopsCreate'])->name('coops.create');
            Route::post('/coops', [FarmMasterDataController::class, 'coopsStore'])->name('coops.store');
            Route::get('/coops/{farmCoop}/edit', [FarmMasterDataController::class, 'coopsEdit'])->name('coops.edit');
            Route::put('/coops/{farmCoop}', [FarmMasterDataController::class, 'coopsUpdate'])->name('coops.update');
            Route::delete('/coops/{farmCoop}', [FarmMasterDataController::class, 'coopsDestroy'])->name('coops.destroy');
        });
    });
    // ===================== END FARM DIVISION ROUTES =====================
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';

Route::get('/run-db-fix', function () {
    try {
        $indexes = Illuminate\Support\Facades\DB::select("SHOW INDEX FROM invoices WHERE Key_name = 'invoices_invoice_number_unique'");
        if (count($indexes) > 0) {
            Illuminate\Support\Facades\Schema::table('invoices', function (Illuminate\Database\Schema\Blueprint $table) {
                $table->dropUnique('invoices_invoice_number_unique');
            });
            echo "Berhasil menghapus aturan unik pada nomor invoice.<br>";
        } else {
            echo "Aturan unik invoice sudah tidak ada.<br>";
        }

        $purchaseIndexes = Illuminate\Support\Facades\DB::select("SHOW INDEX FROM purchases WHERE Key_name = 'purchases_purchase_number_unique'");
        if (count($purchaseIndexes) > 0) {
            Illuminate\Support\Facades\Schema::table('purchases', function (Illuminate\Database\Schema\Blueprint $table) {
                $table->dropUnique('purchases_purchase_number_unique');
            });
            echo "Berhasil menghapus aturan unik pada nomor pembelian.<br>";
        } else {
            echo "Aturan unik pembelian sudah tidak ada.<br>";
        }

        Illuminate\Support\Facades\DB::statement('ALTER TABLE invoices MODIFY invoice_number VARCHAR(255) NULL');
        Illuminate\Support\Facades\DB::statement('ALTER TABLE purchases MODIFY purchase_number VARCHAR(255) NULL');
        echo "Berhasil mengubah kolom invoice_number & purchase_number menjadi nullable.<br>";

        Illuminate\Support\Facades\DB::table('invoices')->where('invoice_number', '')->update(['invoice_number' => null]);
        Illuminate\Support\Facades\DB::table('purchases')->where('purchase_number', '')->update(['purchase_number' => null]);
        echo "Berhasil merapikan data lama.<br>";

        echo "<br><b>Selesai! Halaman ini sudah bisa ditutup.</b>";
    } catch (\Exception $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
});
