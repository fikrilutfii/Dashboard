<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'farm_sender_id',
        'farm_customer_id',
        'invoice_date',
        'due_date',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function sender()
    {
        return $this->belongsTo(FarmSender::class, 'farm_sender_id');
    }

    public function customer()
    {
        return $this->belongsTo(FarmCustomer::class, 'farm_customer_id');
    }

    public function items()
    {
        return $this->hasMany(FarmInvoiceItem::class, 'farm_invoice_id');
    }

    public function payments()
    {
        return $this->hasMany(FarmInvoicePayment::class, 'farm_invoice_id');
    }

    public function transportation()
    {
        return $this->hasOne(FarmTransportation::class, 'farm_invoice_id');
    }

    // Recalculate paid_amount and status from payments
    public function recalculatePayments()
    {
        $totalPaid = $this->payments()->sum('amount');
        $this->paid_amount = $totalPaid;
        $this->remaining_amount = max(0, $this->total_amount - $totalPaid);
        
        if ($this->remaining_amount <= 0 && $this->total_amount > 0) {
            $this->status = 'lunas';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'sebagian';
        } else {
            $this->status = 'belum_lunas';
        }
        
        $this->save();
    }
}
