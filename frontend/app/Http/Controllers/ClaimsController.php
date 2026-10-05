<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
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
     * Employees see only their own claims.
     * Admin/HR see all claims.
     */
    public function index()
    {
        $user = auth()->user();
        $isEmployee = $user && strtolower($user->role ?? '') === 'employee';

        try {
            $response = Http::timeout(10)->get(
                $this->gateway . '/api/claims'
            );

            if ($response->successful()) {
                $allClaims = $response->json('data', []);
                if (!is_array($allClaims)) {
                    $allClaims = [];
                }
            } else {
                $allClaims = [];
            }

            // Employees only see their own claims
            if ($isEmployee && $user->employee_id) {
                $claims = collect($allClaims)->filter(function ($claim) use ($user) {
                    return ($claim['employee_id'] ?? '') === $user->employee_id;
                })->values()->all();
            } else {
                $claims = $allClaims;
            }

            $claimCollection = collect($claims);

            // =========================
            // CLAIMS MONITORING
            // =========================

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

            $returnedClaims = $claimCollection->filter(function ($claim) {
                return strtolower($claim['status'] ?? '') === 'returned';
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

            // =========================
            // CLAIM TYPE BREAKDOWN
            // =========================

            $claimTypeBreakdown = $claimCollection
                ->groupBy(function ($claim) {
                    return $claim['claim_type'] ?? 'Other';
                })
                ->map(function ($items) {
                    return [
                        'count'  => $items->count(),
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
                'returnedClaims',
                'totalClaimAmount',
                'approvedClaimAmount',
                'pendingClaimAmount',
                'claimTypeBreakdown',
                'isEmployee'
            ));

        } catch (\Exception $e) {

            return view('claims.index', [
                'claims'             => [],
                'totalClaims'        => 0,
                'approvedClaims'     => collect(),
                'pendingClaims'      => collect(),
                'rejectedClaims'     => collect(),
                'returnedClaims'     => collect(),
                'totalClaimAmount'   => 0,
                'approvedClaimAmount'=> 0,
                'pendingClaimAmount' => 0,
                'claimTypeBreakdown' => collect(),
                'isEmployee'         => $isEmployee,
                'error'              => 'Connection Error: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the Add Claim form.
     */
    public function create()
    {
        $user = auth()->user();
        return view('claims.create', compact('user'));
    }

    /**
     * Store a new claim.
     * Employees submit with their own employee_id and status=pending.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $isEmployee = $user && strtolower($user->role ?? '') === 'employee';

        $rules = [
            'claim_type'  => 'required|string|max:100',
            'description' => 'nullable|string',
            'amount'      => 'required|numeric|min:0',
            'claim_date'  => 'required|date',
            'receipt'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];

        // Employees always submit for themselves; Admin/HR can specify employee_id
        if ($isEmployee) {
            $rules['employee_id'] = 'nullable|string|max:50';
        } else {
            $rules['employee_id'] = 'required|string|max:50';
            $rules['status'] = 'required|string|max:30';
        }

        $validated = $request->validate($rules);

        // Force employee_id and status for employee role
        if ($isEmployee) {
            $validated['employee_id'] = $user->employee_id ?? $user->id;
            $validated['status'] = 'pending';
        }

        // Handle receipt upload
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $receiptPath = $file->storeAs('claims/receipts', $fileName, 'public');
            $validated['receipt_path'] = $receiptPath;
        }

        $payload = collect($validated)->except(['receipt'])->toArray();

        try {
            $response = Http::timeout(10)->post(
                $this->gateway . '/api/claims',
                $payload
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id'     => auth()->id(),
                    'action'      => 'CLAIM_CREATED',
                    'description' => 'Created claim for employee: '
                        . ($validated['employee_id'] ?? 'self')
                        . ' | Type: '
                        . $validated['claim_type']
                        . ' | Amount: ₱'
                        . number_format((float) $validated['amount'], 2),
                    'ip_address' => $request->ip(),
                ]);

                return redirect('/claims')
                    ->with('success', 'Claim submitted successfully.');
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
                ->with('error', 'Connection Error: ' . $e->getMessage());
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
                return view('claims.edit', compact('claim'));
            }

            return redirect('/claims')
                ->with('error', 'Claim record not found.');

        } catch (\Exception $e) {

            return redirect('/claims')
                ->with('error', 'Connection Error: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing claim.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'claim_type'  => 'required|string|max:100',
            'description' => 'nullable|string',
            'amount'      => 'required|numeric|min:0',
            'claim_date'  => 'required|date',
            'status'      => 'required|string|max:30',
        ]);

        try {
            $response = Http::timeout(10)->put(
                $this->gateway . '/api/claims/' . $id,
                $validated
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id'     => auth()->id(),
                    'action'      => 'CLAIM_UPDATED',
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
                    ->with('success', 'Claim updated successfully.');
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
                ->with('error', 'Connection Error: ' . $e->getMessage());
        }
    }

    /**
     * Approve a claim (Admin/HR only).
     */
    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'approval_notes' => 'nullable|string',
        ]);

        $approver = auth()->user()->name ?? auth()->user()->email ?? 'System';

        try {
            $response = Http::timeout(10)->patch(
                $this->gateway . '/api/claims/' . $id . '/approve',
                [
                    'approved_by'    => $approver,
                    'approval_notes' => $validated['approval_notes'] ?? null,
                ]
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id'     => auth()->id(),
                    'action'      => 'CLAIM_APPROVED',
                    'description' => 'Approved claim ID: ' . $id . ' by ' . $approver,
                    'ip_address'  => $request->ip(),
                ]);

                return redirect('/claims')
                    ->with('success', 'Claim approved successfully.');
            }

            return redirect('/claims')
                ->with('error', 'Failed to approve claim.');

        } catch (\Exception $e) {

            return redirect('/claims')
                ->with('error', 'Connection Error: ' . $e->getMessage());
        }
    }

    /**
     * Reject a claim (Admin/HR only).
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'approval_notes' => 'required|string',
        ]);

        $approver = auth()->user()->name ?? auth()->user()->email ?? 'System';

        try {
            $response = Http::timeout(10)->patch(
                $this->gateway . '/api/claims/' . $id . '/reject',
                [
                    'approved_by'    => $approver,
                    'approval_notes' => $validated['approval_notes'],
                ]
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id'     => auth()->id(),
                    'action'      => 'CLAIM_REJECTED',
                    'description' => 'Rejected claim ID: ' . $id . ' | Reason: ' . $validated['approval_notes'],
                    'ip_address'  => $request->ip(),
                ]);

                return redirect('/claims')
                    ->with('success', 'Claim rejected.');
            }

            return redirect('/claims')
                ->with('error', 'Failed to reject claim.');

        } catch (\Exception $e) {

            return redirect('/claims')
                ->with('error', 'Connection Error: ' . $e->getMessage());
        }
    }

    /**
     * Return a claim for revision (Admin/HR only).
     */
    public function returnClaim(Request $request, $id)
    {
        $validated = $request->validate([
            'return_reason' => 'required|string',
        ]);

        $approver = auth()->user()->name ?? auth()->user()->email ?? 'System';

        try {
            $response = Http::timeout(10)->patch(
                $this->gateway . '/api/claims/' . $id . '/return',
                [
                    'approved_by'   => $approver,
                    'return_reason' => $validated['return_reason'],
                ]
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id'     => auth()->id(),
                    'action'      => 'CLAIM_RETURNED',
                    'description' => 'Returned claim ID: ' . $id . ' for revision | Reason: ' . $validated['return_reason'],
                    'ip_address'  => $request->ip(),
                ]);

                return redirect('/claims')
                    ->with('success', 'Claim returned for revision.');
            }

            return redirect('/claims')
                ->with('error', 'Failed to return claim.');

        } catch (\Exception $e) {

            return redirect('/claims')
                ->with('error', 'Connection Error: ' . $e->getMessage());
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
                    'user_id'    => auth()->id(),
                    'action'     => 'CLAIM_DELETED',
                    'description'=> $description,
                    'ip_address' => request()->ip(),
                ]);

                return redirect('/claims')
                    ->with('success', 'Claim deleted successfully.');
            }

            $errorMessage =
                $response->json('message')
                ?? $response->json('error')
                ?? $response->body();

            return redirect('/claims')
                ->with('error', 'API Error: ' . $errorMessage);

        } catch (\Exception $e) {

            return redirect('/claims')
                ->with('error', 'Connection Error: ' . $e->getMessage());
        }
    }

    /**
     * Real-time JSON polling endpoint for Claims dashboard.
     * Returns live stats: counts by status, financial totals, type breakdown.
     */
    public function refreshData()
    {
        try {
            $res = Http::timeout(10)->get($this->gateway . '/api/claims');
            $claims = $res->successful() ? ($res->json('data', []) ?? []) : [];
            $cc = collect($claims);

            $pending  = $cc->filter(fn($c) => strtolower($c['status'] ?? '') === 'pending');
            $approved = $cc->filter(fn($c) => strtolower($c['status'] ?? '') === 'approved');
            $rejected = $cc->filter(fn($c) => strtolower($c['status'] ?? '') === 'rejected');
            $returned = $cc->filter(fn($c) => strtolower($c['status'] ?? '') === 'returned');

            $types = $cc->groupBy(fn($c) => trim($c['claim_type'] ?? '') ?: 'Other')
                ->map(fn($items) => [
                    'count'  => $items->count(),
                    'amount' => round($items->sum(fn($c) => (float)($c['amount'] ?? 0)), 2),
                ]);

            return response()->json([
                'success' => true,
                'timestamp' => now()->format('H:i:s'),
                'stats' => [
                    'total'           => $cc->count(),
                    'pending_count'   => $pending->count(),
                    'approved_count'  => $approved->count(),
                    'rejected_count'  => $rejected->count(),
                    'returned_count'  => $returned->count(),
                    'total_amount'    => round($cc->sum(fn($c) => (float)($c['amount'] ?? 0)), 2),
                    'approved_amount' => round($approved->sum(fn($c) => (float)($c['amount'] ?? 0)), 2),
                    'pending_amount'  => round($pending->sum(fn($c) => (float)($c['amount'] ?? 0)), 2),
                    'rejected_amount' => round($rejected->sum(fn($c) => (float)($c['amount'] ?? 0)), 2),
                ],
                'types' => $types,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
