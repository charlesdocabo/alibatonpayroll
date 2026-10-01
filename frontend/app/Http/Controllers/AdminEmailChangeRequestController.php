<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\EmailChangeRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AdminEmailChangeRequestController extends Controller
{
    private string $gateway;

    public function __construct()
    {
        $this->gateway = config('services.gateway.url');
    }

    /**
     * Display pending and previously reviewed email change requests.
     */
    public function index(): View
    {
        $requests = EmailChangeRequest::with([
            'user',
            'reviewer',
        ])
            ->latest()
            ->paginate(20);

        return view(
            'email_change_requests.index',
            compact('requests')
        );
    }

    /**
     * Approve an employee email change request.
     */
    public function approve(
        EmailChangeRequest $emailChangeRequest,
        Request $request
    ): RedirectResponse {
        $admin = $request->user();

        // Only pending requests can be approved.
        if ($emailChangeRequest->status !== 'pending') {
            return back()->with(
                'error',
                'This email change request has already been reviewed.'
            );
        }

        $user = $emailChangeRequest->user;

        if (!$user) {
            return back()->with(
                'error',
                'The employee account associated with this request no longer exists.'
            );
        }

        // Verify the account is still an Employee.
        if ($user->role !== 'Employee') {
            return back()->with(
                'error',
                'Only Employee accounts can have email change requests.'
            );
        }

        // Verify the employee account is still linked.
        if (!$user->employee_id) {
            return back()->with(
                'error',
                'The employee account is not linked to an employee record.'
            );
        }

        $newEmail = strtolower(
            trim($emailChangeRequest->requested_email)
        );

        // Make sure the requested email is not already assigned
        // to another user account.
        $emailAlreadyUsed = User::where(
            'email',
            $newEmail
        )
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailAlreadyUsed) {
            return back()->with(
                'error',
                'The requested email address is already being used by another account.'
            );
        }

        /*
         * Update the Employee Service first.
         *
         * We do this before changing the local user account so that
         * the two email records remain synchronized.
         */
        try {
            $employeeResponse = Http::timeout(10)->patch(
                $this->gateway
                . '/api/employees/'
                . $user->employee_id,
                [
                    'email' => $newEmail,
                ]
            );

            if (!$employeeResponse->successful()) {
                return back()->with(
                    'error',
                    'Unable to update the employee email in the Employee Service. The request remains pending.'
                );
            }
        } catch (\Throwable $e) {
            return back()->with(
                'error',
                'Unable to connect to the Employee Service. The request remains pending.'
            );
        }

        /*
         * Update the local authentication account.
         */
        try {
            DB::transaction(function () use (
                $emailChangeRequest,
                $user,
                $newEmail,
                $admin,
                $request
            ) {
                $user->email = $newEmail;

                /*
                 * The new email must be verified again.
                 */
                $user->email_verified_at = null;

                $user->save();

                $emailChangeRequest->status = 'approved';
                $emailChangeRequest->reviewed_by = $admin->id;
                $emailChangeRequest->reviewed_at = now();
                $emailChangeRequest->admin_note =
                    'Email change request approved by Admin.';
                $emailChangeRequest->save();

                AuditLog::create([
                    'user_id' => $admin->id,
                    'action' => 'EMAIL_CHANGE_APPROVED',
                    'description' =>
                        'Approved email change request #'
                        . $emailChangeRequest->id
                        . ' for employee account '
                        . $user->name
                        . '. New email: '
                        . $newEmail,
                    'ip_address' => $request->ip(),
                ]);
            });
        } catch (\Throwable $e) {
            /*
             * The Employee Service was already updated.
             * Try to restore the previous employee-service email
             * if the local database transaction fails.
             */
            try {
                Http::timeout(10)->patch(
                    $this->gateway
                    . '/api/employees/'
                    . $user->employee_id,
                    [
                        'email' =>
                            $emailChangeRequest->current_email,
                    ]
                );
            } catch (\Throwable $rollbackException) {
                // Keep the original failure as the main response.
            }

            return back()->with(
                'error',
                'The email change could not be completed. The previous employee email was restored where possible.'
            );
        }

        return back()->with(
            'success',
            'Email change request approved successfully.'
        );
    }

    /**
     * Reject an employee email change request.
     */
    public function reject(
        EmailChangeRequest $emailChangeRequest,
        Request $request
    ): RedirectResponse {
        $admin = $request->user();

        if ($emailChangeRequest->status !== 'pending') {
            return back()->with(
                'error',
                'This email change request has already been reviewed.'
            );
        }

        $validated = $request->validate([
            'admin_note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $emailChangeRequest->status = 'rejected';
        $emailChangeRequest->reviewed_by = $admin->id;
        $emailChangeRequest->reviewed_at = now();
        $emailChangeRequest->admin_note =
            trim($validated['admin_note'] ?? '')
            ?: 'Email change request rejected by Admin.';
        $emailChangeRequest->save();

        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'EMAIL_CHANGE_REJECTED',
            'description' =>
                'Rejected email change request #'
                . $emailChangeRequest->id
                . ' for employee account '
                . ($emailChangeRequest->user->name ?? 'Unknown')
                . '. Requested email: '
                . $emailChangeRequest->requested_email,
            'ip_address' => $request->ip(),
        ]);

        return back()->with(
            'success',
            'Email change request rejected successfully.'
        );
    }
}