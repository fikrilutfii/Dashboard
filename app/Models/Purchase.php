<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_number',
        'supplier_id',
        'date',
        'due_date',
        'status',
        'total_amount',
        'description',
        'entity',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'reference');
    }

    public function debt(): HasOne
    {
        return $this->hasOne(CompanyDebt::class);
    }

    public function syncToDebt()
    {
        $paid_amount = $this->debt ? ($this->debt->amount - $this->debt->remaining_amount) : 0;
        
        if ($this->status === 'lunas') {
            $new_remaining = 0;
            $status = 'lunas';
            $type = 'cash';
        } else {
            $new_remaining = max(0, $this->total_amount - $paid_amount);
            $status = $new_remaining <= 0 ? 'lunas' : ($paid_amount > 0 ? 'sebagian' : 'belum_lunas');
            $type = 'credit';
        }

        $debtData = [
            'purchase_id'      => $this->id,
            'name'             => $this->supplier->name ?? 'Supplier',
            'description'      => 'Hutang Pembelian #' . $this->purchase_number,
            'amount'           => $this->total_amount,
            'remaining_amount' => $new_remaining,
            'due_date'         => $this->due_date,
            'status'           => $status,
            'type'             => $type,
            'division'         => $this->division ?? session('division'),
            'entity'           => $this->entity ?? ($this->division ?? session('division')),
        ];

        if ($this->debt) {
            $this->debt->update($debtData);
        } else {
            $this->debt()->create($debtData);
        }
    }
}
