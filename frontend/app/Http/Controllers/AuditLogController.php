<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')
            ->latest();

        // Search by action, description, or user name/email
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by security event
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $logs = $query
            ->paginate(20)
            ->withQueryString();

        $actions = AuditLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('audit-logs.index', compact(
            'logs',
            'actions'
        ));
    }

    public function latest(Request $request): JsonResponse
{
    $query = AuditLog::with('user')
        ->latest();

    // Keep the same filters used by the current page
    if ($request->filled('search')) {
        $search = $request->input('search');

        $query->where(function ($q) use ($search) {
            $q->where('action', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
        });
    }

    if ($request->filled('action')) {
        $query->where('action', $request->input('action'));
    }

    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->input('date'));
    }

    $page = max((int) $request->input('page', 1), 1);

    $logs = $query
        ->paginate(20, ['*'], 'page', $page);

    return response()->json([
        'success' => true,
        'data' => $logs->items(),
        'current_page' => $logs->currentPage(),
        'last_page' => $logs->lastPage(),
    ]);
}
    }
