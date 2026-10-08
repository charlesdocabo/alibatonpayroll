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
            if (masked) {
                if (raw.startsWith('₱') || raw.includes('₱')) {
                    el.textContent = '₱••••••';
                } else if (raw === '-' || raw === '—' || raw === 'N/A' || raw === '0' || raw === '0.00' || raw === '') {
                    el.textContent = raw;
                } else {
                    el.textContent = '••••••';
                }
            } else {
                el.textContent = raw;
            }
        });
    }

    function updateToggleButtons(masked) {
        const svgEyeOff = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;flex-shrink:0"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
        const svgEye    = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;flex-shrink:0"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';

        document.querySelectorAll('.btn-privacy-toggle, .btn-privacy-page-toggle').forEach(btn => {
            if (masked) {
                btn.classList.add('is-masked');
                btn.innerHTML = svgEyeOff + ' <span>Masked</span>';
                btn.setAttribute('title', 'Confidential Mode Active — sensitive amounts are hidden (₱••••••). Click to unmask.');
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

</body>
</html>