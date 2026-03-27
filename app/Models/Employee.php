<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'division',
        'salary_base',
        'overtime_rate',
    ];

    public function kasbons(): HasMany
    {
        return $this->hasMany(Kasbon::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function getAttendanceCountInRange(string $start, string $end): int
    {
        return $this->attendances()
            ->whereBetween('date', [$start, $end])
            ->where('status', 'masuk')
            ->count();
    }
}
