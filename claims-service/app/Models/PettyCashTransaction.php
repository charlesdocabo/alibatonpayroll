<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PettyCashTransaction extends Model
{
    protected $fillable = [
        'petty_cash_fund_id',
        'claim_id',
        'transaction_type',
        'direction',
        'amount',
        'balance_after',
        'reference_number',
        'description',
        'performed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function fund(): BelongsTo
    {
        return $this->belongsTo(PettyCashFund::class, 'petty_cash_fund_id');
    }

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }
}
