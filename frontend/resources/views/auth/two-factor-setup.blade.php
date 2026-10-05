@extends('layouts.app')

@section('title', 'Set Up Two-Factor Authentication — Alibaton Payroll')

@section('styles')
<style>
.page-header { margin-bottom: 28px; }
.page-title  { margin: 0; font-size: 26px; font-weight: 700; }
.page-subtitle { margin: 8px 0 0; color: #777; font-size: 14px; }

.setup-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    max-width: 900px;
}

.setup-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.09);
    padding: 28px;
}

.step-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px; height: 32px;
    background: #f4c400;
    color: #111;
    border-radius: 50%;
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 14px;
}

.setup-card h2 {
    font-size: 17px;
    font-weight: bold;
    margin: 0 0 6px;
    color: #111;
}

.setup-card p {
    font-size: 14px;
    color: #555;
    margin: 0 0 18px;
    line-height: 1.5;
}

/* QR Code */
.qr-box {
    text-align: center;
    background: #f9f9f9;
    border: 2px dashed #e0e0e0;
    border-radius: 10px;
    padding: 22px;
    margin-bottom: 18px;
}

.qr-box img {
    width: 180px;
    height: 180px;
    display: block;
    margin: 0 auto 12px;
    border: 4px solid #fff;
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.12);
}

.qr-note {
    font-size: 12px;
    color: #888;
    margin: 0;
}

/* Secret key */
.secret-box {
    background: #111;
    color: #f4c400;
    border-radius: 8px;
    padding: 14px 18px;
    font-family: 'Courier New', monospace;
    font-size: 16px;
    font-weight: bold;
    letter-spacing: 3px;
    text-align: center;
    word-break: break-all;
    margin-bottom: 10px;
    user-select: all;
}

.copy-hint {
    font-size: 12px;
    color: #888;
    text-align: center;
    margin-bottom: 18px;
}

/* Step list */
.steps-list {
    list-style: none;
    padding: 0;
    margin: 0 0 20px;
}

.steps-list li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 14px;
    font-size: 14px;
    color: #444;
    line-height: 1.5;
}

.step-num {
    flex-shrink: 0;
    width: 24px; height: 24px;
    background: #111;
    color: #f4c400;
    border-radius: 50%;
    font-size: 12px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 1px;
}

/* Code verify form */
.verify-form { margin-top: 10px; }

label {
    display: block;
    font-size: 13px;
    font-weight: bold;
    margin-bottom: 8px;
    color: #333;
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
    margin-bottom: 4px;
    transition: border-color 0.2s;
}

.code-input:focus {
    outline: none;
    border-color: #f4c400;
    box-shadow: 0 0 0 3px rgba(244,196,0,0.15);
}

.field-error {
    color: #c62828;
    font-size: 12px;
    margin-bottom: 14px;
}

