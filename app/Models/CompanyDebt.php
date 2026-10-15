<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class CompanyDebt extends Model
{
    use HasFactory, \App\Traits\LogsActivity;

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    protected $fillable = [
        'name',
        'description',
        'amount',
        'remaining_amount',
        'monthly_amount',
        'due_date',
        'status',
        'type',
        'division',
        'entity',
    ];

    public function getPaidAmountAttribute(): float
    {
        return $this->amount - ($this->remaining_amount ?? $this->amount);
    }

    public function getPaymentPercentageAttribute(): float
    {
        if ($this->amount <= 0) return 0;
        return ($this->paid_amount / $this->amount) * 100;
    }

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function isLunas(): bool
    {
        return $this->status === 'lunas';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'lunas' ? 'Lunas' : 'Belum Lunas';
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'cash' ? 'Tunai' : 'Cicilan';
    }

    /**
     * Total kewajiban pembayaran untuk satu bulan.
     *
     * Hutang dengan angsuran memakai nominal angsurannya (maksimal sisa hutang).
     * Hutang tanpa angsuran dihitung penuh saat tanggal jatuh temponya berada
     * pada bulan yang diminta.
     */
    public static function monthlyDueTotal(?string $division, Carbon $date): float
    {
        return (float) static::query()
            ->when($division, fn ($query) => $query->where('division', $division))
            ->where('status', '!=', 'lunas')
            ->get(['remaining_amount', 'monthly_amount', 'due_date'])
            ->sum(function (self $debt) use ($date) {
                $remaining = (float) $debt->remaining_amount;
                $monthlyAmount = (float) $debt->monthly_amount;

                if ($remaining <= 0) {
                    return 0;
                }

                if ($monthlyAmount > 0) {
                    return min($monthlyAmount, $remaining);
                }

                return $debt->due_date
                    && $debt->due_date->isSameMonth($date)
                    ? $remaining
                    : 0;
            });
    }

    /**
     * Mengambil daftar rinci seluruh kewajiban pembayaran yang harus dibayar bulan ini,
     * diurutkan berdasarkan tanggal jatuh tempo / urutan tanggal pembayaran.
     */
    public static function monthlyDueItems(?string $division, Carbon $date): \Illuminate\Support\Collection
    {
        $endOfMonth = $date->copy()->endOfMonth();
        $now = Carbon::now();

        return static::query()
            ->when($division, fn ($query) => $query->where('division', $division))
            ->where('status', '!=', 'lunas')
            ->where('remaining_amount', '>', 0)
            ->with(['purchase.supplier', 'payroll.employee'])
            ->get()
            ->filter(function (self $debt) use ($date, $endOfMonth) {
                $remaining = (float) $debt->remaining_amount;
                $monthlyAmount = (float) $debt->monthly_amount;

                if ($remaining <= 0) {
                    return false;
                }

                // Jika memiliki cicilan bulanan, selalu masuk daftar pembayaran bulan ini
                if ($monthlyAmount > 0) {
                    return true;
                }

                // Jika bukan cicilan bulanan, cek apakah jatuh tempo di bulan ini atau sudah overdue
                if ($debt->due_date) {
                    return $debt->due_date->lte($endOfMonth);
                }

                return true;
            })
            ->map(function (self $debt) use ($date, $endOfMonth, $now) {
                $remaining = (float) $debt->remaining_amount;
                $monthlyAmount = (float) $debt->monthly_amount;
                $dueAmount = ($monthlyAmount > 0) ? min($monthlyAmount, $remaining) : $remaining;

                // Tentukan tanggal jatuh tempo / estimasi tanggal bulan ini
                $scheduleDate = $debt->due_date ? $debt->due_date->copy() : $endOfMonth->copy();
                
                // Hitung selisih hari dari hari ini
                $daysDiff = $now->startOfDay()->diffInDays($scheduleDate->startOfDay(), false);
                $isOverdue = $daysDiff < 0;
                $isToday = $daysDiff === 0;

                $creditorName = $debt->name;
                if (empty($creditorName)) {
                    if ($debt->purchase && $debt->purchase->supplier) {
                        $creditorName = $debt->purchase->supplier->name;
                    } elseif ($debt->payroll && $debt->payroll->employee) {
                        $creditorName = 'Gaji: ' . $debt->payroll->employee->name;
                    } else {
                        $creditorName = 'Kewajiban Pembayaran #' . $debt->id;
                    }
                }

                $description = $debt->description;
                if (empty($description) && $debt->purchase) {
                    $description = 'Pembelian PO #' . ($debt->purchase->purchase_number ?: $debt->purchase->id);
                }

                return [
                    'id'               => $debt->id,
                    'name'             => $creditorName,
                    'description'      => $description ?: 'Pembayaran operasional',
                    'type'             => $debt->type,
                    'type_label'       => $monthlyAmount > 0 ? 'Angsuran / Cicilan' : 'Pelunasan Penuh',
                    'schedule_date'    => $scheduleDate,
                    'date_formatted'   => $scheduleDate->format('d M Y'),
                    'day_formatted'    => $scheduleDate->format('d'),
                    'month_formatted'  => $scheduleDate->format('M'),
                    'due_amount'       => $dueAmount,
                    'remaining_amount' => $remaining,
                    'total_amount'     => (float) $debt->amount,
                    'status'           => $debt->status,
                    'is_overdue'       => $isOverdue,
                    'is_today'         => $isToday,
                    'days_diff'        => $daysDiff,
                    'entity'           => $debt->entity,
                    'purchase_id'      => $debt->purchase_id,
                ];
            })
            ->sortBy('schedule_date')
            ->values();
    }
}
