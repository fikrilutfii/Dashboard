<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'vehicle_type',
        'default_driver',
        'notes',
    ];

    public function transportations()
    {
        return $this->hasMany(FarmTransportation::class, 'farm_vehicle_id');
    }
}
