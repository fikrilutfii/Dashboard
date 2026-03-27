<?php

namespace App\Http\Controllers;

use App\Models\CompanyDebt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyDebtController extends Controller
{
    public function index(Request $request)
    {
        $division = session('division');
        $query = CompanyDebt::query();

        if ($division) {
            $query->where('division', $division);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $debts = $query->latest()->paginate(15);

        $totalBelumLunas = CompanyDebt::when($division, fn($q) => $q->where('division', $division))
            ->where('status', '!=', 'lunas')
            ->sum('remaining_amount');
        $totalLunas = CompanyDebt::when($division, fn($q) => $q->where('division', $division))
            ->where('status', 'lunas')
            ->sum('amount');

        return view('company_debts.index', compact('debts', 'totalBelumLunas', 'totalLunas'));
    }

    public function create()
    {
        return view('company_debts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount'      => 'required|numeric|min:0',
            'monthly_amount' => 'nullable|numeric|min:0',
            'due_date'    => 'nullable|date',
            'type'        => 'required|in:cash,credit',
            'entity'      => 'nullable|string|in:percetakan,konfeksi,pribadi',
        ]);

        $division = session('division');
        $validated['remaining_amount'] = $validated['amount'];
        $validated['status'] = 'belum_lunas';
        $validated['division'] = $division;
        $validated['entity'] = $validated['entity'] ?? $division;

        DB::transaction(function () use ($validated, $division) {
            $debt = CompanyDebt::create($validated);

            // If it's a CASH debt, it means we received money but owe it back.
            // Records as Credit (Money In) in the Transaction Ledger.
            if ($validated['type'] === 'cash') {
                \App\Models\Transaction::create([
                    'type'           => 'credit',
                    'amount'         => $validated['amount'],
                    'category'       => 'pembayaran_perusahaan',
                    'reference_type' => CompanyDebt::class,
                    'reference_id'   => $debt->id,
                    'description'    => 'Penerimaan Pinjaman Tunai dari: ' . $validated['name'],
                    'date'           => now(),
                    'division'       => $division,
                    'entity'         => $validated['entity'],
                ]);
            }
        });

        return redirect()->route('company-debts.index')->with('success', 'Pembayaran perusahaan berhasil ditambahkan.');
    }

    public function edit(CompanyDebt $companyDebt)
    {
        return view('company_debts.edit', compact('companyDebt'));
    }

    public function update(Request $request, CompanyDebt $companyDebt)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'entity'      => 'nullable|string|in:percetakan,konfeksi,pribadi',
            'description' => 'nullable|string',
            'amount'      => 'required|numeric|min:0',
            'monthly_amount' => 'nullable|numeric|min:0',
            'due_date'    => 'nullable|date',
            'type'        => 'required|in:cash,credit',
            'status'      => 'required|in:belum_lunas,lunas',
        ]);

        DB::transaction(function () use ($validated, $companyDebt) {
            // Check if status changed to 'lunas'
            if ($validated['status'] === 'lunas' && $companyDebt->getOriginal('status') !== 'lunas') {
                \App\Models\Transaction::create([
                    'type'           => 'debit',
                    'amount'         => $companyDebt->amount,
                    'category'       => 'pembayaran_perusahaan',
                    'reference_type' => CompanyDebt::class,
                    'reference_id'   => $companyDebt->id,
                    'description'    => 'Pelunasan Pembayaran Perusahaan ke: ' . $companyDebt->name,
                    'date'           => now(),
                    'division'       => $companyDebt->division,
                    'entity'         => $companyDebt->entity ?? $companyDebt->division,
                ]);
            }
            $companyDebt->update($validated);
        });

        return redirect()->route('company-debts.index')->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    public function destroy(CompanyDebt $companyDebt)
    {
        DB::transaction(function () use ($companyDebt) {
            // Delete associated transactions
            \App\Models\Transaction::where('reference_type', CompanyDebt::class)
                ->where('reference_id', $companyDebt->id)
                ->delete();

            $companyDebt->delete();
        });

        return redirect()->route('company-debts.index')->with('success', 'Data pembayaran berhasil dihapus.');
    }

    public function markLunas(CompanyDebt $companyDebt)
    {
        DB::transaction(function() use ($companyDebt) {
            $paymentAmount = $companyDebt->remaining_amount;
            
            $companyDebt->update([
                'status' => 'lunas',
                'remaining_amount' => 0
            ]);

            \App\Models\Transaction::create([
                'type'           => 'debit',
                'amount'         => $paymentAmount,
                'category'       => 'pembayaran_perusahaan',
                'reference_type' => CompanyDebt::class,
                'reference_id'   => $companyDebt->id,
                'description'    => 'Pelunasan Pembayaran Perusahaan ke: ' . $companyDebt->name,
                'date'           => now(),
                'division'       => $companyDebt->division,
                'entity'         => $companyDebt->entity ?? $companyDebt->division,
            ]);
        });

        return back()->with('success', 'Pembayaran ditandai Lunas dan tercatat di kas.');
    }

    public function recordPayment(Request $request, CompanyDebt $companyDebt)
    {
        $validated = $request->validate([
            'payment_amount' => 'required|numeric|min:1|max:' . $companyDebt->remaining_amount,
        ]);

        DB::transaction(function () use ($validated, $companyDebt) {
            $newRemaining = $companyDebt->remaining_amount - $validated['payment_amount'];
            $status = $newRemaining <= 0 ? 'lunas' : 'belum_lunas';

            $companyDebt->update([
                'remaining_amount' => max(0, $newRemaining),
                'status' => $status,
            ]);

            // Recording the payment of debt: DEBIT (Money Out)
            \App\Models\Transaction::create([
                'type'           => 'debit',
                'amount'         => $validated['payment_amount'],
                'category'       => 'pembayaran_perusahaan',
                'reference_type' => CompanyDebt::class,
                'reference_id'   => $companyDebt->id,
                'description'    => 'Pembayaran Angsuran ke: ' . $companyDebt->name,
                'date'           => now(),
                'division'       => $companyDebt->division,
                'entity'         => $companyDebt->entity ?? $companyDebt->division,
            ]);
        });

        return back()->with('success', 'Angsuran pembayaran berhasil dicatat.');
    }
}
