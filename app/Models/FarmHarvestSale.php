<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmHarvestSale extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function harvestLog()
    {
        return $this->belongsTo(FarmHarvestLog::class, 'farm_harvest_log_id');
    }

    public function invoice()
    {
        return $this->belongsTo(FarmInvoice::class, 'farm_invoice_id');
    }
}
