<?php

namespace App\Services;

use App\Models\PasswordOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordOtpService
{
    /**
     * Generate and store a 6-digit OTP.
     */
    public function generate(User $user): string
    {
        // Invalidate previous unused OTPs
        PasswordOtp::where('user_id', $user->id)
            ->where('used', false)
            ->update([
                'used' => true,
            ]);

        // Generate cryptographically secure 6-digit OTP
        $otp = (string) random_int(100000, 999999);

        PasswordOtp::create([
            'user_id' => $user->id,
            'otp_hash' => Hash::make($otp),
            'expires_at' => now()->addMinute(),
            'attempts' => 0,
            'used' => false,
        ]);

        return $otp;
    }

    /**
     * Verify the supplied OTP.
     */
    public function verify(User $user, string $otp): bool
    {
        $record = PasswordOtp::where('user_id', $user->id)
            ->where('used', false)
            ->latest()
            ->first();

        if (!$record) {
            return false;
        }

        // OTP expired
        if (now()->greaterThan($record->expires_at)) {
            $record->update([
                'used' => true,
            ]);

            return false;
        }

        // Maximum 3 attempts
        if ($record->attempts >= 3) {
            $record->update([
                'used' => true,
            ]);

            return false;
        }

        // Count this attempt
        $record->increment('attempts');

        // Check OTP
        if (!Hash::check($otp, $record->otp_hash)) {
            return false;
        }

        // OTP can only be used once
        $record->update([
            'used' => true,
        ]);

        return true;
    }
}