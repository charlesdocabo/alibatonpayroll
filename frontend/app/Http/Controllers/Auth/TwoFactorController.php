<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\TotpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 2FA CHALLENGE — shown after login when 2FA is enabled
    |--------------------------------------------------------------------------
    */

    /**
     * Show the 2FA code challenge page (step 2 of login).
     */
    public function challenge(): View|RedirectResponse
    {
        // Already passed 2FA check → go to dashboard
        if (session('2fa_verified')) {
            return redirect()->route('dashboard');
        }

        // Not yet authenticated at all
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // User doesn't have 2FA enabled — nothing to challenge
        if (!$user->hasTwoFactorEnabled()) {
            session(['2fa_verified' => true]);
            return redirect()->route('dashboard');
        }

        return view('auth.two-factor-challenge');
    }

    /**
     * Verify the submitted TOTP code during login.
     */
    public function verify(Request $request): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasTwoFactorEnabled()) {
            session(['2fa_verified' => true]);
            return redirect()->intended(route('dashboard'));
        }

        if (!TotpService::verify($user->two_factor_secret, $request->input('code'))) {
            AuditLog::create([
                'user_id'     => $user->id,
                'action'      => '2FA_FAILED',
                'description' => 'Invalid 2FA code submitted during login.',
                'ip_address'  => $request->ip(),
            ]);

            return back()->withErrors([
                'code' => 'The authentication code is invalid. Please try again.',
            ]);
        }

        session(['2fa_verified' => true]);

        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => '2FA_SUCCESS',
            'description' => 'Two-factor authentication passed successfully.',
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->intended(route('dashboard'));
    }

    /*
    |--------------------------------------------------------------------------
    | 2FA SETUP — inside profile/settings
    |--------------------------------------------------------------------------
    */

    /**
     * Show the 2FA setup page (generate secret, display QR code).
     */
    public function setup(): View|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Already fully enabled
        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('two-factor.manage');
        }

        // Generate a new pending secret (stored in session until confirmed)
        if (!session('2fa_pending_secret')) {
            session(['2fa_pending_secret' => TotpService::generateSecret()]);
        }

        $secret    = session('2fa_pending_secret');
        $label     = $user->email;
        $qrUrl     = TotpService::getQrCodeUrl($secret, $label);
        $qrUri     = TotpService::getQrUri($secret, $label);

        return view('auth.two-factor-setup', compact('secret', 'qrUrl', 'qrUri'));
    }

    /**
     * Confirm and activate 2FA — user must verify their first code.
     */
    public function enable(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ]);

        $secret = session('2fa_pending_secret');

        if (!$secret) {
            return redirect()->route('two-factor.setup')
                ->with('error', 'Session expired. Please restart setup.');
        }

        if (!TotpService::verify($secret, $request->input('code'))) {
            return back()->withErrors([
                'code' => 'Invalid code. Make sure your authenticator app is synced and try again.',
            ]);
        }

        // Save and activate
        $user->forceFill([
            'two_factor_secret'       => $secret,
            'two_factor_enabled'      => true,
            'two_factor_confirmed_at' => now(),
        ])->save();

        session()->forget('2fa_pending_secret');
        session(['2fa_verified' => true]);

        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => '2FA_ENABLED',
            'description' => 'Two-factor authentication enabled by user.',
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('two-factor.manage')
            ->with('success', '✅ Two-factor authentication has been enabled successfully!');
    }

    /**
     * Show the 2FA management page (status, disable option).
     */
    public function manage(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('auth.two-factor-manage', compact('user'));
    }

    /**
     * Disable 2FA — requires current password confirmation.
     */
    public function disable(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user->forceFill([
            'two_factor_secret'       => null,
            'two_factor_enabled'      => false,
            'two_factor_confirmed_at' => null,
        ])->save();

        session()->forget('2fa_verified');

        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => '2FA_DISABLED',
            'description' => 'Two-factor authentication disabled by user.',
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('two-factor.manage')
            ->with('success', 'Two-factor authentication has been disabled.');
    }
}
