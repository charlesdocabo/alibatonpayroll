<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class AnalyticsController extends Controller
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
                $this->gateway . '/api/analytics'
            );

            $analytics = $response->successful()
                ? $response->json('data', [])
                : [];

            /*
            |--------------------------------------------------------------------------
            | ANALYTICS MONITORING VALUES
            |--------------------------------------------------------------------------
            */

            $totalEmployees = (int) ($analytics['total_employees'] ?? 0);

            $activeEmployees = (int) ($analytics['active_employees'] ?? 0);

            $inactiveEmployees = (int) ($analytics['inactive_employees'] ?? 0);

            $totalPayroll = (float) ($analytics['total_payroll'] ?? 0);

            $averageSalary = (float) ($analytics['average_salary'] ?? 0);

            $payrollRecords = (int) ($analytics['payroll_records'] ?? 0);

            $totalBenefits = (int) ($analytics['total_benefits'] ?? 0);

            $totalClaims = (int) ($analytics['total_claims'] ?? 0);


            /*
            |--------------------------------------------------------------------------
            | ACTIVE EMPLOYEE PERCENTAGE
            |--------------------------------------------------------------------------
            */

            $activePercentage = $totalEmployees > 0
                ? ($activeEmployees / $totalEmployees) * 100
                : 0;


            /*
            |--------------------------------------------------------------------------
            | INACTIVE EMPLOYEE PERCENTAGE
            |--------------------------------------------------------------------------
            */

            $inactivePercentage = $totalEmployees > 0
                ? ($inactiveEmployees / $totalEmployees) * 100
                : 0;


            return view('analytics.index', compact(
                'analytics',
                'totalEmployees',
                'activeEmployees',
                'inactiveEmployees',
                'totalPayroll',
                'averageSalary',
                'payrollRecords',
                'totalBenefits',
                'totalClaims',
                'activePercentage',
                'inactivePercentage'
            ));

        } catch (\Exception $e) {

            return view('analytics.index', [
                'analytics' => [],

                'totalEmployees' => 0,
                'activeEmployees' => 0,
                'inactiveEmployees' => 0,

                'totalPayroll' => 0,
                'averageSalary' => 0,
                'payrollRecords' => 0,

                'totalBenefits' => 0,
                'totalClaims' => 0,

                'activePercentage' => 0,
                'inactivePercentage' => 0,

                'error' => 'Connection Error: ' . $e->getMessage()
            ]);
        }
    }
}




