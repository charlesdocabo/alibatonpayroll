<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalaryGrade;
use Illuminate\Http\Request;

class SalaryGradeController extends Controller
{
    public function index()
    {
        $salaryGrades = SalaryGrade::orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $salaryGrades
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'minimum_salary' => 'required|numeric|min:0',
            'maximum_salary' => 'required|numeric|gte:minimum_salary',
        ]);

        $salaryGrade = SalaryGrade::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Salary grade created successfully.',
            'data' => $salaryGrade
        ], 201);
    }

    public function show(SalaryGrade $salaryGrade)
    {
        return response()->json([
            'success' => true,
            'data' => $salaryGrade
        ]);
    }

    public function update(Request $request, SalaryGrade $salaryGrade)
    {
        $validated = $request->validate([
            'grade_name' => 'sometimes|required|string|max:100',
            'description' => 'nullable|string',
            'minimum_salary' => 'sometimes|required|numeric|min:0',
            'maximum_salary' => 'sometimes|required|numeric|gte:minimum_salary',
        ]);

        $salaryGrade->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Salary grade updated successfully.',
            'data' => $salaryGrade->fresh()
        ]);
    }

    public function destroy(SalaryGrade $salaryGrade)
    {
        $salaryGrade->delete();

        return response()->json([
            'success' => true,
            'message' => 'Salary grade deleted successfully.'
        ]);
    }
}