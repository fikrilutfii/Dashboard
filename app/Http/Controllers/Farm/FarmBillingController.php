<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmCustomer;
use App\Models\FarmInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FarmBillingController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();

        $query = FarmInvoice::with(['customer', 'sender', 'payments'])
            ->whereIn('status', ['belum_lunas', 'sebagian']);

        // Filter by Customer
        if ($request->filled('customer_id')) {
            $query->where('farm_customer_id', $request->customer_id);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Aging Bracket
        if ($request->filled('aging_bracket')) {
            switch ($request->aging_bracket) {
                case '0_7':
                    $query->whereRaw('DATEDIFF(?, invoice_date) BETWEEN 0 AND 7', [$today->toDateString()]);
                    break;
                case '8_14':
                    $query->whereRaw('DATEDIFF(?, invoice_date) BETWEEN 8 AND 14', [$today->toDateString()]);
                    break;
                case '15_30':
                    $query->whereRaw('DATEDIFF(?, invoice_date) BETWEEN 15 AND 30', [$today->toDateString()]);
                    break;
                case 'over_30':
                    $query->whereRaw('DATEDIFF(?, invoice_date) > 30', [$today->toDateString()]);
                    break;
            }
        }

        $invoices = $query->orderBy('invoice_date', 'asc')->paginate(20);

        // Calculate Aging Summary Metrics across all unpaid invoices
        $allUnpaid = FarmInvoice::whereIn('status', ['belum_lunas', 'sebagian'])
            ->select('id', 'invoice_date', 'total_amount', 'paid_amount')
            ->get();

        $aging0to7 = 0;
        $aging8to14 = 0;
        $aging15to30 = 0;
        $agingOver30 = 0;

        foreach ($allUnpaid as $inv) {
            $days = $today->diffInDays($inv->invoice_date);
            $remaining = max(0, (float) $inv->total_amount - (float) $inv->paid_amount);
            if ($days <= 7) {
                $aging0to7 += $remaining;
            } elseif ($days <= 14) {
                $aging8to14 += $remaining;
            } elseif ($days <= 30) {
                $aging15to30 += $remaining;
            } else {
                $agingOver30 += $remaining;
            }
        }

        $totalReceivables = $allUnpaid->sum(function ($inv) {
            return max(0, (float) $inv->total_amount - (float) $inv->paid_amount);
        });

        $customers = FarmCustomer::orderBy('name')->get();

        return view('farm.billing.index', compact(
            'invoices',
            'customers',
            'aging0to7',
            'aging8to14',
            'aging15to30',
            'agingOver30',
            'totalReceivables'
        ));
    }
}
