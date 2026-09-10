<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'salary_type',
        'daily_rate',
        'piece_rate_per_kg',
        'piece_rate_per_ekor',
        'monthly_salary',
        'phone',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function payrolls()
    {
        return $this->hasMany(FarmPayroll::class, 'farm_employee_id');
    }
}
