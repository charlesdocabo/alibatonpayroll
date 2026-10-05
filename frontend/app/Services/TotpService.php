<?php

namespace App\Services;

/**
 * Pure-PHP TOTP (Time-based One-Time Password) implementation
 * Compatible with Google Authenticator, Authy, Microsoft Authenticator, etc.
 * RFC 6238 / RFC 4226 compliant. No external packages required.
 */
class TotpService
{
    // TOTP parameters
    private const DIGITS   = 6;
    private const PERIOD   = 30;   // seconds
    private const ALGO     = 'sha1';
    private const WINDOW   = 1;    // allow ±1 interval (30s window each side)

    // Base32 alphabet
    private const BASE32_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Generate a cryptographically random Base32 secret (160-bit / 20 bytes).
     */
    public static function generateSecret(): string
    {
        $bytes = random_bytes(20);
        return self::base32Encode($bytes);
    }

    /**
     * Generate the current TOTP code for a given secret.
     */
    public static function getCode(string $secret, ?int $timestamp = null): string
    {
        $timestamp ??= time();
        $counter = (int) floor($timestamp / self::PERIOD);
        return self::hotp($secret, $counter);
    }

    /**
     * Verify a TOTP code, allowing a ±WINDOW interval drift.
     */
    public static function verify(string $secret, string $code, ?int $timestamp = null): bool
    {
        $code      = trim($code);
        $timestamp ??= time();

        if (!preg_match('/^\d{6}$/', $code)) {
            return false;
        }

        $counter = (int) floor($timestamp / self::PERIOD);

        for ($i = -self::WINDOW; $i <= self::WINDOW; $i++) {
            if (hash_equals(self::hotp($secret, $counter + $i), $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build the otpauth:// URI for QR code generation.
     */
    public static function getQrUri(string $secret, string $label, string $issuer = 'Alibaton Payroll'): string
    {
        $params = http_build_query([
            'secret' => $secret,
            'issuer' => $issuer,
            'algorithm' => strtoupper(self::ALGO),
            'digits' => self::DIGITS,
            'period' => self::PERIOD,
        ]);

        $encodedLabel  = rawurlencode($issuer . ':' . $label);
        $encodedIssuer = rawurlencode($issuer);

        return "otpauth://totp/{$encodedLabel}?secret={$secret}&issuer={$encodedIssuer}&algorithm=" . strtoupper(self::ALGO) . "&digits=" . self::DIGITS . "&period=" . self::PERIOD;
    }

    /**
     * Generate a Google Charts QR code URL for the OTP URI.
     * Uses Google Charts API (no server-side QR library needed).
     */
    public static function getQrCodeUrl(string $secret, string $label, string $issuer = 'Alibaton Payroll'): string
    {
        $otpauth = self::getQrUri($secret, $label, $issuer);
        return 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . rawurlencode($otpauth);
    }

    // -------------------------------------------------------
    // HOTP (HMAC-based OTP) — RFC 4226
    // -------------------------------------------------------

    private static function hotp(string $secret, int $counter): string
    {
        $key     = self::base32Decode($secret);
        $message = pack('J', $counter);                        // 8-byte big-endian
        $hash    = hash_hmac(self::ALGO, $message, $key, true);
        $offset  = ord($hash[19]) & 0x0F;
        $code    = (
            ((ord($hash[$offset])     & 0x7F) << 24)
          | ((ord($hash[$offset + 1]) & 0xFF) << 16)
          | ((ord($hash[$offset + 2]) & 0xFF) << 8)
          |  (ord($hash[$offset + 3]) & 0xFF)
        ) % (10 ** self::DIGITS);

        return str_pad((string) $code, self::DIGITS, '0', STR_PAD_LEFT);
    }

    // -------------------------------------------------------
    // Base32 encode / decode
    // -------------------------------------------------------

    private static function base32Encode(string $data): string
    {
        $chars   = self::BASE32_CHARS;
        $encoded = '';
        $buffer  = 0;
        $bits    = 0;

        foreach (str_split($data) as $byte) {
            $buffer = ($buffer << 8) | ord($byte);
            $bits  += 8;

            while ($bits >= 5) {
                $bits   -= 5;
                $encoded .= $chars[($buffer >> $bits) & 0x1F];
            }
        }

        if ($bits > 0) {
            $encoded .= $chars[($buffer << (5 - $bits)) & 0x1F];
        }

        return $encoded;
    }

    private static function base32Decode(string $data): string
    {
        $chars   = self::BASE32_CHARS;
        $data    = strtoupper(preg_replace('/\s+/', '', $data));
        $decoded = '';
        $buffer  = 0;
        $bits    = 0;

        foreach (str_split($data) as $char) {
            $pos = strpos($chars, $char);
            if ($pos === false) {
                continue;
            }
            $buffer = ($buffer << 5) | $pos;
            $bits  += 5;

            if ($bits >= 8) {
                $bits   -= 8;
                $decoded .= chr(($buffer >> $bits) & 0xFF);
            }
        }

        return $decoded;
    }
}
