<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmSupplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'farm_location',
        'phone',
        'address',
        'contact_person',
        'notes',
    ];

    public function productionBatches()
    {
        return $this->hasMany(FarmProductionBatch::class, 'farm_supplier_id');
    }
}
