<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmInvoice;
use App\Models\FarmExpense;
use App\Models\FarmPayroll;
use App\Models\FarmTransportation;
use App\Models\FarmCoop;
use Carbon\Carbon;

class FarmDashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth   = $now->copy()->endOfMonth();

        // Total tagihan belum lunas
        $totalTagihanBelumLunas = FarmInvoice::whereIn('status', ['belum_lunas', 'sebagian'])
            ->sum(\Illuminate\Support\Facades\DB::raw('total_amount - paid_amount'));

        // Penjualan bulan ini
        $penjualanBulanIni = FarmInvoice::whereBetween('invoice_date', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');

        // Pengeluaran bulan ini
        $pengeluaranBulanIni = FarmExpense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Gaji bulan ini
        $gajiBulanIni = FarmPayroll::where('status', 'dibayar')
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->sum('net_salary');

        // Transportasi bulan ini
        $transportasiBulanIni = FarmTransportation::whereBetween('transport_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Kandang aktif
        $kandangAktif = FarmCoop::where('status', 'aktif')->count();
        $totalKandang = FarmCoop::count();

        // Faktur terbaru
        $recentInvoices = FarmInvoice::with('customer')
            ->latest()
            ->take(5)
            ->get();

        // Pengeluaran terbaru
        $recentExpenses = FarmExpense::latest()
            ->take(5)
            ->get();

        // Keuntungan bersih bulan ini
        $keuntunganBulanIni = $penjualanBulanIni - $pengeluaranBulanIni - $gajiBulanIni - $transportasiBulanIni;

        return view('farm.dashboard', compact(
            'totalTagihanBelumLunas',
            'penjualanBulanIni',
            'pengeluaranBulanIni',
            'gajiBulanIni',
            'transportasiBulanIni',
            'kandangAktif',
            'totalKandang',
            'recentInvoices',
            'recentExpenses',
            'keuntunganBulanIni',
            'now'
        ));
    }
}
