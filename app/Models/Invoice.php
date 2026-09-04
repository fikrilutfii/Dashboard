<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\LogsActivity;

    protected $fillable = [
        'invoice_number',
        'faktur_number',
        'customer_id',
        'invoice_date',
        'due_date',
        'total_amount',
        'paid_amount', // New
        'status',
        'division',
        'surat_jalan_number',
        'entity',
        'payment_method',
        'tenure',
        'is_printed',
    ];

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'reference');
    }

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'tenure' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(InvoiceLog::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'lunas';
    }

    public function receivable(): HasOne
    {
        return $this->hasOne(CompanyReceivable::class);
    }

    public function syncToReceivable()
    {
        if ($this->status === 'lunas') {
            if ($this->receivable) {
                $this->receivable->update([
                    'status' => 'lunas',
                    'remaining_amount' => 0,
                ]);
            }
            return;
        }

        $receivableData = [
            'invoice_id'       => $this->id,
            'name'             => $this->customer->name ?? 'Customer',
            'description'      => 'Tagihan Invoice #' . ($this->faktur_number ?? $this->invoice_number),
            'total_amount'     => $this->total_amount,
            'remaining_amount' => $this->total_amount - $this->paid_amount,
            'monthly_amount'   => $this->tenure > 0 ? $this->total_amount / $this->tenure : 0,
            'due_date'         => $this->due_date,
            'status'           => $this->paid_amount > 0 ? 'sebagian' : 'belum_lunas',
            'type'             => $this->payment_method === 'credit' ? 'installment' : 'cash',
            'division'         => $this->division,
            'entity'           => $this->entity ?? $this->division,
        ];

        if ($this->receivable) {
            $this->receivable->update($receivableData);
        } else {
            $this->receivable()->create($receivableData);
        }
    }

    public static function generateFakturNumber($division)
    {
        // Try to get next sequential number by looking at the last invoice's faktur_number
        $lastInvoice = self::where('division', $division)
            ->whereNotNull('faktur_number')
            ->where('faktur_number', '<>', '')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = $lastInvoice->faktur_number;
            
            // If it's numeric, increment it
            if (is_numeric($lastNumber)) {
                return (int)$lastNumber + 1;
            }
            
            // If it matches pattern with digits at the end (e.g. INV/2026/001)
            if (preg_match('/^(.*?)([0-9]+)$/', $lastNumber, $matches)) {
                $prefix = $matches[1];
                $number = $matches[2];
                $newNumber = str_pad((int)$number + 1, strlen($number), '0', STR_PAD_LEFT);
                return $prefix . $newNumber;
            }
        }

        // Fallback to 936001
        return 936001;
    }
}
