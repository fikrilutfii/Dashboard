<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'period_start',
        'period_end',
        'daily_rate',
        'working_days',
        'working_days_count',
        'basic_salary',
        'bonus',
        'kasbon_deduction',
        'overtime_hours',
        'overtime_rate',
        'overtime_pay',
        'total_salary',
        'status',
        'daily_salary', // alias for daily_rate
    ];

    protected $casts = [
        'period_start'      => 'date',
        'period_end'        => 'date',
        'daily_rate'        => 'decimal:2',
        'basic_salary'      => 'decimal:2',
        'bonus'             => 'decimal:2',
        'kasbon_deduction'  => 'decimal:2',
        'overtime_hours'    => 'decimal:2',
        'overtime_rate'     => 'decimal:2',
        'overtime_pay'      => 'decimal:2',
        'total_salary'      => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function isLunas(): bool
    {
        return in_array($this->status, ['lunas', 'paid']);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->isLunas() ? 'Lunas' : 'Belum Lunas';
    }
}
