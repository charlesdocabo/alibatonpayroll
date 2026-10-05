<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication — Alibaton Payroll</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .card {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.12);
            overflow: hidden;
        }

        .card-header {
            background: #111111;
            padding: 28px 32px 24px;
            text-align: center;
        }

        .logo {
            font-size: 22px;
            font-weight: 800;
            color: #f4c400;
            letter-spacing: 1px;
        }

        .logo-sub {
            font-size: 12px;
            color: #aaaaaa;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        .card-body {
            padding: 32px;
        }

        .shield-icon {
            text-align: center;
            font-size: 48px;
            margin-bottom: 16px;
        }

        h1 {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            color: #111;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-bottom: 28px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .code-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            color: #111;
            transition: border-color 0.2s;
        }

        .code-input:focus {
            outline: none;
            border-color: #f4c400;
            box-shadow: 0 0 0 3px rgba(244, 196, 0, 0.15);
        }

        .error-msg {
            background: #fdecea;
            color: #c62828;
            border: 1px solid #f1b8b5;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #f4c400;
            color: #111;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }

        .submit-btn:hover { background: #dcae00; }

        .hint-box {
            background: #f9f9f9;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            padding: 14px 16px;
            margin-top: 20px;
            font-size: 13px;
            color: #555;
            line-height: 1.6;
        }

        .hint-box strong { color: #111; }

        .logout-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #888;
            text-decoration: none;
        }

        .logout-link:hover { color: #333; }

        .timer {
            text-align: center;
            font-size: 12px;
            color: #aaa;
            margin-top: 12px;
        }

        .timer span {
            font-weight: bold;
            color: #f4c400;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="logo">ALIBATON PAYROLL</div>
            <div class="logo-sub">HR &amp; Finance Management System</div>
        </div>

        <div class="card-body">
            <div class="shield-icon">🔐</div>
            <h1>Two-Factor Authentication</h1>
            <p class="subtitle">
                Open your <strong>Google Authenticator</strong> app and enter the 6-digit code shown for <strong>Alibaton Payroll</strong>.
            </p>

            @if($errors->any())
                <div class="error-msg">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('error'))
                <div class="error-msg">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('two-factor.verify') }}">
                @csrf

                <div class="form-group">
                    <label for="code">Authentication Code</label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        class="code-input"
                        maxlength="6"
                        minlength="6"
                        pattern="\d{6}"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        placeholder="000000"
                        autofocus
                        required
                    >
                </div>

                <button type="submit" class="submit-btn">
                    ✓ Verify &amp; Sign In
                </button>
            </form>

            <div class="hint-box">
                <strong>Having trouble?</strong><br>
                • Make sure your phone's time is synced correctly.<br>
                • Codes refresh every <strong>30 seconds</strong> — wait for a new one if it just changed.<br>
                • Open <strong>Google Authenticator</strong> → find <em>Alibaton Payroll</em>.
            </div>

            <div class="timer" id="timerBox">
                Code refreshes in <span id="countdown">--</span> seconds
            </div>

            <a href="{{ route('logout') }}" class="logout-link"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                ← Sign in with a different account
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </div>
</div>

<script>
// Countdown timer showing seconds until next code
function updateTimer() {
    const now = Math.floor(Date.now() / 1000);
    const remaining = 30 - (now % 30);
    document.getElementById('countdown').textContent = remaining;
}
updateTimer();
setInterval(updateTimer, 1000);

// Auto-submit when 6 digits entered
document.getElementById('code').addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '').substring(0, 6);
    if (this.value.length === 6) {
        this.closest('form').submit();
    }
});
</script>
</body>
</html>
