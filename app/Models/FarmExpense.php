<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmExpense extends Model
{
    protected $fillable = [
        'expense_date', 'category', 'description', 'amount',
        'payment_method', 'farm_supplier_id', 'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(FarmSupplier::class, 'farm_supplier_id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'doc'           => 'DOC / Bibit',
            'pakan'         => 'Pakan',
            'obat'          => 'Obat & Vaksin',
            'listrik'       => 'Listrik',
            'air'           => 'Air',
            'alat'          => 'Peralatan',
            'transportasi'  => 'Transportasi',
            'gaji'          => 'Gaji',
            default         => 'Lain-lain',
        };
    }
}
