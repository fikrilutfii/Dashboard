<?php

namespace App\Http\Controllers;

use App\Services\FinanceReportService;
use App\Models\Transaction;
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

        $selectedMonth = (int) $request->input('month', now()->month);
        $selectedYear = (int) $request->input('year', now()->year);
        $activeTab = $request->input('tab', 'ringkasan');

        // Perhitungan Data
        $saldoGlobal = $this->reportService->getSaldoGlobal($filters['division']);
        $ringkasan = $this->reportService->getRingkasanPeriode($filters);
        $chartData = $this->reportService->getChartData($filters);
        $periodik = $this->reportService->getPeriodicSummary($filters);
        $breakdown = $this->reportService->getCategoryBreakdown($filters);
        $transaksi = $this->reportService->getTransaksiDenganSaldoBerjalan($filters);

        // Breakdown Baru (Mingguan & 12 Bulan)
        $weeklyBreakdown = $this->reportService->getWeeklyBreakdown($selectedMonth, $selectedYear, $filters['division']);
        $monthlyBreakdown = $this->reportService->getMonthlyBreakdown($selectedYear, $filters['division']);
        $yearlyBreakdown = $this->reportService->getYearlyBreakdown($filters['division']);

        // Monitoring Keuangan Terserap
        $terserapSummary = $this->reportService->getKeuanganTerserapSummary($filters['division'] ?: 'percetakan');

        $kategori = Transaction::distinct()->pluck('category');

        return view('finance.index', compact(
            'saldoGlobal', 
            'ringkasan', 
            'chartData', 
            'periodik', 
            'weeklyBreakdown',
            'monthlyBreakdown',
            'yearlyBreakdown',
            'terserapSummary',
            'breakdown', 
            'transaksi', 
            'filters',
            'kategori',
            'selectedMonth',
            'selectedYear',
            'activeTab'
        ));
    }

    public function storeKeuanganTerserap(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'date' => 'required|date',
            'target' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        Transaction::create([
            'type' => 'debit',
            'amount' => $request->amount,
            'category' => 'keuangan_terserap',
            'description' => '[Dana Terserap: ' . $request->target . '] ' . ($request->description ?? ''),
            'date' => $request->date,
            'division' => 'percetakan',
            'entity' => 'percetakan',
        ]);

        return redirect()->route('finance.index', ['tab' => 'terserap'])
            ->with('success', 'Keuangan terserap sebesar Rp ' . number_format($request->amount, 0, ',', '.') . ' berhasil dicatat.');
    }

    public function repayKeuanganTerserap(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'date' => 'required|date',
            'source' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        Transaction::create([
            'type' => 'credit',
            'amount' => $request->amount,
            'category' => 'pengembalian_keuangan_terserap',
            'description' => '[Pengembalian Dana: ' . ($request->source ?: 'Divisi Lain') . '] ' . ($request->description ?? ''),
            'date' => $request->date,
            'division' => 'percetakan',
            'entity' => 'percetakan',
        ]);

        return redirect()->route('finance.index', ['tab' => 'terserap'])
            ->with('success', 'Pengembalian keuangan terserap sebesar Rp ' . number_format($request->amount, 0, ',', '.') . ' berhasil dicatat.');
    }

    public function destroyKeuanganTerserap(Transaction $transaction)
    {
        if (in_array($transaction->category, ['keuangan_terserap', 'pengembalian_keuangan_terserap'])) {
            $transaction->delete();
            return redirect()->route('finance.index', ['tab' => 'terserap'])
                ->with('success', 'Catatan keuangan terserap berhasil dihapus.');
        }

        return redirect()->route('finance.index', ['tab' => 'terserap'])
            ->with('error', 'Transaksi tidak valid untuk dihapus dari menu ini.');
    }

    public function exportPDF(Request $request)
    {
        $filters = $request->all();
        $transactions = $this->reportService->getTransaksiDenganSaldoBerjalan($filters);
        $summary = $this->reportService->getRingkasanPeriode($filters);

        $pdf = Pdf::loadView('finance.pdf', compact('transactions', 'summary', 'filters'))
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
