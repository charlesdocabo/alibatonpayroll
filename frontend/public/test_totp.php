<?php
require '/var/www/vendor/autoload.php';
$app = require '/var/www/bootstrap/app.php';

use App\Services\TotpService;

echo "=== TOTP SERVICE TEST ===\n\n";

// Test 1: Generate secret
$secret = TotpService::generateSecret();
echo "Secret generated : " . $secret . "\n";
echo "Secret length    : " . strlen($secret) . " chars\n\n";

// Test 2: Generate code
$code = TotpService::getCode($secret);
echo "Current code     : " . $code . "\n";
echo "Code length      : " . strlen($code) . " digits\n\n";

// Test 3: Verify the generated code
$valid = TotpService::verify($secret, $code);
echo "Code valid?      : " . ($valid ? "✅ YES" : "❌ NO") . "\n\n";

// Test 4: Verify wrong code
$invalid = TotpService::verify($secret, '000000');
echo "Wrong code (000000) valid? : " . ($invalid ? "❌ FAIL (should be invalid)" : "✅ Correctly rejected") . "\n\n";

// Test 5: QR code URL
$qrUrl = TotpService::getQrCodeUrl($secret, 'admin@alibaton.com');
echo "QR URL length    : " . strlen($qrUrl) . " chars\n";
echo "QR URL starts    : " . substr($qrUrl, 0, 60) . "...\n\n";

echo "=== ALL TESTS DONE ===\n";
