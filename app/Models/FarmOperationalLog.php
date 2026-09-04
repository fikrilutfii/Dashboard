<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmOperationalLog extends Model
{
    protected $fillable = [
        'farm_coop_id', 'log_date', 'population', 'mortality',
        'feed_kg', 'avg_weight', 'age_days', 'vaccine_notes', 'notes',
    ];

    protected $casts = [
        'log_date'   => 'date',
        'feed_kg'    => 'decimal:2',
        'avg_weight' => 'decimal:3',
    ];

    public function coop(): BelongsTo
    {
        return $this->belongsTo(FarmCoop::class, 'farm_coop_id');
    }

    // FCR = Feed Conversion Ratio (simplified approximation)
    public function getFcrAttribute(): ?float
    {
        if ($this->avg_weight && $this->age_days && $this->age_days > 0) {
            return round($this->feed_kg / ($this->avg_weight * max(1, $this->population)), 3);
        }
        return null;
    }
}
