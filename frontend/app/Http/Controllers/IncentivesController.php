<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AuditLog;
use PDO;
use PDOException;

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
                    return strtolower(trim($item['status'] ?? '')) === 'cancelled';
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
                        'type'   => $type,
                        'count'  => $items->count(),
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
                'incentives'             => [],
                'totalIncentives'        => 0,
                'approvedIncentives'     => 0,
                'pendingIncentives'      => 0,
                'rejectedIncentives'     => 0,
                'totalIncentiveAmount'   => 0,
                'approvedIncentiveAmount'=> 0,
                'pendingIncentiveAmount' => 0,
                'incentiveTypeBreakdown' => collect(),
                'error'                  => 'Connection Error: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the Add Incentive form.
     * Loads employee list for the dropdown.
     */
    public function create()
    {
        $employees = [];

        try {
            $empResponse = Http::timeout(10)->get(
                $this->gateway . '/api/employees'
            );
            if ($empResponse->successful()) {
                $employees = $empResponse->json('data', []) ?? [];
            }
        } catch (\Exception $e) {
            // Non-fatal; form still works with manual entry
        }

        return view('incentives.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'    => 'required|string|max:50',
            'incentive_type' => 'required|string|max:100',
            'description'    => 'nullable|string',
            'amount'         => 'required|numeric|min:0',
            'incentive_date' => 'required|date',
            'status'         => 'required|string|max:30',
            'payroll_period' => 'nullable|string|max:20',
            'approved_by'    => 'nullable|string|max:100',
            'trip_id'        => 'nullable|integer',
            'trip_reference' => 'nullable|string|max:50',
        ]);

        try {
            $response = Http::timeout(10)->post(
                $this->gateway . '/api/incentives',
                $validated
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id'     => auth()->id(),
                    'action'      => 'INCENTIVE_CREATED',
                    'description' => 'Created incentive for employee: '
                        . $validated['employee_id']
                        . ' | Type: '
                        . $validated['incentive_type']
                        . ' | Amount: ₱'
                        . number_format((float) $validated['amount'], 2),
                    'ip_address' => $request->ip(),
                ]);

                return redirect('/incentives')
                    ->with('success', 'Incentive added successfully.');
            }

            $errorMessage = $response->json('message')
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

    public function edit($id)
    {
        $employees = [];

        try {
            $empResponse = Http::timeout(10)->get(
                $this->gateway . '/api/employees'
            );
            if ($empResponse->successful()) {
                $employees = $empResponse->json('data', []) ?? [];
            }
        } catch (\Exception $e) {
            // Non-fatal
        }

        try {
            $response = Http::timeout(10)->get(
                $this->gateway . '/api/incentives/' . $id
            );

            if ($response->successful()) {

                $incentive = $response->json('data');

                return view(
                    'incentives.edit',
                    compact('incentive', 'employees')
                );
            }

            return redirect('/incentives')
                ->with('error', 'Incentive record not found.');

        } catch (\Exception $e) {

            return redirect('/incentives')
                ->with('error', 'Connection Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id'    => 'required|string|max:50',
            'incentive_type' => 'required|string|max:100',
            'description'    => 'nullable|string',
            'amount'         => 'required|numeric|min:0',
            'incentive_date' => 'required|date',
            'status'         => 'required|string|max:30',
            'payroll_period' => 'nullable|string|max:20',
            'approved_by'    => 'nullable|string|max:100',
        ]);

        try {
            $response = Http::timeout(10)->put(
                $this->gateway . '/api/incentives/' . $id,
                $validated
            );

            if ($response->successful()) {

                AuditLog::create([
                    'user_id'     => auth()->id(),
                    'action'      => 'INCENTIVE_UPDATED',
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
                    ->with('success', 'Incentive updated successfully.');
            }

            $errorMessage = $response->json('message')
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
                    'user_id'     => auth()->id(),
                    'action'      => 'INCENTIVE_DELETED',
                    'description' => $description,
                    'ip_address'  => $request->ip(),
                ]);

                return redirect('/incentives')
                    ->with('success', 'Incentive deleted successfully.');
            }

            $errorMessage = $response->json('message')
                ?? $response->json('error')
                ?? $response->body();

            return redirect('/incentives')
                ->with('error', 'API Error: ' . $errorMessage);

        } catch (\Exception $e) {

            return redirect('/incentives')
                ->with('error', 'Connection Error: ' . $e->getMessage());
        }
    }

    /**
     * Show completed driver trips from microfleet that can be
     * converted into payroll incentives.
     *
     * Reads directly from the mounted microfleet SQLite file at
     * /var/www/microfleet-db/database.sqlite (read-only).
     */
    public function driverTrips()
    {
        $trips       = [];
        $error       = null;
        $dbPath      = '/var/www/microfleet-db/database.sqlite';

        // Fetch already-linked trip_ids from our incentives API
        $linkedTripIds = [];
        try {
            $incResponse = Http::timeout(10)->get(
                $this->gateway . '/api/incentives'
            );
            if ($incResponse->successful()) {
                $linkedTripIds = collect($incResponse->json('data', []))
                    ->pluck('trip_id')
                    ->filter()
                    ->toArray();
            }
        } catch (\Exception $e) {
            // Non-fatal
        }

        if (!file_exists($dbPath)) {
            $error = 'microfleet database not accessible. Ensure the microfleet volume is mounted.';
        } else {
            try {
                $pdo = new PDO('sqlite:' . $dbPath, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);

                $stmt = $pdo->query(
                    "SELECT
                        t.id            AS trip_id,
                        t.trip_number,
                        t.status,
                        t.distance_km,
                        t.departed_at,
                        t.arrived_at,
                        d.id            AS driver_id,
                        d.employee_number,
                        d.license_number
                    FROM trips t
                    LEFT JOIN drivers d ON t.driver_id = d.id
                    WHERE t.status = 'completed'
                    ORDER BY t.arrived_at DESC"
                );

                $rawTrips = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($rawTrips as $trip) {
                    $trip['already_linked'] = in_array($trip['trip_id'], $linkedTripIds);
                    $trips[] = $trip;
                }

            } catch (PDOException $e) {
                $error = 'Could not read microfleet database: ' . $e->getMessage();
            }
        }

        // Fetch employees for mapping
        $employees = [];
        try {
            $empResponse = Http::timeout(10)->get(
                $this->gateway . '/api/employees'
            );
            if ($empResponse->successful()) {
                $employees = collect($empResponse->json('data', []) ?? [])
                    ->keyBy('employee_id')
                    ->toArray();
            }
        } catch (\Exception $e) {
            // Non-fatal
        }

        return view('incentives.driver-trips', compact(
            'trips',
            'employees',
            'error'
        ));
    }

    /**
     * Real-time polling endpoint for incentives dashboard (every 25s).
     */
    public function refreshData()
    {
        try {
            $response = Http::timeout(10)->get($this->gateway . '/api/incentives');
            $incentives = $response->successful() ? ($response->json('data', []) ?? []) : [];
            $ic = collect($incentives);

            $approved = $ic->filter(fn($i) => strtolower(trim($i['status'] ?? '')) === 'approved');
            $pending  = $ic->filter(fn($i) => strtolower(trim($i['status'] ?? '')) === 'pending');
            $rejected = $ic->filter(fn($i) => strtolower(trim($i['status'] ?? '')) === 'cancelled' || strtolower(trim($i['status'] ?? '')) === 'rejected');

            $types = $ic->groupBy(fn($i) => trim($i['incentive_type'] ?? '') ?: 'Other')
                ->map(fn($items) => [
                    'count'  => $items->count(),
                    'amount' => $items->sum(fn($i) => (float)($i['amount'] ?? 0)),
                ]);

            return response()->json([
                'success' => true,
                'stats' => [
                    'total'           => $ic->count(),
                    'approved_count'  => $approved->count(),
                    'pending_count'   => $pending->count(),
                    'rejected_count'  => $rejected->count(),
                    'total_amount'    => $ic->sum(fn($i) => (float)($i['amount'] ?? 0)),
                    'approved_amount' => $approved->sum(fn($i) => (float)($i['amount'] ?? 0)),
                    'pending_amount'  => $pending->sum(fn($i) => (float)($i['amount'] ?? 0)),
                ],
                'types' => $types,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Real-time polling endpoint for driver trips (every 25s).
     */
    public function driverTripsData()
    {
        $dbPath = '/var/www/microfleet-db/database.sqlite';
        $totalTrips = 0;
        $eligibleTrips = 0;
        $totalDistance = 0.0;

        $linkedTripIds = [];
        try {
            $incResponse = Http::timeout(6)->get($this->gateway . '/api/incentives');
            if ($incResponse->successful()) {
                $linkedTripIds = collect($incResponse->json('data', []))->pluck('trip_id')->filter()->toArray();
            }
        } catch (\Exception $e) {}

        if (file_exists($dbPath)) {
            try {
                $pdo = new PDO('sqlite:' . $dbPath, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                $stmt = $pdo->query("SELECT id, distance_km FROM trips WHERE status = 'completed'");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $totalTrips = count($rows);
                foreach ($rows as $r) {
                    $totalDistance += (float)($r['distance_km'] ?? 0);
                    if (!in_array($r['id'], $linkedTripIds)) {
                        $eligibleTrips++;
                    }
                }
            } catch (\Exception $e) {}
        }

        return response()->json([
            'success' => true,
            'stats' => [
                'total_trips'    => $totalTrips,
                'eligible_trips' => $eligibleTrips,
                'linked_trips'   => $totalTrips - $eligibleTrips,
                'total_distance' => round($totalDistance, 1),
            ]
        ]);
    }
}

