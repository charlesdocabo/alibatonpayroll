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
    public function index(): View
    {
        $response = Http::timeout(10)
            ->get($this->gateway . '/api/employees');

        if (!$response->successful()) {
            return view('employees.index', [
                'employees' => [],
            ])->with('error', 'Unable to load employees from Employee Service.');
        }

        $employees = $response->json('data', []);

        return view('employees.index', compact('employees'));
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