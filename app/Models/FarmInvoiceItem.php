<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmInvoiceItem extends Model
{
    protected $fillable = ['farm_invoice_id', 'description', 'qty', 'unit', 'unit_price', 'total_price'];

    protected $casts = [
        'qty'        => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price'=> 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(FarmInvoice::class);
    }
}
