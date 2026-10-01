<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
       $gateway = config('services.gateway.url');

        $employees = Http::timeout(10)
            ->get($gateway . '/api/employees')
            ->json();

        $payrolls = Http::timeout(10)
            ->get($gateway . '/api/payrolls')
            ->json();

        $benefits = Http::timeout(10)
            ->get($gateway . '/api/benefits')
            ->json();

        $claims = Http::timeout(10)
            ->get($gateway . '/api/claims')
            ->json();

        $incentives = Http::timeout(10)
            ->get($gateway . '/api/incentives')
            ->json();

        $salaryGrades = Http::timeout(10)
            ->get($gateway . '/api/salary-grades')
            ->json();

        $analytics = Http::timeout(10)
            ->get($gateway . '/api/analytics')
            ->json();

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
