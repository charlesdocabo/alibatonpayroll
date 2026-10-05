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

            // =========================
            // FETCH EMPLOYEES FOR ASSIGNMENT
            // =========================
            $employees = [];
            try {
                $empResponse = Http::timeout(10)->get($this->gateway . '/api/employees');
                if ($empResponse->successful()) {
                    $employees = $empResponse->json('data', []) ?? [];
                }
            } catch (\Exception $e) {
                // Non-fatal
            }

            // Map employees to matching salary grade based on basic salary
            $employeeAssignments = [];
            $gradeEmployeeCount = [];

            foreach ($employees as $emp) {
                $salary = (float) ($emp['salary'] ?? 0);
                $matchedGrade = null;
                $compliance = 'Unassigned';

                foreach ($salaryGrades as $grade) {
                    $min = (float) ($grade['minimum_salary'] ?? 0);
                    $max = (float) ($grade['maximum_salary'] ?? 0);

                    if ($salary >= $min && $salary <= $max) {
                        $matchedGrade = $grade['grade_name'];
                        $compliance = 'Within Range';
                        $gradeEmployeeCount[$grade['id']] = ($gradeEmployeeCount[$grade['id']] ?? 0) + 1;
                        break;
                    }
                }

                // If no exact match, find closest grade
                if (!$matchedGrade && count($salaryGrades) > 0) {
                    foreach ($salaryGrades as $grade) {
                        $min = (float) ($grade['minimum_salary'] ?? 0);
                        $max = (float) ($grade['maximum_salary'] ?? 0);

                        if ($salary < $min) {
                            $matchedGrade = $grade['grade_name'] . ' (Below Min)';
                            $compliance = 'Below Range';
                            break;
                        } elseif ($salary > $max) {
                            $matchedGrade = $grade['grade_name'] . ' (Above Max)';
                            $compliance = 'Above Range';
                        }
                    }
                }

                $employeeAssignments[] = [
                    'employee_id' => $emp['employee_id'] ?? '-',
                    'name'        => ($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? ''),
                    'position'    => $emp['position'] ?? '-',
                    'department'  => $emp['department'] ?? '-',
                    'salary'      => $salary,
                    'grade'       => $matchedGrade ?? 'Unassigned',
                    'compliance'  => $compliance,
                    'status'      => $emp['status'] ?? 'Active',
                ];
            }

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
                'averageMaximumSalary',
                'employeeAssignments',
                'gradeEmployeeCount'
            ));

        } catch (\Exception $e) {

            return view('salary_grades.index', [
                'salaryGrades'          => [],
                'totalSalaryGrades'     => 0,
                'lowestMinimumSalary'   => 0,
                'highestMaximumSalary'  => 0,
                'averageMinimumSalary'  => 0,
                'averageMaximumSalary'  => 0,
                'employeeAssignments'   => [],
                'gradeEmployeeCount'    => [],
                'error'                 => 'Connection Error: ' . $e->getMessage()
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
            'grade_name'     => 'required|string|max:50',
            'minimum_salary' => 'required|numeric|min:0',
            'maximum_salary' => 'required|numeric|min:0',
            'effective_date' => 'nullable|date',
            'description'    => 'nullable|string',
        ]);

        if ($validated['maximum_salary'] < $validated['minimum_salary']) {
            return back()
                ->withInput()
                ->with('error', 'Maximum salary cannot be lower than minimum salary.');
        }

        try {
            $response = Http::timeout(10)->post(
                $this->gateway . '/api/salary-grades',
                $validated
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id'     => auth()->id(),
                    'action'      => 'SALARY_GRADE_CREATED',
                    'description' => 'Created salary grade: '
                        . $validated['grade_name']
                        . ' | Minimum: ₱'
                        . number_format((float) $validated['minimum_salary'], 2)
                        . ' | Maximum: ₱'
                        . number_format((float) $validated['maximum_salary'], 2),
                    'ip_address'  => $request->ip(),
                ]);

                return redirect('/salary-grades')
                    ->with('success', 'Salary grade added successfully.');
            }

            $errorMessage = $response->json('message')
                ?? $response->json('error')
                ?? $response->body();

            return back()
                ->withInput()
                ->with('error', 'API Error: ' . $errorMessage);

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Connection Error: ' . $e->getMessage());
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
                ->with('error', 'Salary grade record not found.');

        } catch (\Exception $e) {

            return redirect('/salary-grades')
                ->with('error', 'Connection Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'grade_name'     => 'required|string|max:50',
            'minimum_salary' => 'required|numeric|min:0',
            'maximum_salary' => 'required|numeric|min:0',
            'effective_date' => 'nullable|date',
            'description'    => 'nullable|string',
        ]);

        if ($validated['maximum_salary'] < $validated['minimum_salary']) {
            return back()
                ->withInput()
                ->with('error', 'Maximum salary cannot be lower than minimum salary.');
        }

        try {
            $response = Http::timeout(10)->put(
                $this->gateway . '/api/salary-grades/' . $id,
                $validated
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id'     => auth()->id(),
                    'action'      => 'SALARY_GRADE_UPDATED',
                    'description' => 'Updated salary grade ID: '
                        . $id
                        . ' | Grade: '
                        . $validated['grade_name']
                        . ' | Minimum: ₱'
                        . number_format((float) $validated['minimum_salary'], 2)
                        . ' | Maximum: ₱'
                        . number_format((float) $validated['maximum_salary'], 2),
                    'ip_address'  => $request->ip(),
                ]);

                return redirect('/salary-grades')
                    ->with('success', 'Salary grade updated successfully.');
            }

            $errorMessage = $response->json('message')
                ?? $response->json('error')
                ?? $response->body();

            return back()
                ->withInput()
                ->with('error', 'API Error: ' . $errorMessage);

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Connection Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $gradeResponse = Http::timeout(10)->get(
                $this->gateway . '/api/salary-grades/' . $id
            );

            $grade = $gradeResponse->successful()
                ? $gradeResponse->json('data')
                : null;

            $response = Http::timeout(10)->delete(
                $this->gateway . '/api/salary-grades/' . $id
            );

            if ($response->successful()) {

                $description = $grade
                    ? 'Deleted salary grade ID: '
                        . $id
                        . ' (' . ($grade['grade_name'] ?? 'Unknown') . ')'
                    : 'Deleted salary grade record ID: ' . $id;

                AuditLog::create([
                    'user_id'     => auth()->id(),
                    'action'      => 'SALARY_GRADE_DELETED',
                    'description' => $description,
                    'ip_address'  => request()->ip(),
                ]);

                return redirect('/salary-grades')
                    ->with('success', 'Salary grade deleted successfully.');
            }

            $errorMessage = $response->json('message')
                ?? $response->json('error')
                ?? $response->body();

        } catch (\Exception $e) {

            return redirect('/salary-grades')
                ->with('error', 'Connection Error: ' . $e->getMessage());
        }
    }

    /**
     * JSON endpoint for real-time auto-refresh polling (called every 25s).
     */
    public function refreshData()
    {
        try {
            $response = Http::timeout(10)->get($this->gateway . '/api/salary-grades');
            $salaryGrades = $response->successful() ? ($response->json('data', []) ?? []) : [];

            $employees = [];
            try {
                $empResponse = Http::timeout(6)->get($this->gateway . '/api/employees');
                if ($empResponse->successful()) {
                    $employees = $empResponse->json('data', []) ?? [];
                }
            } catch (\Exception $e) {}

            $withinCount = 0;
            $belowCount  = 0;
            $aboveCount  = 0;
            $unassignedCount = 0;

            foreach ($employees as $emp) {
                $salary = (float) ($emp['salary'] ?? 0);
                $matched = false;

                foreach ($salaryGrades as $grade) {
                    $min = (float) ($grade['minimum_salary'] ?? 0);
                    $max = (float) ($grade['maximum_salary'] ?? 0);
                    if ($salary >= $min && $salary <= $max) {
                        $withinCount++;
                        $matched = true;
                        break;
                    }
                }

                if (!$matched && count($salaryGrades) > 0) {
                    $lowestMin = collect($salaryGrades)->min('minimum_salary') ?? 0;
                    $highestMax = collect($salaryGrades)->max('maximum_salary') ?? 0;
                    if ($salary < $lowestMin) {
                        $belowCount++;
                    } elseif ($salary > $highestMax) {
                        $aboveCount++;
                    } else {
                        $unassignedCount++;
                    }
                } elseif (!$matched) {
                    $unassignedCount++;
                }
            }

            $mins = collect($salaryGrades)->pluck('minimum_salary')->map(fn($v) => (float) $v);
            $maxs = collect($salaryGrades)->pluck('maximum_salary')->map(fn($v) => (float) $v);

            return response()->json([
                'success' => true,
                'stats' => [
                    'total_grades'   => count($salaryGrades),
                    'lowest_min'     => $mins->min() ?? 0,
                    'highest_max'    => $maxs->max() ?? 0,
                    'avg_min'        => $mins->avg() ?? 0,
                    'avg_max'        => $maxs->avg() ?? 0,
                    'total_employees'=> count($employees),
                    'within_range'   => $withinCount,
                    'below_range'    => $belowCount,
                    'above_range'    => $aboveCount,
                    'unassigned'     => $unassignedCount,
                ],
                'grades' => collect($salaryGrades)->map(fn($g) => [
                    'name' => $g['grade_name'],
                    'min'  => (float) $g['minimum_salary'],
                    'max'  => (float) $g['maximum_salary'],
                ]),
                'compliance' => [
                    'within'     => $withinCount,
                    'below'      => $belowCount,
                    'above'      => $aboveCount,
                    'unassigned' => $unassignedCount,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}

