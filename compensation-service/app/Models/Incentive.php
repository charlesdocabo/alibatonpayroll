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
        'payroll_period',
        'approved_by',
        'trip_id',
        'trip_reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'incentive_date' => 'date',
        'trip_id' => 'integer',
    ];
}