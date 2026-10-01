@extends('layouts.app')

@section('title', 'Payslip - Alibaton Construction Inc.')

@section('styles')
<style>
    .payslip-wrapper {
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
}

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 28px;
    }

    .page-header p {
        margin: 6px 0 0;
        color: #666;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn {
        display: inline-block;
        padding: 11px 18px;
        border-radius: 6px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
    }

    .btn-back {
        background: #eeeeee;
        color: #111111;
    }

    .btn-print {
        background: #f4c400;
        color: #111111;
    }
.payslip-card {
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

    .payslip-header {
        background: #111111;
        color: #ffffff;
        padding: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .company {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .company-logo {
        width: 55px;
        height: 55px;
        background: #f4c400;
        color: #111111;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: bold;
    }

    .company h2 {
        margin: 0;
        font-size: 21px;
    }

    .company p {
        margin: 5px 0 0;
        color: #cccccc;
        font-size: 13px;
    }

    .payslip-title {
        text-align: right;
    }

    .payslip-title h3 {
        margin: 0;
        color: #f4c400;
        font-size: 22px;
    }

    .payslip-title p {
        margin: 5px 0 0;
        color: #cccccc;
        font-size: 13px;
    }

    .employee-info {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        padding: 25px 30px;
        border-bottom: 1px solid #eeeeee;
    }

    .info-item label {
        display: block;
        font-size: 11px;
        color: #777777;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 5px;
    }

    .info-item strong {
        font-size: 15px;
        color: #111111;
    }

    .pay-section {
        padding: 25px 30px;
    }

    .section-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f4c400;
    }

    .pay-table {
        width: 100%;
        border-collapse: collapse;
    }

    .pay-table th {
        background: #f5f5f5;
        text-align: left;
        padding: 12px;
        font-size: 12px;
        color: #555555;
        text-transform: uppercase;
    }

    .pay-table td {
        padding: 13px 12px;
        border-bottom: 1px solid #eeeeee;
        font-size: 14px;
    }

    .pay-table td:last-child,
    .pay-table th:last-child {
        text-align: right;
    }

    .total-row td {
        font-weight: bold;
        font-size: 15px;
        background: #fafafa;
    }

    .net-pay {
        margin: 0 30px 30px;
        background: #111111;
        color: #ffffff;
        border-radius: 8px;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .net-pay-label {
        font-size: 14px;
        color: #cccccc;
    }

    .net-pay-amount {
        font-size: 28px;
        font-weight: bold;
        color: #f4c400;
    }

    .footer-note {
        padding: 20px 30px;
        background: #f5f5f5;
        text-align: center;
        color: #777777;
        font-size: 12px;
    }

    @media print {
        body {
            background: #ffffff !important;
        }

        .sidebar,
        .page-header,
        .action-buttons {
            display: none !important;
        }

        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }

        .payslip-wrapper {
            max-width: 100%;
        }

        .payslip-card {
            box-shadow: none;
        }

        .payslip-header {
            background: #111111 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .net-pay {
            background: #111111 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .company-logo,
        .net-pay-amount {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    @media (max-width: 700px) {
        .payslip-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .payslip-title {
            text-align: left;
        }

        .employee-info {
            grid-template-columns: 1fr;
        }

        .net-pay {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
    }
</style>


@endsection

@section('content')

@php
    $basicSalary = (float) ($payroll['basic_salary'] ?? 0);
    $overtimePay = (float) ($payroll['overtime_pay'] ?? 0);
    $allowances = (float) ($payroll['allowances'] ?? 0);

    $sss = (float) ($payroll['sss_deduction'] ?? 0);
    $philhealth = (float) ($payroll['philhealth_deduction'] ?? 0);
    $pagibig = (float) ($payroll['pagibig_deduction'] ?? 0);
    $otherDeductions = (float) ($payroll['other_deductions'] ?? 0);

    $grossPay = (float) ($payroll['gross_pay'] ?? 0);
    $totalDeductions = (float) ($payroll['total_deductions'] ?? 0);
    $netPay = (float) ($payroll['net_pay'] ?? 0);

    $payDate = $payroll['pay_date'] ?? null;
@endphp

<div class="payslip-wrapper">

    <div class="page-header">
        <div>
            <h1>Payslip</h1>
            <p>Payroll record #{{ $payroll['id'] ?? 'N/A' }}</p>
        </div>

        <div class="action-buttons">
            <a href="/payrolls" class="btn btn-back">
                ← Back
            </a>

            <button onclick="window.print()" class="btn btn-print">
                🖨 Print Payslip
            </button>
        </div>
    </div>

    <div class="payslip-card">

        <div class="payslip-header">

            <div class="company">
                <div class="company-logo">AC</div>

                <div>
                    <h2>Alibaton Construction Inc.</h2>
                    <p>Payroll & Benefits System</p>
                </div>
            </div>

            <div class="payslip-title">
                <h3>PAYSLIP</h3>
                <p>
                    Pay Date:
                    {{ $payDate ? date('F d, Y', strtotime($payDate)) : 'N/A' }}
                </p>
            </div>

        </div>

        <div class="employee-info">

            <div class="info-item">
                <label>Employee ID</label>
                <strong>{{ $payroll['employee_id'] ?? 'N/A' }}</strong>
            </div>

            <div class="info-item">
                <label>Payroll ID</label>
                <strong>#{{ $payroll['id'] ?? 'N/A' }}</strong>
            </div>

            <div class="info-item">
                <label>Pay Date</label>
                <strong>
                    {{ $payDate ? date('F d, Y', strtotime($payDate)) : 'N/A' }}
                </strong>
            </div>

            <div class="info-item">
                <label>Overtime Hours</label>
                <strong>
                    {{ number_format((float) ($payroll['overtime_hours'] ?? 0), 2) }}
                    hours
                </strong>
            </div>

        </div>

        <div class="pay-section">

            <div class="section-title">
                Earnings
            </div>

            <table class="pay-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>Basic Salary</td>
                        <td>₱{{ number_format($basicSalary, 2) }}</td>
                    </tr>

                    <tr>
                        <td>Overtime Pay</td>
                        <td>₱{{ number_format($overtimePay, 2) }}</td>
                    </tr>

                    <tr>
                        <td>Allowances</td>
                        <td>₱{{ number_format($allowances, 2) }}</td>
                    </tr>

                    <tr class="total-row">
                        <td>Gross Pay</td>
                        <td>₱{{ number_format($grossPay, 2) }}</td>
                    </tr>

                </tbody>
            </table>

        </div>

        <div class="pay-section">

            <div class="section-title">
                Deductions
            </div>

            <table class="pay-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>SSS Contribution</td>
                        <td>₱{{ number_format($sss, 2) }}</td>
                    </tr>

                    <tr>
                        <td>PhilHealth Contribution</td>
                        <td>₱{{ number_format($philhealth, 2) }}</td>
                    </tr>

                    <tr>
                        <td>Pag-IBIG Contribution</td>
                        <td>₱{{ number_format($pagibig, 2) }}</td>
                    </tr>

                    <tr>
                        <td>Other Deductions</td>
                        <td>₱{{ number_format($otherDeductions, 2) }}</td>
                    </tr>

                    <tr class="total-row">
                        <td>Total Deductions</td>
                        <td>₱{{ number_format($totalDeductions, 2) }}</td>
                    </tr>

                </tbody>
            </table>

        </div>

        <div class="net-pay">
            <div class="net-pay-label">
                NET PAY
            </div>

            <div class="net-pay-amount">
                ₱{{ number_format($netPay, 2) }}
            </div>
        </div>

        <div class="footer-note">
            This payslip is generated by the Alibaton Construction Inc.
            Payroll & Benefits System.
        </div>

    </div>

</div>

@endsection