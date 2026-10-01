<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'position',
        'department',
        'salary',
        'sss_number',
        'philhealth_number',
        'pagibig_number',
        'status',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
    ];

    /**
     * Use employee_id for route model binding.
     *
     * Example:
     * /api/employees/EMP001
     */
    public function getRouteKeyName()
    {
        return 'employee_id';
    }
}