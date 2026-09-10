<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmCustomer;
use App\Models\FarmExpense;
use App\Models\FarmInvoice;
use App\Models\FarmInvoicePayment;
use App\Models\FarmProduct;
use App\Models\FarmProductionBatch;
use App\Models\FarmSupplier;
use App\Models\FarmTransportation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FarmDashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. Tagihan (Total sisa piutang penjualan karkas yang belum lunas)
        $totalReceivables = FarmInvoice::whereIn('status', ['belum_lunas', 'sebagian'])->sum('remaining_amount');

        // 2. Pembayaran (Total hutang pembelian ayam hidup ke supplier yang belum lunas)
        $totalPayables = FarmProductionBatch::where('supplier_payment_status', 'belum_lunas')->sum('total_buy_price');

        // 3. Pemasukan (Total uang kas masuk dari pembayaran faktur & cicilan)
        $totalIncomeMonth = FarmInvoicePayment::whereBetween('payment_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $totalIncomeToday = FarmInvoicePayment::whereDate('payment_date', $today)->sum('amount');

        // 4. Pengeluaran (Total kas keluar operasional harian RPA)
        $totalExpenseMonth = FarmExpense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $totalExpenseToday = FarmExpense::whereDate('expense_date', $today)->sum('amount');

        // 5. Sisa Stok Karkas & Parting Hari Ini (Perishable Stock Tracking)
        $productsStock = FarmProduct::orderBy('category')->orderBy('name')->get();
        $totalStockKg = $productsStock->sum('current_stock_kg');
        $totalStockEkor = $productsStock->sum('current_stock_ekor');

        // 6. Indikator Margin / Spread Harian (Harga Beli Ayam Hidup vs Harga Jual Karkas Hari Ini)
        $todayBatches = FarmProductionBatch::whereDate('production_date', $today)->get();
        $avgBuyPricePerKg = $todayBatches->count() > 0 ? $todayBatches->avg('buy_price_per_kg') : 0;

        // Rata-rata harga jual karkas hari ini dari faktur
        $todayInvoiceItems = FarmInvoice::whereDate('invoice_date', $today)
            ->with('items')
            ->get()
            ->flatMap->items;
        $avgSellPricePerKg = $todayInvoiceItems->count() > 0 ? $todayInvoiceItems->avg('unit_price') : 0;
        $marginPerKg = max(0, $avgSellPricePerKg - $avgBuyPricePerKg);

        // 7. Ringkasan Produksi & Penjualan Hari Ini
        $todayLiveBirds = $todayBatches->sum('live_birds_count');
        $todayLiveKg = $todayBatches->sum('live_birds_weight_kg');
        $todayYieldKg = $todayBatches->sum('total_yield_weight_kg');
        $todaySoldKg = $todayInvoiceItems->sum('weight_kg');

        // 8. Faktur Belum Lunas Terbaru (Jatuh Tempo Mendekat)
        $pendingInvoices = FarmInvoice::with(['customer', 'sender'])
            ->whereIn('status', ['belum_lunas', 'sebagian'])
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // 9. Batch Produksi Hari Ini / Terakhir
        $recentBatches = FarmProductionBatch::with(['supplier', 'items.product'])
            ->orderBy('production_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('farm.dashboard', compact(
            'totalReceivables',
            'totalPayables',
            'totalIncomeMonth',
            'totalIncomeToday',
            'totalExpenseMonth',
            'totalExpenseToday',
            'productsStock',
            'totalStockKg',
            'totalStockEkor',
            'avgBuyPricePerKg',
            'avgSellPricePerKg',
            'marginPerKg',
            'todayLiveBirds',
            'todayLiveKg',
            'todayYieldKg',
            'todaySoldKg',
            'pendingInvoices',
            'recentBatches'
        ));
    }
}
