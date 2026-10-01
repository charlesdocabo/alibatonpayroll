<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\EmailChangeRequest;
use App\Models\PasswordOtp;
use App\Services\PasswordOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $employee = null;
        $pendingEmailChangeRequest = null;

        if ($user->role === 'Employee' && $user->employee_id) {
            try {
                $response = Http::timeout(10)->get(
                    config('services.gateway.url')
                    . '/api/employees/'
                    . $user->employee_id
                );

                if ($response->successful()) {
                    $employee = $response->json('data');
                }
            } catch (\Throwable $e) {
                $employee = null;
            }

            $pendingEmailChangeRequest = EmailChangeRequest::where(
                'user_id',
                $user->id
            )
                ->where('status', 'pending')
                ->latest()
                ->first();
        }

        return view('profile.edit', [
            'user' => $user,
            'employee' => $employee,
            'pendingEmailChangeRequest' => $pendingEmailChangeRequest,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        /*
         * Employees cannot directly change their email.
         * They must submit an Email Change Request for Admin approval.
         */
        if (
            $user->role === 'Employee'
            && strcasecmp(
                trim((string) $request->input('email')),
                trim((string) $user->email)
            ) !== 0
        ) {
            return Redirect::route('profile.edit')
                ->withErrors([
                    'email' =>
                        'Employees cannot directly change their email address. Please submit an Email Change Request for Admin approval.',
                ])
                ->withInput();
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'PROFILE_UPDATED',
            'description' =>
                'User updated their account profile information.',
            'ip_address' => $request->ip(),
        ]);

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    public function updateEmployeeProfile(
        Request $request
    ): RedirectResponse {
        $user = $request->user();

        if (
            $user->role !== 'Employee'
            || !$user->employee_id
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'first_name' => [
                'required',
                'string',
                'max:100',
            ],
            'last_name' => [
                'required',
                'string',
                'max:100',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],
        ]);

        try {
            $response = Http::timeout(10)->patch(
                config('services.gateway.url')
                . '/api/employees/'
                . $user->employee_id,
                $validated
            );

            if (!$response->successful()) {
                return Redirect::route('profile.edit')
                    ->withErrors([
                        'employee_profile' =>
                            'Unable to update your employee information at this time.',
                    ]);
            }
        } catch (\Throwable $e) {
            return Redirect::route('profile.edit')
                ->withErrors([
                    'employee_profile' =>
                        'Unable to connect to the Employee Service.',
                ]);
        }

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'EMPLOYEE_PROFILE_UPDATED',
            'description' =>
                'Employee updated their personal profile information.',
            'ip_address' => $request->ip(),
        ]);

        return Redirect::route('profile.edit')
            ->with('status', 'employee-profile-updated');
    }

  public function updatePassword(
    Request $request,
    PasswordOtpService $otpService
): RedirectResponse {
    $validated = $request->validate([
        'current_password' => [
            'required',
            'current_password',
        ],
        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/[A-Z]/',
            'regex:/[a-z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*?&]/',
        ],
    ], [
        'current_password.required' =>
            'Please enter your current password.',
        'current_password.current_password' =>
            'Your current password is incorrect.',
        'password.required' =>
            'New password is required.',
        'password.min' =>
            'New password must be at least 8 characters.',
        'password.confirmed' =>
            'Password confirmation does not match.',
        'password.regex' =>
            'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@ $ ! % * ? &).',
    ]);

    if (
        Hash::check(
            $validated['password'],
            $request->user()->password
        )
    ) {
        return Redirect::route('profile.edit')
            ->withErrors([
                'password' =>
                    'Your new password must be different from your current password.',
            ]);
    }

    $otp = $otpService->generate($request->user());

    $request->session()->put(
        'password_change_pending',
        [
            'user_id' => $request->user()->id,
            'password' => Crypt::encryptString(
                $validated['password']
            ),
            'expires_at' => now()->addMinutes(5)->timestamp,
        ]
    );

    Mail::raw(
        "Your Alibaton Payroll password change OTP is: {$otp}\n\n"
        . "This OTP expires in 1 minute and can only be used once.",
        function ($message) use ($request) {
            $message
                ->to($request->user()->email)
                ->subject('Password Change OTP - Alibaton Payroll');
        }
    );

    AuditLog::create([
        'user_id' => $request->user()->id,
        'action' => 'PASSWORD_OTP_SENT',
        'description' =>
            'Password change OTP sent to the user account email address.',
        'ip_address' => $request->ip(),
    ]);

    return Redirect::route('profile.password.otp');
}

 public function showPasswordOtp(
    Request $request
): View|RedirectResponse {
    if (
        !$request->session()->has(
            'password_change_pending'
        )
    ) {
        return Redirect::route('profile.edit');
    }

    return view('profile.verify-password-otp');
}

    public function verifyPasswordOtp(
    Request $request,
    PasswordOtpService $otpService
): RedirectResponse {
    $request->validate([
        'otp' => [
            'required',
            'digits:6',
        ],
    ], [
        'otp.required' =>
            'Please enter the OTP.',
        'otp.digits' =>
            'The OTP must be exactly 6 digits.',
    ]);

    $pending = $request->session()->get(
        'password_change_pending'
    );

    if (!$pending) {
        return Redirect::route('profile.edit')
            ->withErrors([
                'otp' =>
                    'No password change request is pending.',
            ]);
    }

    if (
        now()->timestamp > $pending['expires_at']
    ) {
        $request->session()->forget(
            'password_change_pending'
        );

        return Redirect::route('profile.edit')
            ->withErrors([
                'otp' =>
                    'Your password change request has expired. Please request a new OTP.',
            ]);
    }

    $user = $request->user();

    if (
        (int) $pending['user_id'] !== (int) $user->id
    ) {
        $request->session()->forget(
            'password_change_pending'
        );

        return Redirect::route('profile.edit')
            ->withErrors([
                'otp' =>
                    'Invalid password change request.',
            ]);
    }

    if (
        !$otpService->verify(
            $user,
            $request->input('otp')
        )
    ) {
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'PASSWORD_OTP_FAILED',
            'description' =>
                'Invalid or expired password change OTP was submitted.',
            'ip_address' => $request->ip(),
        ]);

        return back()
            ->withErrors([
                'otp' =>
                    'Invalid or expired OTP. You have a maximum of 3 attempts.',
            ])
            ->withInput();
    }

    try {
        $newPassword = Crypt::decryptString(
            $pending['password']
        );
    } catch (\Throwable $e) {
        $request->session()->forget(
            'password_change_pending'
        );

        return Redirect::route('profile.edit')
            ->withErrors([
                'otp' =>
                    'The password change request is no longer valid.',
            ]);
    }

    $user->password = Hash::make($newPassword);
    $user->save();

    $request->session()->forget(
        'password_change_pending'
    );

    AuditLog::create([
        'user_id' => $user->id,
        'action' => 'PASSWORD_CHANGED',
        'description' =>
            'User changed their password after successful OTP verification.',
        'ip_address' => $request->ip(),
    ]);

    return Redirect::route('profile.edit')
        ->with('status', 'password-updated');
}

    public function destroy(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'password' => [
                'required',
                'current_password',
            ],
        ]);

        $user = $request->user();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'ACCOUNT_DELETED',
            'description' =>
                'User deleted their own account.',
            'ip_address' => $request->ip(),
        ]);

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return Redirect::to('/');
    }
}