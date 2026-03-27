<?php

namespace App\Http\Controllers;

use App\Models\CompanyReceivable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyReceivableController extends Controller
{
    public function index(Request $request)
    {
        $division = session('division');
        $query = CompanyReceivable::query();

        if ($division) {
            $query->where('division', $division);
        }

        if ($request->filled('status')) {
            $status = $request->status;
            $query->when($status === 'belum_lunas', function($q) {
                return $q->whereIn('status', ['belum_lunas', 'sebagian']);
            })
            ->when($status === 'lunas', function($q) {
                return $q->where('status', 'lunas');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $receivables = $query->latest()->paginate(15);

        $totalBelumLunas = CompanyReceivable::when($division, fn($q) => $q->where('division', $division))
            ->whereIn('status', ['belum_lunas', 'sebagian'])
            ->sum('remaining_amount');
        $totalLunas = CompanyReceivable::when($division, fn($q) => $q->where('division', $division))
            ->where('status', 'lunas')
            ->sum('total_amount');

        return view('company_receivables.index', compact('receivables', 'totalBelumLunas', 'totalLunas'));
    }

    public function create()
    {
        return view('company_receivables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
            'due_date'     => 'nullable|date',
            'type'         => 'required|in:cash,installment',
            'entity'       => 'nullable|string|in:percetakan,konfeksi,pribadi',
        ]);

        $division = session('division');
        $validated['remaining_amount'] = $validated['total_amount'];
        $validated['status'] = 'belum_lunas';
        $validated['division'] = $division;
        $validated['entity'] = $validated['entity'] ?? $division;

        DB::transaction(function () use ($validated, $division) {
            $receivable = CompanyReceivable::create($validated);

            // If it's a CASH receivable, it means we sent cash out but expect it back (like a loan TO someone).
            // This is a DEBIT (Money Out) from our perspective.
            if ($validated['type'] === 'cash') {
                \App\Models\Transaction::create([
                    'type'           => 'debit',
                    'amount'         => $validated['total_amount'],
                    'category'       => 'tagihan_perusahaan',
                    'reference_type' => CompanyReceivable::class,
                    'reference_id'   => $receivable->id,
                    'description'    => 'Pemberian Dana/Pinjaman Tunai ke: ' . $validated['name'],
                    'date'           => now(),
                    'division'       => $division,
                    'entity'         => $validated['entity'],
                ]);
            }
        });

        return redirect()->route('company-receivables.index')->with('success', 'Tagihan perusahaan berhasil ditambahkan.');
    }

    public function edit(CompanyReceivable $companyReceivable)
    {
        return view('company_receivables.edit', compact('companyReceivable'));
    }

    public function update(Request $request, CompanyReceivable $companyReceivable)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'entity'           => 'nullable|string|in:percetakan,konfeksi,pribadi',
            'description'      => 'nullable|string',
            'total_amount'     => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'due_date'         => 'nullable|date',
            'type'             => 'required|in:cash,installment',
            'status'           => 'required|in:belum_lunas,sebagian,lunas',
        ]);

        $companyReceivable->update($validated);

        return redirect()->route('company-receivables.index')->with('success', 'Data tagihan berhasil diperbarui.');
    }

    public function destroy(CompanyReceivable $companyReceivable)
    {
        DB::transaction(function () use ($companyReceivable) {
            // Delete associated transactions
            \App\Models\Transaction::where('reference_type', CompanyReceivable::class)
                ->where('reference_id', $companyReceivable->id)
                ->delete();

            $companyReceivable->delete();
        });

        return redirect()->route('company-receivables.index')->with('success', 'Data tagihan berhasil dihapus.');
    }

    public function markLunas(CompanyReceivable $companyReceivable)
    {
        DB::transaction(function () use ($companyReceivable) {
            $paymentAmount = $companyReceivable->remaining_amount;
            
            $companyReceivable->update([
                'status' => 'lunas',
                'remaining_amount' => 0,
            ]);

            // Recording the receipt of money: CREDIT (Money In)
            \App\Models\Transaction::create([
                'type'           => 'credit',
                'amount'         => $paymentAmount,
                'category'       => 'tagihan_perusahaan',
                'reference_type' => CompanyReceivable::class,
                'reference_id'   => $companyReceivable->id,
                'description'    => 'Penerimaan Pelunasan Tagihan dari: ' . $companyReceivable->name,
                'date'           => now(),
                'division'       => $companyReceivable->division,
                'entity'         => $companyReceivable->entity ?? $companyReceivable->division,
            ]);
        });

        return back()->with('success', 'Tagihan ditandai Lunas dan tercatat di kas.');
    }

    public function recordPayment(Request $request, CompanyReceivable $companyReceivable)
    {
        $validated = $request->validate([
            'payment_amount' => 'required|numeric|min:1|max:' . $companyReceivable->remaining_amount,
        ]);

        DB::transaction(function () use ($validated, $companyReceivable) {
            $newRemaining = $companyReceivable->remaining_amount - $validated['payment_amount'];
            $status = $newRemaining <= 0 ? 'lunas' : 'sebagian';

            $companyReceivable->update([
                'remaining_amount' => max(0, $newRemaining),
                'status' => $status,
            ]);

            // Recording the receipt of money: CREDIT (Money In)
            \App\Models\Transaction::create([
                'type'           => 'credit',
                'amount'         => $validated['payment_amount'],
                'category'       => 'tagihan_perusahaan',
                'reference_type' => CompanyReceivable::class,
                'reference_id'   => $companyReceivable->id,
                'description'    => 'Penerimaan Cicilan Tagihan dari: ' . $companyReceivable->name,
                'date'           => now(),
                'division'       => $companyReceivable->division,
                'entity'         => $companyReceivable->entity ?? $companyReceivable->division,
            ]);
        });

        return back()->with('success', 'Pembayaran tagihan berhasil dicatat di kas.');
    }
}
