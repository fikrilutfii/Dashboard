<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmFeedLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'log_date' => 'date',
    ];

    public function batch()
    {
        return $this->belongsTo(FarmBatch::class, 'farm_batch_id');
    }

    public function coop()
    {
        return $this->belongsTo(FarmCoop::class, 'farm_coop_id');
    }
}