.alert {
    padding: 12px 16px;
    border-radius: 7px;
    margin-bottom: 18px;
    font-size: 14px;
}
.alert-success { background:#e8f5e9; color:#256029; border:1px solid #b7dfba; }
.alert-error   { background:#fdecea; color:#c62828; border:1px solid #f1b8b5; }

.timer-note {
    font-size: 12px;
    color: #999;
    text-align: center;
    margin-bottom: 16px;
}

.timer-note span { font-weight: bold; color: #f4c400; }

.btn-activate {
    width: 100%;
    padding: 14px;
    background: #2e7d32;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-activate:hover { background: #1b5e20; }

.btn-cancel {
    display: block;
    text-align: center;
    margin-top: 14px;
    font-size: 13px;
    color: #888;
    text-decoration: none;
}

.btn-cancel:hover { color: #333; }

/* Download app hints */
.app-badges {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 16px;
}

.app-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f5f5f5;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: bold;
    color: #333;
    text-decoration: none;
}

.app-badge:hover { background: #ececec; }

@media (max-width: 768px) {
    .setup-grid { grid-template-columns: 1fr; }
}
</style>
@endsection

@section('content')

<div class="page-header">
    <h1 class="page-title">🔐 Set Up Two-Factor Authentication</h1>
    <p class="page-subtitle">Secure your account with Google Authenticator — scan the QR code below to get started.</p>
</div>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="setup-grid">

    {{-- LEFT: QR Code + Secret --}}
    <div class="setup-card">
        <div class="step-badge">1</div>
        <h2>Scan QR Code</h2>
        <p>Open <strong>Google Authenticator</strong> (or any TOTP app) and scan the QR code below.</p>

        <div class="qr-box">
            <img src="{{ $qrUrl }}" alt="TOTP QR Code" id="qrImg">
            <p class="qr-note">Scan with Google Authenticator, Authy, or Microsoft Authenticator</p>
        </div>

        <p style="font-size:13px; color:#777; margin-bottom:8px;">
            <strong>Can't scan?</strong> Enter this key manually into your authenticator app:
        </p>

        <div class="secret-box" id="secretBox" title="Click to copy">{{ $secret }}</div>
        <div class="copy-hint" id="copyHint">Click the key above to copy it</div>

        <p style="font-size:13px; color:#777; margin:0 0 8px;">Don't have an authenticator app yet?</p>
        <div class="app-badges">
            <a class="app-badge" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank">
                📱 Android
            </a>
            <a class="app-badge" href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank">
                🍎 iOS / iPhone
            </a>
        </div>
    </div>

    {{-- RIGHT: Verify & Activate --}}
    <div class="setup-card">
        <div class="step-badge">2</div>
        <h2>Verify &amp; Activate</h2>
        <p>Once you've scanned the QR code, enter the 6-digit code from your authenticator app to confirm setup.</p>

        <ul class="steps-list">
            <li>
                <div class="step-num">1</div>
                Open <strong>Google Authenticator</strong> on your phone
            </li>
            <li>
                <div class="step-num">2</div>
                Tap <strong>+</strong> → <em>Scan a QR code</em> or <em>Enter a setup key</em>
            </li>
            <li>
                <div class="step-num">3</div>
                Scan the QR code on the left (or enter the key manually)
            </li>
            <li>
                <div class="step-num">4</div>
                Enter the <strong>6-digit code</strong> shown in your app below
            </li>
        </ul>

        <form class="verify-form" method="POST" action="{{ route('two-factor.enable') }}">
            @csrf

            <label for="code">Enter 6-Digit Code from Authenticator App</label>

            <input
                type="text"
                id="code"
                name="code"
                class="code-input"
                maxlength="6"
                minlength="6"
                pattern="\d{6}"
                inputmode="numeric"
                placeholder="000000"
                autocomplete="off"
                autofocus
                required
            >

            @error('code')
                <div class="field-error">{{ $message }}</div>
            @enderror

            <div class="timer-note">
                Code refreshes every 30 seconds — current code expires in <span id="countdown">--</span>s
            </div>

            <button type="submit" class="btn-activate">
                ✅ Activate Two-Factor Authentication
            </button>
        </form>

        <a href="{{ route('profile.edit') ?? '/profile' }}" class="btn-cancel">
            ← Cancel — I'll set this up later
        </a>
    </div>

</div>

<script>
// Countdown timer
function updateTimer() {
    const remaining = 30 - (Math.floor(Date.now() / 1000) % 30);
    document.getElementById('countdown').textContent = remaining;
}
updateTimer();
setInterval(updateTimer, 1000);

// Auto-format: digits only
document.getElementById('code').addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '').substring(0, 6);
});

// Copy secret to clipboard
document.getElementById('secretBox').addEventListener('click', function() {
    const text = this.textContent.trim().replace(/\s/g, '');
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            document.getElementById('copyHint').textContent = '✅ Copied to clipboard!';
            setTimeout(() => {
                document.getElementById('copyHint').textContent = 'Click the key above to copy it';
            }, 2500);
        });
    }
});
</script>

@endsection
