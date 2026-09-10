<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmPayroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_employee_id',
        'employee_name',
        'role',
        'salary_type',
        'period_start',
        'period_end',
        'work_days',
        'total_kg_produced',
        'total_ekor_produced',
        'basic_salary',
        'overtime_pay',
        'allowances',
        'deductions',
        'net_salary',
        'status',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'paid_at' => 'date',
        'total_kg_produced' => 'decimal:2',
        'basic_salary' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(FarmEmployee::class, 'farm_employee_id');
    }
}
