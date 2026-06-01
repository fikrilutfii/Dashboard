<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'saved_salary',
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
        'saved_salary'      => 'decimal:2',
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

    public function debt(): HasOne
    {
        return $this->hasOne(CompanyDebt::class);
    }

    public function syncToDebt()
    {
        if ($this->isLunas()) {
            if ($this->debt) {
                $this->debt->update([
                    'status' => 'lunas',
                    'remaining_amount' => 0,
                ]);
            }
            return;
        }

        $debtData = [
            'payroll_id'       => $this->id,
            'name'             => 'Gaji ' . ($this->employee->name ?? 'Karyawan'),
            'description'      => 'Kewajiban Gaji Periode ' . $this->period_start->format('d/m') . ' - ' . $this->period_end->format('d/m/Y'),
            'amount'           => $this->total_salary,
            'remaining_amount' => $this->total_salary,
            'due_date'         => $this->period_end,
            'status'           => 'belum_lunas',
            'type'             => 'cash',
            'division'         => $this->employee->division,
            'entity'           => $this->employee->division,
        ];

        if ($this->debt) {
            $this->debt->update($debtData);
        } else {
            $this->debt()->create($debtData);
        }
    }
}
