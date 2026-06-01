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
    public function syncStock(int $quantity, string $type = 'system', string $description = null, string $reference_type = null, int $reference_id = null)
    {
        $userId = auth()->id();
        
        if (!empty($this->shared_stock_code)) {
            // Update all products that share this stock code
            $products = self::where('shared_stock_code', $this->shared_stock_code)->get();
            foreach ($products as $p) {
                $p->increment('stock', $quantity);
                
                \App\Models\StockLog::create([
                    'product_id' => $p->id,
                    'quantity' => $quantity,
                    'new_stock' => $p->fresh()->stock,
                    'type' => $type,
                    'description' => $description ?? 'Auto-sync from shared stock code',
                    'reference_type' => $reference_type,
                    'reference_id' => $reference_id,
                    'user_id' => $userId,
                ]);
            }
        } else {
            // Update only this product
            $this->increment('stock', $quantity);
            
            \App\Models\StockLog::create([
                'product_id' => $this->id,
                'quantity' => $quantity,
                'new_stock' => $this->fresh()->stock,
                'type' => $type,
                'description' => $description ?? 'Auto-sync',
                'reference_type' => $reference_type,
                'reference_id' => $reference_id,
                'user_id' => $userId,
            ]);
        }
    }
}

