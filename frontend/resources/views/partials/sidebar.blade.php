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

    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 260px;
        height: 100vh;
        background: #111111;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        z-index: 1000;
        box-shadow: 4px 0 15px rgba(0, 0, 0, 0.12);
    }

    .sidebar-brand {
        padding: 24px 22px;
        border-bottom: 1px solid #333333;
    }

    .brand-icon {
        width: 42px;
        height: 42px;
        background: #f4c400;
        color: #111111;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 12px;
    }

    .brand-name {
        font-size: 17px;
        font-weight: bold;
        color: #ffffff;
        line-height: 1.3;
    }

    .brand-subtitle {
        font-size: 12px;
        color: #bbbbbb;
        margin-top: 4px;
    }

    .sidebar-section {
        padding: 20px 15px 8px;
        font-size: 11px;
        font-weight: bold;
        color: #888888;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        padding: 0 12px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 13px;
        color: #dddddd;
        text-decoration: none;
        padding: 13px 14px;
        margin: 3px 0;
        border-radius: 7px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .sidebar-link:hover {
        background: #2a2a2a;
        color: #ffffff;
        transform: translateX(2px);
    }

    .sidebar-link.active {
        background: #f4c400;
        color: #111111;
        font-weight: bold;
    }

    .sidebar-icon {
        width: 22px;
        min-width: 22px;
        text-align: center;
        font-size: 16px;
        line-height: 1;
    }

    .sidebar-footer {
        border-top: 1px solid #333333;
        padding: 15px 20px;
        font-size: 11px;
        color: #888888;
    }

    .sidebar-footer strong {
        color: #f4c400;
    }

    .main-content {
        margin-left: 260px;
        min-height: 100vh;
        padding: 30px;
    }

    @media (max-width: 768px) {

        .sidebar {
            width: 220px;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px;
        }

        .brand-name {
            font-size: 14px;
        }

        .sidebar-link {
            font-size: 13px;
        }
    }
</style>

<div class="sidebar">

    <!-- BRAND -->

    <div class="sidebar-brand">

        <div class="brand-icon">
            AC
        </div>

        <div class="brand-name">
            Alibaton Construction Inc.
        </div>

        <div class="brand-subtitle">
            Payroll &amp; Benefits System
        </div>

    </div>


    <!-- NAVIGATION -->

    <div class="sidebar-nav">

        <!-- MAIN MENU -->

        <div class="sidebar-section">
            Main Menu
        </div>


        <!-- DASHBOARD - ALL USERS -->

        <a
            href="{{ route('dashboard') }}"
            class="sidebar-link {{ request()->is('dashboard') || request()->is('/') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">⌂</span>
            <span>Dashboard</span>
        </a>


        <!-- ADMIN + HR -->

        @if(in_array(auth()->user()->role, ['Admin', 'HR']))

            <!-- EMPLOYEES -->

            <a
                href="{{ route('employees.index') }}"
                class="sidebar-link {{ request()->is('employees*') ? 'active' : '' }}"
            >
                <span class="sidebar-icon">👥</span>
                <span>Employees</span>
            </a>


            <!-- PAYROLL -->

            <a
                href="{{ route('payrolls.index') }}"
                class="sidebar-link {{ request()->is('payrolls*') ? 'active' : '' }}"
            >
                <span class="sidebar-icon">₱</span>
                <span>Payroll</span>
            </a>


            <!-- EMPLOYEE SERVICES -->

            <div class="sidebar-section">
                Employee Services
            </div>


            <!-- BENEFITS -->

            <a
                href="{{ route('benefits.index') }}"
                class="sidebar-link {{ request()->is('benefits*') ? 'active' : '' }}"
            >
                <span class="sidebar-icon">▣</span>
                <span>Benefits</span>
            </a>


            <!-- CLAIMS -->

            <a
                href="{{ route('claims.index') }}"
                class="sidebar-link {{ request()->is('claims*') ? 'active' : '' }}"
            >
                <span class="sidebar-icon">✓</span>
                <span>Claims</span>
            </a>


            <!-- INCENTIVES -->

            <a
                href="{{ route('incentives.index') }}"
                class="sidebar-link {{ request()->is('incentives*') ? 'active' : '' }}"
            >
                <span class="sidebar-icon">★</span>
                <span>Incentives</span>
            </a>

        @endif


        <!-- MANAGEMENT -->

        <div class="sidebar-section">
            Management
        </div>


        <!-- ADMIN ONLY -->

        @if(auth()->user()->role === 'Admin')

            <!-- USER MANAGEMENT -->

            <a
                href="{{ route('users.index') }}"
                class="sidebar-link {{ request()->is('users*') ? 'active' : '' }}"
            >
                <span class="sidebar-icon">👤</span>
                <span>User Management</span>
            </a>

<!-- EMAIL CHANGE REQUESTS -->

<a
    href="{{ route('email-change-requests.index') }}"
    class="sidebar-link {{ request()->is('email-change-requests*') ? 'active' : '' }}"
>
    <span class="sidebar-icon">✉</span>
    <span>Email Change Requests</span>
</a>


            <!-- AUDIT LOGS -->

            <a
                href="{{ route('audit-logs.index') }}"
                class="sidebar-link {{ request()->is('audit-logs*') ? 'active' : '' }}"
            >
                <span class="sidebar-icon">▤</span>
                <span>Audit Logs</span>
            </a>


            <!-- SALARY GRADES -->

            <a
                href="{{ route('salary-grades.index') }}"
                class="sidebar-link {{ request()->is('salary-grades*') ? 'active' : '' }}"
            >
                <span class="sidebar-icon">₱</span>
                <span>Salary Grades</span>
            </a>

        @endif


        <!-- HR ANALYTICS - ADMIN + HR -->

        @if(in_array(auth()->user()->role, ['Admin', 'HR']))

            <a
                href="{{ route('analytics.index') }}"
                class="sidebar-link {{ request()->is('analytics*') ? 'active' : '' }}"
            >
                <span class="sidebar-icon">◈</span>
                <span>HR Analytics</span>
            </a>

        @endif


        <!-- PROFILE - ALL AUTHENTICATED USERS -->

        <a
            href="{{ route('profile.edit') }}"
            class="sidebar-link {{ request()->is('profile*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">👤</span>
            <span>Profile</span>
        </a>

    </div>


    <!-- FOOTER -->

    <div class="sidebar-footer">

        <strong>ALIBATON</strong><br>

        Construction Inc. © 2026

    </div>

</div>

