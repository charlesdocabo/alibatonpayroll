@extends('layouts.app')

@section('title', 'Payroll - Alibaton Construction Inc.')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
    }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        color: #111111;
    }

    .page-subtitle {
        margin: 6px 0 0;
        color: #666666;
        font-size: 14px;
    }

    .add-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #F4C400;
        color: #111111;
        padding: 11px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700;
        white-space: nowrap;
        transition: 0.2s ease;
    }

    .add-button:hover {
        background: #DCAE00;
        color: #111111;
        transform: translateY(-1px);
    }

    /* Alerts */

    .message {
        padding: 13px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .success {
        background: #e8f5e9;
        border: 1px solid #c8e6c9;
        color: #2e7d32;
    }

    .error {
        background: #ffebee;
        border: 1px solid #ffcdd2;
        color: #c62828;
    }

    /* Section */

    .section-title {
        margin: 30px 0 15px;
        font-size: 18px;
        font-weight: 800;
        color: #111111;
    }

    /* Monitoring */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .financial-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 14px rgba(0, 0, 0, 0.07);
    }

    .stat-label {
        color: #777777;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 9px;
    }

    .stat-value {
        color: #111111;
        font-size: 26px;
        font-weight: 800;
        line-height: 1.2;
    }

    .amount {
        font-size: 22px;
    }

    .stat-card.highlight {
        border-top: 4px solid #F4C400;
    }

    .stat-card.success {
        border-top: 4px solid #2e7d32;
    }

    .stat-card.warning {
        border-top: 4px solid #DCAE00;
    }

    .stat-card.danger {
        border-top: 4px solid #c62828;
    }

    .stat-card.dark {
        border-top: 4px solid #111111;
    }

    /* Payroll Summary */

    .summary-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .summary-item {
        background: #f8f8f8;
        border-radius: 9px;
        padding: 16px;
    }

    .summary-item-label {
        color: #777777;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .summary-item-value {
        color: #111111;
        font-size: 20px;
        font-weight: 800;
    }

    /* Records */

    .table-container {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        overflow-x: auto;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1150px;
    }

    th {
        background: #111111;
        color: #ffffff;
        padding: 13px 12px;
        text-align: left;
        font-size: 13px;
        white-space: nowrap;
    }

    td {
        padding: 13px 12px;
        border-bottom: 1px solid #eeeeee;
        font-size: 14px;
        white-space: nowrap;
        vertical-align: middle;
    }

    tbody tr:hover {
        background: #fafafa;
    }

    .amount-cell {
        text-align: right;
    }

    .net-pay {
        font-weight: 800;
        color: #2e7d32;
    }

    .empty {
        text-align: center;
        padding: 45px 20px;
        color: #777777;
    }

    .empty h3 {
        margin: 0 0 8px;
        color: #111111;
        font-size: 17px;
    }

    .empty p {
        margin: 0;
        font-size: 14px;
    }

    /* Actions */

    .actions {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .actions form {
        margin: 0;
    }

    .action-button {
        display: inline-block;
        padding: 7px 11px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: 0.2s ease;
        white-space: nowrap;
    }

    .payslip-button {
        background: #111111;
        color: #F4C400;
    }

    .payslip-button:hover {
        background: #333333;
        color: #F4C400;
    }

    .edit-button {
        background: #F4C400;
        color: #111111;
    }

    .edit-button:hover {
        background: #DCAE00;
    }

    .delete-button {
        background: #eeeeee;
        color: #111111;
    }

    .delete-button:hover {
        background: #dddddd;
    }

    /* Responsive */

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .financial-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 650px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .add-button {
            width: 100%;
        }

        .stats-grid,
        .financial-grid,
        .summary-grid {
            grid-template-columns: 1fr;
        }

        .stat-value {
            font-size: 24px;
        }
    }
</style>
@endsection

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Payroll
        </h1>

        <p class="page-subtitle">
            Manage employee payroll records, compensation, and payroll payments.
        </p>
    </div>

    <a href="{{ url('/payrolls/create') }}" class="add-button">
        + Add Payroll
    </a>

</div>


{{-- =========================
     MESSAGES
========================= --}}

@if(session('success'))
    <div class="message success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="message error">
        {{ session('error') }}
    </div>
@endif

@if(isset($error))
    <div class="message error">
        {{ $error }}
    </div>
@endif


{{-- =========================
     PAYROLL MONITORING
========================= --}}

<h2 class="section-title">
    Payroll Monitoring
</h2>

<div class="stats-grid">

    <div class="stat-card highlight">

        <div class="stat-label">
            Total Payroll Records
        </div>

        <div class="stat-value">
            {{ $totalPayrollRecords ?? 0 }}
        </div>

    </div>


    <div class="stat-card dark">

        <div class="stat-label">
            Total Basic Salary
        </div>

        <div class="stat-value amount">
            ₱{{ number_format($totalBasicSalary ?? 0, 2) }}
        </div>

    </div>


    <div class="stat-card warning">

        <div class="stat-label">
            Total Overtime Pay
        </div>

        <div class="stat-value amount">
            ₱{{ number_format($totalOvertimePay ?? 0, 2) }}
        </div>

    </div>


    <div class="stat-card success">

        <div class="stat-label">
            Total Allowances
        </div>

        <div class="stat-value amount">
            ₱{{ number_format($totalAllowances ?? 0, 2) }}
        </div>

    </div>

