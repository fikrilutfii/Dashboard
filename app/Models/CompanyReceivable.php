<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyReceivable extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'total_amount',
        'remaining_amount',
        'due_date',
        'status',
        'type',
        'division',
        'entity',
    ];

    protected $casts = [
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function isLunas(): bool
    {
        return $this->status === 'lunas';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'lunas' => 'Lunas',
            'sebagian' => 'Sebagian',
            default => 'Belum Lunas',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'installment' ? 'Cicilan' : 'Tunai';
    }

    public function getPaidAmountAttribute(): float
    {
        return $this->total_amount - $this->remaining_amount;
    }
}
