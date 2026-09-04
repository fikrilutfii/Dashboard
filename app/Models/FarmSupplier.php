<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FarmSupplier extends Model
{
    protected $fillable = ['name', 'type', 'phone', 'address', 'contact_person', 'notes'];

    public function expenses(): HasMany
    {
        return $this->hasMany(FarmExpense::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'doc'   => 'DOC / Bibit Ayam',
            'pakan' => 'Pakan Ternak',
            'obat'  => 'Obat & Vaksin',
            'alat'  => 'Peralatan',
            default => 'Lain-lain',
        };
    }
}
