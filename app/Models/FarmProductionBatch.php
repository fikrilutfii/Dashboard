<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmProductionBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number',
        'production_date',
        'farm_supplier_id',
        'live_birds_count',
        'live_birds_weight_kg',
        'buy_price_per_kg',
        'total_buy_price',
        'supplier_payment_status',
        'doa_count',
        'doa_weight_kg',
        'total_yield_weight_kg',
        'shrinkage_weight_kg',
        'shrinkage_percentage',
        'notes',
    ];

    protected $casts = [
        'production_date' => 'date',
        'live_birds_weight_kg' => 'decimal:2',
        'buy_price_per_kg' => 'decimal:2',
        'total_buy_price' => 'decimal:2',
        'doa_weight_kg' => 'decimal:2',
        'total_yield_weight_kg' => 'decimal:2',
        'shrinkage_weight_kg' => 'decimal:2',
        'shrinkage_percentage' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(FarmSupplier::class, 'farm_supplier_id');
    }

    public function items()
    {
        return $this->hasMany(FarmProductionItem::class, 'farm_production_batch_id');
    }
}
