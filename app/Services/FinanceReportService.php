<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\CompanyDebt;
use App\Models\CompanyReceivable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceReportService
{
    /**
     * Exclude kasbon-related transactions from a query.
     * Kasbon is an employee advance and must NOT appear in general financial reports.
     */
    private function excludeKasbon($query)
    {
        return $query->where('category', '!=', 'kasbon')
                     ->where(function($q) {
                         $q->whereNull('reference_type')
                           ->orWhere('reference_type', 'not like', '%Kasbon%');
                     });
    }

    /**
     * Exclude kasbon from CompanyReceivable queries.
     */
    private function excludeKasbonReceivable($query)
    {
        return $query->whereNull('kasbon_id');
    }

    public function getSaldoGlobal($division = null, $entity = null)
    {
        $query = Transaction::query();
        $query = $this->excludeKasbon($query);
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
        $query = $this->excludeKasbon($query);
        if ($division) $query->where('division', $division);
        if ($entity) $query->where('entity', $entity);
        if ($startDate) $query->whereDate('date', '>=', $startDate);
        if ($endDate) $query->whereDate('date', '<=', $endDate);

        $totalPemasukan = (clone $query)->where('type', 'credit')->sum('amount');
        $totalPembayaran = (clone $query)->where('type', 'debit')->sum('amount');
        $arusKasBersih = $totalPemasukan - $totalPembayaran;

        // Company Obligations (exclude kasbon)
        $hutangQuery = CompanyDebt::where('status', 'belum_lunas');
        if ($division) $hutangQuery->where('division', $division);
        if ($entity) $hutangQuery->where('entity', $entity);
        $totalHutang = $hutangQuery->sum('amount');

        $tagihanQuery = CompanyReceivable::whereIn('status', ['belum_lunas', 'sebagian']);
        $tagihanQuery = $this->excludeKasbonReceivable($tagihanQuery);
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
        ->where('category', '!=', 'kasbon')
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
        ->where('category', '!=', 'kasbon')
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
            ->where('category', '!=', 'kasbon')
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
        ->where('category', '!=', 'kasbon')
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
        ->where('category', '!=', 'kasbon')
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
        $query = $this->excludeKasbon($query);

        if ($division) $query->where('division', $division);
        if ($entity) $query->where('entity', $entity);
        if ($startDate) $query->whereDate('date', '>=', $startDate);
        if ($endDate) $query->whereDate('date', '<=', $endDate);
        if ($type) $query->where('type', $type);
        if ($category) $query->where('category', $category);

        // Saldo Awal sebelum tanggal filter
        $saldoAwal = 0;
        if ($startDate) {
            $baseQuery = Transaction::whereDate('date', '<', $startDate)
                ->where('category', '!=', 'kasbon')
                ->when($division, fn($q) => $q->where('division', $division))
                ->when($entity, fn($q) => $q->where('entity', $entity));

            $kreditSebelum = (clone $baseQuery)->where('type', 'credit')->sum('amount');
            $debitSebelum  = (clone $baseQuery)->where('type', 'debit')->sum('amount');
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

    /**
     * Rincian Mingguan (5 Minggu dalam bulan terpilih)
     */
    public function getWeeklyBreakdown($month = null, $year = null, $division = null, $entity = null)
    {
        $month = (int) ($month ?: Carbon::now()->month);
        $year = (int) ($year ?: Carbon::now()->year);

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $daysInMonth = $startOfMonth->daysInMonth;

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $weeks = [
            ['week' => 1, 'start_day' => 1, 'end_day' => 7],
            ['week' => 2, 'start_day' => 8, 'end_day' => 14],
            ['week' => 3, 'start_day' => 15, 'end_day' => 21],
            ['week' => 4, 'start_day' => 22, 'end_day' => 28],
        ];

        if ($daysInMonth > 28) {
            $weeks[] = ['week' => 5, 'start_day' => 29, 'end_day' => $daysInMonth];
        }

        $results = [];
        $totalPemasukan = 0;
        $totalPengeluaran = 0;

        foreach ($weeks as $w) {
            $startDate = Carbon::createFromDate($year, $month, $w['start_day'])->startOfDay();
            $endDate = Carbon::createFromDate($year, $month, $w['end_day'])->endOfDay();

            $query = Transaction::query()
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->where('category', '!=', 'kasbon');

            if ($division) $query->where('division', $division);
            if ($entity) $query->where('entity', $entity);

            $pemasukan = (clone $query)->where('type', 'credit')
                ->where('category', '!=', 'pengembalian_keuangan_terserap')
                ->sum('amount');
            $pengeluaran = (clone $query)->where('type', 'debit')
                ->where('category', '!=', 'keuangan_terserap')
                ->sum('amount');
            $terserap = (clone $query)->where('category', 'keuangan_terserap')->sum('amount');
            $kembali = (clone $query)->where('category', 'pengembalian_keuangan_terserap')->sum('amount');

            $bersih = $pemasukan - $pengeluaran;

            $totalPemasukan += $pemasukan;
            $totalPengeluaran += $pengeluaran;

            $results[] = [
                'week_number' => $w['week'],
                'label' => 'Minggu ' . $w['week'],
                'date_range' => $startDate->format('d M') . ' - ' . $endDate->format('d M Y'),
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'bersih' => $bersih,
                'terserap' => $terserap,
                'kembali' => $kembali,
                'status' => $bersih >= 0 ? 'untung' : 'rugi',
            ];
        }

        return [
            'month' => $month,
            'year' => $year,
            'month_name' => $monthNames[$month] ?? 'Bulan',
            'weeks' => $results,
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'total_bersih' => $totalPemasukan - $totalPengeluaran,
        ];
    }

    /**
     * Rekapitulasi 12 Bulan (Tahunan)
     */
    public function getMonthlyBreakdown($year = null, $division = null, $entity = null)
    {
        $year = (int) ($year ?: Carbon::now()->year);
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $months = [];
        $totalPemasukan = 0;
        $totalPengeluaran = 0;
        $cumulativeBalance = 0;

        for ($m = 1; $m <= 12; $m++) {
            $startDate = Carbon::createFromDate($year, $m, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $m, 1)->endOfMonth();

            $query = Transaction::query()
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->where('category', '!=', 'kasbon');

            if ($division) $query->where('division', $division);
            if ($entity) $query->where('entity', $entity);

            $pemasukan = (clone $query)->where('type', 'credit')
                ->where('category', '!=', 'pengembalian_keuangan_terserap')
                ->sum('amount');
            $pengeluaran = (clone $query)->where('type', 'debit')
                ->where('category', '!=', 'keuangan_terserap')
                ->sum('amount');
            $terserap = (clone $query)->where('category', 'keuangan_terserap')->sum('amount');
            $kembali = (clone $query)->where('category', 'pengembalian_keuangan_terserap')->sum('amount');

            $bersih = $pemasukan - $pengeluaran;
            $cumulativeBalance += $bersih;

            $totalPemasukan += $pemasukan;
            $totalPengeluaran += $pengeluaran;

            $months[] = [
                'month_number' => $m,
                'month_name' => $monthNames[$m] ?? "Bulan $m",
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'bersih' => $bersih,
                'terserap' => $terserap,
                'kembali' => $kembali,
                'saldo_kumulatif' => $cumulativeBalance,
                'status' => $bersih >= 0 ? 'untung' : 'rugi',
            ];
        }

        return [
            'year' => $year,
            'months' => $months,
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'total_bersih' => $totalPemasukan - $totalPengeluaran,
        ];
    }

    /**
     * Perbandingan Tahunan (Multi-Tahun)
     */
    public function getYearlyBreakdown($division = null, $entity = null)
    {
        $currentYear = (int) Carbon::now()->year;
        $years = [];

        for ($y = $currentYear - 2; $y <= $currentYear; $y++) {
            $startDate = Carbon::createFromDate($y, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($y, 12, 31)->endOfYear();

            $query = Transaction::query()
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->where('category', '!=', 'kasbon');

            if ($division) $query->where('division', $division);
            if ($entity) $query->where('entity', $entity);

            $pemasukan = (clone $query)->where('type', 'credit')
                ->where('category', '!=', 'pengembalian_keuangan_terserap')
                ->sum('amount');
            $pengeluaran = (clone $query)->where('type', 'debit')
                ->where('category', '!=', 'keuangan_terserap')
                ->sum('amount');

            $years[] = [
                'year' => $y,
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'bersih' => $pemasukan - $pengeluaran,
            ];
        }

        return $years;
    }

    /**
     * Monitoring Khusus Keuangan Terserap (Fokus Divisi Percetakan)
     */
    public function getKeuanganTerserapSummary($division = 'percetakan')
    {
        $query = Transaction::query();
        if ($division) {
            $query->where('division', $division);
        }

        $totalTerserap = (clone $query)
            ->where('type', 'debit')
            ->where('category', 'keuangan_terserap')
            ->sum('amount');

        $totalDikembalikan = (clone $query)
            ->where('type', 'credit')
            ->where('category', 'pengembalian_keuangan_terserap')
            ->sum('amount');

        $sisaTerserap = max(0, $totalTerserap - $totalDikembalikan);

        $riwayat = Transaction::query()
            ->when($division, fn($q) => $q->where('division', $division))
            ->whereIn('category', ['keuangan_terserap', 'pengembalian_keuangan_terserap'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(50)
            ->get();

        return [
            'total_terserap' => $totalTerserap,
            'total_dikembalikan' => $totalDikembalikan,
            'sisa_terserap' => $sisaTerserap,
            'riwayat' => $riwayat,
        ];
    }
}
