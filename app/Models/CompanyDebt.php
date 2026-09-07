<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class CompanyDebt extends Model
{
    use HasFactory;

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
}
