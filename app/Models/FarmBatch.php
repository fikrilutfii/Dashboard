<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmBatch extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'entry_date' => 'date',
        'target_harvest_date' => 'date',
    ];

    public function coop()
    {
        return $this->belongsTo(FarmCoop::class, 'farm_coop_id');
    }

    public function feedLogs()
    {
        return $this->hasMany(FarmFeedLog::class, 'farm_batch_id');
    }

    public function healthLogs()
    {
        return $this->hasMany(FarmHealthLog::class, 'farm_batch_id');
    }

    public function vaccineSchedules()
    {
        return $this->hasMany(FarmVaccineSchedule::class, 'farm_batch_id');
    }

    public function productionLogs()
    {
        return $this->hasMany(FarmProductionLog::class, 'farm_batch_id');
    }

    public function harvestLogs()
    {
        return $this->hasMany(FarmHarvestLog::class, 'farm_batch_id');
    }

    // Hitung umur ayam dalam hari
    public function getAgeDaysAttribute()
    {
        if (!$this->entry_date) return 0;
        return (int) round($this->entry_date->diffInDays(now()));
    }

    // Hitung FCR otomatis (Total Pakan (kg) / Total Panen (kg))
    public function calculateFcr()
    {
        $totalFeedKg = $this->feedLogs()->sum('quantity_kg');
        $totalHarvestKg = $this->harvestLogs()->sum('total_weight_kg');

        if ($totalHarvestKg <= 0) {
            return 0;
        }

        return round($totalFeedKg / $totalHarvestKg, 2);
    }
}
