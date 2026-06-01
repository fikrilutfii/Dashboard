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

    public function transactions(Request $request)
    {
        $division = session('division');
        $query = Transaction::query();

        if ($division) {
            $query->where('division', $division);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $transaksi = $query->orderBy('date', 'desc')->paginate(15);
        
        $totalPemasukan = (clone $query)->where('type', 'credit')->sum('amount');
        $totalPengeluaran = (clone $query)->where('type', 'debit')->sum('amount');

        return view('finance.transactions', compact('transaksi', 'totalPemasukan', 'totalPengeluaran'));
    }

    public function storeTransaction(Request $request)
    {
        $request->validate([
            'type'           => 'required|in:credit,debit', // credit=pemasukan, debit=pengeluaran
            'payment_method' => 'required|in:cash,credit', // credit=tempo/berjangka
            'amount'         => 'required|numeric|min:0',
            'category'       => 'nullable|string',
            'description'    => 'required|string',
            'date'           => 'required|date',
            'division'       => 'required|string',
            'entity'         => 'nullable|string|in:percetakan,konfeksi,pribadi',
            'tenure'         => 'nullable|integer|min:1',
            'due_date'       => 'nullable|date',
            'is_loan'        => 'nullable|boolean',
        ]);

        $division = $request->division;
        $entity = $request->entity ?? $division;

        DB::transaction(function () use ($request, $division, $entity) {
            if ($request->payment_method === 'cash') {
                // Record Direct Transaction
                Transaction::create([
                    'type'        => $request->type,
                    'amount'      => $request->amount,
                    'category'    => $request->category ?? ($request->type === 'credit' ? 'Pemasukan Manual' : 'Pengeluaran Manual'),
                    'description' => $request->description,
                    'date'        => $request->date,
                    'division'    => $division,
                    'entity'      => $entity,
                ]);

                // If marked as LOAN/HUTANG/PIUTANG
                if ($request->is_loan) {
                    if ($request->type === 'credit') {
                        // Uang Masuk dari Pinjaman -> Masuk ke PEMBAYARAN (Hutang)
                        \App\Models\CompanyDebt::create([
                            'name'             => 'Pinjaman: ' . $request->description,
                            'description'      => 'Pinjaman Tunai Masuk',
                            'amount'           => $request->amount,
                            'remaining_amount' => $request->amount,
                            'monthly_amount'   => $request->tenure > 0 ? $request->amount / $request->tenure : $request->amount,
                            'due_date'         => $request->due_date,
                            'status'           => 'belum_lunas',
                            'type'             => $request->tenure > 1 ? 'credit' : 'cash',
                            'division'         => $division,
                            'entity'           => $entity,
                        ]);
                    } else {
                        // Uang Keluar dipinjamkan -> Masuk ke TAGIHAN (Piutang)
                        \App\Models\CompanyReceivable::create([
                            'name'             => 'Piutang: ' . $request->description,
                            'description'      => 'Dana Dipinjamkan Keluar',
                            'total_amount'     => $request->amount,
                            'remaining_amount' => $request->amount,
                            'monthly_amount'   => $request->tenure > 0 ? $request->amount / $request->tenure : $request->amount,
                            'due_date'         => $request->due_date,
                            'status'           => 'belum_lunas',
                            'type'             => 'installment',
                            'division'         => $division,
                            'entity'           => $entity,
                        ]);
                    }
                }
            } else {
                // Handle Tempo/Non-Cash
                // User wants Pemasukan (Tempo) -> Pembayaran (Debt)
                // And Pengeluaran (Tempo) -> Tagihan (Receivable)
                if ($request->type === 'credit') {
                    // Pemasukan Tempo -> Hutang (Pembayaran Cicilan)
                    $monthlyAmount = $request->tenure > 0 ? $request->amount / $request->tenure : 0;
                    \App\Models\CompanyDebt::create([
                        'name'             => 'Hutang: ' . $request->description,
                        'description'      => $request->category ?? 'Pemasukan Kredit (Hutang)',
                        'amount'           => $request->amount,
                        'remaining_amount' => $request->amount,
                        'monthly_amount'   => $monthlyAmount,
                        'due_date'         => $request->due_date,
                        'status'           => 'belum_lunas',
                        'type'             => 'credit',
                        'division'         => $division,
                        'entity'           => $entity,
                    ]);
                } else {
                    // Pengeluaran Tempo -> Tagihan (Tagihan Perusahaan)
                    $monthlyAmount = $request->tenure > 0 ? $request->amount / $request->tenure : 0;
                    \App\Models\CompanyReceivable::create([
                        'name'             => 'Tagihan: ' . $request->description,
                        'description'      => $request->category ?? 'Pengeluaran Kredit (Piutang)',
                        'total_amount'     => $request->amount,
                        'remaining_amount' => $request->amount,
                        'monthly_amount'   => $monthlyAmount,
                        'due_date'         => $request->due_date,
                        'status'           => 'belum_lunas',
                        'type'             => 'installment',
                        'division'         => $division,
                        'entity'           => $entity,
                    ]);
                }
            }
        });

        return redirect()->route('finance.transactions')->with('success', 'Transaksi berhasil dicatat.');
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
