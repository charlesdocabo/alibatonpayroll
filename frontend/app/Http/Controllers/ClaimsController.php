<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AuditLog;

class ClaimsController extends Controller
{
    private string $gateway;

    public function __construct()
    {
        $this->gateway = config('services.gateway.url');
    }

    /**
     * Display claims and monitoring statistics.
     */
    public function index()
    {
        try {
            $response = Http::timeout(10)->get(
                $this->gateway . '/api/claims'
            );

            if ($response->successful()) {
                $claims = $response->json('data', []);

                if (!is_array($claims)) {
                    $claims = [];
                }
            } else {
                $claims = [];
            }

            $claimCollection = collect($claims);

            /*
             * Claims Monitoring
             */

            $totalClaims = $claimCollection->count();

            $approvedClaims = $claimCollection->filter(function ($claim) {
                return strtolower($claim['status'] ?? '') === 'approved';
            });

            $pendingClaims = $claimCollection->filter(function ($claim) {
                return strtolower($claim['status'] ?? '') === 'pending';
            });

            $rejectedClaims = $claimCollection->filter(function ($claim) {
                return strtolower($claim['status'] ?? '') === 'rejected';
            });

            $totalClaimAmount = $claimCollection->sum(function ($claim) {
                return (float) ($claim['amount'] ?? 0);
            });

            $approvedClaimAmount = $approvedClaims->sum(function ($claim) {
                return (float) ($claim['amount'] ?? 0);
            });

            $pendingClaimAmount = $pendingClaims->sum(function ($claim) {
                return (float) ($claim['amount'] ?? 0);
            });

            /*
             * Claim Type Breakdown
             */

            $claimTypeBreakdown = $claimCollection
                ->groupBy(function ($claim) {
                    return $claim['claim_type'] ?? 'Other';
                })
                ->map(function ($items) {
                    return [
                        'count' => $items->count(),
                        'amount' => $items->sum(function ($claim) {
                            return (float) ($claim['amount'] ?? 0);
                        }),
                    ];
                });

            return view('claims.index', compact(
                'claims',
                'totalClaims',
                'approvedClaims',
                'pendingClaims',
                'rejectedClaims',
                'totalClaimAmount',
                'approvedClaimAmount',
                'pendingClaimAmount',
                'claimTypeBreakdown'
            ));

        } catch (\Exception $e) {

            return view('claims.index', [
                'claims' => [],
                'totalClaims' => 0,
                'approvedClaims' => collect(),
                'pendingClaims' => collect(),
                'rejectedClaims' => collect(),
                'totalClaimAmount' => 0,
                'approvedClaimAmount' => 0,
                'pendingClaimAmount' => 0,
                'claimTypeBreakdown' => collect(),
                'error' => 'Connection Error: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the Add Claim form.
     */
    public function create()
    {
        return view('claims.create');
    }

    /**
     * Store a new claim.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'claim_type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'claim_date' => 'required|date',
            'status' => 'required|string|max:30',
        ]);

        try {
            $response = Http::timeout(10)->post(
                $this->gateway . '/api/claims',
                $validated
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'CLAIM_CREATED',
                    'description' => 'Created claim for employee: '
                        . $validated['employee_id']
                        . ' | Type: '
                        . $validated['claim_type']
                        . ' | Amount: ₱'
                        . number_format((float) $validated['amount'], 2),
                    'ip_address' => $request->ip(),
                ]);

                return redirect('/claims')
                    ->with('success', 'Claim added successfully.');
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
     * Show the Edit Claim form.
     */
    public function edit($id)
    {
        try {
            $response = Http::timeout(10)->get(
                $this->gateway . '/api/claims/' . $id
            );

            if ($response->successful()) {
                $claim = $response->json('data');

                return view(
                    'claims.edit',
                    compact('claim')
                );
            }

            return redirect('/claims')
                ->with(
                    'error',
                    'Claim record not found.'
                );

        } catch (\Exception $e) {

            return redirect('/claims')
                ->with(
                    'error',
                    'Connection Error: ' . $e->getMessage()
                );
        }
    }

    /**
     * Update an existing claim.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'claim_type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'claim_date' => 'required|date',
            'status' => 'required|string|max:30',
        ]);

        try {
            $response = Http::timeout(10)->put(
                $this->gateway . '/api/claims/' . $id,
                $validated
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'CLAIM_UPDATED',
                    'description' => 'Updated claim ID: '
                        . $id
                        . ' for employee: '
                        . $validated['employee_id']
                        . ' | Type: '
                        . $validated['claim_type']
                        . ' | Status: '
                        . $validated['status']
                        . ' | Amount: ₱'
                        . number_format((float) $validated['amount'], 2),
                    'ip_address' => $request->ip(),
                ]);

                return redirect('/claims')
                    ->with(
                        'success',
                        'Claim updated successfully.'
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
     * Delete a claim.
     */
    public function destroy($id)
    {
        try {

            // Get claim information before deleting
            $claimResponse = Http::timeout(10)->get(
                $this->gateway . '/api/claims/' . $id
            );

            $claim = $claimResponse->successful()
                ? $claimResponse->json('data')
                : null;

            $response = Http::timeout(10)->delete(
                $this->gateway . '/api/claims/' . $id
            );

            if ($response->successful()) {

                $description = $claim
                    ? 'Deleted claim ID: '
                        . $id
                        . ' for employee: '
                        . ($claim['employee_id'] ?? 'Unknown')
                        . ' | Type: '
                        . ($claim['claim_type'] ?? 'Unknown')
                        . ' | Amount: ₱'
                        . number_format(
                            (float) ($claim['amount'] ?? 0),
                            2
                        )
                    : 'Deleted claim record ID: ' . $id;

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'CLAIM_DELETED',
                    'description' => $description,
                    'ip_address' => request()->ip(),
                ]);

                return redirect('/claims')
                    ->with(
                        'success',
                        'Claim deleted successfully.'
                    );
            }

            $errorMessage =
                $response->json('message')
                ?? $response->json('error')
                ?? $response->body();

            return redirect('/claims')
                ->with(
                    'error',
                    'API Error: ' . $errorMessage
                );

        } catch (\Exception $e) {

            return redirect('/claims')
                ->with(
                    'error',
                    'Connection Error: ' . $e->getMessage()
                );
        }
    }
}



