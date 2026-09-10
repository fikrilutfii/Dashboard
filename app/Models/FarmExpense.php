<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_date',
        'category',
        'subcategory',
        'description',
        'amount',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'operasional_rpa' => 'Operasional RPA',
            'administrasi' => 'Administrasi & Umum',
            'armada_umum' => 'Armada Kendaraan (Servis/Pajak)',
            default => 'Lain-lain',
        };
    }
}
