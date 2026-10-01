<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AuditLog;

class PayrollController extends Controller
{
    private string $gateway;

    public function __construct()
    {
        $this->gateway = config('services.gateway.url');
    }

    /*
    |--------------------------------------------------------------------------
    | Payroll Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        try {

            $response = Http::timeout(10)->get(
                $this->gateway . '/api/payrolls'
            );

            $payrolls = $response->successful()
                ? ($response->json('data', []) ?? [])
                : [];

            // =========================
            // PAYROLL MONITORING
            // =========================

            $totalPayrollRecords = count($payrolls);

            // =========================
            // BASIC SALARY
            // =========================

            $totalBasicSalary = collect($payrolls)
                ->sum(function ($item) {
                    return (float) ($item['basic_salary'] ?? 0);
                });

            // =========================
            // OVERTIME
            // =========================

            $totalOvertimePay = collect($payrolls)
                ->sum(function ($item) {
                    return (float) ($item['overtime_pay'] ?? 0);
                });

            $totalOvertimeHours = collect($payrolls)
                ->sum(function ($item) {
                    return (float) ($item['overtime_hours'] ?? 0);
                });

            // =========================
            // ALLOWANCES
            // =========================

            $totalAllowances = collect($payrolls)
                ->sum(function ($item) {
                    return (float) ($item['allowances'] ?? 0);
                });

            // =========================
            // GROSS PAY
            // =========================

            $totalGrossPay = collect($payrolls)
                ->sum(function ($item) {
                    return (float) ($item['gross_pay'] ?? 0);
                });

            // =========================
            // DEDUCTIONS
            // =========================

            $totalDeductions = collect($payrolls)
                ->sum(function ($item) {
                    return (float) ($item['total_deductions'] ?? 0);
                });

            // =========================
            // NET PAY
            // =========================

            $totalNetPay = collect($payrolls)
                ->sum(function ($item) {
                    return (float) ($item['net_pay'] ?? 0);
                });

            $averageNetPay = $totalPayrollRecords > 0
                ? $totalNetPay / $totalPayrollRecords
                : 0;

            return view('payrolls.index', compact(
                'payrolls',
                'totalPayrollRecords',
                'totalBasicSalary',
                'totalOvertimePay',
                'totalOvertimeHours',
                'totalAllowances',
                'totalGrossPay',
                'totalDeductions',
                'totalNetPay',
                'averageNetPay'
            ));

        } catch (\Exception $e) {

            return view('payrolls.index', [
                'payrolls' => [],

                'totalPayrollRecords' => 0,
                'totalBasicSalary' => 0,
                'totalOvertimePay' => 0,
                'totalOvertimeHours' => 0,
                'totalAllowances' => 0,
                'totalGrossPay' => 0,
                'totalDeductions' => 0,
                'totalNetPay' => 0,
                'averageNetPay' => 0,

                'error' => 'Connection Error: ' . $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Create Payroll
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('payrolls.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Store Payroll
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'basic_salary' => 'required|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'pay_date' => 'required|date',
        ]);

        try {

            $response = Http::timeout(10)->post(
                $this->gateway . '/api/payrolls',
                $validated
            );

            if ($response->successful()) {

                // =========================
                // AUDIT LOG
                // =========================

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'PAYROLL_CREATED',
                    'description' => 'Created payroll for employee: '
                        . $validated['employee_id']
                        . ' | Pay Date: '
                        . $validated['pay_date'],
                    'ip_address' => $request->ip(),
                ]);

                return redirect('/payrolls')
                    ->with(
                        'success',
                        'Payroll created successfully.'
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

    /*
    |--------------------------------------------------------------------------
    | Edit Payroll
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        try {

            $response = Http::timeout(10)->get(
                $this->gateway . '/api/payrolls/' . $id
            );

            if ($response->successful()) {

                $payroll = $response->json('data');

                return view(
                    'payrolls.edit',
                    compact('payroll')
                );
            }

            return redirect('/payrolls')
                ->with(
                    'error',
                    'Payroll record not found.'
                );

        } catch (\Exception $e) {

            return redirect('/payrolls')
                ->with(
                    'error',
                    'Connection Error: ' . $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Update Payroll
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'basic_salary' => 'required|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'pay_date' => 'required|date',
        ]);

        try {

            $response = Http::timeout(10)->put(
                $this->gateway . '/api/payrolls/' . $id,
                $validated
            );

            if ($response->successful()) {

                // =========================
                // AUDIT LOG
                // =========================

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'PAYROLL_UPDATED',
                    'description' => 'Updated payroll ID: '
                        . $id
                        . ' for employee: '
                        . $validated['employee_id']
                        . ' | Pay Date: '
                        . $validated['pay_date'],
                    'ip_address' => $request->ip(),
                ]);

                return redirect('/payrolls')
                    ->with(
                        'success',
                        'Payroll updated successfully.'
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

    /*
    |--------------------------------------------------------------------------
    | Delete Payroll
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        try {

            $response = Http::timeout(10)->delete(
                $this->gateway . '/api/payrolls/' . $id
            );

            if ($response->successful()) {

                // =========================
                // AUDIT LOG
                // =========================

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'PAYROLL_DELETED',
                    'description' => 'Deleted payroll record ID: ' . $id,
                    'ip_address' => request()->ip(),
                ]);

                return redirect('/payrolls')
                    ->with(
                        'success',
                        'Payroll deleted successfully.'
                    );
            }

            $errorMessage = $response->json('message')
                ?? $response->json('error')
                ?? $response->body();

            return redirect('/payrolls')
                ->with(
                    'error',
                    'API Error: ' . $errorMessage
                );

        } catch (\Exception $e) {

            return redirect('/payrolls')
                ->with(
                    'error',
                    'Connection Error: ' . $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Payslip
    |--------------------------------------------------------------------------
    */

    public function payslip($id)
    {
        try {

            $response = Http::timeout(10)->get(
                $this->gateway . '/api/payrolls/' . $id
            );

            if (!$response->successful()) {

                return redirect('/payrolls')
                    ->with(
                        'error',
                        'Payroll record not found.'
                    );
            }

            $payroll = $response->json('data');

            if (!$payroll) {

                return redirect('/payrolls')
                    ->with(
                        'error',
                        'Payroll data is unavailable.'
                    );
            }

            return view(
                'payrolls.payslip',
                compact('payroll')
            );

        } catch (\Exception $e) {

            return redirect('/payrolls')
                ->with(
                    'error',
                    'Connection Error: ' . $e->getMessage()
                );
        }
    }
}




