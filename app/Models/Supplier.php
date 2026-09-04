<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory, \App\Traits\LogsActivity;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'division',
    ];
}
