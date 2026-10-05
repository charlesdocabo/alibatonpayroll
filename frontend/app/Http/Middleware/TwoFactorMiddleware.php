<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enforce two-factor authentication after login.
 *
 * If the authenticated user has 2FA enabled and has NOT yet passed
 * the 2FA challenge this session, redirect them to the challenge page.
 *
 * Routes excluded from 2FA enforcement:
 *  - /two-factor/*      (the challenge/setup/manage pages themselves)
 *  - /logout            (allow logout without completing 2FA)
 */
class TwoFactorMiddleware
{
    // Routes that bypass the 2FA redirect
    private const BYPASS_PREFIXES = [
        'two-factor',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        // Check if the current route should bypass 2FA
        $path = ltrim($request->path(), '/');
        foreach (self::BYPASS_PREFIXES as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return $next($request);
            }
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // If 2FA is enabled and not yet verified this session → redirect to challenge
        if ($user->hasTwoFactorEnabled() && !session('2fa_verified')) {
            return redirect()->route('two-factor.challenge');
        }

        return $next($request);
    }
}
