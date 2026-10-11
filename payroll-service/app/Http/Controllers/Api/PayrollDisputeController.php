<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\PayrollDispute;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PayrollDisputeController extends Controller
{
    /**
     * List disputes.
     * Optional filters: employee_id, status.
     */
    public function index(Request $request)
    {
        $query = PayrollDispute::query()->latest();

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->input('employee_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return response()->json([
            'data' => $query->paginate(15),
        ]);
    }

    /**
     * Submit a dispute about an existing payroll record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payroll_id' => [
                'required',
                'integer',
                Rule::exists('payrolls', 'id'),
            ],
            'employee_id' => 'required|string|max:50',
            'dispute_type' => [
                'required',
                Rule::in([
                    'salary',
                    'overtime',
                    'deductions',
                    'allowances',
                    'incentives',
                    'other',
                ]),
            ],
            'subject' => 'required|string|max:150',
            'description' => 'required|string|max:5000',
            'claimed_amount' => 'nullable|numeric|min:0|max:9999999999.99',
        ]);

        $payroll = Payroll::findOrFail($validated['payroll_id']);

        if ((string) $payroll->employee_id !== (string) $validated['employee_id']) {
            return response()->json([
                'message' => 'The selected payroll does not belong to this employee.',
            ], 422);
        }

        $dispute = PayrollDispute::create([
            'payroll_id' => $payroll->id,
            'employee_id' => $payroll->employee_id,
            'dispute_type' => $validated['dispute_type'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'claimed_amount' => $validated['claimed_amount'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Payroll dispute submitted successfully.',
            'data' => $dispute,
        ], 201);
    }

    /**
     * Show one dispute.
     */
    public function show(string $id)
    {
        $dispute = PayrollDispute::with('payroll')->findOrFail($id);

        return response()->json([
            'data' => $dispute,
        ]);
    }

    /**
     * HR/Admin review action.
     * Frontend must enforce the HR/Admin role before calling this endpoint.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['under_review', 'resolved', 'rejected']),
            ],
            'hr_response' => 'required|string|max:5000',
            'reviewed_by' => 'required|string|max:100',
        ]);

        $dispute = PayrollDispute::findOrFail($id);

        $dispute->status = $validated['status'];
        $dispute->hr_response = $validated['hr_response'];
        $dispute->reviewed_by = $validated['reviewed_by'];
        $dispute->reviewed_at = now();
        $dispute->save();

        return response()->json([
            'message' => 'Payroll dispute review saved successfully.',
            'data' => $dispute,
        ]);
    }

    /**
     * Deletion is intentionally disabled to preserve dispute history.
     */
    public function destroy(string $id)
    {
        return response()->json([
            'message' => 'Payroll disputes cannot be deleted through this endpoint.',
        ], 405);
    }
}
