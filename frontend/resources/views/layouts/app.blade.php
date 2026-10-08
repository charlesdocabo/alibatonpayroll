<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Alibaton Construction Inc.')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f5f5;
            color: #111111;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* MAIN CONTENT */

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
        }

        .topbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e5e5;
            padding: 17px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }

        .user-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
        }

        .logout-btn {
            border: none;
            background: #111111;
            color: #ffffff;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }

        .logout-btn:hover {
            background: #333333;
        }

        .content {
            padding: 30px;
        }

        .btn-privacy-toggle {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0f0f0;
            color: #333333;
            border: 1px solid #dcdcdc;
            padding: 7px 14px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
            transition: all 0.2s ease;
            user-select: none;
            text-decoration: none;
        }

        .btn-privacy-toggle:hover {
            background: #e2e2e2;
            border-color: #bcbcbc;
        }

        .btn-privacy-toggle.is-masked {
            background: #111111;
            color: #f4c400;
            border-color: #111111;
            box-shadow: 0 2px 6px rgba(0,0,0,0.18);
        }

        .btn-privacy-toggle.is-masked:hover {
            background: #252525;
        }

        /* Confidential Masking Classes */
        body.privacy-masked .confidential-amount,
        body.privacy-masked .confidential-val,
        body.privacy-masked .amount,
        body.privacy-masked .amount-cell,
        body.privacy-masked .amount-bold {
            letter-spacing: 2px !important;
            user-select: none;
        }

        /* ---- SESSION TIMEOUT MODAL ---- */
        #session-timeout-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 99999;
            align-items: center;
            justify-content: center;
        }
        #session-timeout-overlay.show { display: flex; }
        #session-timeout-box {
            background: #ffffff;
            border-radius: 14px;
            padding: 36px 40px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            text-align: center;
        }
        #session-timeout-box h3 {
            margin: 0 0 10px;
            font-size: 20px;
            font-weight: 800;
            color: #111;
        }
        #session-timeout-box p {
            margin: 0 0 20px;
            color: #555;
            font-size: 14px;
            line-height: 1.5;
        }
        #session-countdown {
            display: inline-block;
            font-size: 48px;
            font-weight: 900;
            color: #e53e3e;
            margin-bottom: 24px;
            font-variant-numeric: tabular-nums;
        }
        #session-stay-btn {
            background: #111111;
            color: #f4c400;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
        }
        #session-stay-btn:hover { background: #333; }
        #session-timeout-bar-wrap {
            background: #f0f0f0;
            border-radius: 6px;
            height: 6px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        #session-timeout-bar {
            height: 6px;
            background: #e53e3e;
            border-radius: 6px;
            transition: width 1s linear;
            width: 100%;
        }

        /* RESPONSIVE */

        @media (max-width: 768px) {

            .main {
                margin-left: 260px;
                width: calc(100% - 260px);
            }

            .content {
                padding: 18px;
            }

            .topbar {
                padding: 15px 18px;
            }

            .user-name {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    {{-- SHARED SIDEBAR --}}
    @include('partials.sidebar')


    {{-- MAIN CONTENT --}}
    <main class="main">

        <header class="topbar">

            <h2>
                @yield('title', 'Dashboard')
            </h2>

            <div class="user-area">

                @auth

                    <!-- CONFIDENTIAL / PRIVACY MODE TOGGLE -->
                    <button type="button" class="btn-privacy-toggle is-masked" id="globalPrivacyToggle" title="Confidential Mode Active — sensitive amounts are hidden. Click to unmask.">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;flex-shrink:0"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        <span>Masked</span>
                    </button>

                    <span class="user-name">
                        {{ Auth::user()->name }}
                    </span>

                    <form method="POST"
                          action="{{ route('logout') }}">

                        @csrf

                        <button type="submit"
                                class="logout-btn">
                            Logout
                        </button>

                    </form>

                @endauth

            </div>

        </header>


        <section class="content">

            @yield('content')

        </section>

    </main>

</div>

{{-- GLOBAL CONFIDENTIAL PRIVACY MASKING SCRIPT --}}
<script>
(function() {
    const STORAGE_KEY = 'alibaton_privacy_mask';

    /* ---- helper: mask a raw string showing first + last char ---- */
    function maskString(raw) {
        // Skip trivial / non-sensitive values
        if (!raw || raw === '-' || raw === '—' || raw === 'N/A' || raw === '0' || raw === '0.00') {
            return raw;
        }
        const prefix = (raw.startsWith('₱') || raw.includes('₱')) ? '₱' : '';
        const body   = prefix ? raw.replace('₱', '').trim() : raw.trim();

        if (body.length === 0) return raw;
        if (body.length === 1) return prefix + '•';
        // Strip commas/dots for counting but keep first/last of original body
        const first = body[0];
        const last  = body[body.length - 1];
        return prefix + first + '••••' + last;
    }

    function isMasked() {
        const stored = localStorage.getItem(STORAGE_KEY);
        return stored === null ? true : stored === 'true'; // Default is MASKED
    }

    function setMaskedState(masked) {
        localStorage.setItem(STORAGE_KEY, masked ? 'true' : 'false');
        applyMask(masked);
        updateToggleButtons(masked);
    }

    function applyMask(masked) {
        if (masked) {
            document.body.classList.add('privacy-masked');
        } else {
            document.body.classList.remove('privacy-masked');
        }

        document.querySelectorAll('.confidential-amount, .confidential-val, .amount, .amount-cell, .amount-bold').forEach(el => {
            if (!el.hasAttribute('data-raw-val')) {
                el.setAttribute('data-raw-val', el.textContent.trim());
            }
            const raw = el.getAttribute('data-raw-val');
            el.textContent = masked ? maskString(raw) : raw;
        });
    }

    function updateToggleButtons(masked) {
        const svgEyeOff = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;flex-shrink:0"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
        const svgEye    = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;flex-shrink:0"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';

        document.querySelectorAll('.btn-privacy-toggle, .btn-privacy-page-toggle').forEach(btn => {
            if (masked) {
                btn.classList.add('is-masked');
                btn.innerHTML = svgEyeOff + ' <span>Masked</span>';
                btn.setAttribute('title', 'Confidential Mode Active — sensitive amounts are partially hidden. Click to unmask.');
            } else {
                btn.classList.remove('is-masked');
                btn.innerHTML = svgEye + ' <span>Unmasked</span>';
                btn.setAttribute('title', 'Confidential Mode Inactive — full financial amounts are visible. Click to mask.');
            }
        });
    }

    window.togglePrivacyMask = function() {
        setMaskedState(!isMasked());
    };

    window.refreshPrivacyMask = function() {
        applyMask(isMasked());
    };

    document.addEventListener('DOMContentLoaded', function() {
        setMaskedState(isMasked());

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-privacy-toggle, .btn-privacy-page-toggle');
            if (btn) {
                e.preventDefault();
                window.togglePrivacyMask();
            }
        });

        // Alt+M shortcut to toggle
        document.addEventListener('keydown', function(e) {
            if (e.altKey && (e.key === 'm' || e.key === 'M')) {
                window.togglePrivacyMask();
            }
        });
    });
})();
</script>

