<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\AuditLog;

class UserManagementController extends Controller
{
    private string $gateway;

    public function __construct()
    {
        $this->gateway = config('services.gateway.url');
    }

    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        try {
            $response = Http::timeout(10)->get(
                $this->gateway . '/api/employees'
            );

            $employees = $response->successful()
                ? $response->json('data', [])
                : [];

        } catch (\Exception $e) {
            $employees = [];
        }

        // Only employees that are not yet linked to a user account
        $assignedEmployeeIds = User::whereNotNull('employee_id')
            ->pluck('employee_id')
            ->toArray();

        $employees = collect($employees)
            ->filter(function ($employee) use ($assignedEmployeeIds) {
                return !in_array(
                    $employee['employee_id'] ?? null,
                    $assignedEmployeeIds
                );
            })
            ->values()
            ->all();

        return view('users.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],

            'role' => 'required|in:Admin,HR,Employee',

            'employee_id' => [
                'nullable',
                'string',
                'max:50',
                'unique:users,employee_id',
                'required_if:role,Employee',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Employee Account Validation
        |--------------------------------------------------------------------------
        */

        if ($validated['role'] === 'Employee') {

            try {
                $employeeResponse = Http::timeout(10)->get(
                    $this->gateway . '/api/employees/'
                    . $validated['employee_id']
                );

                if (!$employeeResponse->successful()) {
                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'The selected employee record could not be found.'
                        );
                }

            } catch (\Exception $e) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Unable to connect to the Employee Service.'
                    );
            }

        } else {
            // Admin and HR accounts do not need an employee mapping.
            $validated['employee_id'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Create User
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'employee_id' => $validated['employee_id'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit Log
        |--------------------------------------------------------------------------
        */

        $description = "Created user account: {$user->email} ({$user->role})";

        if ($user->role === 'Employee' && $user->employee_id) {
            $description .= " - Employee ID: {$user->employee_id}";
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'USER_CREATED',
            'description' => $description,
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User account created successfully.'
            );
    }

    public function toggleStatus(User $user, Request $request)
    {
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'You cannot deactivate your own account.'
            );
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $status = $user->is_active
            ? 'activated'
            : 'deactivated';

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $user->is_active
                ? 'USER_ACTIVATED'
                : 'USER_DEACTIVATED',
            'description' => ucfirst($status)
                . " user account: {$user->email}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                "User account {$status} successfully."
            );
    }

    public function destroy(User $user, Request $request)
    {
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        $email = $user->email;

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'USER_DELETED',
            'description' => "Deleted user account: {$email}",
            'ip_address' => $request->ip(),
        ]);

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User account deleted successfully.'
            );
    }
}