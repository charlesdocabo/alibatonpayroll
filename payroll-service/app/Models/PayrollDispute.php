<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id',
        'employee_id',
        'dispute_type',
        'subject',
        'description',
        'claimed_amount',
        'status',
        'hr_response',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'claimed_amount' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
