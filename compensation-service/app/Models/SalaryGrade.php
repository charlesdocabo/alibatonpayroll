<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryGrade extends Model
{
    protected $fillable = [
        'grade_name',
        'description',
        'minimum_salary',
        'maximum_salary',
        'effective_date',
    ];

    protected $casts = [
        'minimum_salary' => 'decimal:2',
        'maximum_salary' => 'decimal:2',
        'effective_date' => 'date',
    ];
}