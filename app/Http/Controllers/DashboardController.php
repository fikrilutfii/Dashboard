<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Purchase;
use App\Models\Transaction;
use App\Models\Payroll;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$request->session()->has('division')) {
            return view('division-selection');
        }

        $division = $request->session()->get('division');

        if ($user->allowed_division !== 'all' && $user->allowed_division !== $division) {
            $request->session()->forget('division');
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke divisi tersebut.');
        }

        $now = Carbon::now();
        $startOfWeek = Carbon::now()->startOfWeek();

        // --- PEMBAYARAN & TAGIHAN PERCETAKAN ---
        // 1. Pembayaran Percetakan (Bulan Ini) -> Income
        $pembayaranPercetakan = Transaction::where('type', 'credit')
            ->where('division', 'percetakan')
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        // 2. Tagihan Percetakan (Bulan Ini) -> Invoices
        $tagihanPercetakan = Invoice::where('division', 'percetakan')
            ->whereMonth('invoice_date', $now->month)
            ->whereYear('invoice_date', $now->year)
            ->sum('total_amount');

        // --- PEMBAYARAN & TAGIHAN KONVEKSI (For totals) ---
        $pembayaranKonveksi = Transaction::where('type', 'credit')
            ->where('division', 'konfeksi')
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        $tagihanKonveksi = Invoice::where('division', 'konfeksi')
            ->whereMonth('invoice_date', $now->month)
            ->whereYear('invoice_date', $now->year)
            ->sum('total_amount');

        // 3. Total Pembayaran (Bulan Ini)
        $totalPembayaran = $pembayaranPercetakan + $pembayaranKonveksi;

        // 4. Total Tagihan (Bulan Ini)
        $totalTagihan = $tagihanPercetakan + $tagihanKonveksi;

        // --- KEUNTUNGAN (Pembayaran - Pengeluaran) ---
        
        // 5. Keuntungan Percetakan (Bulan Ini)
        $pengeluaranPercetakanBulanIni = \App\Models\Expense::where('division', 'percetakan')
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');
        $keuntunganPercetakan = $pembayaranPercetakan - $pengeluaranPercetakanBulanIni;

        // 6. Keuntungan Konveksi (Minggu Ini)
        $pembayaranKonveksiMingguIni = Transaction::where('type', 'credit')
            ->where('division', 'konfeksi')
            ->whereBetween('date', [$startOfWeek, $now])
            ->sum('amount');
        $pengeluaranKonveksiMingguIni = \App\Models\Expense::where('division', 'konfeksi')
            ->whereBetween('date', [$startOfWeek, $now])
            ->sum('amount');
        $keuntunganKonveksiMingguIni = $pembayaranKonveksiMingguIni - $pengeluaranKonveksiMingguIni;

        // 7. Keuntungan Konveksi (Bulan Ini)
        $pengeluaranKonveksiBulanIni = \App\Models\Expense::where('division', 'konfeksi')
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');
        $keuntunganKonveksiBulanIni = $pembayaranKonveksi - $pengeluaranKonveksiBulanIni;

        return view('dashboard', compact(
            'division', 
            'pembayaranPercetakan',
            'tagihanPercetakan',
            'totalPembayaran',
            'totalTagihan',
            'keuntunganPercetakan',
            'keuntunganKonveksiMingguIni',
            'keuntunganKonveksiBulanIni'
        ));
    }

    public function setDivision(Request $request)
    {
        $request->validate([
            'division' => 'required|in:percetakan,konfeksi',
        ]);

        $user = $request->user();

        // Access Control
        if ($user->allowed_division !== 'all' && $user->allowed_division !== $request->division) {
            return back()->with('error_division', $request->division);
        }

        $request->session()->put('division', $request->division);

        return redirect()->route('dashboard');
    }
    public function switchDivision(Request $request)
    {
        $request->session()->forget('division');
        return redirect()->route('dashboard');
    }
}
