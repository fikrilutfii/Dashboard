<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\Purchase;
use App\Models\CompanyDebt;
use Illuminate\Support\Facades\DB;

class FinanceDashboardService
{
    /**
     * Get saldo for each entity.
     * Saldo Entitas = Total Pemasukan Entitas – Total Pembayaran Entitas
     */
    public function getSaldoPerEntitas()
    {
        $stats = Transaction::select('entity', 
            DB::raw("SUM(CASE WHEN type = 'credit' THEN amount ELSE 0 END) as total_credit"),
            DB::raw("SUM(CASE WHEN type = 'debit' THEN amount ELSE 0 END) as total_debit")
        )
        ->groupBy('entity')
        ->get();

        $entities = ['pribadi' => 0, 'percetakan' => 0, 'konfeksi' => 0];
        
        foreach ($stats as $stat) {
            $entity = strtolower($stat->entity);
            if (array_key_exists($entity, $entities)) {
                $entities[$entity] = $stat->total_credit - $stat->total_debit;
            }
        }

        return $entities;
    }

    /**
     * Get total balance across all entities.
     */
    public function getSaldoGabungan()
    {
        $entities = $this->getSaldoPerEntitas();
        return array_sum($entities);
    }

    /**
     * Get unpaid invoices (Tagihan) per entity.
     */
    public function getRingkasanTagihanPerEntitas($divisionFilter = null)
    {
        $query = Invoice::where('status', 'unpaid');
        
        if ($divisionFilter) {
            $query->where('division', $divisionFilter);
        }
        
        $stats = $query->select('entity', DB::raw('SUM(total_amount - paid_amount) as total'))
            ->groupBy('entity')
            ->get()
            ->pluck('total', 'entity')
            ->toArray();

        // Convert keys to lowercase for consistency
        $results = array_change_key_case($stats, CASE_LOWER);

        return [
            'pribadi' => $results['pribadi'] ?? 0,
            'percetakan' => $results['percetakan'] ?? 0,
            'konfeksi' => $results['konfeksi'] ?? 0,
            'total' => array_sum($results)
        ];
    }

    public function getRingkasanPembayaranPerEntitas($divisionFilter = null)
    {
        // Company Debt (standardized term for company-level liabilities)
        $debtQuery = CompanyDebt::where('status', 'unpaid');
        if ($divisionFilter) {
            $debtQuery->where('division', $divisionFilter);
        }
        $debtStats = $debtQuery->select('entity', DB::raw('SUM(amount) as total'))
            ->groupBy('entity')
            ->get()
            ->pluck('total', 'entity')
            ->toArray();

        $entities = ['pribadi', 'percetakan', 'konfeksi'];
        $results = [];
        
        $debtStats = array_change_key_case($debtStats, CASE_LOWER);

        foreach ($entities as $ent) {
            $results[$ent] = $debtStats[$ent] ?? 0;
        }
        $results['total'] = array_sum($results);

        return $results;
    }
}
