<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmTransportation extends Model
{
    protected $fillable = [
        'transport_date', 'type', 'description', 'destination',
        'driver', 'vehicle_plate', 'amount', 'status', 'notes',
    ];

    protected $casts = [
        'transport_date' => 'date',
        'amount'         => 'decimal:2',
    ];

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'masuk'  => 'Supply Masuk',
            'keluar' => 'Pengiriman Keluar',
            default  => ucfirst($this->type),
        };
    }
}
