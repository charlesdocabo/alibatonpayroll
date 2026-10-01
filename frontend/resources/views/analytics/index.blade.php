@extends('layouts.app')

@section('title', 'HR Analytics - Alibaton Construction Inc.')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 20px;
    }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #111111;
    }

    .page-subtitle {
        margin: 5px 0 0;
        color: #666666;
        font-size: 14px;
    }

    /* =========================
       ALERTS
    ========================= */

    .alert {
        padding: 13px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-error {
        background: #ffebee;
        color: #c62828;
        border: 1px solid #ffcdd2;
    }

    /* =========================
       ANALYTICS CARDS
    ========================= */

    .analytics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 25px;
    }

    .analytics-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        border-top: 4px solid #F4C400;
    }

    .analytics-card.dark {
        border-top-color: #111111;
    }

    .analytics-label {
        color: #777777;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .analytics-value {
        color: #111111;
        font-size: 25px;
        font-weight: 800;
    }

    .analytics-description {
        margin-top: 6px;
        color: #888888;
        font-size: 12px;
    }

    /* =========================
       MAIN GRID
    ========================= */

    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 25px;
    }

    .panel {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .panel-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #111111;
    }

    .panel-subtitle {
        margin: 4px 0 20px;
        color: #777777;
        font-size: 13px;
    }

    /* =========================
       WORKFORCE STATUS
    ========================= */

    .status-row {
        margin-bottom: 18px;
    }

    .status-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 7px;
    }

    .status-name {
        font-size: 13px;
        font-weight: 700;
        color: #333333;
    }

    .status-count {
        font-size: 13px;
        font-weight: 700;
        color: #111111;
    }

    .progress-bar {
        width: 100%;
        height: 10px;
        background: #eeeeee;
        border-radius: 20px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 20px;
        background: #F4C400;
    }

    .progress-fill.inactive {
        background: #111111;
    }

    .status-summary {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 25px;
    }

    .status-box {
        padding: 15px;
        border-radius: 9px;
        background: #f7f7f7;
        border-left: 4px solid #F4C400;
    }

    .status-box.dark {
        border-left-color: #111111;
    }

    .status-box-label {
        font-size: 12px;
        color: #777777;
        margin-bottom: 5px;
    }

    .status-box-value {
        font-size: 21px;
        font-weight: 800;
        color: #111111;
    }

    /* =========================
       PAYROLL PANEL
    ========================= */

    .payroll-main {
        font-size: 30px;
        font-weight: 800;
        color: #111111;
        margin-bottom: 5px;
    }

    .payroll-label {
        color: #777777;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .payroll-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .payroll-stat {
        background: #f7f7f7;
        border-radius: 9px;
        padding: 15px;
    }

    .payroll-stat-label {
        color: #777777;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .payroll-stat-value {
        color: #111111;
        font-size: 20px;
        font-weight: 800;
    }

    /* =========================
       HR SERVICES
    ========================= */

    .service-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .service-card {
        background: #f7f7f7;
        border: 1px solid #eeeeee;
        border-radius: 10px;
        padding: 18px;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .service-card:hover {
        border-color: #F4C400;
        background: #fffdf0;
        transform: translateY(-2px);
    }

    .service-icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #F4C400;
        color: #111111;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 12px;
    }

    .service-name {
        color: #111111;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .service-count {
        color: #777777;
        font-size: 12px;
    }

    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 1050px) {
        .analytics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 800px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .service-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .analytics-grid {
            grid-template-columns: 1fr;
        }

        .status-summary,
        .payroll-stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            HR Analytics
        </h1>

        <p class="page-subtitle">
            Workforce, payroll, benefits, and claims overview.
        </p>
    </div>

</div>


{{-- =========================
     ERROR
========================= --}}

@if(isset($error))

    <div class="alert alert-error">
        {{ $error }}
    </div>

@endif


{{-- =========================
     MAIN ANALYTICS CARDS
========================= --}}

<div class="analytics-grid">

    {{-- TOTAL EMPLOYEES --}}
    <div class="analytics-card">

        <div class="analytics-label">
            Total Employees
        </div>

        <div class="analytics-value">
            {{ $totalEmployees ?? 0 }}
        </div>

        <div class="analytics-description">
            Total employees in the system
        </div>

    </div>


    {{-- ACTIVE EMPLOYEES --}}
    <div class="analytics-card">

        <div class="analytics-label">
            Active Employees
        </div>

        <div class="analytics-value">
            {{ $activeEmployees ?? 0 }}
        </div>

        <div class="analytics-description">
            Currently active employees
        </div>

    </div>


    {{-- TOTAL PAYROLL --}}
    <div class="analytics-card dark">

        <div class="analytics-label">
            Total Payroll
        </div>

        <div class="analytics-value">
            ₱{{ number_format($totalPayroll ?? 0, 2) }}
        </div>

        <div class="analytics-description">
            Recorded payroll amount
        </div>

    </div>


    {{-- AVERAGE SALARY --}}
    <div class="analytics-card dark">

        <div class="analytics-label">
            Average Salary
        </div>

        <div class="analytics-value">
            ₱{{ number_format($averageSalary ?? 0, 2) }}
        </div>

        <div class="analytics-description">
            Average employee salary
        </div>

    </div>

</div>


{{-- =========================
     WORKFORCE + PAYROLL
========================= --}}

<div class="dashboard-grid">


    {{-- WORKFORCE STATUS --}}
    <div class="panel">

        <h2 class="panel-title">
            Workforce Status
        </h2>

        <p class="panel-subtitle">
            Current employee status distribution.
        </p>


        {{-- ACTIVE --}}
        <div class="status-row">

            <div class="status-header">

                <span class="status-name">
                    Active Employees
                </span>

                <span class="status-count">
                    {{ $activeEmployees ?? 0 }}
                    ({{ number_format($activePercentage ?? 0, 1) }}%)
                </span>

            </div>

            <div class="progress-bar">

                <div
                    class="progress-fill"
                    style="width: {{ min(100, max(0, $activePercentage ?? 0)) }}%;"
                ></div>

            </div>

        </div>


        {{-- INACTIVE --}}
        <div class="status-row">

            <div class="status-header">

                <span class="status-name">
                    Inactive Employees
                </span>

                <span class="status-count">
                    {{ $inactiveEmployees ?? 0 }}
                    ({{ number_format($inactivePercentage ?? 0, 1) }}%)
                </span>

            </div>

            <div class="progress-bar">

                <div
                    class="progress-fill inactive"
                    style="width: {{ min(100, max(0, $inactivePercentage ?? 0)) }}%;"
                ></div>

            </div>

        </div>


        {{-- STATUS SUMMARY --}}
        <div class="status-summary">

            <div class="status-box">

                <div class="status-box-label">
                    Active
                </div>

                <div class="status-box-value">
                    {{ $activeEmployees ?? 0 }}
                </div>

            </div>


            <div class="status-box dark">

                <div class="status-box-label">
                    Inactive
                </div>

                <div class="status-box-value">
                    {{ $inactiveEmployees ?? 0 }}
                </div>

            </div>

        </div>

    </div>


    {{-- PAYROLL OVERVIEW --}}
    <div class="panel">

        <h2 class="panel-title">
            Payroll Overview
        </h2>

        <p class="panel-subtitle">
            Summary of payroll records and employee compensation.
        </p>


        <div class="payroll-main">
            ₱{{ number_format($totalPayroll ?? 0, 2) }}
        </div>

        <div class="payroll-label">
            Total recorded payroll
        </div>


        <div class="payroll-stats">

            <div class="payroll-stat">

                <div class="payroll-stat-label">
                    Payroll Records
                </div>

                <div class="payroll-stat-value">
                    {{ $payrollRecords ?? 0 }}
                </div>

            </div>


            <div class="payroll-stat">

                <div class="payroll-stat-label">
                    Average Salary
                </div>

                <div class="payroll-stat-value">
                    ₱{{ number_format($averageSalary ?? 0, 2) }}
                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================
     HR SERVICES
========================= --}}

<div class="panel">

    <h2 class="panel-title">
        HR Services Overview
    </h2>

    <p class="panel-subtitle">
        Current records across employee service modules.
    </p>


    <div class="service-grid">


        {{-- BENEFITS --}}
        <a
            href="{{ url('/benefits') }}"
            class="service-card"
        >

            <div class="service-icon">
                B
            </div>

            <div class="service-name">
                Benefits
            </div>

            <div class="service-count">
                {{ $totalBenefits ?? 0 }} records
            </div>

        </a>


        {{-- CLAIMS --}}
        <a
            href="{{ url('/claims') }}"
            class="service-card"
        >

            <div class="service-icon">
                C
            </div>

            <div class="service-name">
                Claims
            </div>

            <div class="service-count">
                {{ $totalClaims ?? 0 }} records
            </div>

        </a>


        {{-- PAYROLL --}}
        <a
            href="{{ url('/payrolls') }}"
            class="service-card"
        >

            <div class="service-icon">
                ₱
            </div>

            <div class="service-name">
                Payroll
            </div>

            <div class="service-count">
                {{ $payrollRecords ?? 0 }} records
            </div>

        </a>

    </div>

</div>

@endsection

