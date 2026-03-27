<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KasbonRepayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'kasbon_id',
        'amount',
        'date',
        'method', // payroll_deduction, cash, transfer
        'description',
    ];

    public function kasbon(): BelongsTo
    {
        return $this->belongsTo(Kasbon::class);
    }
}
