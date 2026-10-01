<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnalyticsController extends Controller
{
    public function index()
    {
        try {

            $employeeService = Http::timeout(10)->get(
                'http://employee-service:8000/api/employees'
            );

            $payrollService = Http::timeout(10)->get(
                'http://payroll-service:8000/api/payrolls'
            );

            $benefitsService = Http::timeout(10)->get(
                'http://benefits-service:8000/api/benefits'
            );

            $claimsService = Http::timeout(10)->get(
                'http://claims-service:8000/api/claims'
            );

            $incentiveService = Http::timeout(10)->get(
                'http://compensation-service:8000/api/incentives'
            );

            /*
            |--------------------------------------------------------------------------
            | Get Service Data
            |--------------------------------------------------------------------------
            */

            $employees = $employeeService->successful()
                ? ($employeeService->json('data') ?? [])
                : [];

            $payrolls = $payrollService->successful()
                ? ($payrollService->json('data') ?? [])
                : [];

            $benefits = $benefitsService->successful()
                ? ($benefitsService->json('data') ?? [])
                : [];

            $claims = $claimsService->successful()
                ? ($claimsService->json('data') ?? [])
                : [];

            $incentives = $incentiveService->successful()
                ? ($incentiveService->json('data') ?? [])
                : [];

            /*
            |--------------------------------------------------------------------------
            | Employee Analytics
            |--------------------------------------------------------------------------
            */

            $totalEmployees = count($employees);

            $activeEmployees = collect($employees)
                ->filter(function ($employee) {
                    return strtolower(
                        trim($employee['status'] ?? '')
                    ) === 'active';
                })
                ->count();

            $inactiveEmployees = collect($employees)
                ->filter(function ($employee) {
                    return strtolower(
                        trim($employee['status'] ?? '')
                    ) === 'inactive';
                })
                ->count();

            $averageSalary = collect($employees)
                ->avg(function ($employee) {
                    return (float) ($employee['salary'] ?? 0);
                }) ?? 0;

            /*
            |--------------------------------------------------------------------------
            | Payroll Analytics
            |--------------------------------------------------------------------------
            */

            $payrollRecords = count($payrolls);

            $totalPayroll = collect($payrolls)
                ->sum(function ($payroll) {
                    return (float) ($payroll['gross_pay'] ?? 0);
                });

            $totalNetPayroll = collect($payrolls)
                ->sum(function ($payroll) {
                    return (float) ($payroll['net_pay'] ?? 0);
                });

            $totalDeductions = collect($payrolls)
                ->sum(function ($payroll) {
                    return (float) ($payroll['total_deductions'] ?? 0);
                });

            /*
            |--------------------------------------------------------------------------
            | Benefits Analytics
            |--------------------------------------------------------------------------
            */

            $benefitRecords = count($benefits);

            $totalBenefits = collect($benefits)
                ->sum(function ($benefit) {
                    return (float) ($benefit['amount'] ?? 0);
                });

            /*
            |--------------------------------------------------------------------------
            | Claims Analytics
            |--------------------------------------------------------------------------
            */

            $claimRecords = count($claims);

            $totalClaims = collect($claims)
                ->sum(function ($claim) {
                    return (float) ($claim['amount'] ?? 0);
                });

            $pendingClaims = collect($claims)
                ->filter(function ($claim) {
                    return strtolower(
                        trim($claim['status'] ?? '')
                    ) === 'pending';
                })
                ->count();

            $approvedClaims = collect($claims)
                ->filter(function ($claim) {
                    return strtolower(
                        trim($claim['status'] ?? '')
                    ) === 'approved';
                })
                ->count();

            $rejectedClaims = collect($claims)
                ->filter(function ($claim) {
                    return strtolower(
                        trim($claim['status'] ?? '')
                    ) === 'rejected';
                })
                ->count();

            /*
            |--------------------------------------------------------------------------
            | Incentive Analytics
            |--------------------------------------------------------------------------
            */

            $incentiveRecords = count($incentives);

            $totalIncentives = collect($incentives)
                ->sum(function ($incentive) {
                    return (float) ($incentive['amount'] ?? 0);
                });

            /*
            |--------------------------------------------------------------------------
            | Service Health
            |--------------------------------------------------------------------------
            */

            $services = [
                'employee_service' => $employeeService->successful(),
                'payroll_service' => $payrollService->successful(),
                'benefits_service' => $benefitsService->successful(),
                'claims_service' => $claimsService->successful(),
                'compensation_service' => $incentiveService->successful(),
            ];

            /*
            |--------------------------------------------------------------------------
            | Analytics Response
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,

                'message' => 'Analytics generated successfully.',

                'data' => [

                    // Employees
                    'total_employees' => $totalEmployees,
                    'active_employees' => $activeEmployees,
                    'inactive_employees' => $inactiveEmployees,
                    'average_salary' => round($averageSalary, 2),

                    // Payroll
                    'payroll_records' => $payrollRecords,
                    'total_payroll' => round($totalPayroll, 2),
                    'total_net_payroll' => round($totalNetPayroll, 2),
                    'total_deductions' => round($totalDeductions, 2),

                    // Benefits
                    'benefit_records' => $benefitRecords,
                    'total_benefits' => round($totalBenefits, 2),

                    // Claims
                    'claim_records' => $claimRecords,
                    'total_claims' => round($totalClaims, 2),
                    'pending_claims' => $pendingClaims,
                    'approved_claims' => $approvedClaims,
                    'rejected_claims' => $rejectedClaims,

                    // Incentives
                    'incentive_records' => $incentiveRecords,
                    'total_incentives' => round($totalIncentives, 2),

                    // Service Health
                    'services' => $services,
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,

                'message' => 'Unable to generate analytics.',

                'error' => $e->getMessage(),

                'data' => [
                    'total_employees' => 0,
                    'active_employees' => 0,
                    'inactive_employees' => 0,
                    'average_salary' => 0,

                    'payroll_records' => 0,
                    'total_payroll' => 0,
                    'total_net_payroll' => 0,
                    'total_deductions' => 0,

                    'benefit_records' => 0,
                    'total_benefits' => 0,

                    'claim_records' => 0,
                    'total_claims' => 0,
                    'pending_claims' => 0,
                    'approved_claims' => 0,
                    'rejected_claims' => 0,

                    'incentive_records' => 0,
                    'total_incentives' => 0,

                    'services' => [],
                ]

            ], 500);
        }
    }

    public function show($id)
    {
        $report = AnalyticsReport::find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Analytics report not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|string|max:255',
            'total_employees' => 'required|integer|min:0',
            'total_payroll' => 'required|numeric|min:0',
            'total_benefits' => 'required|numeric|min:0',
            'total_claims' => 'required|numeric|min:0',
            'total_incentives' => 'required|numeric|min:0',
            'active_employees' => 'required|integer|min:0',
            'pending_claims' => 'required|integer|min:0',
            'report_date' => 'required|date',
        ]);

        $report = AnalyticsReport::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Analytics report created successfully.',
            'data' => $report
        ], 201);
    }
}


