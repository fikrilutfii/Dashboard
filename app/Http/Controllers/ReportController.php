<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Monthly Income (Based on Paid Invoices)
        $monthlyIncome = Invoice::where('status', 'paid')
            ->select(
                DB::raw('YEAR(invoice_date) as year'),
                DB::raw('MONTH(invoice_date) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Status Distribution
        $invoiceStatus = Invoice::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Recent Invoices
        $recentInvoices = Invoice::with('customer')->latest()->take(5)->get();

        // Totals
        $totalUnpaid = Invoice::where('status', 'unpaid')->sum('total_amount');
        $totalPaid = Invoice::where('status', 'paid')->sum('total_amount');

        return view('dashboard', compact('monthlyIncome', 'invoiceStatus', 'recentInvoices', 'totalUnpaid', 'totalPaid'));
    }
}
