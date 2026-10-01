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
            'data' => $claims
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'claim_type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'claim_date' => 'required|date',
            'status' => 'nullable|in:pending,approved,rejected,paid',
        ]);

        $claim = Claim::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Claim created successfully.',
            'data' => $claim
        ], 201);
    }

    public function show(Claim $claim)
    {
        return response()->json([
            'success' => true,
            'data' => $claim
        ]);
    }

    public function update(Request $request, Claim $claim)
    {
        $validated = $request->validate([
            'employee_id' => 'sometimes|required|string|max:50',
            'claim_type' => 'sometimes|required|string|max:100',
            'description' => 'nullable|string',
            'amount' => 'sometimes|required|numeric|min:0',
            'claim_date' => 'sometimes|required|date',
            'status' => 'sometimes|in:pending,approved,rejected,paid',
        ]);

        $claim->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Claim updated successfully.',
            'data' => $claim->fresh()
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
}