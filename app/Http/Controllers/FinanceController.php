<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\FinanceReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    protected $reportService;

    public function __construct(FinanceReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        // Redirecting to FinanceReportController@index based on routes
        return redirect()->route('finance.index');
    }

    public function pemasukan(Request $request)
    {
        $query = Transaction::where('type', 'credit')
            ->where(function($q) {
                $q->where('category', 'Pemasukan Manual')
                  ->orWhere('category', 'LIKE', '%Pemasukan%');
            });

        if ($request->division) {
            $query->where('division', $request->division);
        }

        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $transaksi = $query->orderBy('date', 'desc')->paginate(15);
        
        $totalPemasukan = $query->sum('amount');

        return view('finance.pemasukan', compact('transaksi', 'totalPemasukan'));
    }

    public function storePemasukan(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'category' => 'nullable|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'division' => 'required|string',
            'entity' => 'nullable|string|in:percetakan,konfeksi,pribadi',
        ]);

        Transaction::create([
            'type' => 'credit',
            'amount' => $request->amount,
            'category' => $request->category ?? 'Pemasukan Manual',
            'description' => $request->description,
            'date' => $request->date,
            'division' => $request->division,
            'entity' => $request->entity ?? $request->division,
        ]);

        return redirect()->route('finance.pemasukan')->with('success', 'Pemasukan berhasil dicatat.');
    }

    public function storeLoan(Request $request)
    {
        $request->validate([
            'creditor_name' => 'required|string',
            'amount'        => 'required|numeric|min:0',
            'date'          => 'required|date',
            'description'   => 'nullable|string',
            'entity'        => 'nullable|string|in:percetakan,konfeksi,pribadi',
        ]);

        $division = session('division') ?? 'percetakan';
        $entity = $request->entity ?? $division;

        // Catat sebagai Pemasukan (kredit) di transaksi
        Transaction::create([
            'type'        => 'credit',
            'amount'      => $request->amount,
            'category'    => 'Pinjaman / Pembayaran Perusahaan',
            'description' => 'Pinjaman dari: ' . $request->creditor_name . '. ' . ($request->description ?? ''),
            'date'        => $request->date,
            'division'    => $division,
            'entity'      => $entity,
        ]);

        // Catat juga sebagai Pembayaran Perusahaan (hutang)
        \App\Models\CompanyDebt::create([
            'name'        => $request->creditor_name,
            'amount'      => $request->amount,
            'due_date'    => null,
            'status'      => 'belum_lunas',
            'type'        => 'credit',
            'description' => $request->description,
            'division'    => $division,
            'entity'      => $entity,
        ]);

        return back()->with('success', 'Pinjaman / Pembayaran Perusahaan berhasil dicatat.');
    }
}
