<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmCustomerPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_customer_id',
        'farm_product_id',
        'custom_price',
    ];

    public function customer()
    {
        return $this->belongsTo(FarmCustomer::class, 'farm_customer_id');
    }

    public function product()
    {
        return $this->belongsTo(FarmProduct::class, 'farm_product_id');
    }
}
