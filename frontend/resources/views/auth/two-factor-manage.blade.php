@extends('layouts.app')

@section('title', 'Two-Factor Authentication Settings — Alibaton Payroll')

@section('styles')
<style>
.page-header { margin-bottom: 28px; }
.page-title  { margin: 0; font-size: 26px; font-weight: 700; }
.page-subtitle { margin: 8px 0 0; color: #777; font-size: 14px; }

.manage-card {
    max-width: 680px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.09);
    overflow: hidden;
}

.status-banner {
    padding: 20px 28px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.status-banner.enabled  { background: #e8f5e9; border-bottom: 2px solid #2e7d32; }
.status-banner.disabled { background: #fff8d6; border-bottom: 2px solid #e6a800; }

.status-icon { font-size: 36px; }

.status-info h2 {
    margin: 0 0 4px;
    font-size: 18px;
    font-weight: bold;
}

.status-enabled  h2 { color: #2e7d32; }
.status-disabled h2 { color: #856404; }

.status-info p {
    margin: 0;
    font-size: 14px;
    color: #555;
}

.card-body { padding: 28px; }

.info-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 28px;
    font-size: 14px;
}

.info-table tr { border-bottom: 1px solid #f0f0f0; }
.info-table tr:last-child { border-bottom: none; }
.info-table td { padding: 12px 4px; vertical-align: top; }
.info-table td:first-child {
    width: 180px;
    color: #777;
    font-weight: bold;
    font-size: 13px;
}
.info-table td:last-child { color: #111; }

.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}
.badge-enabled  { background: #e8f5e9; color: #2e7d32; }
.badge-disabled { background: #fff8d6; color: #856404; }

.section-divider {
    border: none;
    border-top: 2px solid #f0f0f0;
    margin: 0 0 24px;
}

.action-section h3 {
    font-size: 16px;
    font-weight: bold;
    margin: 0 0 6px;
    color: #111;
}

.action-section p {
    font-size: 14px;
    color: #666;
    margin: 0 0 18px;
    line-height: 1.5;
}

.alert {
    padding: 12px 16px;
    border-radius: 7px;
    margin-bottom: 20px;
    font-size: 14px;
}
.alert-success { background:#e8f5e9; color:#256029; border:1px solid #b7dfba; }
.alert-error   { background:#fdecea; color:#c62828; border:1px solid #f1b8b5; }

.btn-setup {
    display: inline-block;
    padding: 12px 22px;
    background: #f4c400;
    color: #111;
    border-radius: 7px;
    font-size: 14px;
    font-weight: bold;
    text-decoration: none;
    transition: background 0.2s;
}
.btn-setup:hover { background: #dcae00; }

label {
    display: block;
    font-size: 13px;
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
}

.password-input {
    width: 100%;
    max-width: 320px;
    padding: 11px 13px;
    border: 2px solid #e0e0e0;
    border-radius: 7px;
    font-size: 14px;
    font-family: Arial, sans-serif;
    margin-bottom: 4px;
    transition: border-color 0.2s;
}
.password-input:focus {
    outline: none;
    border-color: #c62828;
    box-shadow: 0 0 0 3px rgba(198,40,40,0.12);
}

.field-error {
    color: #c62828;
    font-size: 12px;
    margin-bottom: 14px;
}

.btn-disable {
    padding: 11px 22px;
    background: #c62828;
    color: #fff;
    border: none;
    border-radius: 7px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 4px;
    transition: background 0.2s;
}
.btn-disable:hover { background: #a31f1f; }

.back-link {
    display: inline-block;
    margin-top: 24px;
    font-size: 13px;
    color: #888;
    text-decoration: none;
}
.back-link:hover { color: #333; }

/* Security tips */
.tips-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.tips-list li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 10px;
    font-size: 13px;
    color: #555;
    line-height: 1.5;
}
.tips-list li::before {
    content: '✓';
    color: #2e7d32;
    font-weight: bold;
    flex-shrink: 0;
    margin-top: 1px;
}

.tips-card {
    max-width: 680px;
    background: #f9f9f9;
    border: 1px solid #e8e8e8;
    border-radius: 10px;
    padding: 20px 24px;
    margin-top: 20px;
}
.tips-card h3 {
    font-size: 14px;
    font-weight: bold;
    color: #333;
    margin: 0 0 12px;
}
</style>
@endsection

@section('content')

<div class="page-header">
    <h1 class="page-title">🔒 Two-Factor Authentication</h1>
    <p class="page-subtitle">Manage your Google Authenticator two-factor authentication settings.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="manage-card">

    {{-- Status Banner --}}
    <div class="status-banner {{ $user->hasTwoFactorEnabled() ? 'enabled' : 'disabled' }}">
        <div class="status-icon">{{ $user->hasTwoFactorEnabled() ? '🛡️' : '⚠️' }}</div>
        <div class="status-info {{ $user->hasTwoFactorEnabled() ? 'status-enabled' : 'status-disabled' }}">
            <h2>
                @if($user->hasTwoFactorEnabled())
                    Two-Factor Authentication is Active
                @else
                    Two-Factor Authentication is Not Enabled
                @endif
            </h2>
            <p>
                @if($user->hasTwoFactorEnabled())
                    Your account is protected with an additional verification layer.
                @else
                    Your account only uses a password. Enable 2FA for better security.
                @endif
            </p>
        </div>
    </div>

    <div class="card-body">

        {{-- Info Table --}}
        <table class="info-table">
            <tr>
                <td>Status</td>
                <td>
                    <span class="badge {{ $user->hasTwoFactorEnabled() ? 'badge-enabled' : 'badge-disabled' }}">
                        {{ $user->hasTwoFactorEnabled() ? '✓ Enabled' : '✗ Disabled' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>Account</td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td>Authenticator</td>
                <td>Google Authenticator (TOTP — RFC 6238)</td>
            </tr>
            @if($user->hasTwoFactorEnabled() && $user->two_factor_confirmed_at)
            <tr>
                <td>Activated On</td>
                <td>{{ $user->two_factor_confirmed_at->format('F d, Y \a\t h:i A') }}</td>
            </tr>
            @endif
        </table>

        <hr class="section-divider">

        @if($user->hasTwoFactorEnabled())
            {{-- DISABLE 2FA --}}
            <div class="action-section">
                <h3>🔓 Disable Two-Factor Authentication</h3>
                <p>
                    To disable 2FA, enter your current password below. Once disabled, your account
                    will only be protected by your password. You can re-enable it at any time.
                </p>

                <form method="POST" action="{{ route('two-factor.disable') }}"
                      onsubmit="return confirm('Are you sure you want to disable two-factor authentication? Your account will be less secure.');">
                    @csrf

                    <label for="password">Confirm Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="password-input"
                        placeholder="Enter your current password"
                        required
                        autocomplete="current-password"
                    >

                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror

                    <br>
                    <button type="submit" class="btn-disable">
                        🔓 Disable Two-Factor Authentication
                    </button>
                </form>
            </div>
        @else
            {{-- ENABLE 2FA --}}
            <div class="action-section">
                <h3>🔐 Enable Two-Factor Authentication</h3>
                <p>
                    Add an extra layer of security. After enabling, you'll need to enter a
                    6-digit code from your <strong>Google Authenticator</strong> app every time you sign in.
                </p>
                <a href="{{ route('two-factor.setup') }}" class="btn-setup">
                    🔐 Set Up Google Authenticator
                </a>
            </div>
        @endif

        <a href="{{ url()->previous() ?? '/dashboard' }}" class="back-link">← Back</a>

    </div>
</div>

{{-- Security Tips --}}
<div class="tips-card">
    <h3>🔐 Security Tips</h3>
    <ul class="tips-list">
        <li>Keep your phone's time synced — TOTP codes are time-sensitive.</li>
        <li>Treat your authenticator app as a secure key — don't share codes with anyone.</li>
        <li>If you lose access to your authenticator, contact your system administrator immediately.</li>
        <li>Consider backing up your Google Authenticator to Google account (Android) or iCloud (iOS).</li>
        <li>2FA codes refresh every 30 seconds — enter them promptly.</li>
    </ul>
</div>

@endsection
