@extends('layouts.app')

@section('title', 'Official Payslip - Alibaton Construction Inc.')

@section('styles')
<style>
    /* Web Layout */
    .payslip-page-wrapper {
        max-width: 900px;
        margin: 0 auto;
        padding: 10px 15px 50px;
    }

    .payslip-top-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .payslip-top-actions h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        color: #111111;
    }

    .payslip-top-actions p {
        margin: 4px 0 0;
        font-size: 13px;
        color: #666666;
    }

    .action-btn-group {
        display: flex;
        gap: 10px;
    }

    .btn-payslip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 6px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: 0.15s ease;
    }

    .btn-payslip-back {
        background: #eeeeee;
        color: #111111;
    }

    .btn-payslip-back:hover {
        background: #dddddd;
    }

    .btn-payslip-print {
        background: #f4c400;
        color: #111111;
    }

    .btn-payslip-print:hover {
        background: #dcae00;
        transform: translateY(-1px);
    }

    .formal-sheet-container {
        background: #ffffff;
        padding: 28px;
        border-radius: 4px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #cccccc;
        font-family: Arial, Helvetica, sans-serif;
        color: #000000;
    }

    /* Exact Grid matching user photo */
    .formal-grid-table {
        width: 100%;
        border-collapse: collapse;
        border: 2px solid #000000;
        background: #ffffff;
        font-size: 11px;
        color: #000000;
        table-layout: fixed;
    }

    .formal-grid-table th,
    .formal-grid-table td {
        border: 1px solid #000000;
        padding: 4px 7px;
        vertical-align: middle;
        line-height: 1.3;
    }

    .formal-grid-table .bold {
        font-weight: 700;
    }

    .formal-grid-table .text-center {
        text-align: center;
    }

    .formal-grid-table .text-right {
        text-align: right;
        font-variant-numeric: tabular-nums;
    }

    .formal-grid-table .text-left {
        text-align: left;
    }

    .formal-header-row td {
        font-weight: 700;
        font-size: 12.5px;
        text-transform: uppercase;
    }

    .formal-th {
        font-weight: 700;
        background: #ffffff;
    }

    .amount-bold {
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        text-align: right;
    }

    /* Empty buffer rows to match spreadsheet look */
    .empty-row td {
        height: 18px;
    }

    /* Signature footer */
    .payslip-acknowledgment {
        margin-top: 22px;
        font-size: 11px;
        color: #111111;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .sig-underline {
        display: inline-block;
        width: 220px;
        border-bottom: 1px solid #000000;
        margin-left: 5px;
    }

    /* Exact Print Formatting */
    @media print {
        @page {
            size: A4 portrait;
            margin: 8mm 8mm 8mm 8mm;
        }

        html, body {
            background: #ffffff !important;
            color: #000000 !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* Hide all chrome — sidebar, topbar, action buttons */
        .sidebar,
        .topbar,
        .layout > .sidebar,
        .payslip-top-actions,
        .no-print {
            display: none !important;
        }

        /* Reset the layout flex/grid so sidebar space is reclaimed */
        .layout {
            display: block !important;
            width: 100% !important;
        }

        /* Main content takes full width */
        .main {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            min-height: auto !important;
            display: block !important;
        }

        .content {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }

        .payslip-page-wrapper {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .formal-sheet-container {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }

        .formal-grid-table {
            width: 100% !important;
            font-size: 9pt !important;
            border: 1.5pt solid #000000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            table-layout: fixed !important;
            page-break-inside: avoid;
        }

        .formal-grid-table th,
        .formal-grid-table td {
            border: 0.5pt solid #000000 !important;
            padding: 2pt 4pt !important;
            font-size: 9pt !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .formal-header-row td {
            font-size: 11pt !important;
            font-weight: 800 !important;
        }

        .payslip-acknowledgment {
            margin-top: 10pt !important;
            font-size: 8pt !important;
        }
    }
</style>
@endsection

@section('content')

@php
    $basicSalary = (float) ($payroll['basic_salary'] ?? 0);
    $overtimePay = (float) ($payroll['overtime_pay'] ?? 0);
    $overtimeHours = (float) ($payroll['overtime_hours'] ?? 0);
    $allowances = (float) ($payroll['allowances'] ?? 0);
    $incentives = (float) ($payroll['incentives'] ?? 0);

    $sss = (float) ($payroll['sss_deduction'] ?? 0);
    $philhealth = (float) ($payroll['philhealth_deduction'] ?? 0);
    $pagibig = (float) ($payroll['pagibig_deduction'] ?? 0);
    $otherDeductions = (float) ($payroll['other_deductions'] ?? 0);

    $grossPay = (float) ($payroll['gross_pay'] ?? 0);
    $totalDeductions = (float) ($payroll['total_deductions'] ?? 0);
    $netPay = (float) ($payroll['net_pay'] ?? 0);

    $payDate = $payroll['pay_date'] ?? null;
    $payTimestamp = $payDate ? strtotime($payDate) : time();
    $day = (int) date('j', $payTimestamp);

    // Corporate Pay Period format: e.g. AUGUST 21 - SEPTEMBER 5, 2026
    if ($day <= 15) {
        $periodStart = date('F 1', $payTimestamp);
        $periodEnd = date('F 15, Y', $payTimestamp);
        $payPeriodFormatted = strtoupper($periodStart . ' - ' . $periodEnd);
    } else {
        $lastDay = date('t', $payTimestamp);
        $periodStart = date('F 16', $payTimestamp);
        $periodEnd = date('F ' . $lastDay . ', Y', $payTimestamp);
        $payPeriodFormatted = strtoupper($periodStart . ' - ' . $periodEnd);
    }

    // Regular hours: 88 for semi-monthly period (11 working days × 8 hrs), 176 for monthly
    $regHours = ($day <= 15) ? 88.00 : 88.00; // both halves = 88 hrs (standard semi-monthly)


    // Format Employee Name as "Cabundoc, Mark Anthony" (Lastname, Firstname)
    $formattedName = 'Cabundoc, Mark Anthony';
    $empNumber = $payroll['employee_id'] ?? '4';
    $position = 'Driver / Fleet Logistics';
    $location = 'ALIBATON MAIN YARD / SITES';

    if (isset($employee) && is_array($employee)) {
        $lName = trim($employee['last_name'] ?? '');
        $fName = trim($employee['first_name'] ?? '');
        if ($lName || $fName) {
            $formattedName = $lName && $fName ? "$lName, $fName" : ($lName ?: $fName);
        }
        $empNumber = $employee['employee_id'] ?? $empNumber;
        $position = $employee['position'] ?? $position;
        $location = ($employee['department'] ?? 'Alibaton Fleet') . ' - HEAD OFFICE';
    }
@endphp

<div class="payslip-page-wrapper">

    {{-- Top Action Bar (Hidden on Print) --}}
    <div class="payslip-top-actions no-print">
        <div>
            <h1>Employee Payslip</h1>
            <p>Payroll Record #{{ $payroll['id'] ?? '-' }} &bull; Employee No: {{ $empNumber }}</p>
        </div>

        <div class="action-btn-group">
            <a href="{{ url('/payrolls') }}" class="btn-payslip btn-payslip-back">
                &larr; Back to Payrolls
            </a>
            <button onclick="window.print()" class="btn-payslip btn-payslip-print">
                &#128438; Print Payslip
            </button>
        </div>
    </div>

    {{-- Formal Payslip Paper Card --}}
    <div class="formal-sheet-container">

        <table class="formal-grid-table">
            <colgroup>
                {{-- Col1: Earnings label | Col2: Hrs | Col3: Amount | Col4: Deduction label | Col5: Amount | Col6: (Net Pay label+value merged in summary) --}}
                <col style="width:22%">
                <col style="width:10%">
                <col style="width:16%">
                <col style="width:20%">
                <col style="width:16%">
                <col style="width:16%">
            </colgroup>

            {{-- HEADER: Company Name spanning all 6 columns --}}
            <tr class="formal-header-row">
                <td colspan="6" style="text-align:center; font-size:13px; letter-spacing:0.5px;">
                    ALIBATON CONSTRUCTION INC. — PAYROLL
                </td>
            </tr>

            {{-- Pay Period row --}}
            <tr>
                <td class="bold">Pay Period:</td>
                <td colspan="5" class="bold">{{ $payPeriodFormatted }}</td>
            </tr>

            {{-- Name & Position --}}
            <tr>
                <td class="bold">Name</td>
                <td colspan="2" class="bold" style="text-align:center;">{{ $formattedName }}</td>
                <td class="bold">Position</td>
                <td colspan="2" class="bold">{{ $position }}</td>
            </tr>

            {{-- Employee No. & Location --}}
            <tr>
                <td class="bold">Employee No.</td>
                <td colspan="2">{{ $empNumber }}</td>
                <td class="bold">Location</td>
                <td colspan="2">{{ $location }}</td>
            </tr>

            {{-- Column headers --}}
            <tr style="background:#f4f4f4;">
                <td class="formal-th" style="text-align:left;">Description</td>
                <th class="formal-th">Hours</th>
                <th class="formal-th">Earnings (₱)</th>
                <th class="formal-th" style="text-align:left;">Deduction</th>
                <th class="formal-th">Amount (₱)</th>
                <th class="formal-th"></th>
            </tr>

            {{-- ROW: Reg Hours vs Tardiness --}}
            <tr>
                <td>Reg Hours</td>
                <td class="text-right">{{ number_format($regHours, 2) }}</td>
                <td class="amount-bold">{{ number_format($basicSalary, 2) }}</td>
                <td>Tardiness</td>
                <td class="amount-bold">{{ number_format((float)($payroll['tardiness_deduction'] ?? 0), 2) }}</td>
                <td></td>
            </tr>

            {{-- ROW: Reg OT vs PhilHealth --}}
            <tr>
                <td>Reg OT</td>
                <td class="text-right">{{ number_format($overtimeHours, 2) }}</td>
                <td class="amount-bold">{{ number_format($overtimePay, 2) }}</td>
                <td>PhilHealth</td>
                <td class="amount-bold">{{ number_format($philhealth, 2) }}</td>
                <td></td>
            </tr>

            {{-- ROW: Spl Hol vs Pag-IBIG --}}
            <tr>
                <td>Spl Hol.</td>
                <td class="text-right">0.00</td>
                <td class="text-right">0.00</td>
                <td>Pag-IBIG</td>
                <td class="amount-bold">{{ number_format($pagibig, 2) }}</td>
                <td></td>
            </tr>

            {{-- ROW: RDOT vs SSS --}}
            <tr>
                <td>RDOT</td>
                <td class="text-right">0.00</td>
                <td class="text-right">0.00</td>
                <td>SSS</td>
                <td class="amount-bold">{{ number_format($sss, 2) }}</td>
                <td></td>
            </tr>

            {{-- ROW: Leg Hol vs Pag-IBIG Loan --}}
            <tr>
                <td>Leg Hol.</td>
                <td class="text-right">{{ number_format((float)($payroll['leg_hol_hours'] ?? 0), 2) }}</td>
                <td class="amount-bold">{{ number_format((float)($payroll['leg_hol_pay'] ?? 0), 2) }}</td>
                <td>Pag-IBIG Loan</td>
                <td class="amount-bold">{{ number_format((float)($payroll['pagibig_loan_deduction'] ?? 0), 2) }}</td>
                <td></td>
            </tr>

            {{-- ROW: LHOT vs SSS Loan --}}
            <tr>
                <td>LHOT</td>
                <td class="text-right">0.00</td>
                <td class="text-right">0.00</td>
                <td>SSS Loan</td>
                <td class="amount-bold">0.00</td>
                <td></td>
            </tr>

            {{-- ROW: Hourly Allow. vs Others --}}
            <tr>
                <td>Hourly Allow.</td>
                <td class="text-right">0.00</td>
                <td class="amount-bold">{{ number_format($allowances, 2) }}</td>
                <td>Others</td>
                <td class="amount-bold">{{ number_format($otherDeductions, 2) }}</td>
                <td></td>
            </tr>

            {{-- ROW: Night Diff vs ATD --}}
            <tr>
                <td>Night Diff</td>
                <td class="text-right">{{ number_format((float)($payroll['night_diff_hours'] ?? 0), 2) }}</td>
                <td class="amount-bold">{{ number_format((float)($payroll['night_diff_pay'] ?? 0), 2) }}</td>
                <td>ATD</td>
                <td class="text-center">—</td>
                <td></td>
            </tr>

            {{-- ROW: SHRDOT --}}
            <tr>
                <td>SHRDOT</td>
                <td class="text-right">0.00</td>
                <td class="text-right">0.00</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            {{-- ROW: SHOT --}}
            <tr>
                <td>SHOT</td>
                <td class="text-right">0.00</td>
                <td class="text-right">0.00</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            {{-- ROW: LHRDOT --}}
            <tr>
                <td>LHRDOT</td>
                <td class="text-right">0.00</td>
                <td class="text-right">0.00</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            {{-- ROW: Incentives & Bonuses --}}
            <tr>
                <td>Incentives &amp; Bonuses</td>
                <td class="text-center">—</td>
                <td class="amount-bold">{{ number_format($incentives, 2) }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            {{-- SUMMARY ROW: Gross | Deduction | Net Pay --}}
            <tr style="background:#f9f9f9; border-top:2px solid #000;">
                <td class="bold" style="text-align:right;">GROSS PAY</td>
                <td colspan="2" class="amount-bold" style="font-size:12px; text-align:right;">
                    ₱ {{ number_format($grossPay, 2) }}
                </td>
                <td class="bold">TOTAL DEDUCTIONS</td>
                <td class="amount-bold" style="font-size:12px; text-align:right;">
                    ₱ {{ number_format($totalDeductions, 2) }}
                </td>
                <td></td>
            </tr>

            {{-- NET PAY HIGHLIGHT ROW --}}
            <tr style="background:#fff8dc;">
                <td colspan="4" class="bold" style="text-align:right; font-size:12.5px; letter-spacing:0.3px;">
                    NET PAY
                </td>
                <td colspan="2" class="amount-bold" style="font-size:14px; text-align:right; color:#000;">
                    ₱ {{ number_format($netPay, 2) }}
                </td>
            </tr>

            {{-- Buffer row --}}
            <tr class="empty-row">
                <td></td><td></td><td></td><td></td><td></td><td></td>
            </tr>

        </table>


        {{-- Formal Acknowledgment Signature Section --}}
        <div class="payslip-acknowledgment">
            <div>
                I acknowledge receipt of the payment and verify that the earnings and deductions stated are correct.
            </div>
            <div>
                Received by: <span class="sig-underline"></span>
                &nbsp;&nbsp;Date: <span style="display:inline-block; width:110px; border-bottom:1px solid #000;"></span>
            </div>
        </div>

    </div>

</div>

@endsection
