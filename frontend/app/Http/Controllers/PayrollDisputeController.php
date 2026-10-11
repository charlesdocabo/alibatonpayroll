<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PayrollDisputeController extends Controller
{
    private string $gateway;

    public function __construct()
    {
        $this->gateway = rtrim(config('services.gateway.url'), '/');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $isEmployee = strtolower($user->role ?? '') === 'employee';

        $query = [];

        if ($isEmployee) {
            $query['employee_id'] = (string) ($user->employee_id ?? $user->id);
        }

        if (!$isEmployee && $request->filled('status')) {
            $query['status'] = $request->input('status');
        }

        $response = Http::timeout(10)->get(
            $this->gateway . '/api/payroll-disputes',
            $query
        );

        if (!$response->successful()) {
            return back()->with(
                'error',
                'Unable to load payroll disputes. Please try again.'
            );
        }

        $disputes = $response->json('data');

        return view('payroll-disputes.index', compact('disputes', 'isEmployee'));
    }

    public function create()
    {
        $user = auth()->user();

        $employeeId = (string) ($user->employee_id ?? $user->id);

        $response = Http::timeout(10)->get(
            $this->gateway . '/api/payrolls'
        );

        if (!$response->successful()) {
            return back()->with(
                'error',
                'Unable to load payroll records. Please try again.'
            );
        }

        $payload = $response->json('data', []);
        $payrolls = collect(
            isset($payload['data']) ? $payload['data'] : $payload
        );

        if (strtolower($user->role ?? '') === 'employee') {
            $payrolls = $payrolls->filter(
                fn ($payroll) =>
                    (string) ($payroll['employee_id'] ?? '') === $employeeId
            );
        }

        return view('payroll-disputes.create', compact('payrolls'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $isEmployee = strtolower($user->role ?? '') === 'employee';

        $validated = $request->validate([
            'payroll_id' => 'required|integer|min:1',
            'dispute_type' => 'required|in:salary,overtime,deductions,allowances,incentives,other',
            'subject' => 'required|string|max:150',
            'description' => 'required|string|max:5000',
            'claimed_amount' => 'nullable|numeric|min:0|max:9999999999.99',
        ]);

        $payrollResponse = Http::timeout(10)->get(
            $this->gateway . '/api/payrolls/' . $validated['payroll_id']
        );

        if (!$payrollResponse->successful()) {
            return back()->withInput()->withErrors([
                'payroll_id' => 'The selected payroll record could not be found.',
            ]);
        }

        $payroll = $payrollResponse->json('data');

        if (isset($payroll['data'])) {
            $payroll = $payroll['data'];
        }

        if (!is_array($payroll) || !isset($payroll['employee_id'])) {
            return back()->withInput()->withErrors([
                'payroll_id' => 'Unable to verify the selected payroll record.',
            ]);
        }

        $employeeId = (string) ($user->employee_id ?? $user->id);

        if (
            $isEmployee &&
            (string) $payroll['employee_id'] !== $employeeId
        ) {
            abort(403, 'You may only dispute your own payroll.');
        }

        $response = Http::timeout(10)->post(
            $this->gateway . '/api/payroll-disputes',
            [
                'payroll_id' => $validated['payroll_id'],
                'employee_id' => $payroll['employee_id'],
                'dispute_type' => $validated['dispute_type'],
                'subject' => $validated['subject'],
                'description' => $validated['description'],
                'claimed_amount' => $validated['claimed_amount'] ?? null,
            ]
        );

        if (!$response->successful()) {
            return back()->withInput()->withErrors([
                'dispute' => $response->json('message')
                    ?? 'Unable to submit the payroll dispute.',
            ]);
        }

        $destination = $isEmployee
            ? '/my-payroll-disputes'
            : '/payroll-disputes';

        return redirect($destination)->with(
            'success',
            'Payroll dispute submitted successfully.'
        );
    }

    public function show(string $id)
    {
        $response = Http::timeout(10)->get(
            $this->gateway . '/api/payroll-disputes/' . $id
        );

        if (!$response->successful()) {
            abort($response->status() === 404 ? 404 : 502);
        }

        $dispute = $response->json('data');

        if (isset($dispute['data'])) {
            $dispute = $dispute['data'];
        }

        $user = auth()->user();

        if (
            strtolower($user->role ?? '') === 'employee' &&
            (string) ($dispute['employee_id'] ?? '') !==
                (string) ($user->employee_id ?? $user->id)
        ) {
            abort(403);
        }

        return view('payroll-disputes.show', compact('dispute'));
    }

    public function update(Request $request, string $id)
    {
        $user = auth()->user();

        if (!in_array(strtolower($user->role ?? ''), ['admin', 'hr'], true)) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:under_review,resolved,rejected',
            'hr_response' => 'required|string|max:5000',
        ]);

        $response = Http::timeout(10)->put(
            $this->gateway . '/api/payroll-disputes/' . $id,
            [
                'status' => $validated['status'],
                'hr_response' => $validated['hr_response'],
                'reviewed_by' => $user->name ?? 'HR',
            ]
        );

        if (!$response->successful()) {
            return back()->withErrors([
                'review' => $response->json('message')
                    ?? 'Unable to save the dispute review.',
            ]);
        }

        return redirect('/payroll-disputes')->with(
            'success',
            'Payroll dispute review saved successfully.'
        );
    }
}
