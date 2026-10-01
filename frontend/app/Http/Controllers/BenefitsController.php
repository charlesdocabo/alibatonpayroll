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

            return view('benefits.index', compact(
                'benefits',
                'totalBenefits',
                'activeBenefits',
                'pendingBenefits',
                'inactiveBenefits',
                'totalBenefitAmount',
                'activeBenefitAmount',
                'benefitTypeBreakdown'
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
                'error' => 'Connection Error: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the Add Benefit form.
     */
    public function create()
    {
        return view('benefits.create');
    }

    /**
     * Store a new benefit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'benefit_type' => 'required|string|max:100',
            'provider' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:30',
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

                return view(
                    'benefits.edit',
                    compact('benefit')
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
            'provider' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:30',
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
}



