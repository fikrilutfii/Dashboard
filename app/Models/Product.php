<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'unit',
        'price',
        'division',
        'stock',
        'shared_stock_code',
    ];

    /**
     * Sync stock for this product and any other product sharing the same code.
     * $quantity can be negative (for sales) or positive (for purchases).
     */
    public function syncStock(int $quantity)
    {
        if (!empty($this->shared_stock_code)) {
            // Update all products that share this stock code
            self::where('shared_stock_code', $this->shared_stock_code)->increment('stock', $quantity);
        } else {
            // Update only this product
            $this->increment('stock', $quantity);
        }
    }
}
