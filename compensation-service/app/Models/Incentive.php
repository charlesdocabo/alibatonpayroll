<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incentive extends Model
{
    protected $fillable = [
        'employee_id',
        'incentive_type',
        'description',
        'amount',
        'incentive_date',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'incentive_date' => 'date',
    ];
}