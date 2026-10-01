<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\EmailChangeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class EmailChangeRequestController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Only Employees can submit email change requests.
        if ($user->role !== 'Employee') {
            abort(403);
        }

        // Employee account must be linked to an employee record.
        if (!$user->employee_id) {
            return Redirect::route('profile.edit')
                ->withErrors([
                    'email_change' =>
                        'Your account is not linked to an employee record.',
                ]);
        }

        $validated = $request->validate([
            'requested_email' => [
    'required',
    'email',
    'max:255',
    Rule::unique('users', 'email'),
],
            'reason' => [
                'required',
                'string',
                'min:10',
                'max:1000',
            ],
        ], [
            'requested_email.required' =>
                'Please enter your new Gmail address.',
            'requested_email.email' =>
                'Please enter a valid email address.',
            'requested_email.unique' =>
                'That email address is already being used by another account.',
            'requested_email.different' =>
                'The new email must be different from your current email.',
            'reason.required' =>
                'Please provide a reason for changing your email.',
            'reason.min' =>
                'Please provide a more detailed reason.',
        ]);

        $requestedEmail = strtolower(
            trim($validated['requested_email'])
        );
if (strcasecmp($requestedEmail, trim($user->email)) === 0) {
    return Redirect::route('profile.edit')
        ->withErrors([
            'requested_email' =>
                'The new email must be different from your current email.',
        ])
        ->withInput();
}

        // Prevent multiple pending requests for the same employee.
        $existingRequest = EmailChangeRequest::where(
            'user_id',
            $user->id
        )
            ->where('status', 'pending')
            ->exists();

        if ($existingRequest) {
            return Redirect::route('profile.edit')
                ->withErrors([
                    'email_change' =>
                        'You already have a pending email change request. Please wait for the Admin to review it.',
                ]);
        }

        // Re-check that the requested email is not already registered.
        $emailAlreadyUsed = \App\Models\User::where(
            'email',
            $requestedEmail
        )
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailAlreadyUsed) {
            return Redirect::route('profile.edit')
                ->withErrors([
                    'requested_email' =>
                        'That email address is already registered to another account.',
                ])
                ->withInput();
        }

        $emailChangeRequest = EmailChangeRequest::create([
            'user_id' => $user->id,
            'current_email' => $user->email,
            'requested_email' => $requestedEmail,
            'reason' => trim($validated['reason']),
            'status' => 'pending',
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'EMAIL_CHANGE_REQUESTED',
            'description' =>
                'Employee requested an email address change from '
                . $user->email
                . ' to '
                . $requestedEmail
                . '. Request ID: '
                . $emailChangeRequest->id,
            'ip_address' => $request->ip(),
        ]);

        return Redirect::route('profile.edit')
            ->with(
                'status',
                'email-change-requested'
            );
    }
}