<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kasbon extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type', // staff_kasbon, personal_credit, personal_loan
        'amount',
        'remaining_amount',
        'installment_amount',
        'date',
        'status', // open, paid, deducted
        'description',
    ];

    public function repayments()
    {
        return $this->hasMany(KasbonRepayment::class);
    }

    protected $casts = [
        'date' => 'date',
    ];

    public function getPaidAmountAttribute(): float
    {
        return $this->amount - $this->remaining_amount;
    }

    public function getPaymentPercentageAttribute(): float
    {
        if ($this->amount <= 0) return 0;
        return ($this->paid_amount / $this->amount) * 100;
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
