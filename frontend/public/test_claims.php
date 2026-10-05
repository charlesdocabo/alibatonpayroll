<?php
/**
 * Test Claims enhancements — HTTP endpoint check
 */

$baseUrl = 'http://localhost:9000';

$tests = [
    '/claims/data' => 'Claims real-time JSON endpoint',
];

echo "=== CLAIMS ENDPOINT TESTS ===\n\n";

foreach ($tests as $path => $label) {
    $ctx = stream_context_create(['http' => [
        'timeout' => 10,
        'ignore_errors' => true,
    ]]);
    $url = $baseUrl . $path;
    $body = @file_get_contents($url, false, $ctx);
    $code = isset($http_response_header[0])
        ? (int) explode(' ', $http_response_header[0])[1]
        : 0;

    // 200 = success, 302 = redirect (auth required — still means route exists), 401/403 also fine
    $ok = in_array($code, [200, 302]);
    echo ($ok ? '✅' : '❌') . " [{$code}] {$label}\n";
    echo "    URL: {$url}\n";

    if ($code === 200 && $body) {
        $json = json_decode($body, true);
        if ($json && isset($json['success'])) {
            echo "    JSON success: " . ($json['success'] ? 'true' : 'false') . "\n";
            if (isset($json['stats'])) {
                $s = $json['stats'];
                echo "    Stats: total={$s['total']}, pending={$s['pending_count']}, approved={$s['approved_count']}\n";
                echo "    Amounts: total=₱{$s['total_amount']}, approved=₱{$s['approved_amount']}\n";
            }
            if (isset($json['types']) && count($json['types'])) {
                echo "    Claim types: " . implode(', ', array_keys($json['types'])) . "\n";
            }
        } else {
            echo "    Body (first 200): " . substr($body, 0, 200) . "\n";
        }
    }
    echo "\n";
}

// Check index view render size
echo "=== VIEW RENDER SIZE ===\n\n";
$viewPath = '/var/www/resources/views/claims/index.blade.php';
$size = @filesize($viewPath);
echo "  claims/index.blade.php: " . ($size ? number_format($size) . " bytes" : "NOT FOUND") . "\n";

echo "\n=== DONE ===\n";
