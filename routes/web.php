<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/set-division', [App\Http\Controllers\DashboardController::class, 'setDivision'])->name('division.set');
    Route::post('/switch-division', [App\Http\Controllers\DashboardController::class, 'switchDivision'])->name('division.switch');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/invoices/{id}/export', [App\Http\Controllers\InvoiceExportController::class, 'export'])->name('invoices.export');
    Route::resource('invoices', InvoiceController::class);
    
    // Master Data
    Route::resource('customers', CustomerController::class);
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);

    // API for Product Lookup
    Route::get('/api/products/{code}', [ProductController::class, 'getByCode'])->name('products.lookup');
    Route::post('/products/import-csv', [ProductController::class, 'importCsv'])->name('products.import-csv');

    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
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
    Route::get('/finance/pemasukan', [FinanceController::class, 'pemasukan'])->name('finance.pemasukan');
    Route::post('/finance/pemasukan', [FinanceController::class, 'storePemasukan'])->name('finance.storePemasukan');
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
    Route::resource('kasbons', KasbonController::class)->only(['index', 'create', 'store', 'destroy']);


    // Penggajian
    Route::get('/payrolls/recap', [PayrollController::class, 'recap'])->name('payrolls.recap');
    Route::post('/payrolls/recap', [PayrollController::class, 'storeRecap'])->name('payrolls.storeRecap');
    Route::get('/payrolls/print', [PayrollController::class, 'print'])->name('payrolls.print');
    Route::get('/payrolls/{payroll}/slip', [PayrollController::class, 'printSlip'])->name('payrolls.slip');
    Route::post('/payrolls/{payroll}/mark-lunas', [PayrollController::class, 'markLunas'])->name('payrolls.mark-lunas');
    Route::resource('payrolls', PayrollController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // API for Payroll Calculation
    Route::get('/api/employees/{employee}/data', [PayrollController::class, 'getEmployeeData'])->name('api.employees.data');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';

