<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display all employees.
     */
    public function index()
    {
        $employees = Employee::orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }

    /**
     * Store a new employee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50|unique:employees,employee_id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'position' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
            'sss_number' => 'nullable|string|max:50',
            'philhealth_number' => 'nullable|string|max:50',
            'pagibig_number' => 'nullable|string|max:50',
            'status' => 'nullable|in:active,inactive',
        ]);

        $employee = Employee::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully.',
            'data' => $employee
        ], 201);
    }

    /**
     * Display one employee using employee_id.
     */
    public function show(string $employee)
    {
        $employeeRecord = Employee::where(
            'employee_id',
            $employee
        )->first();

        if (!$employeeRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $employeeRecord
        ]);
    }

    /**
     * Update an employee using employee_id.
     */
    public function update(Request $request, string $employee)
    {
        $employeeRecord = Employee::where(
            'employee_id',
            $employee
        )->first();

        if (!$employeeRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.'
            ], 404);
        }

        $validated = $request->validate([
            'employee_id' => 'sometimes|required|string|max:50|unique:employees,employee_id,' . $employeeRecord->id,
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'position' => 'sometimes|required|string|max:100',
            'department' => 'sometimes|required|string|max:100',
            'salary' => 'sometimes|required|numeric|min:0',
            'sss_number' => 'nullable|string|max:50',
            'philhealth_number' => 'nullable|string|max:50',
            'pagibig_number' => 'nullable|string|max:50',
            'status' => 'sometimes|in:active,inactive',
        ]);

        $employeeRecord->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully.',
            'data' => $employeeRecord->fresh()
        ]);
    }

    /**
     * Delete an employee using employee_id.
     */
    public function destroy(string $employee)
    {
        $employeeRecord = Employee::where(
            'employee_id',
            $employee
        )->first();

        if (!$employeeRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.'
            ], 404);
        }

        $employeeRecord->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully.'
        ]);
    }
}