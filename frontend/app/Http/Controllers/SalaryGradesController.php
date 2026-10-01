<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AuditLog;

class SalaryGradesController extends Controller
{
    private string $gateway;

    public function __construct()
    {
        $this->gateway = config('services.gateway.url');
    }

    public function index()
    {
        try {
            $response = Http::timeout(10)->get(
                $this->gateway . '/api/salary-grades'
            );

            $salaryGrades = $response->successful()
                ? $response->json('data', [])
                : [];

            $totalSalaryGrades = count($salaryGrades);

            $minimumSalaries = collect($salaryGrades)
                ->pluck('minimum_salary')
                ->map(fn ($value) => (float) $value);

            $maximumSalaries = collect($salaryGrades)
                ->pluck('maximum_salary')
                ->map(fn ($value) => (float) $value);

            $lowestMinimumSalary = $minimumSalaries->min() ?? 0;
            $highestMaximumSalary = $maximumSalaries->max() ?? 0;

            $averageMinimumSalary = $minimumSalaries->avg() ?? 0;
            $averageMaximumSalary = $maximumSalaries->avg() ?? 0;

            return view('salary_grades.index', compact(
                'salaryGrades',
                'totalSalaryGrades',
                'lowestMinimumSalary',
                'highestMaximumSalary',
                'averageMinimumSalary',
                'averageMaximumSalary'
            ));

        } catch (\Exception $e) {

            return view('salary_grades.index', [
                'salaryGrades' => [],
                'totalSalaryGrades' => 0,
                'lowestMinimumSalary' => 0,
                'highestMaximumSalary' => 0,
                'averageMinimumSalary' => 0,
                'averageMaximumSalary' => 0,
                'error' => 'Connection Error: ' . $e->getMessage()
            ]);
        }
    }

    public function create()
    {
        return view('salary_grades.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade_name' => 'required|string|max:50',
            'minimum_salary' => 'required|numeric|min:0',
            'maximum_salary' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validated['maximum_salary'] < $validated['minimum_salary']) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Maximum salary cannot be lower than minimum salary.'
                );
        }

        try {
            $response = Http::timeout(10)->post(
                $this->gateway . '/api/salary-grades',
                $validated
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'SALARY_GRADE_CREATED',
                    'description' => 'Created salary grade: '
                        . $validated['grade_name']
                        . ' | Minimum: ₱'
                        . number_format((float) $validated['minimum_salary'], 2)
                        . ' | Maximum: ₱'
                        . number_format((float) $validated['maximum_salary'], 2),
                    'ip_address' => $request->ip(),
                ]);

                return redirect('/salary-grades')
                    ->with(
                        'success',
                        'Salary grade added successfully.'
                    );
            }

            $errorMessage = $response->json('message')
                ?? $response->json('error')
                ?? $response->body();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'API Error: ' . $errorMessage
                );

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Connection Error: ' . $e->getMessage()
                );
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::timeout(10)->get(
                $this->gateway . '/api/salary-grades/' . $id
            );

            if ($response->successful()) {

                $salaryGrade = $response->json('data');

                return view(
                    'salary_grades.edit',
                    compact('salaryGrade')
                );
            }

            return redirect('/salary-grades')
                ->with(
                    'error',
                    'Salary grade record not found.'
                );

        } catch (\Exception $e) {

            return redirect('/salary-grades')
                ->with(
                    'error',
                    'Connection Error: ' . $e->getMessage()
                );
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'grade_name' => 'required|string|max:50',
            'minimum_salary' => 'required|numeric|min:0',
            'maximum_salary' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validated['maximum_salary'] < $validated['minimum_salary']) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Maximum salary cannot be lower than minimum salary.'
                );
        }

        try {
            $response = Http::timeout(10)->put(
                $this->gateway . '/api/salary-grades/' . $id,
                $validated
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'SALARY_GRADE_UPDATED',
                    'description' => 'Updated salary grade ID: '
                        . $id
                        . ' | Grade: '
                        . $validated['grade_name']
                        . ' | Minimum: ₱'
                        . number_format((float) $validated['minimum_salary'], 2)
                        . ' | Maximum: ₱'
                        . number_format((float) $validated['maximum_salary'], 2),
                    'ip_address' => $request->ip(),
                ]);

                return redirect('/salary-grades')
                    ->with(
                        'success',
                        'Salary grade updated successfully.'
                    );
            }

            $errorMessage = $response->json('message')
                ?? $response->json('error')
                ?? $response->body();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'API Error: ' . $errorMessage
                );

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Connection Error: ' . $e->getMessage()
                );
        }
    }

    public function destroy(Request $request, $id)
    {
        try {

            // Get the salary grade first so the audit log
            // can contain the grade details.
            $salaryGradeResponse = Http::timeout(10)->get(
                $this->gateway . '/api/salary-grades/' . $id
            );

            $salaryGrade = $salaryGradeResponse->successful()
                ? $salaryGradeResponse->json('data')
                : null;

            $response = Http::timeout(10)->delete(
                $this->gateway . '/api/salary-grades/' . $id
            );

            if ($response->successful()) {

                $description = $salaryGrade
                    ? 'Deleted salary grade ID: '
                        . $id
                        . ' | Grade: '
                        . ($salaryGrade['grade_name'] ?? 'Unknown')
                        . ' | Minimum: ₱'
                        . number_format(
                            (float) ($salaryGrade['minimum_salary'] ?? 0),
                            2
                        )
                        . ' | Maximum: ₱'
                        . number_format(
                            (float) ($salaryGrade['maximum_salary'] ?? 0),
                            2
                        )
                    : 'Deleted salary grade record ID: ' . $id;

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'SALARY_GRADE_DELETED',
                    'description' => $description,
                    'ip_address' => $request->ip(),
                ]);

                return redirect('/salary-grades')
                    ->with(
                        'success',
                        'Salary grade deleted successfully.'
                    );
            }

            $errorMessage = $response->json('message')
                ?? $response->json('error')
                ?? $response->body();

            return redirect('/salary-grades')
                ->with(
                    'error',
                    'API Error: ' . $errorMessage
                );

        } catch (\Exception $e) {

            return redirect('/salary-grades')
                ->with(
                    'error',
                    'Connection Error: ' . $e->getMessage()
                );
        }
    }
}



