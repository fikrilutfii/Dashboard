<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'division',
        'entity',
        'type',
        'category',
        'description',
        'supplier_name',
        'item_name',
        'quantity',
        'unit_price',
        'amount',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'unit_price' => 'decimal:2',
    ];
}
