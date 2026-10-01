<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AuditLog;

class IncentivesController extends Controller
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
                $this->gateway . '/api/incentives'
            );

            $incentives = $response->successful()
                ? ($response->json('data', []) ?? [])
                : [];

            // =========================
            // INCENTIVE MONITORING
            // =========================

            $totalIncentives = count($incentives);

            $approvedIncentives = collect($incentives)
                ->filter(function ($item) {
                    return strtolower(trim($item['status'] ?? '')) === 'approved';
                })
                ->count();

            $pendingIncentives = collect($incentives)
                ->filter(function ($item) {
                    return strtolower(trim($item['status'] ?? '')) === 'pending';
                })
                ->count();

            $rejectedIncentives = collect($incentives)
                ->filter(function ($item) {
                    return strtolower(trim($item['status'] ?? '')) === 'rejected';
                })
                ->count();

            // =========================
            // FINANCIAL MONITORING
            // =========================

            $totalIncentiveAmount = collect($incentives)
                ->sum(function ($item) {
                    return (float) ($item['amount'] ?? 0);
                });

            $approvedIncentiveAmount = collect($incentives)
                ->filter(function ($item) {
                    return strtolower(trim($item['status'] ?? '')) === 'approved';
                })
                ->sum(function ($item) {
                    return (float) ($item['amount'] ?? 0);
                });

            $pendingIncentiveAmount = collect($incentives)
                ->filter(function ($item) {
                    return strtolower(trim($item['status'] ?? '')) === 'pending';
                })
                ->sum(function ($item) {
                    return (float) ($item['amount'] ?? 0);
                });

            // =========================
            // INCENTIVE TYPE BREAKDOWN
            // =========================

            $incentiveTypeBreakdown = collect($incentives)
                ->groupBy(function ($item) {
                    return trim($item['incentive_type'] ?? '') ?: 'Unspecified';
                })
                ->map(function ($items, $type) {
                    return [
                        'type' => $type,
                        'count' => $items->count(),
                        'amount' => $items->sum(function ($item) {
                            return (float) ($item['amount'] ?? 0);
                        }),
                    ];
                })
                ->values();

            return view('incentives.index', compact(
                'incentives',
                'totalIncentives',
                'approvedIncentives',
                'pendingIncentives',
                'rejectedIncentives',
                'totalIncentiveAmount',
                'approvedIncentiveAmount',
                'pendingIncentiveAmount',
                'incentiveTypeBreakdown'
            ));

        } catch (\Exception $e) {

            return view('incentives.index', [
                'incentives' => [],
                'totalIncentives' => 0,
                'approvedIncentives' => 0,
                'pendingIncentives' => 0,
                'rejectedIncentives' => 0,
                'totalIncentiveAmount' => 0,
                'approvedIncentiveAmount' => 0,
                'pendingIncentiveAmount' => 0,
                'incentiveTypeBreakdown' => collect(),
                'error' => 'Connection Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function create()
    {
        return view('incentives.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'incentive_type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'incentive_date' => 'required|date',
            'status' => 'required|string|max:30',
        ]);

        try {
            $response = Http::timeout(10)->post(
                $this->gateway . '/api/incentives',
                $validated
            );

            if ($response->successful()) {

                // =========================
                // AUDIT LOG
                // =========================

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'INCENTIVE_CREATED',
                    'description' => 'Created incentive for employee: '
                        . $validated['employee_id']
                        . ' | Type: '
                        . $validated['incentive_type']
                        . ' | Amount: ₱'
                        . number_format((float) $validated['amount'], 2),
                    'ip_address' => $request->ip(),
                ]);

                return redirect('/incentives')
                    ->with(
                        'success',
                        'Incentive added successfully.'
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

    public function edit($id)
    {
        try {
            $response = Http::timeout(10)->get(
                $this->gateway . '/api/incentives/' . $id
            );

            if ($response->successful()) {

                $incentive = $response->json('data');

                return view(
                    'incentives.edit',
                    compact('incentive')
                );
            }

            return redirect('/incentives')
                ->with(
                    'error',
                    'Incentive record not found.'
                );

        } catch (\Exception $e) {

            return redirect('/incentives')
                ->with(
                    'error',
                    'Connection Error: ' . $e->getMessage()
                );
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'incentive_type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'incentive_date' => 'required|date',
            'status' => 'required|string|max:30',
        ]);

        try {
            $response = Http::timeout(10)->put(
                $this->gateway . '/api/incentives/' . $id,
                $validated
            );

            if ($response->successful()) {

                // =========================
                // AUDIT LOG
                // =========================

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'INCENTIVE_UPDATED',
                    'description' => 'Updated incentive ID: '
                        . $id
                        . ' for employee: '
                        . $validated['employee_id']
                        . ' | Type: '
                        . $validated['incentive_type']
                        . ' | Status: '
                        . $validated['status']
                        . ' | Amount: ₱'
                        . number_format((float) $validated['amount'], 2),
                    'ip_address' => $request->ip(),
                ]);

                return redirect('/incentives')
                    ->with(
                        'success',
                        'Incentive updated successfully.'
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

    public function destroy(Request $request, $id)
    {
        try {

            // Get the incentive first so the audit log
            // can contain useful information.
            $incentiveResponse = Http::timeout(10)->get(
                $this->gateway . '/api/incentives/' . $id
            );

            $incentive = $incentiveResponse->successful()
                ? $incentiveResponse->json('data')
                : null;

            $response = Http::timeout(10)->delete(
                $this->gateway . '/api/incentives/' . $id
            );

            if ($response->successful()) {

                // =========================
                // AUDIT LOG
                // =========================

                $description = $incentive
                    ? 'Deleted incentive ID: '
                        . $id
                        . ' for employee: '
                        . ($incentive['employee_id'] ?? 'Unknown')
                        . ' | Type: '
                        . ($incentive['incentive_type'] ?? 'Unknown')
                        . ' | Amount: ₱'
                        . number_format(
                            (float) ($incentive['amount'] ?? 0),
                            2
                        )
                    : 'Deleted incentive record ID: ' . $id;

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'INCENTIVE_DELETED',
                    'description' => $description,
                    'ip_address' => $request->ip(),
                ]);

                return redirect('/incentives')
                    ->with(
                        'success',
                        'Incentive deleted successfully.'
                    );
            }

            $errorMessage = $response->json('message')
                ?? $response->json('error')
                ?? $response->body();

            return redirect('/incentives')
                ->with(
                    'error',
                    'API Error: ' . $errorMessage
                );

        } catch (\Exception $e) {

            return redirect('/incentives')
                ->with(
                    'error',
                    'Connection Error: ' . $e->getMessage()
                );
        }
    }
}



