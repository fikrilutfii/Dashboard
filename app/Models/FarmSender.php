<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmSender extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'invoice_footer_notes',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function invoices()
    {
        return $this->hasMany(FarmInvoice::class, 'farm_sender_id');
    }
}
