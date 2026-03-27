<?php

namespace App\Http\Controllers;

use App\Services\FinanceReportService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinanceReportExport;

class FinanceReportController extends Controller
{
    protected $reportService;

    public function __construct(FinanceReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $filters = [
            'division' => $request->division,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'category' => $request->category,
        ];

        // Default filters if empty
        if (!$request->has('start_date')) {
            $filters['start_date'] = now()->startOfMonth()->format('Y-m-d');
        }
        if (!$request->has('end_date')) {
            $filters['end_date'] = now()->endOfMonth()->format('Y-m-d');
        }

        // Perhitungan Data
        $saldoGlobal = $this->reportService->getSaldoGlobal($filters['division']);
        $ringkasan = $this->reportService->getRingkasanPeriode($filters);
        $chartData = $this->reportService->getChartData($filters);
        $periodik = $this->reportService->getPeriodicSummary($filters);
        $breakdown = $this->reportService->getCategoryBreakdown($filters);
        $transaksi = $this->reportService->getTransaksiDenganSaldoBerjalan($filters);

        $kategori = \App\Models\Transaction::distinct()->pluck('category');

        return view('finance.index', compact(
            'saldoGlobal', 
            'ringkasan', 
            'chartData', 
            'periodik', 
            'breakdown', 
            'transaksi', 
            'filters',
            'kategori'
        ));
    }

    public function exportPDF(Request $request)
    {
        $filters = $request->all();
        $transactions = $this->reportService->getTransaksiDenganSaldoBerjalan($filters);
        $summary = $this->reportService->getRingkasanPeriode($filters);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance.pdf', compact('transactions', 'summary', 'filters'))
            ->setPaper('a4', 'landscape');
            
        return $pdf->download('laporan-keuangan-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->all();
        $transactions = $this->reportService->getTransaksiDenganSaldoBerjalan($filters);

        return Excel::download(new FinanceReportExport($transactions, $filters), 'laporan-keuangan-' . now()->format('Y-m-d') . '.xlsx');
    }
}
