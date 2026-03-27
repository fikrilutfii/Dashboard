<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDebt extends Model
{
    use HasFactory;

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
}
