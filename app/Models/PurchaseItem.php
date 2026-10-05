<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'item_name',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function getQuantityAttribute($value)
    {
        if ($value === null) return $value;
        $float = (float) $value;
        return $float == (int) $float ? (int) $float : $float;
    }

    public function getFormattedQuantityAttribute(): string
    {
        $val = (float) $this->quantity;
        if ($val == (int) $val) {
            return (string) (int) $val;
        }
        $formatted = number_format($val, 3, ',', '.');
        return rtrim(rtrim($formatted, '0'), ',');
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
}
