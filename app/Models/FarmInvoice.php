<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmInvoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number', 'farm_customer_id', 'farm_coop_id',
        'invoice_date', 'due_date', 'total_amount', 'paid_amount',
        'status', 'payment_method', 'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date'     => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount'  => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(FarmCustomer::class, 'farm_customer_id');
    }

    public function coop(): BelongsTo
    {
        return $this->belongsTo(FarmCoop::class, 'farm_coop_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(FarmInvoiceItem::class);
    }

    public function harvestSales(): HasMany
    {
        return $this->hasMany(FarmHarvestSale::class, 'farm_invoice_id');
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, (float)$this->total_amount - (float)$this->paid_amount);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'lunas'      => 'Lunas',
            'sebagian'   => 'Sebagian',
            'belum_lunas'=> 'Belum Lunas',
            default      => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'lunas'      => 'emerald',
            'sebagian'   => 'amber',
            'belum_lunas'=> 'rose',
            default      => 'zinc',
        };
    }
}
