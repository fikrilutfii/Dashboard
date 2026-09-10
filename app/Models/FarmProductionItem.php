<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmProductionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_production_batch_id',
        'farm_product_id',
        'qty_ekor',
        'weight_kg',
        'notes',
    ];

    protected $casts = [
        'weight_kg' => 'decimal:2',
    ];

    public function batch()
    {
        return $this->belongsTo(FarmProductionBatch::class, 'farm_production_batch_id');
    }

    public function product()
    {
        return $this->belongsTo(FarmProduct::class, 'farm_product_id');
    }
}
