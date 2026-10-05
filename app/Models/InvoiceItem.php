<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_code',
        'item_name',
        'specification',
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

    /**
     * Quantity for documents: retain meaningful decimals, but omit trailing zeroes.
     * Examples: 1.000 -> 1, 427.000 -> 427, 12.500 -> 12,5.
     */
    public function getFormattedQuantityAttribute(): string
    {
        $val = (float) $this->quantity;
        if ($val == (int) $val) {
            return (string) (int) $val;
        }
        $formatted = number_format($val, 3, ',', '.');
        return rtrim(rtrim($formatted, '0'), ',');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
