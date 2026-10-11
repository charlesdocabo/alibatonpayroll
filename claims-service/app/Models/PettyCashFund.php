<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PettyCashFund extends Model
{
    protected $fillable = [
        'fund_name',
        'initial_balance',
        'current_balance',
        'updated_by',
    ];

    protected $casts = [
        'initial_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(PettyCashTransaction::class);
    }
}
