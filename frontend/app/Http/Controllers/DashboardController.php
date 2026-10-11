<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $gateway = config('services.gateway.url', 'http://api-gateway:8000');

        $fetchData = function (string $endpoint): array {
            try {
                $response = Http::timeout(10)->get($endpoint);

                if ($response->successful()) {
                    return $response->json() ?? [];
                }
            } catch (\Throwable $e) {
                report($e);
            }

            return [];
        };

        $employees = $fetchData($gateway . '/api/employees');
        $payrolls = $fetchData($gateway . '/api/payrolls');
        $benefits = $fetchData($gateway . '/api/benefits');
        $claims = $fetchData($gateway . '/api/claims');
        $incentives = $fetchData($gateway . '/api/incentives');
        $salaryGrades = $fetchData($gateway . '/api/salary-grades');
        $analyticsResponse = $fetchData($gateway . '/api/analytics');

        $analytics = $analyticsResponse['data'] ?? [];

        return view('dashboard', compact(
            'employees',
            'payrolls',
            'benefits',
            'claims',
            'incentives',
            'salaryGrades',
            'analytics'
        ));
    }
}
