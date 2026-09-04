<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FarmCoop extends Model
{
    protected $fillable = ['name', 'capacity', 'location', 'status', 'notes'];

    public function invoices(): HasMany
    {
        return $this->hasMany(FarmInvoice::class);
    }

    public function operationalLogs(): HasMany
    {
        return $this->hasMany(FarmOperationalLog::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(FarmBatch::class, 'farm_coop_id');
    }

    public function activeBatch(): HasOne
    {
        return $this->hasOne(FarmBatch::class, 'farm_coop_id')->where('status', 'aktif')->latestOfMany();
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'aktif'       => 'Aktif',
            'pemeliharaan'=> 'Pemeliharaan',
            'non_aktif'   => 'Tidak Aktif',
            default       => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'aktif'       => 'emerald',
            'pemeliharaan'=> 'amber',
            'non_aktif'   => 'zinc',
            default       => 'zinc',
        };
    }

    public function currentPopulation(): int
    {
        $batch = $this->activeBatch;
        return $batch ? $batch->current_population : 0;
    }
}