{{-- ========================================================
     SESSION TIMEOUT — 5 minutes inactivity → auto-logout
     Warning modal appears at 60 seconds remaining
     ======================================================== --}}
@auth
<div id="session-timeout-overlay" role="dialog" aria-modal="true" aria-labelledby="session-timeout-title">
    <div id="session-timeout-box">
        <h3 id="session-timeout-title">⏱ Session Expiring Soon</h3>
        <p>You've been inactive for a while. For your security, you will be automatically logged out in:</p>
        <div id="session-countdown">60</div>
        <div id="session-timeout-bar-wrap">
            <div id="session-timeout-bar"></div>
        </div>
        <button id="session-stay-btn" type="button">Stay Logged In</button>
    </div>
</div>

<form id="session-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
    @csrf
</form>

<script>
(function() {
    const TIMEOUT_MS  = 5 * 60 * 1000;   // 5 minutes total inactivity
    const WARN_BEFORE = 60;               // seconds of warning before logout
    const WARN_MS     = WARN_BEFORE * 1000;
    const TICK_MS     = 1000;

    let idleTimer     = null;
    let countdownInt  = null;
    let countdownSecs = WARN_BEFORE;
    let warningShown  = false;

    const overlay    = document.getElementById('session-timeout-overlay');
    const countdownEl = document.getElementById('session-countdown');
    const bar        = document.getElementById('session-timeout-bar');
    const stayBtn    = document.getElementById('session-stay-btn');
    const logoutForm = document.getElementById('session-logout-form');

    function resetIdle() {
        if (warningShown) return; // Don't reset while warning is visible
        clearTimeout(idleTimer);
        idleTimer = setTimeout(showWarning, TIMEOUT_MS - WARN_MS);
    }

    function showWarning() {
        warningShown  = true;
        countdownSecs = WARN_BEFORE;
        overlay.classList.add('show');
        countdownEl.textContent = countdownSecs;
        bar.style.width = '100%';

        countdownInt = setInterval(function() {
            countdownSecs--;
            countdownEl.textContent = countdownSecs;
            bar.style.width = ((countdownSecs / WARN_BEFORE) * 100) + '%';

            if (countdownSecs <= 0) {
                clearInterval(countdownInt);
                overlay.classList.remove('show');
                logoutForm.submit();
            }
        }, TICK_MS);
    }

    function stayLoggedIn() {
        clearInterval(countdownInt);
        warningShown = false;
        overlay.classList.remove('show');
        resetIdle();
    }

    // Activity events that reset the idle timer
    ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click'].forEach(function(evt) {
        document.addEventListener(evt, resetIdle, { passive: true });
    });

    stayBtn.addEventListener('click', stayLoggedIn);

    // Start the timer on load
    resetIdle();
})();
</script>
@endauth

</body>
</html>