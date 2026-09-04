<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FarmCustomer extends Model
{
    protected $fillable = ['name', 'phone', 'address', 'city', 'contact_person', 'notes'];

    public function invoices(): HasMany
    {
        return $this->hasMany(FarmInvoice::class);
    }
}
