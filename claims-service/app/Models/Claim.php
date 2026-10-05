<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'employee_id',
        'claim_type',
        'description',
        'amount',
        'claim_date',
        'status',
        'approved_by',
        'approval_notes',
        'approved_at',
        'return_reason',
        'receipt_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'claim_date' => 'date',
        'approved_at' => 'datetime',
    ];
}