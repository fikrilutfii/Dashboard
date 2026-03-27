<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', // debit/credit
        'amount',
        'category',
        'reference_type',
        'reference_id',
        'description',
        'date',
        'division',
        'entity',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function reference()
    {
        return $this->morphTo();
    }
}
