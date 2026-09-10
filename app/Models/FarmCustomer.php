<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'city',
        'contact_person',
        'notes',
    ];

    public function invoices()
    {
        return $this->hasMany(FarmInvoice::class, 'farm_customer_id');
    }

    public function customPrices()
    {
        return $this->hasMany(FarmCustomerPrice::class, 'farm_customer_id');
    }
}
