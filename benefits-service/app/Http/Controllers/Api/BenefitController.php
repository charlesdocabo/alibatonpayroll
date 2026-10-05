<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Benefit;
use Illuminate\Http\Request;

class BenefitController extends Controller
{
    public function index()
    {
        $benefits = Benefit::orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $benefits
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'benefit_type' => 'required|string|max:100',
            'provider' => 'nullable|string|max:150',
            'coverage' => 'nullable|numeric|min:0',
            'membership_number' => 'nullable|string|max:100',
            'amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:active,inactive,pending',
            'description' => 'nullable|string',
        ]);

        $benefit = Benefit::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Benefit created successfully.',
            'data' => $benefit
        ], 201);
    }

    public function show(Benefit $benefit)
    {
        return response()->json([
            'success' => true,
            'data' => $benefit
        ]);
    }

    public function update(Request $request, Benefit $benefit)
    {
        $validated = $request->validate([
            'employee_id' => 'sometimes|required|string|max:50',
            'benefit_type' => 'sometimes|required|string|max:100',
            'provider' => 'nullable|string|max:150',
            'coverage' => 'nullable|numeric|min:0',
            'membership_number' => 'nullable|string|max:100',
            'amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'sometimes|in:active,inactive,pending',
            'description' => 'nullable|string',
        ]);

        $benefit->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Benefit updated successfully.',
            'data' => $benefit->fresh()
        ]);
    }

    public function destroy(Benefit $benefit)
    {
        $benefit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Benefit deleted successfully.'
        ]);
    }
}