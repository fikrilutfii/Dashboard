<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\CompanyDebt;
use App\Models\CompanyReceivable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceReportService
{
    public function getSaldoGlobal($division = null, $entity = null)
    {
        $query = Transaction::query();
        if ($division) $query->where('division', $division);
        if ($entity) $query->where('entity', $entity);

        $kredit = (clone $query)->where('type', 'credit')->sum('amount');
        $debit = (clone $query)->where('type', 'debit')->sum('amount');

        return $kredit - $debit;
    }

    public function getRingkasanPeriode($filters)
    {
        $division = $filters['division'] ?? null;
        $entity = $filters['entity'] ?? null;
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;

        $query = Transaction::query();
        if ($division) $query->where('division', $division);
        if ($entity) $query->where('entity', $entity);
        if ($startDate) $query->whereDate('date', '>=', $startDate);
        if ($endDate) $query->whereDate('date', '<=', $endDate);

        $totalPemasukan = (clone $query)->where('type', 'credit')->sum('amount');
        $totalPembayaran = (clone $query)->where('type', 'debit')->sum('amount');
        $arusKasBersih = $totalPemasukan - $totalPembayaran;

        // Company Obligations
        $hutangQuery = CompanyDebt::where('status', 'belum_lunas');
        if ($division) $hutangQuery->where('division', $division);
        if ($entity) $hutangQuery->where('entity', $entity);
        $totalHutang = $hutangQuery->sum('amount');

        $tagihanQuery = CompanyReceivable::whereIn('status', ['belum_lunas', 'sebagian']);
        if ($division) $tagihanQuery->where('division', $division);
        if ($entity) $tagihanQuery->where('entity', $entity);
        $totalTagihan = $tagihanQuery->sum('remaining_amount');

        return [
            'total_pemasukan' => $totalPemasukan,
            'total_pembayaran' => $totalPembayaran,
            'arus_kas_bersih' => $arusKasBersih,
            'total_hutang' => $totalHutang,
            'total_tagihan' => $totalTagihan,
        ];
    }

    public function getChartData($filters)
    {
        $division = $filters['division'] ?? null;
        $entity = $filters['entity'] ?? null;
        
        // 1. Line Chart: 30-day Tren (Pemasukan vs Pembayaran)
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(29);

        $dailyData = Transaction::select(
            DB::raw('DATE(date) as day'),
            DB::raw('SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as expense')
        )
        ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
        ->when($division, fn($q) => $q->where('division', $division))
        ->when($entity, fn($q) => $q->where('entity', $entity))
        ->groupBy('day')
        ->orderBy('day')
        ->get();

        // 2. Bar Chart: Bulan Ini vs Bulan Lalu
        $thisMonthStart = Carbon::now()->startOfMonth();
        $thisMonthEnd = Carbon::now()->endOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $thisMonth = $this->getPeriodTotals($thisMonthStart, $thisMonthEnd, $division, $entity);
        $lastMonth = $this->getPeriodTotals($lastMonthStart, $lastMonthEnd, $division, $entity);

        // 3. Pie Chart: Distribusi Kategori (Pengeluaran in period)
        $filterStart = $filters['start_date'] ?? $thisMonthStart->format('Y-m-d');
        $filterEnd = $filters['end_date'] ?? $thisMonthEnd->format('Y-m-d');

        $categoryData = Transaction::select(
            'category',
            DB::raw('SUM(amount) as total')
        )
        ->where('type', 'debit')
        ->whereBetween('date', [$filterStart, $filterEnd])
        ->when($division, fn($q) => $q->where('division', $division))
        ->when($entity, fn($q) => $q->where('entity', $entity))
        ->groupBy('category')
        ->get();

        return [
            'tren' => [
                'labels' => $dailyData->pluck('day')->map(fn($d) => Carbon::parse($d)->format('d/M'))->toArray(),
                'pemasukan' => $dailyData->pluck('income')->toArray(),
                'pembayaran' => $dailyData->pluck('expense')->toArray(),
            ],
            'perbandingan' => [
                'labels' => ['Bulan Lalu', 'Bulan Ini'],
                'pemasukan' => [$lastMonth['pemasukan'], $thisMonth['pemasukan']],
                'pembayaran' => [$lastMonth['pembayaran'], $thisMonth['pembayaran']],
            ],
            'pie' => [
                'labels' => $categoryData->pluck('category')->map(fn($c) => ucwords(str_replace('_', ' ', $c)))->toArray(),
                'totals' => $categoryData->pluck('total')->toArray(),
            ]
        ];
    }

    public function getCategoryBreakdown($filters)
    {
        $division = $filters['division'] ?? null;
        $entity = $filters['entity'] ?? null;
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;

        $totalExpense = Transaction::where('type', 'debit')
            ->when($startDate, fn($q) => $q->whereDate('date', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('date', '<=', $endDate))
            ->when($division, fn($q) => $q->where('division', $division))
            ->when($entity, fn($q) => $q->where('entity', $entity))
            ->sum('amount');

        $breakdown = Transaction::select(
            'category',
            DB::raw('SUM(amount) as total')
        )
        ->where('type', 'debit')
        ->when($startDate, fn($q) => $q->whereDate('date', '>=', $startDate))
        ->when($endDate, fn($q) => $q->whereDate('date', '<=', $endDate))
        ->when($division, fn($q) => $q->where('division', $division))
        ->when($entity, fn($q) => $q->where('entity', $entity))
        ->groupBy('category')
        ->get()
        ->map(function($item) use ($totalExpense) {
            $item->percentage = $totalExpense > 0 ? ($item->total / $totalExpense) * 100 : 0;
            return $item;
        });

        return $breakdown;
    }

    public function getPeriodicSummary($filters)
    {
        $division = $filters['division'] ?? null;
        $entity = $filters['entity'] ?? null;
        $now = Carbon::now();

        // Mingguan (Minggu Ini)
        $weekStart = $now->copy()->startOfWeek();
        $weekEnd = $now->copy()->endOfWeek();
        $weekly = $this->getPeriodTotals($weekStart, $weekEnd, $division, $entity);

        // Bulanan (Bulan Ini)
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();
        $monthly = $this->getPeriodTotals($monthStart, $monthEnd, $division, $entity);

        // Tahunan (Tahun Ini)
        $yearStart = $now->copy()->startOfYear();
        $yearEnd = $now->copy()->endOfYear();
        $yearly = $this->getPeriodTotals($yearStart, $yearEnd, $division, $entity);

        return [
            'mingguan' => $weekly,
            'bulanan' => $monthly,
            'tahunan' => $yearly,
        ];
    }

    private function getPeriodTotals($start, $end, $division, $entity = null)
    {
        $data = Transaction::select(
            DB::raw('SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as expense')
        )
        ->whereBetween('date', [$start, $end])
        ->when($division, fn($q) => $q->where('division', $division))
        ->when($entity, fn($q) => $q->where('entity', $entity))
        ->first();

        return [
            'pemasukan' => $data->income ?? 0,
            'pembayaran' => $data->expense ?? 0,
            'bersih' => ($data->income ?? 0) - ($data->expense ?? 0),
        ];
    }

    public function getTransaksiDenganSaldoBerjalan($filters)
    {
        $division = $filters['division'] ?? null;
        $entity = $filters['entity'] ?? null;
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;
        $type = $filters['type'] ?? null;
        $category = $filters['category'] ?? null;

        $query = Transaction::query()->orderBy('date', 'asc')->orderBy('id', 'asc');

        if ($division) $query->where('division', $division);
        if ($entity) $query->where('entity', $entity);
        if ($startDate) $query->whereDate('date', '>=', $startDate);
        if ($endDate) $query->whereDate('date', '<=', $endDate);
        if ($type) $query->where('type', $type);
        if ($category) $query->where('category', $category);

        // Untuk "Saldo Berjalan", kita hitung saldo awal sebelum tanggal filter
        $saldoAwal = 0;
        if ($startDate) {
            $kreditSebelum = Transaction::whereDate('date', '<', $startDate)
                ->when($division, fn($q) => $q->where('division', $division))
                ->when($entity, fn($q) => $q->where('entity', $entity))
                ->where('type', 'credit')->sum('amount');
            $debitSebelum = Transaction::whereDate('date', '<', $startDate)
                ->when($division, fn($q) => $q->where('division', $division))
                ->when($entity, fn($q) => $q->where('entity', $entity))
                ->where('type', 'debit')->sum('amount');
            $saldoAwal = $kreditSebelum - $debitSebelum;
        }

        $transactions = $query->get();
        
        $saldo = $saldoAwal;
        foreach ($transactions as $trx) {
            if ($trx->type == 'credit') {
                $saldo += $trx->amount;
            } else {
                $saldo -= $trx->amount;
            }
            $trx->running_balance = $saldo;
        }

        return $transactions->reverse(); // Urutan terbaru ke terlama untuk tampilan
    }
}
