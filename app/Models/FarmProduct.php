<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category',
        'unit',
        'standard_price',
        'current_stock_kg',
        'current_stock_ekor',
        'notes',
    ];

    public function invoiceItems()
    {
        return $this->hasMany(FarmInvoiceItem::class, 'farm_product_id');
    }

    public function productionItems()
    {
        return $this->hasMany(FarmProductionItem::class, 'farm_product_id');
    }

    public function customPrices()
    {
        return $this->hasMany(FarmCustomerPrice::class, 'farm_product_id');
    }
}
