<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'creditor_name',
        'amount',
        'remaining_amount',
        'type', // cash, credit
        'description',
        'status', // open, paid
    ];

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'reference');
    }
}
