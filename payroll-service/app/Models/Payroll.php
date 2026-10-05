<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'basic_salary',
        'overtime_hours',
        'overtime_pay',
        'allowances',
        'incentives',
        'sss_deduction',
        'philhealth_deduction',
        'pagibig_deduction',
        'other_deductions',
        'gross_pay',
        'total_deductions',
        'net_pay',
        'pay_date',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'allowances' => 'decimal:2',
        'incentives' => 'decimal:2',
        'sss_deduction' => 'decimal:2',
        'philhealth_deduction' => 'decimal:2',
        'pagibig_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'gross_pay' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'pay_date' => 'date',
    ];
}