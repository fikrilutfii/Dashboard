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

        if ($division === 'peternakan') {
            return redirect()->route('farm.dashboard');
        }

        // Inisialisasi variabel statistik default
        $pembayaranPercetakan = 0;
        $tagihanPercetakan = 0;
        $totalPembayaran = 0;
        $totalTagihan = 0;
        $keuntunganPercetakan = 0;
        $keuntunganKonveksiMingguIni = 0;
        $keuntunganKonveksiBulanIni = 0;

        // Hanya hitung statistik jika bukan limited_invoice
        if ($user->role !== 'limited_invoice') {
            $now = Carbon::now();
            $startOfWeek = Carbon::now()->startOfWeek();

            // --- PEMBAYARAN & TAGIHAN PERCETAKAN ---
            // 1. Pembayaran Percetakan (Bulan Ini) -> Pengeluaran / Transaksi Debit Bulan Ini
            $pembayaranPercetakan = Transaction::where('type', 'debit')
                ->where('division', 'percetakan')
                ->whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->sum('amount');

            // 2. Total Tagihan Percetakan (Seluruhnya) -> sisa nilai invoice.
            // Invoice menjadi sumber data utama agar tagihan lama yang belum memiliki
            // record CompanyReceivable tetap masuk ke dashboard.
            $tagihanPercetakan = Invoice::where('division', 'percetakan')
                ->where('status', '!=', 'lunas')
                ->sum(\Illuminate\Support\Facades\DB::raw('total_amount - COALESCE(paid_amount, 0)'));

            // Tagihan Bulan Ini -> Faktur belum lunas yang JATUH TEMPO bulan ini.
            $tagihanPercetakanBulanIni = \App\Models\Invoice::where('division', 'percetakan')
                ->where('status', '!=', 'lunas')
                ->whereMonth('due_date', $now->month)
                ->whereYear('due_date', $now->year)
                ->sum('total_amount');

            // --- PEMBAYARAN & TAGIHAN KONVEKSI (For totals) ---
            $pembayaranKonveksi = Transaction::where('type', 'debit')
                ->where('division', 'konfeksi')
                ->whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->sum('amount');

            $tagihanKonveksi = \App\Models\Invoice::where('division', 'konfeksi')
                ->where('status', '!=', 'lunas')
                ->whereMonth('due_date', $now->month)
                ->whereYear('due_date', $now->year)
                ->sum('total_amount');

            // 3. Total Pembayaran Percetakan (Seluruhnya) -> Company Debt (Sisa Hutang Seluruhnya)
            $totalPembayaran = \App\Models\CompanyDebt::where('division', 'percetakan')
                ->whereIn('status', ['belum_lunas', 'sebagian'])
                ->sum('remaining_amount');

            // 4. Total Tagihan (Bulan Ini) -> Penjualan Bulan Ini
            $totalTagihan = $tagihanPercetakanBulanIni + $tagihanKonveksi;

            // --- KEUNTUNGAN (Pemasukan - Pengeluaran) ---
            
            // Pemasukan Percetakan
            $pemasukanPercetakanBulanIni = Transaction::where('type', 'credit')
                ->where('division', 'percetakan')
                ->whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->sum('amount');

            // 5. Keuntungan Percetakan (Bulan Ini)
            $pengeluaranPercetakanBulanIni = Transaction::where('type', 'debit')
                ->where('division', 'percetakan')
                ->whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->sum('amount');
            $keuntunganPercetakan = $pemasukanPercetakanBulanIni - $pengeluaranPercetakanBulanIni;

            // 6. Keuntungan Konveksi (Minggu Ini)
            $pemasukanKonveksiMingguIni = Transaction::where('type', 'credit')
                ->where('division', 'konfeksi')
                ->whereBetween('date', [$startOfWeek, $now])
                ->sum('amount');
            $pengeluaranKonveksiMingguIni = Transaction::where('type', 'debit')
                ->where('division', 'konfeksi')
                ->whereBetween('date', [$startOfWeek, $now])
                ->sum('amount');
            $keuntunganKonveksiMingguIni = $pemasukanKonveksiMingguIni - $pengeluaranKonveksiMingguIni;

            // 7. Keuntungan Konveksi (Bulan Ini)
            $pemasukanKonveksiBulanIni = Transaction::where('type', 'credit')
                ->where('division', 'konfeksi')
                ->whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->sum('amount');
            $pengeluaranKonveksiBulanIni = Transaction::where('type', 'debit')
                ->where('division', 'konfeksi')
                ->whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->sum('amount');
            $keuntunganKonveksiBulanIni = $pemasukanKonveksiBulanIni - $pengeluaranKonveksiBulanIni;
        }

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
            'division' => 'required|in:percetakan,konfeksi,peternakan',
        ]);

        $user = $request->user();

        // Access Control
        if ($user->allowed_division !== 'all' && $user->allowed_division !== $request->division) {
            return back()->with('error_division', $request->division);
        }

        $request->session()->put('division', $request->division);

        if ($request->division === 'peternakan') {
            return redirect()->route('farm.dashboard');
        }

        return redirect()->route('dashboard');
    }
    public function switchDivision(Request $request)
    {
        $request->session()->forget('division');
        return redirect()->route('dashboard');
    }
}
