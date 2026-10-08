@extends('layouts.app')

@section('title', 'Salary Grades - Alibaton Construction Inc.')

@section('styles')
<style>
    .print-only {
        display: none !important;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #111111;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-subtitle {
        margin: 5px 0 0;
        color: #666666;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #F4C400;
        color: #111111;
        text-decoration: none;
        padding: 10px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: bold;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        background: #DCAE00;
        transform: translateY(-1px);
    }

    .btn-action.dark {
        background: #111111;
        color: #F4C400;
    }

    .btn-action.dark:hover {
        background: #2a2a2a;
    }

    .btn-action.outline {
        background: #ffffff;
        color: #333333;
        border: 1px solid #cccccc;
    }

    .btn-action.outline:hover {
        background: #f9f9f9;
        border-color: #999999;
    }

    /* Live status indicator badge */
    .live-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1px solid #e0e0e0;
        padding: 7px 14px;
        border-radius: 20px;
        font-size: 12px;
        color: #444444;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }

    .pulse-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .alert {
        padding: 13px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }

    .alert-error {
        background: #ffebee;
        color: #c62828;
        border: 1px solid #ffcdd2;
    }

    /* =========================
       MONITORING CARDS
    ========================= */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .summary-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        border-left: 4px solid #F4C400;
        transition: 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 14px rgba(0, 0, 0, 0.08);
    }

    .summary-card.dark {
        background: #111111;
        border-left-color: #F4C400;
    }
    .summary-card.dark .summary-label,
    .summary-card.dark .summary-note {
        color: #cccccc;
    }
    .summary-card.dark .summary-value {
        color: #F4C400;
    }

    .summary-card.success {
        border-left-color: #10b981;
    }
    .summary-card.success .summary-value {
        color: #059669;
    }

    .summary-card.warning {
        border-left-color: #f59e0b;
    }
    .summary-card.warning .summary-value {
        color: #d97706;
    }

    .summary-label {
        color: #777777;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .summary-value {
        color: #111111;
        font-size: 26px;
        font-weight: 800;
        line-height: 1.2;
    }

    .summary-note {
        margin-top: 6px;
        font-size: 12px;
        color: #888888;
    }

    /* Charts Section */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 25px;
    }

    .chart-card {
        background: #ffffff;
        border-radius: 10px;
        padding: 20px 22px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .chart-title {
        font-size: 15px;
        font-weight: 700;
        color: #111111;
        margin: 0;
    }

    .chart-subtitle {
        font-size: 12px;
        color: #888888;
        margin: 2px 0 0;
    }

    .chart-wrapper {
        position: relative;
        height: 230px;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Toolbar */
    .toolbar-card {
        background: #ffffff;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 16px;
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .search-input-group {
        position: relative;
        flex: 1;
        min-width: 240px;
    }

    .search-input-group input {
        width: 100%;
        padding: 9px 12px 9px 36px;
        border: 1px solid #d5d5d5;
        border-radius: 6px;
        font-size: 13px;
        outline: none;
    }

    .search-input-group input:focus {
        border-color: #F4C400;
        box-shadow: 0 0 0 2px rgba(244, 196, 0, 0.2);
    }

    .search-icon {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: #888888;
        font-size: 14px;
        pointer-events: none;
    }

    .toolbar-select {
        padding: 9px 12px;
        border: 1px solid #d5d5d5;
        border-radius: 6px;
        font-size: 13px;
        background: #ffffff;
        color: #333333;
        outline: none;
    }

    /* Records Tables */
    .records-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        margin-bottom: 25px;
    }

    .records-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 15px;
        flex-wrap: wrap;
    }

    .records-title {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #111111;
    }

    .records-subtitle {
        margin: 4px 0 0;
        color: #777777;
        font-size: 13px;
    }

    .records-table {
        width: 100%;
        border-collapse: collapse;
    }

    .records-table th {
        background: #111111;
        color: #ffffff;
        padding: 13px 14px;
        font-size: 12px;
        text-align: left;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .records-table td {
        padding: 13px 14px;
        border-bottom: 1px solid #eeeeee;
        font-size: 13px;
        color: #333333;
        vertical-align: middle;
    }

    .records-table tbody tr:hover {
        background: #fffdf0;
    }

    .salary-amount {
        font-weight: 700;
        color: #111111;
        font-family: Consolas, monospace;
    }

    .grade-badge {
        display: inline-block;
        padding: 4px 10px;
        background: #fff8e1;
        color: #8a6d00;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }

    .btn-edit {
        background: #F4C400;
        color: #111111;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
    }

    .btn-delete {
        background: #111111;
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
    }
    .btn-delete:hover {
        background: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #777777;
    }

    /* ===================================================
       FORMAL CORPORATE PRINT / PDF STYLES
    =================================================== */
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm 12mm 12mm 12mm;
        }

        html, body {
            background: #ffffff !important;
            color: #000000 !important;
            margin: 0 !important;
            padding: 0 !important;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif !important;
            font-size: 8.5pt !important;
            line-height: 1.3 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .sidebar, .topbar, .layout > .sidebar, .user-area, .header-actions,
        .toolbar-card, .live-status-pill, .page-header, .summary-grid, .charts-grid,
        .actions, th.no-print, td.no-print, .no-print, .alert, .btn-action {
            display: none !important;
        }

        .layout, .main, .content {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            min-height: auto !important;
        }

        .print-only {
            display: block !important;
        }

        .formal-letterhead {
            display: flex !important;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2pt solid #000000;
            padding-bottom: 8pt;
            margin-bottom: 12pt;
        }

        .company-name {
            font-size: 15pt !important;
            font-weight: 800 !important;
            color: #000000 !important;
            letter-spacing: 0.5px;
            margin: 0 0 2pt 0;
            text-transform: uppercase;
        }

        .company-sub {
            font-size: 8pt !important;
            color: #444444 !important;
            margin: 0;
            line-height: 1.3;
        }

        .doc-type-title {
            font-size: 12pt !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            color: #000000 !important;
            margin: 0 0 4pt 0;
        }

        .doc-meta {
            font-size: 7.5pt !important;
            color: #444444 !important;
            line-height: 1.4;
        }

        .formal-summary-box {
            display: table !important;
            width: 100% !important;
            border-collapse: collapse !important;
            margin-bottom: 12pt !important;
            border: 1pt solid #000000 !important;
            page-break-inside: avoid;
        }

        .formal-summary-box th, .formal-summary-box td {
            border: 0.5pt solid #000000 !important;
            padding: 4pt 6pt !important;
            font-size: 8pt !important;
        }

        .formal-summary-box th {
            background: #f0f0f0 !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
        }

        .formal-summary-box td.val {
            font-weight: 700 !important;
            text-align: right !important;
        }

        .records-card {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin-bottom: 15pt !important;
        }

        .records-table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 8pt !important;
            page-break-inside: auto;
        }

        .records-table th {
            background: #e8e8e8 !important;
            color: #000000 !important;
            border: 1pt solid #000000 !important;
            padding: 4pt 5pt !important;
            font-weight: 800 !important;
            font-size: 7.5pt !important;
        }

        .records-table td {
            border: 0.5pt solid #000000 !important;
            padding: 3.5pt 5pt !important;
            font-size: 8pt !important;
            background: #ffffff !important;
        }

        .formal-signatories {
            display: flex !important;
            justify-content: space-between;
            margin-top: 25pt !important;
            page-break-inside: avoid !important;
            padding-top: 10pt;
        }

        .sig-col {
            width: 30%;
            text-align: center;
        }

        .sig-caption {
            font-size: 7.5pt !important;
            color: #444444 !important;
            margin-bottom: 35pt;
            text-align: left;
            text-transform: uppercase;
            font-weight: 600;
        }

        .sig-line {
            border-bottom: 1pt solid #000000 !important;
            margin-bottom: 3pt;
        }

        .sig-name {
            font-weight: 800 !important;
            font-size: 8.5pt !important;
            text-transform: uppercase;
        }

        .sig-designation {
            font-size: 7pt !important;
            color: #444444 !important;
        }

        .formal-footer-note {
            display: block !important;
            text-align: center;
            font-size: 6.5pt !important;
            color: #777777 !important;
            margin-top: 20pt;
            border-top: 0.5pt solid #cccccc;
            padding-top: 4pt;
        }
    }
</style>
@endsection

@section('content')

    <!-- FORMAL PRINT-ONLY DOCUMENT HEADER -->
    <div class="print-only formal-letterhead">
        <div>
            <h1 class="company-name">Alibaton Construction Inc.</h1>
            <p class="company-sub">
                Corporate Compensation Planning Division<br>
                Km. 12 Sasa, Davao City, Davao del Sur, Philippines
            </p>
        </div>
        <div style="text-align:right;">
            <h2 class="doc-type-title">Salary Grade Structure &amp; Compliance Audit</h2>
            <div class="doc-meta">
                <div><strong>Ref:</strong> ACI-SGC-{{ date('Ymd') }}</div>
                <div><strong>Generated:</strong> {{ date('F d, Y - h:i A') }}</div>
                <div><strong>Auditor:</strong> {{ Auth::user()->name ?? 'Compensation Manager' }}</div>
                <div><strong>Classification:</strong> OFFICIAL CORPORATE RECORD</div>
            </div>
        </div>
    </div>

    <!-- FORMAL PRINT-ONLY SUMMARY TABLE -->
    <div class="print-only">
        <table class="formal-summary-box">
            <thead>
                <tr>
                    <th colspan="4" style="background:#000000 !important; color:#ffffff !important; text-align:center;">
                        EXECUTIVE SUMMARY &amp; PAY BAND AUDIT TOTALS
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:25%;"><strong>Total Salary Grades:</strong></td>
                    <td class="val" style="width:25%;">{{ $totalSalaryGrades ?? 0 }}</td>
                    <td style="width:25%;"><strong>Total Employees Audited:</strong></td>
                    <td class="val" style="width:25%;">{{ count($employeeAssignments ?? []) }}</td>
                </tr>
                <tr>
                    <td><strong>Overall Minimum Salary:</strong></td>
                    <td class="val">₱{{ number_format($lowestMinimumSalary ?? 0, 2) }}</td>
                    <td><strong>Overall Maximum Salary:</strong></td>
                    <td class="val">₱{{ number_format($highestMaximumSalary ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Average Minimum Salary:</strong></td>
                    <td class="val">₱{{ number_format($averageMinimumSalary ?? 0, 2) }}</td>
                    <td><strong>Average Maximum Salary:</strong></td>
                    <td class="val">₱{{ number_format($averageMaximumSalary ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- PAGE HEADER (SCREEN ONLY) -->
    <div class="page-header no-print">
        <div>
            <h1 class="page-title">
                Salary Grades Management
            </h1>
            <p class="page-subtitle">
                Configure corporate compensation bands, pay structures, and monitor employee salary range compliance.
            </p>
        </div>

        <div class="header-actions">
            <!-- Live status badge -->
            <div class="live-status-pill" title="Auto-polling real-time updates every 25 seconds">
                <span class="pulse-dot"></span>
                <span id="live_status_text"><strong>Live Sync:</strong> Active</span>
                <span id="last_sync_time" style="color:#888; font-size:11px;">just now</span>
                <button type="button" onclick="triggerManualSync()" style="background:none; border:none; cursor:pointer; color:#111; font-weight:bold; padding:0 2px;" title="Refresh now">
                    ↻
                </button>
            </div>

            <button type="button" class="btn-privacy-page-toggle is-masked" title="Confidential Mode Active — sensitive amounts are hidden. Click to unmask." style="display:inline-flex;align-items:center;gap:6px;padding:9px 14px;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;background:#111111;color:#f4c400;border:1px solid #111111;transition:all 0.2s;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;flex-shrink:0"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                <span>Masked</span>
            </button>

            <button type="button" onclick="window.print()" class="btn-action outline">
                🖨 Print Official Audit
            </button>

            <a href="{{ url('/salary-grades/create') }}" class="btn-action">
                + Add Salary Grade
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success no-print">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error no-print">{{ session('error') }}</div>
    @endif
    @if(isset($error))
        <div class="alert alert-error no-print">{{ $error }}</div>
    @endif

    <!-- REAL-TIME KPI MONITORING CARDS -->
    <div class="summary-grid no-print">
        <div class="summary-card">
            <div class="summary-label">Configured Grades</div>
            <div class="summary-value" id="kpi_total_grades">{{ $totalSalaryGrades ?? 0 }}</div>
            <div class="summary-note">Active pay bands</div>
        </div>

        <div class="summary-card success">
            <div class="summary-label">Lowest Minimum Pay</div>
            <div class="summary-value confidential-amount" id="kpi_lowest_min">₱{{ number_format($lowestMinimumSalary ?? 0, 2) }}</div>
            <div class="summary-note">Entry baseline</div>
        </div>

        <div class="summary-card dark">
            <div class="summary-label">Highest Maximum Pay</div>
            <div class="summary-value confidential-amount" id="kpi_highest_max">₱{{ number_format($highestMaximumSalary ?? 0, 2) }}</div>
            <div class="summary-note">Executive ceiling</div>
        </div>

        <div class="summary-card warning">
            <div class="summary-label">Average Minimum Pay</div>
            <div class="summary-value confidential-amount" id="kpi_avg_min">₱{{ number_format($averageMinimumSalary ?? 0, 2) }}</div>
            <div class="summary-note">Market standard</div>
        </div>
    </div>

    <!-- INTERACTIVE VISUAL DASHBOARDS (CHART.JS) -->
    <div class="charts-grid no-print">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3 class="chart-title">Salary Grade Spans (Min vs Max)</h3>
                    <p class="chart-subtitle">Pay band ranges configured across grades</p>
                </div>
                <span class="tab-badge" id="chart1_grades_badge">{{ count($salaryGrades ?? []) }} Bands</span>
            </div>
            <div class="chart-wrapper">
                <canvas id="salaryGradesChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3 class="chart-title">Employee Salary Range Compliance</h3>
                    <p class="chart-subtitle">Salaries matched within configured grade boundaries</p>
                </div>
                <span class="tab-badge" id="chart2_emp_badge">{{ count($employeeAssignments ?? []) }} Staff</span>
            </div>
            <div class="chart-wrapper">
                <canvas id="complianceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- LIVE SEARCH & FILTER TOOLBAR FOR SALARY GRADES -->
    <div class="toolbar-card no-print">
        <div class="search-input-group">
            <span class="search-icon">🔍</span>
            <input type="text" id="search_grades" placeholder="Search grades by name, description, or salary range..." oninput="filterGradesTable()">
        </div>
        <button type="button" onclick="document.getElementById('search_grades').value=''; filterGradesTable();" class="btn-action outline" style="padding:8px 12px; font-size:12px;">
            Reset
        </button>
        <div style="font-size:12px; color:#777; font-weight:600;" id="grades_count_indicator">
            Showing {{ count($salaryGrades ?? []) }} grade band(s)
        </div>
    </div>

    <!-- SALARY GRADES TABLE -->
    <div class="records-card">
        <div class="records-header no-print">
            <div>
                <h2 class="records-title">Configured Salary Grade Bands</h2>
                <p class="records-subtitle">Defined compensation ranges, salary spreads, and employee match counts.</p>
            </div>
        </div>

        @if(count($salaryGrades ?? []) > 0)
            <table class="records-table" id="table_salary_grades">
                <thead>
                    <tr>
                        <th style="width:18%;">Grade Name</th>
                        <th style="width:16%; text-align:right;">Minimum Salary</th>
                        <th style="width:16%; text-align:right;">Maximum Salary</th>
                        <th style="width:16%; text-align:right;">Salary Spread</th>
                        <th style="width:14%; text-align:center;">Matched Staff</th>
                        <th style="width:12%;">Effective Date</th>
                        <th class="no-print" style="width:8%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salaryGrades as $salaryGrade)
                        @php
                            $spread = (float)($salaryGrade['maximum_salary'] ?? 0) - (float)($salaryGrade['minimum_salary'] ?? 0);
                        @endphp
                        <tr data-name="{{ strtolower($salaryGrade['grade_name'] ?? '') }}"
                            data-desc="{{ strtolower($salaryGrade['description'] ?? '') }}">
                            <td><strong>{{ $salaryGrade['grade_name'] ?? '-' }}</strong></td>
                            <td class="salary-amount confidential-amount" style="text-align:right;">₱{{ number_format((float)($salaryGrade['minimum_salary'] ?? 0), 2) }}</td>
                            <td class="salary-amount confidential-amount" style="text-align:right;">₱{{ number_format((float)($salaryGrade['maximum_salary'] ?? 0), 2) }}</td>
                            <td class="salary-amount confidential-amount" style="text-align:right; color:#2563eb;">+₱{{ number_format($spread, 2) }}</td>
                            <td style="text-align:center;">
                                <span style="display:inline-block;padding:3px 9px;background:#e8f5e9;color:#2e7d32;border-radius:12px;font-size:11px;font-weight:bold;">
                                    {{ $gradeEmployeeCount[$salaryGrade['id']] ?? 0 }} employee(s)
                                </span>
                            </td>
                            <td>{{ !empty($salaryGrade['effective_date']) ? date('M d, Y', strtotime($salaryGrade['effective_date'])) : 'Standard' }}</td>
                            <td class="no-print">
                                <a href="{{ url('/salary-grades/' . ($salaryGrade['id'] ?? '') . '/edit') }}" class="btn-edit">Edit</a>
                                <form action="{{ url('/salary-grades/' . ($salaryGrade['id'] ?? '')) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this salary grade?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <h3>No salary grades found</h3>
                <p>Click "Add Salary Grade" to configure the first corporate pay band.</p>
            </div>
        @endif
    </div>

    <!-- EMPLOYEE ASSIGNMENTS & COMPLIANCE SECTION -->
    <div class="toolbar-card no-print" style="margin-top:20px;">
        <div class="search-input-group">
            <span class="search-icon">🔍</span>
            <input type="text" id="search_assignments" placeholder="Search employees by ID, name, position, department, or assigned grade..." oninput="filterAssignmentsTable()">
        </div>

        <select id="filter_compliance" class="toolbar-select" onchange="filterAssignmentsTable()">
            <option value="">All Compliance Statuses</option>
            <option value="Within Range">✓ Within Range</option>
            <option value="Below Range">▼ Below Minimum</option>
            <option value="Above Range">▲ Above Maximum</option>
            <option value="Unassigned">Unassigned</option>
        </select>

        <button type="button" onclick="document.getElementById('search_assignments').value=''; document.getElementById('filter_compliance').value=''; filterAssignmentsTable();" class="btn-action outline" style="padding:8px 12px; font-size:12px;">
            Reset
        </button>

        <div style="font-size:12px; color:#777; font-weight:600;" id="assignments_count_indicator">
            Showing {{ count($employeeAssignments ?? []) }} employee(s)
        </div>
    </div>

    <div class="records-card">
        <div class="records-header no-print">
            <div>
                <h2 class="records-title">Employee Salary Grade Assignments &amp; Range Compliance</h2>
                <p class="records-subtitle">Real-time matching of active employee salaries against configured pay structures.</p>
            </div>
        </div>

        @if(count($employeeAssignments ?? []) > 0)
            <table class="records-table" id="table_assignments">
                <thead>
                    <tr>
                        <th style="width:12%;">Employee ID</th>
                        <th style="width:22%;">Employee Name</th>
                        <th style="width:18%;">Position &amp; Department</th>
                        <th style="width:16%; text-align:right;">Basic Salary</th>
                        <th style="width:18%;">Assigned Grade Band</th>
                        <th style="width:14%;">Range Compliance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employeeAssignments as $assignment)
                        <tr data-empid="{{ strtolower($assignment['employee_id']) }}"
                            data-name="{{ strtolower($assignment['name']) }}"
                            data-pos="{{ strtolower($assignment['position']) }}"
                            data-dept="{{ strtolower($assignment['department']) }}"
                            data-grade="{{ strtolower($assignment['grade']) }}"
                            data-compliance="{{ $assignment['compliance'] }}">
                            <td><strong>{{ $assignment['employee_id'] }}</strong></td>
                            <td>{{ $assignment['name'] }}</td>
                            <td>
                                <div>{{ $assignment['position'] }}</div>
                                <div style="font-size:11px; color:#666;">{{ $assignment['department'] }}</div>
                            </td>
                            <td class="salary-amount confidential-amount" style="text-align:right;">₱{{ number_format($assignment['salary'], 2) }}</td>
                            <td>
                                <span class="grade-badge">{{ $assignment['grade'] }}</span>
                            </td>
                            <td>
                                @if($assignment['compliance'] === 'Within Range')
                                    <span style="display:inline-block;padding:3px 8px;background:#e8f5e9;color:#2e7d32;border-radius:12px;font-size:11px;font-weight:bold;">
                                        ✓ Within Range
                                    </span>
                                @elseif($assignment['compliance'] === 'Below Range')
                                    <span style="display:inline-block;padding:3px 8px;background:#fff8e1;color:#8a6d00;border-radius:12px;font-size:11px;font-weight:bold;">
                                        ▼ Below Min
                                    </span>
                                @elseif($assignment['compliance'] === 'Above Range')
                                    <span style="display:inline-block;padding:3px 8px;background:#ffebee;color:#c62828;border-radius:12px;font-size:11px;font-weight:bold;">
                                        ▲ Above Max
                                    </span>
                                @else
                                    <span style="display:inline-block;padding:3px 8px;background:#eeeeee;color:#555555;border-radius:12px;font-size:11px;font-weight:bold;">
                                        Unassigned
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <h3>No employee assignments available</h3>
                <p>Employee records will appear here once registered.</p>
            </div>
        @endif
    </div>

    <!-- FORMAL SIGNATORIES (PRINT ONLY) -->
    <div class="print-only formal-signatories">
        <div class="sig-col">
            <div class="sig-caption">Prepared By:</div>
            <div class="sig-line"></div>
            <div class="sig-name">{{ Auth::user()->name ?? 'Compensation Analyst' }}</div>
            <div class="sig-designation">HR &amp; Compensation Officer</div>
        </div>

        <div class="sig-col">
            <div class="sig-caption">Reviewed By:</div>
            <div class="sig-line"></div>
            <div class="sig-name">MARIA ELENA ALIBATON</div>
            <div class="sig-designation">Finance &amp; Payroll Controller</div>
        </div>

        <div class="sig-col">
            <div class="sig-caption">Approved By:</div>
            <div class="sig-line"></div>
            <div class="sig-name">ENGR. ROBERTO ALIBATON</div>
            <div class="sig-designation">President / General Manager</div>
        </div>
    </div>

    <div class="print-only formal-footer-note">
        CONFIDENTIAL COMPENSATION AUDIT &bull; ALIBATON CONSTRUCTION INC. &bull; SUBJECT TO CORPORATE GOVERNANCE POLICIES
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        // ==========================================
        // 1. LIVE FILTERING: SALARY GRADES TABLE
        // ==========================================
        function filterGradesTable() {
            const query = (document.getElementById('search_grades').value || '').trim().toLowerCase();
            const rows = document.querySelectorAll('#table_salary_grades tbody tr');
            let visible = 0;

            rows.forEach(r => {
                const name = r.getAttribute('data-name') || '';
                const desc = r.getAttribute('data-desc') || '';
                if (!query || name.includes(query) || desc.includes(query)) {
                    r.style.display = '';
                    visible++;
                } else {
                    r.style.display = 'none';
                }
            });

            const countEl = document.getElementById('grades_count_indicator');
            if (countEl) countEl.textContent = `Showing ${visible} of ${rows.length} grade band(s)`;
        }

        // ==========================================
        // 2. LIVE FILTERING: ASSIGNMENTS TABLE
        // ==========================================
        function filterAssignmentsTable() {
            const query = (document.getElementById('search_assignments').value || '').trim().toLowerCase();
            const compFilter = (document.getElementById('filter_compliance').value || '').trim();
            const rows = document.querySelectorAll('#table_assignments tbody tr');
            let visible = 0;

            rows.forEach(r => {
                const empid = r.getAttribute('data-empid') || '';
                const name = r.getAttribute('data-name') || '';
                const pos = r.getAttribute('data-pos') || '';
                const dept = r.getAttribute('data-dept') || '';
                const grade = r.getAttribute('data-grade') || '';
                const comp = r.getAttribute('data-compliance') || '';

                const matchesQuery = !query || empid.includes(query) || name.includes(query) || pos.includes(query) || dept.includes(query) || grade.includes(query);
                const matchesComp = !compFilter || comp === compFilter;

                if (matchesQuery && matchesComp) {
                    r.style.display = '';
                    visible++;
                } else {
                    r.style.display = 'none';
                }
            });

            const countEl = document.getElementById('assignments_count_indicator');
            if (countEl) countEl.textContent = `Showing ${visible} of ${rows.length} employee(s)`;
        }

        // ==========================================
        // 3. CHART.JS VISUALIZATIONS
        // ==========================================
        let gradesChartInstance = null;
        let complianceChartInstance = null;

        function initCharts() {
            if (typeof Chart === 'undefined') return;

            const salaryGrades = @json($salaryGrades ?? []);
            const gradeLabels = salaryGrades.map(g => g.grade_name);
            const minSalaries = salaryGrades.map(g => parseFloat(g.minimum_salary || 0));
            const maxSalaries = salaryGrades.map(g => parseFloat(g.maximum_salary || 0));

            const ctx1 = document.getElementById('salaryGradesChart');
            if (ctx1) {
                gradesChartInstance = new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: gradeLabels.length ? gradeLabels : ['No Grades'],
                        datasets: [
                            {
                                label: 'Minimum (₱)',
                                data: minSalaries.length ? minSalaries : [0],
                                backgroundColor: '#F4C400',
                                borderRadius: 4,
                            },
                            {
                                label: 'Maximum (₱)',
                                data: maxSalaries.length ? maxSalaries : [0],
                                backgroundColor: '#111111',
                                borderRadius: 4,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } },
                            tooltip: {
                                callbacks: {
                                    label: function(i) {
                                        return ' ' + i.dataset.label + ': ₱' + Number(i.raw).toLocaleString('en-US', {minimumFractionDigits: 2});
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { callback: v => '₱' + v.toLocaleString() }
                            }
                        }
                    }
                });
            }

            // Compliance Chart
            const assignments = @json($employeeAssignments ?? []);
            let within = 0, below = 0, above = 0, unassigned = 0;
            assignments.forEach(a => {
                if (a.compliance === 'Within Range') within++;
                else if (a.compliance === 'Below Range') below++;
                else if (a.compliance === 'Above Range') above++;
                else unassigned++;
            });

            const ctx2 = document.getElementById('complianceChart');
            if (ctx2) {
                complianceChartInstance = new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: ['Within Range', 'Below Minimum', 'Above Maximum', 'Unassigned'],
                        datasets: [{
                            data: [within, below, above, unassigned],
                            backgroundColor: ['#10b981', '#f59e0b', '#dc2626', '#9ca3af'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'right', labels: { boxWidth: 12, font: { size: 11 } } }
                        }
                    }
                });
            }
        }

        // ==========================================
        // 4. REAL-TIME AUTO POLLING (EVERY 25s)
        // ==========================================
        let lastSyncDate = new Date();

        function updateSyncTimer() {
            const now = new Date();
            const elapsed = Math.floor((now - lastSyncDate) / 1000);
            const label = document.getElementById('last_sync_time');
            if (label) {
                if (elapsed < 5) label.textContent = 'just now';
                else if (elapsed < 60) label.textContent = `${elapsed}s ago`;
                else label.textContent = `${Math.floor(elapsed / 60)}m ago`;
            }
        }
        setInterval(updateSyncTimer, 3000);

        async function fetchRealtimeSalaryGrades() {
            try {
                const response = await fetch('/salary-grades/data', {
                    headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) return;
                const res = await response.json();
                if (!res.success || !res.stats) return;

                const s = res.stats;
                lastSyncDate = new Date();
                updateSyncTimer();

                const elTotal = document.getElementById('kpi_total_grades');
                if (elTotal) elTotal.textContent = s.total_grades;

                const elMin = document.getElementById('kpi_lowest_min');
                if (elMin) elMin.textContent = '₱' + Number(s.lowest_min).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                const elMax = document.getElementById('kpi_highest_max');
                if (elMax) elMax.textContent = '₱' + Number(s.highest_max).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                const elAvgMin = document.getElementById('kpi_avg_min');
                if (elAvgMin) elAvgMin.textContent = '₱' + Number(s.avg_min).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                // Update compliance chart
                if (complianceChartInstance && res.compliance) {
                    const c = res.compliance;
                    complianceChartInstance.data.datasets[0].data = [c.within, c.below, c.above, c.unassigned];
                    complianceChartInstance.update();
                }

                // Update grades chart
                if (gradesChartInstance && res.grades && res.grades.length) {
                    gradesChartInstance.data.labels = res.grades.map(g => g.name);
                    gradesChartInstance.data.datasets[0].data = res.grades.map(g => g.min);
                    gradesChartInstance.data.datasets[1].data = res.grades.map(g => g.max);
                    gradesChartInstance.update();
                }
            } catch (e) {
                console.warn('Salary grades polling paused:', e.message);
            }
        }

        function triggerManualSync() {
            const btn = document.getElementById('last_sync_time');
            if (btn) btn.textContent = 'syncing...';
            fetchRealtimeSalaryGrades();
        }

        setInterval(fetchRealtimeSalaryGrades, 25000);

        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
        });
    </script>

@endsection
