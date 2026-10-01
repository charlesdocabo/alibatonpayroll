<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsReport extends Model
{
    protected $fillable = [
        'report_type',
        'total_employees',
        'total_payroll',
        'total_benefits',
        'total_claims',
        'total_incentives',
        'active_employees',
        'pending_claims',
        'report_date',
    ];

    protected $casts = [
        'total_employees' => 'integer',
        'total_payroll' => 'decimal:2',
        'total_benefits' => 'decimal:2',
        'total_claims' => 'decimal:2',
        'total_incentives' => 'decimal:2',
        'active_employees' => 'integer',
        'pending_claims' => 'integer',
        'report_date' => 'date',
    ];
}