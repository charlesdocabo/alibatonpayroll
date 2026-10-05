<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incentive;
use Illuminate\Http\Request;

class IncentiveController extends Controller
{
    public function index()
    {
        $incentives = Incentive::orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $incentives
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'    => 'required|string|max:50',
            'incentive_type' => 'required|string|max:100',
            'description'    => 'nullable|string',
            'amount'         => 'required|numeric|min:0',
            'incentive_date' => 'required|date',
            'status'         => 'nullable|in:pending,approved,released,cancelled',
            'payroll_period' => 'nullable|string|max:20',
            'approved_by'    => 'nullable|string|max:100',
            'trip_id'        => 'nullable|integer',
            'trip_reference' => 'nullable|string|max:50',
        ]);

        $incentive = Incentive::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Incentive created successfully.',
            'data'    => $incentive
        ], 201);
    }

    public function show(Incentive $incentive)
    {
        return response()->json([
            'success' => true,
            'data'    => $incentive
        ]);
    }

    public function update(Request $request, Incentive $incentive)
    {
        $validated = $request->validate([
            'employee_id'    => 'sometimes|required|string|max:50',
            'incentive_type' => 'sometimes|required|string|max:100',
            'description'    => 'nullable|string',
            'amount'         => 'sometimes|required|numeric|min:0',
            'incentive_date' => 'sometimes|required|date',
            'status'         => 'sometimes|in:pending,approved,released,cancelled',
            'payroll_period' => 'nullable|string|max:20',
            'approved_by'    => 'nullable|string|max:100',
            'trip_id'        => 'nullable|integer',
            'trip_reference' => 'nullable|string|max:50',
        ]);

        $incentive->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Incentive updated successfully.',
            'data'    => $incentive->fresh()
        ]);
    }

    public function destroy(Incentive $incentive)
    {
        $incentive->delete();

        return response()->json([
            'success' => true,
            'message' => 'Incentive deleted successfully.'
        ]);
    }
}