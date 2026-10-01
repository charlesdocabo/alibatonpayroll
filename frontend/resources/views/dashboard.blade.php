<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Alibaton Construction Inc.</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background: #111111;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .brand {
            padding: 24px 20px;
            border-bottom: 1px solid #333;
        }

        .brand-logo {
            width: 45px;
            height: 45px;
            background: #f4c400;
            color: #111111;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 18px;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .brand h1 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
        }

        .brand p {
            margin: 5px 0 0;
            color: #cccccc;
            font-size: 12px;
        }

        .menu {
            padding: 20px 12px;
            flex: 1;
            overflow-y: auto;
        }

        .menu-title {
            color: #999999;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 12px 8px;
            margin-top: 10px;
        }

        .menu-title:not(:first-child) {
            margin-top: 25px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 11px;
            color: #dddddd;
            text-decoration: none;
            padding: 11px 12px;
            margin: 3px 0;
            border-radius: 7px;
            font-size: 14px;
            transition: 0.2s;
        }

        .menu a:hover,
        .menu a.active {
            background: #f4c400;
            color: #111111;
            font-weight: 700;
        }

        .menu-icon {
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 15px;
            border-top: 1px solid #333;
            font-size: 11px;
            color: #999999;
            text-align: center;
        }

        /* MAIN */
        .main {
            margin-left: 250px;
            width: calc(100% - 250px);
            min-height: 100vh;
        }

        .topbar {
            background: white;
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
            color: white;
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

        .welcome {
            background: linear-gradient(135deg, #111111, #292929);
            color: white;
            border-radius: 12px;
            padding: 25px 28px;
            margin-bottom: 25px;
            border-left: 6px solid #f4c400;
        }

        .welcome h1 {
            margin: 0 0 7px;
            font-size: 25px;
        }

        .welcome p {
            margin: 0;
            color: #dddddd;
            font-size: 14px;
        }

        /* STAT CARDS */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-top: 4px solid #f4c400;
        }

        .stat-label {
            color: #777777;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
        }

        /* TABLE */
        .panel {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .panel-header {
            padding: 20px;
            border-bottom: 1px solid #eeeeee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .panel-header h3 {
            margin: 0;
            font-size: 17px;
        }

        .panel-header span {
            color: #777777;
            font-size: 12px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            background: #f5f5f5;
            padding: 13px 18px;
            font-size: 12px;
            color: #555555;
            text-transform: uppercase;
        }

        td {
            padding: 14px 18px;
            border-top: 1px solid #eeeeee;
            font-size: 13px;
        }

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-active {
            background: #e7f7e7;
            color: #217a21;
        }

        .status-inactive {
            background: #eeeeee;
            color: #666666;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #888888;
        }

        /* RESPONSIVE */
        @media (max-width: 1000px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 700px) {
            .sidebar {
                width: 210px;
            }

            .main {
                margin-left: 210px;
                width: calc(100% - 210px);
            }

            .content {
                padding: 18px;
            }

            .topbar {
                padding: 15px 18px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .user-name {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="brand">
            <div class="brand-logo">AC</div>

            <h1>Alibaton Construction Inc.</h1>
            <p>Payroll & Benefits System</p>
        </div>

      <nav class="menu">

    <div class="menu-title">
        Main Menu
    </div>

    <a href="{{ route('dashboard') }}"
       class="{{ request()->is('dashboard') || request()->is('/') ? 'active' : '' }}">
        <span class="menu-icon">▣</span>
        Dashboard
    </a>


    @if(auth()->user()->role === 'Admin' || auth()->user()->role === 'HR')

        <a href="{{ route('employees.index') }}"
           class="{{ request()->is('employees*') ? 'active' : '' }}">
            <span class="menu-icon">♙</span>
            Employees
        </a>

        <a href="{{ route('payrolls.index') }}"
           class="{{ request()->is('payrolls*') ? 'active' : '' }}">
            <span class="menu-icon">₱</span>
            Payroll
        </a>


        <div class="menu-title">
            Employee Services
        </div>

        <a href="{{ route('benefits.index') }}"
           class="{{ request()->is('benefits*') ? 'active' : '' }}">
            <span class="menu-icon">♥</span>
            Benefits
        </a>

        <a href="{{ route('claims.index') }}"
           class="{{ request()->is('claims*') ? 'active' : '' }}">
            <span class="menu-icon">▤</span>
            Claims
        </a>

        <a href="{{ route('incentives.index') }}"
           class="{{ request()->is('incentives*') ? 'active' : '' }}">
            <span class="menu-icon">★</span>
            Incentives
        </a>


        <div class="menu-title">
            Management
        </div>


        {{-- USER MANAGEMENT - ADMIN ONLY --}}

        @if(auth()->user()->role === 'Admin')

            <a href="{{ route('users.index') }}"
               class="{{ request()->is('users*') ? 'active' : '' }}">
                <span class="menu-icon">♟</span>
                User Management
            </a>

        @endif


        {{-- SALARY GRADES - ADMIN ONLY --}}

        @if(auth()->user()->role === 'Admin')

            <a href="{{ route('salary-grades.index') }}"
               class="{{ request()->is('salary-grades*') ? 'active' : '' }}">
                <span class="menu-icon">₱</span>
                Salary Grades
            </a>

        @endif

        @if(auth()->user()->role === 'Admin')
    <a href="{{ route('audit-logs.index') }}"
       class="{{ request()->is('audit-logs*') ? 'active' : '' }}">
        <span class="menu-icon">▤</span>
        Audit Logs
    </a>
@endif


        {{-- HR ANALYTICS - ADMIN + HR --}}

        <a href="{{ route('analytics.index') }}"
           class="{{ request()->is('analytics*') ? 'active' : '' }}">
            <span class="menu-icon">▥</span>
            HR Analytics
        </a>

       @endif

    {{-- PROFILE - ALL AUTHENTICATED USERS --}}
    <div class="menu-title">
        Account
    </div>

    <a href="{{ route('profile.edit') }}"
       class="{{ request()->is('profile*') ? 'active' : '' }}">
        <span class="menu-icon">👤</span>
        Profile
    </a>

</nav>

        <div class="sidebar-footer">
            ALIBATON Construction Inc. © 2026
        </div>

    </aside>


    <!-- MAIN CONTENT -->
    <main class="main">

        <header class="topbar">

            <h2>Dashboard</h2>

            <div class="user-area">

                <span class="user-name">
                    {{ Auth::user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="logout-btn">
                        Logout
                    </button>
                </form>

            </div>

        </header>


        <section class="content">

            <!-- WELCOME -->
            <div class="welcome">

                <h1>Welcome back, {{ Auth::user()->name }}!</h1>

                <p>
                    Manage employees, payroll, benefits, claims,
                    incentives, and HR analytics from one system.
                </p>

            </div>


            <!-- STATISTICS -->
            <div class="stats">

                <div class="stat-card">
                    <div class="stat-label">Total Employees</div>

                    <div class="stat-value">
                        {{ count($employees['data'] ?? []) }}
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Payroll Records</div>

                    <div class="stat-value">
                        {{ count($payrolls['data'] ?? []) }}
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Benefits</div>

                    <div class="stat-value">
                        {{ count($benefits['data'] ?? []) }}
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Claims</div>

                    <div class="stat-value">
                        {{ count($claims['data'] ?? []) }}
                    </div>
                </div>

            </div>


            <!-- EMPLOYEES -->
            <div class="panel">

                <div class="panel-header">

                    <h3>Employee Overview</h3>

                    <span>
                        {{ count($employees['data'] ?? []) }} employee(s)
                    </span>

                </div>

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Salary</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse(($employees['data'] ?? []) as $employee)

                                <tr>

                                    <td>
                                        {{ $employee['employee_id'] ?? '—' }}
                                    </td>

                                    <td>
                                        {{ trim(
                                            ($employee['first_name'] ?? '') . ' ' .
                                            ($employee['last_name'] ?? '')
                                        ) ?: '—' }}
                                    </td>

                                    <td>
                                        {{ $employee['position'] ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $employee['department'] ?? '—' }}
                                    </td>

                                    <td>
                                        ₱{{ number_format((float)($employee['salary'] ?? 0), 2) }}
                                    </td>

                                    <td>

                                        @if(($employee['status'] ?? '') === 'active')

                                            <span class="status status-active">
                                                Active
                                            </span>

                                        @else

                                            <span class="status status-inactive">
                                                {{ ucfirst($employee['status'] ?? 'Unknown') }}
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="empty">
                                        No employees found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

    </main>

</div>

</body>
</html>