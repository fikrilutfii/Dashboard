<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function receivable(): HasOne
    {
        return $this->hasOne(CompanyReceivable::class);
    }

    public function syncToReceivable()
    {
        if ($this->status === 'lunas' || $this->status === 'deducted') {
            if ($this->receivable) {
                $this->receivable->update([
                    'status' => 'lunas',
                    'remaining_amount' => 0,
                ]);
            }
            return;
        }

        $receivableData = [
            'kasbon_id'        => $this->id,
            'name'             => $this->employee->name ?? 'Karyawan',
            'description'      => 'Piutang Kasbon Karyawan: ' . ($this->description ?? 'Tanpa keterangan'),
            'total_amount'     => $this->amount,
            'remaining_amount' => $this->remaining_amount,
            'monthly_amount'   => $this->installment_amount,
            'due_date'         => $this->date,
            'status'           => ($this->remaining_amount < $this->amount) ? 'sebagian' : 'belum_lunas',
            'type'             => $this->installment_amount > 0 ? 'installment' : 'cash',
            'division'         => $this->employee->division,
            'entity'           => $this->employee->division,
        ];

        if ($this->receivable) {
            $this->receivable->update($receivableData);
        } else {
            $this->receivable()->create($receivableData);
        }
    }
}
