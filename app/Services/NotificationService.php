<?php

namespace App\Services;

use App\Models\CompanyDebt;
use App\Models\Invoice;
use App\Models\Payroll;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Mengumpulkan semua notifikasi aktif sistem untuk ditampilkan di topbar.
     */
    public function getNotifications(?User $user): array
    {
        $items = collect();
        $now = Carbon::now();
        $sevenDaysAhead = $now->copy()->addDays(7);

        // 1. NOTIFIKASI HUTANG / TAGIHAN JATUH TEMPO
        try {
            $debtsDue = CompanyDebt::where('status', '!=', 'lunas')
                ->where('remaining_amount', '>', 0)
                ->where(function ($q) use ($sevenDaysAhead) {
                    $q->whereNull('due_date')
                      ->orWhere('due_date', '<=', $sevenDaysAhead);
                })
                ->orderBy('due_date', 'asc')
                ->take(5)
                ->get();

            foreach ($debtsDue as $debt) {
                $dueDate = $debt->due_date;
                $isOverdue = $dueDate && $dueDate->lt($now->startOfDay());
                $isToday = $dueDate && $dueDate->isSameDay($now);
                
                $amountDue = $debt->monthly_amount > 0 
                    ? min((float)$debt->monthly_amount, (float)$debt->remaining_amount) 
                    : (float)$debt->remaining_amount;

                $items->push([
                    'id'          => 'debt_' . $debt->id,
                    'category'    => 'debt',
                    'category_label' => 'Hutang Usaha',
                    'title'       => 'Tagihan: ' . ($debt->name ?: 'Kewajiban Pembayaran'),
                    'message'     => 'Wajib bayar Rp ' . number_format($amountDue, 0, ',', '.') . ($dueDate ? ' jatuh tempo ' . $dueDate->format('d M') : ' bulan ini'),
                    'badge'       => $isOverdue ? 'Jatuh Tempo' : ($isToday ? 'Hari Ini' : 'Mendekati'),
                    'badge_color' => $isOverdue ? 'rose' : ($isToday ? 'amber' : 'teal'),
                    'time'        => $dueDate ? $dueDate->diffForHumans() : 'Bulan ini',
                    'url'         => route('company-debts.index'),
                    'icon'        => 'currency',
                    'is_urgent'   => $isOverdue || $isToday,
                ]);
            }
        } catch (\Throwable $e) {
            // silent catch
        }

        // 2. NOTIFIKASI FAKTUR PENJUALAN KLIEN BELUM LUNAS (Jatuh Tempo / Menunggak)
        try {
            $invoicesDue = Invoice::where('status', 'belum_lunas')
                ->where('total_amount', '>', 0)
                ->where(function ($q) use ($sevenDaysAhead) {
                    $q->whereNull('due_date')
                      ->orWhere('due_date', '<=', $sevenDaysAhead);
                })
                ->with('customer')
                ->orderBy('due_date', 'asc')
                ->take(5)
                ->get();

            foreach ($invoicesDue as $inv) {
                $dueDate = $inv->due_date;
                $isOverdue = $dueDate && $dueDate->lt($now->startOfDay());
                $isToday = $dueDate && $dueDate->isSameDay($now);
                $remaining = max(0, (float)$inv->total_amount - (float)$inv->paid_amount);

                $items->push([
                    'id'          => 'invoice_' . $inv->id,
                    'category'    => 'invoice',
                    'category_label' => 'Faktur Klien',
                    'title'       => 'Faktur ' . ($inv->faktur_number ?: $inv->invoice_number),
                    'message'     => ($inv->customer?->name ?? 'Klien') . ' sisa Rp ' . number_format($remaining, 0, ',', '.'),
                    'badge'       => $isOverdue ? 'Menunggak' : ($isToday ? 'Tempo Hari Ini' : 'Belum Lunas'),
                    'badge_color' => $isOverdue ? 'rose' : ($isToday ? 'amber' : 'blue'),
                    'time'        => $dueDate ? $dueDate->diffForHumans() : $inv->created_at->diffForHumans(),
                    'url'         => route('invoices.show', $inv->id),
                    'icon'        => 'invoice',
                    'is_urgent'   => $isOverdue || $isToday,
                ]);
            }
        } catch (\Throwable $e) {
            // silent catch
        }

        // 3. NOTIFIKASI GAJI PENDING
        try {
            if ($user && in_array($user->role, ['admin', 'owner'])) {
                $pendingPayrollCount = Payroll::where('status', '!=', 'lunas')->count();
                if ($pendingPayrollCount > 0) {
                    $items->push([
                        'id'          => 'payroll_pending',
                        'category'    => 'payroll',
                        'category_label' => 'Penggajian',
                        'title'       => 'Gaji Belum Lunas',
                        'message'     => "Terdapat {$pendingPayrollCount} data penggajian karyawan belum lunas.",
                        'badge'       => "{$pendingPayrollCount} Karyawan",
                        'badge_color' => 'amber',
                        'time'        => 'Bulan berjalan',
                        'url'         => route('payrolls.index'),
                        'icon'        => 'payroll',
                        'is_urgent'   => false,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // silent catch
        }

        // 4. NOTIFIKASI AKTIVITAS TERKINI (Jika notifikasi lain sedikit)
        if ($items->count() < 3) {
            try {
                $recentLogs = ActivityLog::latest()->take(3)->get();
                foreach ($recentLogs as $log) {
                    $items->push([
                        'id'          => 'log_' . $log->id,
                        'category'    => 'activity',
                        'category_label' => 'Aktivitas Sistem',
                        'title'       => $log->user ? $log->user->name : 'Sistem',
                        'message'     => $log->description,
                        'badge'       => strtoupper($log->activity),
                        'badge_color' => 'blue',
                        'time'        => $log->created_at->diffForHumans(),
                        'url'         => route('activity-logs.index'),
                        'icon'        => 'bell',
                        'is_urgent'   => false,
                    ]);
                }
            } catch (\Throwable $e) {
                // silent catch
            }
        }

        $urgentCount = $items->where('is_urgent', true)->count();
        $totalCount = $items->count();

        return [
            'total_count'  => $totalCount,
            'urgent_count' => $urgentCount,
            'items'        => $items,
        ];
    }
}
