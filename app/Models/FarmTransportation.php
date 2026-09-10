<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmTransportation extends Model
{
    use HasFactory;

    protected $fillable = [
        'transport_date',
        'farm_invoice_id',
        'farm_vehicle_id',
        'driver_name',
        'destination',
        'departure_time',
        'arrival_time',
        'delivery_status',
        'bbm_cost',
        'toll_cost',
        'other_cost',
        'notes',
    ];

    protected $casts = [
        'transport_date' => 'date',
        'bbm_cost' => 'decimal:2',
        'toll_cost' => 'decimal:2',
        'other_cost' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(FarmInvoice::class, 'farm_invoice_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(FarmVehicle::class, 'farm_vehicle_id');
    }

    public function getTotalCostAttribute()
    {
        return $this->bbm_cost + $this->toll_cost + $this->other_cost;
    }
}
