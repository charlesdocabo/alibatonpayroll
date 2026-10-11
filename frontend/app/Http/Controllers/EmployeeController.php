<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    private string $gateway;

    public function __construct()
    {
        $this->gateway = config('services.gateway.url');
    }

    /**
     * Display all employees.
     */
    
public function index(Request $request): View
{
    $response = Http::timeout(10)
        ->get($this->gateway . '/api/employees');

    $search = trim((string) $request->query('search', ''));
    $department = trim((string) $request->query('department', ''));
    $status = strtolower(trim((string) $request->query('status', '')));

    if (!$response->successful()) {
        return view('employees.index', [
            'employees' => [],
            'departments' => [],
            'search' => $search,
            'department' => $department,
            'status' => $status,
            'totalEmployees' => 0,
        ])->with('error', 'Unable to load employees from Employee Service.');
    }

    $allEmployees = $response->json('data', []);

    if (!is_array($allEmployees)) {
        $allEmployees = [];
    }

    $departments = collect($allEmployees)
        ->map(fn ($employee) => trim((string) ($employee['department'] ?? '')))
        ->filter(fn ($value) => $value !== '')
        ->unique(fn ($value) => strtolower($value))
        ->sort()
        ->values()
        ->all();

    $filteredEmployees = collect($allEmployees)
        ->filter(function ($employee) use ($search) {
            if ($search === '') {
                return true;
            }

            $name = trim(
                ($employee['first_name'] ?? '') . ' ' .
                ($employee['last_name'] ?? '')
            );

            foreach ([
                $employee['employee_id'] ?? '',
                $name,
                $employee['email'] ?? '',
            ] as $value) {
                if (stripos((string) $value, $search) !== false) {
                    return true;
                }
            }

            return false;
        })
        ->filter(function ($employee) use ($department) {
            return $department === ''
                || strcasecmp(
                    trim((string) ($employee['department'] ?? '')),
                    $department
                ) === 0;
        })
        ->filter(function ($employee) use ($status) {
            return $status === ''
                || strtolower(
                    trim((string) ($employee['status'] ?? ''))
                ) === $status;
        })
        ->values();

    $perPage = 10;
    $currentPage = max(1, $request->integer('page', 1));

    $employees = new \Illuminate\Pagination\LengthAwarePaginator(
        $filteredEmployees->forPage($currentPage, $perPage)->values(),
        $filteredEmployees->count(),
        $perPage,
        $currentPage,
        [
            'path' => $request->url(),
            'query' => $request->query(),
        ]
    );

    return view('employees.index', [
        'employees' => $employees,
        'departments' => $departments,
        'search' => $search,
        'department' => $department,
        'status' => $status,
        'totalEmployees' => $filteredEmployees->count(),
    ]);
}

    /**
     * Show create employee form.
     */
    public function create(): View
    {
        return view('employees.create');
    }

    /**
     * Store a new employee.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'position' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
            'sss' => 'nullable|numeric|min:0',
            'philhealth' => 'nullable|numeric|min:0',
            'pagibig' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $payload = [
            'employee_id' => $validated['employee_id'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'position' => $validated['position'],
            'department' => $validated['department'],
            'salary' => $validated['salary'],
            'sss_number' => $validated['sss'] ?? null,
            'philhealth_number' => $validated['philhealth'] ?? null,
            'pagibig_number' => $validated['pagibig'] ?? null,
            'status' => $validated['status'],
        ];

        $response = Http::timeout(10)
            ->post($this->gateway . '/api/employees', $payload);

        if (!$response->successful()) {
            $message = $response->json('message')
                ?? 'Unable to create employee.';

            return back()
                ->withInput()
                ->with('error', $message);
        }

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Show edit employee form.
     */
    public function edit(string $employee): View|RedirectResponse
    {
        $response = Http::timeout(10)
            ->get($this->gateway . '/api/employees/' . $employee);

        if (!$response->successful()) {
            return redirect()
                ->route('employees.index')
                ->with('error', 'Employee not found.');
        }

        $employeeData = $response->json('data');

        if (!$employeeData) {
            return redirect()
                ->route('employees.index')
                ->with('error', 'Employee not found.');
        }

        /*
         * Convert Employee Service field names to the names
         * expected by the existing Blade edit form.
         */
        $employeeData['sss'] =
            $employeeData['sss']
            ?? $employeeData['sss_number']
            ?? null;

        $employeeData['philhealth'] =
            $employeeData['philhealth']
            ?? $employeeData['philhealth_number']
            ?? null;

        $employeeData['pagibig'] =
            $employeeData['pagibig']
            ?? $employeeData['pagibig_number']
            ?? null;

        return view('employees.edit', [
            'employee' => $employeeData,
        ]);
    }

    /**
     * Update an employee.
     */
    public function update(
        Request $request,
        string $employee
    ): RedirectResponse {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'position' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
            'sss' => 'nullable|numeric|min:0',
            'philhealth' => 'nullable|numeric|min:0',
            'pagibig' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $payload = [
            'employee_id' => $validated['employee_id'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'position' => $validated['position'],
            'department' => $validated['department'],
            'salary' => $validated['salary'],
            'sss_number' => $validated['sss'] ?? null,
            'philhealth_number' => $validated['philhealth'] ?? null,
            'pagibig_number' => $validated['pagibig'] ?? null,
            'status' => $validated['status'],
        ];

        $response = Http::timeout(10)
            ->patch(
                $this->gateway . '/api/employees/' . $employee,
                $payload
            );

        if (!$response->successful()) {
            $message = $response->json('message')
                ?? 'Unable to update employee.';

            return back()
                ->withInput()
                ->with('error', $message);
        }

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Delete an employee.
     */
    public function destroy(string $employee): RedirectResponse
    {
        $response = Http::timeout(10)
            ->delete(
                $this->gateway . '/api/employees/' . $employee
            );

        if (!$response->successful()) {
            $message = $response->json('message')
                ?? 'Unable to delete employee.';

            return redirect()
                ->route('employees.index')
                ->with('error', $message);
        }

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}