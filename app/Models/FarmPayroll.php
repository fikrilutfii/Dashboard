<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmPayroll extends Model
{
    protected $fillable = [
        'employee_name', 'role', 'period_start', 'period_end',
        'basic_salary', 'allowances', 'deductions', 'net_salary',
        'status', 'paid_at', 'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end'   => 'date',
        'paid_at'      => 'date',
        'basic_salary' => 'decimal:2',
        'allowances'   => 'decimal:2',
        'deductions'   => 'decimal:2',
        'net_salary'   => 'decimal:2',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'dibayar' => 'Dibayar',
            'pending' => 'Belum Dibayar',
            default   => ucfirst($this->status),
        };
    }
}
