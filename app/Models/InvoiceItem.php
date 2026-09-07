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

    /**
     * Quantity for documents: retain meaningful decimals, but omit trailing zeroes.
     * Examples: 427.000 -> 427, 12.500 -> 12,5, 1,250.000 -> 1.250.
     */
    public function getFormattedQuantityAttribute(): string
    {
        $formatted = number_format((float) $this->quantity, 3, ',', '.');

        if (str_contains($formatted, ',')) {
            $formatted = rtrim(rtrim($formatted, '0'), ',');
        }

        return $formatted;
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
