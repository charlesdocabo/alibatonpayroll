<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    protected $fillable = [
        'employee_id',
        'benefit_type',
        'provider',
        'coverage',
        'membership_number',
        'amount',
        'start_date',
        'end_date',
        'status',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'coverage' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}