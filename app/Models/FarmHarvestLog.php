<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmHarvestLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'harvest_date' => 'date',
    ];

    public function batch()
    {
        return $this->belongsTo(FarmBatch::class, 'farm_batch_id');
    }

    public function coop()
    {
        return $this->belongsTo(FarmCoop::class, 'farm_coop_id');
    }

    public function harvestSales()
    {
        return $this->hasMany(FarmHarvestSale::class, 'farm_harvest_log_id');
    }
}
