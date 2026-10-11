<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\PettyCashFund;
use App\Models\PettyCashTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PettyCashController extends Controller
{
    private function getMainFund(): PettyCashFund
    {
        return PettyCashFund::firstOrCreate(
            ['fund_name' => 'Main Petty Cash'],
            [
                'initial_balance' => 0,
                'current_balance' => 0,
            ]
        );
    }

    public function balance()
    {
        $fund = $this->getMainFund();

        return response()->json([
            'success' => true,
            'data' => $fund,
        ]);
    }

    public function transactions()
    {
        $fund = $this->getMainFund();

        $transactions = PettyCashTransaction::where(
            'petty_cash_fund_id',
            $fund->id
        )->orderByDesc('id')->get();

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    public function addFunds(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|gt:0|max:9999999999.99',
            'description' => 'nullable|string|max:1000',
            'performed_by' => 'nullable|string|max:100',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $fund = $this->getMainFund();

        $transaction = DB::transaction(function () use ($fund, $validated) {
            $lockedFund = PettyCashFund::whereKey($fund->id)
                ->lockForUpdate()
                ->firstOrFail();

            $amount = (float) $validated['amount'];
            $newBalance = (float) $lockedFund->current_balance + $amount;

            $lockedFund->current_balance = $newBalance;
            $lockedFund->save();

            return PettyCashTransaction::create([
                'petty_cash_fund_id' => $lockedFund->id,
                'transaction_type' => 'fund_added',
                'direction' => 'in',
                'amount' => $amount,
                'balance_after' => $newBalance,
                'reference_number' => $validated['reference_number'] ?? null,
                'description' => $validated['description'] ?? 'Petty Cash fund added',
                'performed_by' => $validated['performed_by'] ?? null,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Petty Cash fund updated successfully.',
            'data' => [
                'fund' => $fund->fresh(),
                'transaction' => $transaction,
            ],
        ]);
    }

    public function payClaim(Request $request, $id)
    {
        $validated = $request->validate([
            'performed_by' => 'required|string|max:100',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $result = DB::transaction(function () use ($id, $validated) {
            $claim = Claim::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($claim->status === 'paid') {
                throw ValidationException::withMessages([
                    'claim' => 'This claim has already been paid.',
                ]);
            }

            if ($claim->status !== 'approved') {
                throw ValidationException::withMessages([
                    'claim' => 'Only approved claims can be paid.',
                ]);
            }

            if ((float) $claim->amount <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'Claim amount must be greater than zero.',
                ]);
            }

            $fund = $this->getMainFund();

            $lockedFund = PettyCashFund::whereKey($fund->id)
                ->lockForUpdate()
                ->firstOrFail();

            $amount = (float) $claim->amount;
            $balance = (float) $lockedFund->current_balance;

            if ($balance < $amount) {
                throw ValidationException::withMessages([
                    'petty_cash' => 'Insufficient Petty Cash balance.',
                ]);
            }

            $newBalance = $balance - $amount;

            $lockedFund->current_balance = $newBalance;
            $lockedFund->save();

            $transaction = PettyCashTransaction::create([
                'petty_cash_fund_id' => $lockedFund->id,
                'claim_id' => $claim->id,
                'transaction_type' => 'claim_payment',
                'direction' => 'out',
                'amount' => $amount,
                'balance_after' => $newBalance,
                'reference_number' => $validated['reference_number'] ?? null,
                'description' => 'Payment for claim #' . $claim->id,
                'performed_by' => $validated['performed_by'],
            ]);

            $claim->status = 'paid';
            $claim->save();

            return [
                'claim' => $claim->fresh(),
                'fund' => $lockedFund->fresh(),
                'transaction' => $transaction,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Claim paid and Petty Cash balance deducted successfully.',
            'data' => $result,
        ]);
    }
}
