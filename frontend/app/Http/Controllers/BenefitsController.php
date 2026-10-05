<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AuditLog;

class BenefitsController extends Controller
{
    private string $gateway;

    public function __construct()
    {
        $this->gateway = config('services.gateway.url');
    }

    /**
     * Display all benefits and monitoring statistics.
     */
    public function index()
    {
        try {
            $response = Http::timeout(10)->get(
                $this->gateway . '/api/benefits'
            );

            if ($response->successful()) {
                $benefits = $response->json('data', []);

                if (!is_array($benefits)) {
                    $benefits = [];
                }
            } else {
                $benefits = [];
            }

            $benefitCollection = collect($benefits);

            /*
             * Benefits Monitoring
             */

            $totalBenefits = $benefitCollection->count();

            $activeBenefits = $benefitCollection->filter(function ($benefit) {
                return strtolower($benefit['status'] ?? '') === 'active';
            });

            $pendingBenefits = $benefitCollection->filter(function ($benefit) {
                return strtolower($benefit['status'] ?? '') === 'pending';
            });

            $inactiveBenefits = $benefitCollection->filter(function ($benefit) {
                return strtolower($benefit['status'] ?? '') === 'inactive';
            });

            $totalBenefitAmount = $benefitCollection->sum(function ($benefit) {
                return (float) ($benefit['amount'] ?? 0);
            });

            $activeBenefitAmount = $activeBenefits->sum(function ($benefit) {
                return (float) ($benefit['amount'] ?? 0);
            });

            /*
             * HMO Specific Monitoring
             */
            $hmoBenefits = $benefitCollection->filter(function ($b) {
                $type = strtolower($b['benefit_type'] ?? '');
                return str_contains($type, 'hmo') || str_contains($type, 'health') || str_contains($type, 'medical');
            });
            $totalHmoEnrollees = $hmoBenefits->count();
            $activeHmoEnrollees = $hmoBenefits->filter(fn($b) => strtolower($b['status'] ?? '') === 'active')->count();
            $totalHmoCoverage = $hmoBenefits->sum(fn($b) => (float) ($b['coverage'] ?? $b['amount'] ?? 0));

            /*
             * Benefit Type Breakdown
             */

            $benefitTypeBreakdown = $benefitCollection
                ->groupBy(function ($benefit) {
                    return $benefit['benefit_type'] ?? 'Other';
                })
                ->map(function ($items) {
                    return [
                        'count' => $items->count(),
                        'amount' => $items->sum(function ($benefit) {
                            return (float) ($benefit['amount'] ?? 0);
                        }),
                    ];
                });

            // Employee map for name / department
            $employeeMap = [];
            try {
                $empRes = Http::timeout(5)->get($this->gateway . '/api/employees');
                if ($empRes->successful()) {
                    foreach ($empRes->json('data', []) as $emp) {
                        $employeeMap[$emp['employee_id']] = [
                            'name' => trim(($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? '')),
                            'department' => $emp['department'] ?? 'General',
                            'position' => $emp['position'] ?? 'Staff',
                        ];
                    }
                }
            } catch (\Exception $e) {}

            // Government Contributions Tracking data from Payroll records
            $governmentContributions = [];
            $totalSssContributions = 0;
            $totalPhilHealthContributions = 0;
            $totalPagibigContributions = 0;

            try {
                $payRes = Http::timeout(6)->get($this->gateway . '/api/payrolls');
                if ($payRes->successful()) {
                    $payrolls = $payRes->json('data', []) ?? [];
                    foreach ($payrolls as $p) {
                        $sss = (float) ($p['sss_deduction'] ?? 0);
                        $ph = (float) ($p['philhealth_deduction'] ?? 0);
                        $pag = (float) ($p['pagibig_deduction'] ?? 0);
                        $totalGov = $sss + $ph + $pag;

                        $totalSssContributions += $sss;
                        $totalPhilHealthContributions += $ph;
                        $totalPagibigContributions += $pag;

                        $governmentContributions[] = [
                            'payroll_id' => $p['id'] ?? null,
                            'employee_id' => $p['employee_id'] ?? 'N/A',
                            'pay_date' => $p['pay_date'] ?? null,
                            'basic_salary' => (float) ($p['basic_salary'] ?? 0),
                            'sss_deduction' => $sss,
                            'philhealth_deduction' => $ph,
                            'pagibig_deduction' => $pag,
                            'total_gov_contribution' => $totalGov,
                        ];
                    }
                }
            } catch (\Exception $e) {}

            $grandTotalGovContributions = $totalSssContributions + $totalPhilHealthContributions + $totalPagibigContributions;

            return view('benefits.index', compact(
                'benefits',
                'totalBenefits',
                'activeBenefits',
                'pendingBenefits',
                'inactiveBenefits',
                'totalBenefitAmount',
                'activeBenefitAmount',
                'benefitTypeBreakdown',
                'hmoBenefits',
                'totalHmoEnrollees',
                'activeHmoEnrollees',
                'totalHmoCoverage',
                'employeeMap',
                'governmentContributions',
                'totalSssContributions',
                'totalPhilHealthContributions',
                'totalPagibigContributions',
                'grandTotalGovContributions'
            ));

        } catch (\Exception $e) {

            return view('benefits.index', [
                'benefits' => [],
                'totalBenefits' => 0,
                'activeBenefits' => collect(),
                'pendingBenefits' => collect(),
                'inactiveBenefits' => collect(),
                'totalBenefitAmount' => 0,
                'activeBenefitAmount' => 0,
                'benefitTypeBreakdown' => collect(),
                'hmoBenefits' => collect(),
                'totalHmoEnrollees' => 0,
                'activeHmoEnrollees' => 0,
                'totalHmoCoverage' => 0,
                'employeeMap' => [],
                'governmentContributions' => [],
                'totalSssContributions' => 0,
                'totalPhilHealthContributions' => 0,
                'totalPagibigContributions' => 0,
                'grandTotalGovContributions' => 0,
                'error' => 'Connection Error: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the Add Benefit form.
     */
    public function create()
    {
        $employees = [];
        try {
            $empRes = Http::timeout(5)->get($this->gateway . '/api/employees');
            if ($empRes->successful()) {
                $employees = $empRes->json('data', []) ?? [];
            }
        } catch (\Exception $e) {}

        return view('benefits.create', compact('employees'));
    }

    /**
     * Store a new benefit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'benefit_type' => 'required|string|max:100',
            'provider' => 'nullable|string|max:150',
            'coverage' => 'nullable|numeric|min:0',
            'membership_number' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|string|max:30',
            'description' => 'nullable|string',
        ]);

        try {
            $response = Http::timeout(10)->post(
                $this->gateway . '/api/benefits',
                $validated
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'BENEFIT_CREATED',
                    'description' => 'Created benefit for employee: '
                        . $validated['employee_id']
                        . ' | Type: '
                        . $validated['benefit_type']
                        . ' | Amount: ₱'
                        . number_format((float) $validated['amount'], 2),
                    'ip_address' => $request->ip(),
                ]);

                return redirect('/benefits')
                    ->with('success', 'Benefit added successfully.');
            }

            $errorMessage =
                $response->json('message')
                ?? $response->json('error')
                ?? $response->body();

            return back()
                ->withInput()
                ->with('error', 'API Error: ' . $errorMessage);

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Connection Error: ' . $e->getMessage()
                );
        }
    }

    /**
     * Show the Edit Benefit form.
     */
    public function edit($id)
    {
        try {
            $response = Http::timeout(10)->get(
                $this->gateway . '/api/benefits/' . $id
            );

            if ($response->successful()) {
                $benefit = $response->json('data');

                $employees = [];
                try {
                    $empRes = Http::timeout(5)->get($this->gateway . '/api/employees');
                    if ($empRes->successful()) {
                        $employees = $empRes->json('data', []) ?? [];
                    }
                } catch (\Exception $e) {}

                return view(
                    'benefits.edit',
                    compact('benefit', 'employees')
                );
            }

            return redirect('/benefits')
                ->with('error', 'Benefit record not found.');

        } catch (\Exception $e) {

            return redirect('/benefits')
                ->with(
                    'error',
                    'Connection Error: ' . $e->getMessage()
                );
        }
    }

    /**
     * Update an existing benefit.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'benefit_type' => 'required|string|max:100',
            'provider' => 'nullable|string|max:150',
            'coverage' => 'nullable|numeric|min:0',
            'membership_number' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|string|max:30',
            'description' => 'nullable|string',
        ]);

        try {
            $response = Http::timeout(10)->put(
                $this->gateway . '/api/benefits/' . $id,
                $validated
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'BENEFIT_UPDATED',
                    'description' => 'Updated benefit ID: '
                        . $id
                        . ' for employee: '
                        . $validated['employee_id']
                        . ' | Type: '
                        . $validated['benefit_type']
                        . ' | Amount: ₱'
                        . number_format((float) $validated['amount'], 2),
                    'ip_address' => $request->ip(),
                ]);

                return redirect('/benefits')
                    ->with(
                        'success',
                        'Benefit updated successfully.'
                    );
            }

            $errorMessage =
                $response->json('message')
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

    /**
     * Delete a benefit.
     */
    public function destroy($id)
    {
        try {

            // Get benefit information before deleting
            $benefitResponse = Http::timeout(10)->get(
                $this->gateway . '/api/benefits/' . $id
            );

            $benefit = $benefitResponse->successful()
                ? $benefitResponse->json('data')
                : null;

            $response = Http::timeout(10)->delete(
                $this->gateway . '/api/benefits/' . $id
            );

            if ($response->successful()) {

                $description = $benefit
                    ? 'Deleted benefit ID: '
                        . $id
                        . ' for employee: '
                        . ($benefit['employee_id'] ?? 'Unknown')
                        . ' | Type: '
                        . ($benefit['benefit_type'] ?? 'Unknown')
                    : 'Deleted benefit record ID: ' . $id;

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'BENEFIT_DELETED',
                    'description' => $description,
                    'ip_address' => request()->ip(),
                ]);

                return redirect('/benefits')
                    ->with(
                        'success',
                        'Benefit deleted successfully.'
                    );
            }

            $errorMessage =
                $response->json('message')
                ?? $response->json('error')
                ?? $response->body();

            return redirect('/benefits')
                ->with(
                    'error',
                    'API Error: ' . $errorMessage
                );

        } catch (\Exception $e) {

            return redirect('/benefits')
                ->with(
                    'error',
                    'Connection Error: ' . $e->getMessage()
                );
        }
    }

    /**
     * JSON endpoint for auto-refresh polling (called every 30s by the frontend).
     */
    public function refreshData()
    {
        try {
            $benefits = [];
            $benefitRes = Http::timeout(10)->get($this->gateway . '/api/benefits');
            if ($benefitRes->successful()) {
                $benefits = $benefitRes->json('data', []) ?? [];
            }
            $bc = collect($benefits);

            $hmoBenefits = $bc->filter(function ($b) {
                $type = strtolower($b['benefit_type'] ?? '');
                return str_contains($type, 'hmo')
                    || str_contains($type, 'health')
                    || str_contains($type, 'medical');
            });

            $totalSss = 0;
            $totalPh  = 0;
            $totalPag = 0;

            try {
                $payRes = Http::timeout(6)->get($this->gateway . '/api/payrolls');
                if ($payRes->successful()) {
                    foreach ($payRes->json('data', []) ?? [] as $p) {
                        $totalSss += (float) ($p['sss_deduction']       ?? 0);
                        $totalPh  += (float) ($p['philhealth_deduction'] ?? 0);
                        $totalPag += (float) ($p['pagibig_deduction']    ?? 0);
                    }
                }
            } catch (\Exception $e) {}

            $activeBc   = $bc->filter(fn($b) => strtolower($b['status'] ?? '') === 'active');
            $pendingBc  = $bc->filter(fn($b) => strtolower($b['status'] ?? '') === 'pending');
            $inactiveBc = $bc->filter(fn($b) => strtolower($b['status'] ?? '') === 'inactive');

            $benefitTypes = $bc
                ->groupBy(fn($b) => $b['benefit_type'] ?? 'Other')
                ->map(fn($items) => [
                    'count'  => $items->count(),
                    'amount' => $items->sum(fn($b) => (float) ($b['amount'] ?? 0)),
                ]);

            return response()->json([
                'success' => true,
                'stats'   => [
                    'total_benefits'        => $bc->count(),
                    'active_benefits'       => $activeBc->count(),
                    'pending_benefits'      => $pendingBc->count(),
                    'inactive_benefits'     => $inactiveBc->count(),
                    'total_benefit_amount'  => $bc->sum(fn($b) => (float) ($b['amount'] ?? 0)),
                    'active_benefit_amount' => $activeBc->sum(fn($b) => (float) ($b['amount'] ?? 0)),
                    'total_hmo_enrollees'   => $hmoBenefits->count(),
                    'active_hmo_enrollees'  => $hmoBenefits->filter(fn($b) => strtolower($b['status'] ?? '') === 'active')->count(),
                    'total_hmo_coverage'    => $hmoBenefits->sum(fn($b) => (float) ($b['coverage'] ?? $b['amount'] ?? 0)),
                    'total_sss'             => $totalSss,
                    'total_philhealth'      => $totalPh,
                    'total_pagibig'         => $totalPag,
                    'grand_total_gov'       => $totalSss + $totalPh + $totalPag,
                ],
                'benefit_types' => $benefitTypes,
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Stream government contributions as a downloadable CSV file.
     */
    public function exportContributions()
    {
        $rows = [];

        try {
            $employeeMap = [];
            try {
                $empRes = Http::timeout(5)->get($this->gateway . '/api/employees');
                if ($empRes->successful()) {
                    foreach ($empRes->json('data', []) as $emp) {
                        $employeeMap[$emp['employee_id']] =
                            trim(($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? ''));
                    }
                }
            } catch (\Exception $e) {}

            $payRes = Http::timeout(10)->get($this->gateway . '/api/payrolls');
            if ($payRes->successful()) {
                foreach ($payRes->json('data', []) ?? [] as $p) {
                    $sss = (float) ($p['sss_deduction']       ?? 0);
                    $ph  = (float) ($p['philhealth_deduction'] ?? 0);
                    $pag = (float) ($p['pagibig_deduction']    ?? 0);
                    $rows[] = [
                        $p['id']          ?? '',
                        $p['employee_id'] ?? '',
                        $employeeMap[$p['employee_id'] ?? ''] ?? 'Unknown',
                        $p['pay_date']    ?? '',
                        number_format((float) ($p['basic_salary'] ?? 0), 2),
                        number_format($sss,  2),
                        number_format($ph,   2),
                        number_format($pag,  2),
                        number_format($sss + $ph + $pag, 2),
                    ];
                }
            }
        } catch (\Exception $e) {}

        $filename = 'government_contributions_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($rows) {
            $fp = fopen('php://output', 'w');
            fputcsv($fp, [
                'Payroll ID', 'Employee ID', 'Employee Name',
                'Pay Date', 'Basic Salary (PHP)',
                'SSS (PHP)', 'PhilHealth (PHP)', 'Pag-IBIG (PHP)',
                'Total Gov Contribution (PHP)',
            ]);
            foreach ($rows as $row) {
                fputcsv($fp, $row);
            }
            fclose($fp);
        };

        return response()->stream($callback, 200, $headers);
    }
}
