<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index()
    {
        $claims = Claim::orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'data'    => $claims
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'    => 'required|string|max:50',
            'claim_type'     => 'required|string|max:100',
            'description'    => 'nullable|string',
            'amount'         => 'required|numeric|min:0',
            'claim_date'     => 'required|date',
            'status'         => 'nullable|in:pending,approved,rejected,paid,returned',
            'approved_by'    => 'nullable|string|max:100',
            'approval_notes' => 'nullable|string',
            'approved_at'    => 'nullable|date',
            'return_reason'  => 'nullable|string',
            'receipt_path'   => 'nullable|string|max:500',
        ]);

        $claim = Claim::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Claim created successfully.',
            'data'    => $claim
        ], 201);
    }

    public function show(Claim $claim)
    {
        return response()->json([
            'success' => true,
            'data'    => $claim
        ]);
    }

    public function update(Request $request, Claim $claim)
    {
        $validated = $request->validate([
            'employee_id'    => 'sometimes|required|string|max:50',
            'claim_type'     => 'sometimes|required|string|max:100',
            'description'    => 'nullable|string',
            'amount'         => 'sometimes|required|numeric|min:0',
            'claim_date'     => 'sometimes|required|date',
            'status'         => 'sometimes|in:pending,approved,rejected,paid,returned',
            'approved_by'    => 'nullable|string|max:100',
            'approval_notes' => 'nullable|string',
            'approved_at'    => 'nullable|date',
            'return_reason'  => 'nullable|string',
            'receipt_path'   => 'nullable|string|max:500',
        ]);

        $claim->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Claim updated successfully.',
            'data'    => $claim->fresh()
        ]);
    }

    public function destroy(Claim $claim)
    {
        $claim->delete();

        return response()->json([
            'success' => true,
            'message' => 'Claim deleted successfully.'
        ]);
    }

    /**
     * Approve a claim.
     */
    public function approve(Request $request, $id)
    {
        $claim = Claim::findOrFail($id);

        $validated = $request->validate([
            'approved_by'    => 'required|string|max:100',
            'approval_notes' => 'nullable|string',
        ]);

        $claim->update([
            'status'         => 'approved',
            'approved_by'    => $validated['approved_by'],
            'approval_notes' => $validated['approval_notes'] ?? null,
            'approved_at'    => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Claim approved successfully.',
            'data'    => $claim->fresh()
        ]);
    }

    /**
     * Reject a claim.
     */
    public function reject(Request $request, $id)
    {
        $claim = Claim::findOrFail($id);

        $validated = $request->validate([
            'approved_by'    => 'required|string|max:100',
            'approval_notes' => 'nullable|string',
        ]);

        $claim->update([
            'status'         => 'rejected',
            'approved_by'    => $validated['approved_by'],
            'approval_notes' => $validated['approval_notes'] ?? null,
            'approved_at'    => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Claim rejected.',
            'data'    => $claim->fresh()
        ]);
    }

    /**
     * Return a claim for revision.
     */
    public function returnForRevision(Request $request, $id)
    {
        $claim = Claim::findOrFail($id);

        $validated = $request->validate([
            'approved_by'   => 'required|string|max:100',
            'return_reason' => 'required|string',
        ]);

        $claim->update([
            'status'        => 'returned',
            'approved_by'   => $validated['approved_by'],
            'return_reason' => $validated['return_reason'],
            'approved_at'   => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Claim returned for revision.',
            'data'    => $claim->fresh()
        ]);
    }
}