</div>


{{-- =========================
     PAYROLL FINANCIAL SUMMARY
========================= --}}

<h2 class="section-title">
    Payroll Financial Summary
</h2>

<div class="financial-grid">

    <div class="stat-card highlight">

        <div class="stat-label">
            Total Gross Pay
        </div>

        <div class="stat-value amount">
            ₱{{ number_format($totalGrossPay ?? 0, 2) }}
        </div>

    </div>


    <div class="stat-card danger">

        <div class="stat-label">
            Total Deductions
        </div>

        <div class="stat-value amount">
            ₱{{ number_format($totalDeductions ?? 0, 2) }}
        </div>

    </div>


    <div class="stat-card success">

        <div class="stat-label">
            Total Net Pay
        </div>

        <div class="stat-value amount">
            ₱{{ number_format($totalNetPay ?? 0, 2) }}
        </div>

    </div>


    <div class="stat-card dark">

        <div class="stat-label">
            Average Net Pay
        </div>

        <div class="stat-value amount">
            ₱{{ number_format($averageNetPay ?? 0, 2) }}
        </div>

    </div>

</div>


{{-- =========================
     PAYROLL SUMMARY
========================= --}}

<h2 class="section-title">
    Payroll Summary
</h2>

<div class="summary-card">

    <div class="summary-grid">

        <div class="summary-item">

            <div class="summary-item-label">
                Total Payroll Records
            </div>

            <div class="summary-item-value">
                {{ $totalPayrollRecords ?? 0 }}
            </div>

        </div>


        <div class="summary-item">

            <div class="summary-item-label">
                Total Overtime Hours
            </div>

            <div class="summary-item-value">
                {{ number_format($totalOvertimeHours ?? 0, 2) }}
            </div>

        </div>


        <div class="summary-item">

            <div class="summary-item-label">
                Total Gross Payroll
            </div>

            <div class="summary-item-value">
                ₱{{ number_format($totalGrossPay ?? 0, 2) }}
            </div>

        </div>


        <div class="summary-item">

            <div class="summary-item-label">
                Total Net Payroll
            </div>

            <div class="summary-item-value">
                ₱{{ number_format($totalNetPay ?? 0, 2) }}
            </div>

        </div>

    </div>

</div>


{{-- =========================
     PAYROLL RECORDS
========================= --}}

<h2 class="section-title">
    Payroll Records
</h2>

<div class="table-container">

    @if(count($payrolls ?? []))

        <table>

            <thead>

                <tr>
                    <th>Employee ID</th>
                    <th>Pay Date</th>
                    <th>Basic Salary</th>
                    <th>Overtime</th>
                    <th>Allowances</th>
                    <th>Gross Pay</th>
                    <th>Deductions</th>
                    <th>Net Pay</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

                @foreach($payrolls as $payroll)

                    <tr>

                        <td>
                            <strong>
                                {{ $payroll['employee_id'] ?? 'N/A' }}
                            </strong>
                        </td>


                        <td>
                            {{ isset($payroll['pay_date'])
                                ? date('M d, Y', strtotime($payroll['pay_date']))
                                : 'N/A' }}
                        </td>


                        <td class="amount-cell">
                            ₱{{ number_format(
                                (float) ($payroll['basic_salary'] ?? 0),
                                2
                            ) }}
                        </td>


                        <td class="amount-cell">
                            ₱{{ number_format(
                                (float) ($payroll['overtime_pay'] ?? 0),
                                2
                            ) }}
                        </td>


                        <td class="amount-cell">
                            ₱{{ number_format(
                                (float) ($payroll['allowances'] ?? 0),
                                2
                            ) }}
                        </td>


                        <td class="amount-cell">
                            ₱{{ number_format(
                                (float) ($payroll['gross_pay'] ?? 0),
                                2
                            ) }}
                        </td>


                        <td class="amount-cell">
                            ₱{{ number_format(
                                (float) ($payroll['total_deductions'] ?? 0),
                                2
                            ) }}
                        </td>


                        <td class="amount-cell net-pay">
                            ₱{{ number_format(
                                (float) ($payroll['net_pay'] ?? 0),
                                2
                            ) }}
                        </td>


                        <td>

                            <div class="actions">

                                <a
                                    href="{{ url('/payrolls/' . ($payroll['id'] ?? '') . '/payslip') }}"
                                    class="action-button payslip-button"
                                >
                                    View Payslip
                                </a>


                                <a
                                    href="{{ url('/payrolls/' . ($payroll['id'] ?? '') . '/edit') }}"
                                    class="action-button edit-button"
                                >
                                    Edit
                                </a>


                                <form
                                    action="{{ url('/payrolls/' . ($payroll['id'] ?? '')) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this payroll record?');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="action-button delete-button"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="empty">

            <h3>
                No payroll records found
            </h3>

            <p>
                Click "Add Payroll" to create the first payroll record.
            </p>

        </div>

    @endif

</div>

@endsection

