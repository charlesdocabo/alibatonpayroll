@extends('layouts.app')

@section('title', 'Benefits & Compensation - Alibaton Construction Inc.')

@section('styles')
<style>
    /* ===================================================
       SCREEN DISPLAY STYLES (Interactive Web UI)
    =================================================== */
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
        margin: 7px 0 0;
        color: #777777;
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
        background: #f4c400;
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
        background: #dcae00;
        transform: translateY(-1px);
    }

    .btn-action.dark {
        background: #111111;
        color: #f4c400;
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
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    .alert {
        padding: 13px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: #e8f5e9;
        color: #256029;
        border: 1px solid #b7dfba;
    }

    .alert-error {
        background: #fdecea;
        color: #a12622;
        border: 1px solid #f1b8b5;
    }

    /* Metrics Cards */
    .monitoring-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 20px;
    }

    .monitor-card {
        background: #ffffff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        border-left: 4px solid #f4c400;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .monitor-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .monitor-label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #777777;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .monitor-value {
        font-size: 26px;
        font-weight: 700;
        color: #111111;
        line-height: 1.2;
    }

    .monitor-note {
        margin-top: 6px;
        font-size: 12px;
        color: #888888;
    }

    .monitor-card.dark {
        background: #111111;
        border-left-color: #f4c400;
    }
    .monitor-card.dark .monitor-label,
    .monitor-card.dark .monitor-note {
        color: #cccccc;
    }
    .monitor-card.dark .monitor-value {
        color: #f4c400;
    }

    .monitor-card.danger {
        border-left-color: #dc2626;
    }
    .monitor-card.danger .monitor-value {
        color: #dc2626;
    }

    .monitor-card.warning {
        border-left-color: #f59e0b;
    }
    .monitor-card.warning .monitor-value {
        color: #d97706;
    }

    .monitor-card.success {
        border-left-color: #10b981;
    }
    .monitor-card.success .monitor-value {
        color: #059669;
    }

    .monitor-card.info {
        border-left-color: #2563eb;
    }
    .monitor-card.info .monitor-value {
        color: #2563eb;
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

    /* Tabs Bar */
    .tabs-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        background: #ffffff;
        padding: 8px;
        border-radius: 10px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.05);
    }

    .tab-btn {
        padding: 10px 22px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        border: none;
        background: transparent;
        color: #555555;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .tab-btn:hover {
        background: #f5f5f5;
        color: #111111;
    }

    .tab-btn.active {
        background: #f4c400;
        color: #111111;
        box-shadow: 0 2px 6px rgba(244, 196, 0, 0.35);
    }

    .tab-badge {
        background: rgba(0, 0, 0, 0.12);
        padding: 2px 7px;
        border-radius: 10px;
        font-size: 11px;
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
        transition: border-color 0.2s ease;
    }

    .search-input-group input:focus {
        border-color: #f4c400;
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

    .toolbar-select:focus {
        border-color: #f4c400;
    }

    .records-count-indicator {
        margin-left: auto;
        font-size: 12px;
        color: #777777;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Card & Table */
    .card {
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        margin-bottom: 25px;
    }

    .card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #eeeeee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .card-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #111111;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #111111;
        color: #ffffff;
        text-align: left;
        padding: 13px 16px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        font-weight: 700;
    }

    td {
        padding: 13px 16px;
        border-bottom: 1px solid #eeeeee;
        font-size: 13px;
        vertical-align: middle;
        white-space: nowrap;
    }

    tbody tr:hover {
        background: #fffdf0;
    }

    .amount {
        font-weight: 700;
        font-family: Consolas, 'Courier New', monospace;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .status-active {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-inactive {
        background: #eeeeee;
        color: #666666;
    }

    .status-pending {
        background: #fff8d6;
        color: #856404;
    }

    .status-warning {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .status-danger {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
    }

    .edit-btn {
        display: inline-block;
        padding: 6px 12px;
        background: #f4c400;
        color: #111111;
        text-decoration: none;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 700;
        margin-right: 5px;
    }
    .edit-btn:hover {
        background: #dcae00;
    }

    .delete-btn {
        background: #111111;
        color: #ffffff;
        border: none;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }
    .delete-btn:hover {
        background: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #777777;
    }
    .empty-icon {
        font-size: 40px;
        margin-bottom: 12px;
    }

    @media (max-width: 1100px) {
        .monitoring-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .monitoring-grid {
            grid-template-columns: 1fr;
        }
        .toolbar-card {
            flex-direction: column;
            align-items: stretch;
        }
    }

    /* ===================================================
       FORMAL CORPORATE PRINT & PDF STYLES
       Transforms page into an official executive document
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

        /* 1. COMPLETELY HIDE ALL WEB APPLICATION CHROME */
        .sidebar,
        .topbar,
        .layout > .sidebar,
        .user-area,
        .header-actions,
        .tabs-bar,
        .toolbar-card,
        .live-status-pill,
        .page-header,
        .monitoring-grid,
        .charts-grid,
        .actions,
        th.no-print,
        td.no-print,
        .no-print,
        .alert,
        .edit-btn,
        .delete-btn {
            display: none !important;
        }

        /* 2. EXPAND LAYOUT TO 100% WIDTH (RECLAIM SIDEBAR SPACE) */
        .layout,
        .main,
        .content {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            min-height: auto !important;
        }

        /* 3. SHOW FORMAL CORPORATE DOCUMENT HEADER & WATERMARK */
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

        .company-branding {
            text-align: left;
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

        .doc-badge-block {
            text-align: right;
        }

        .doc-type-title {
            font-size: 12pt !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            color: #000000 !important;
            margin: 0 0 4pt 0;
            letter-spacing: 0.3px;
        }

        .doc-meta {
            font-size: 7.5pt !important;
            color: #444444 !important;
            line-height: 1.4;
        }

        /* 4. FORMAL EXECUTIVE AUDIT SUMMARY (COMPACT & CLEAN) */
        .formal-summary-box {
            display: table !important;
            width: 100% !important;
            border-collapse: collapse !important;
            margin-bottom: 12pt !important;
            border: 1pt solid #000000 !important;
            page-break-inside: avoid;
        }

        .formal-summary-box th,
        .formal-summary-box td {
            border: 0.5pt solid #000000 !important;
            padding: 4pt 6pt !important;
            font-size: 8pt !important;
            text-align: left;
        }

        .formal-summary-box th {
            background: #f0f0f0 !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            color: #000000 !important;
        }

        .formal-summary-box td.val {
            font-weight: 700 !important;
            text-align: right !important;
        }

        /* 5. FORMAL AUDIT TABLE STYLING */
        .card {
            box-shadow: none !important;
            border: none !important;
            margin: 0 !important;
            padding: 0 !important;
            background: transparent !important;
        }

        .card-header {
            display: none !important;
        }

        .table-wrapper {
            overflow: visible !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 8pt !important;
            page-break-inside: auto;
        }

        thead {
            display: table-header-group !important;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        th {
            background: #e8e8e8 !important;
            color: #000000 !important;
            border: 1pt solid #000000 !important;
            padding: 4pt 5pt !important;
            font-weight: 800 !important;
            font-size: 7.5pt !important;
            text-transform: uppercase !important;
            letter-spacing: 0.3px;
        }

        td {
            border: 0.5pt solid #000000 !important;
            padding: 3.5pt 5pt !important;
            font-size: 8pt !important;
            background: #ffffff !important;
            color: #000000 !important;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) td {
            background: #fafafa !important;
        }

        .amount {
            text-align: right !important;
            font-weight: 700 !important;
            font-family: Arial, monospace !important;
        }

        /* Statuses in Print */
        .status-badge {
            background: transparent !important;
            border: 0.5pt solid #000000 !important;
            color: #000000 !important;
            font-size: 7pt !important;
            font-weight: 700 !important;
            padding: 1pt 4pt !important;
            text-transform: uppercase !important;
            border-radius: 2px !important;
        }

        /* 6. FORMAL SIGNATURE / CERTIFICATION SECTION AT BOTTOM */
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
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    }
</style>
@endsection

@section('content')

    <!-- ===================================================
         FORMAL PRINT-ONLY DOCUMENT HEADER (OFFICIAL LETTERHEAD)
    =================================================== -->
    <div class="print-only formal-letterhead">
        <div class="company-branding">
            <h1 class="company-name">Alibaton Construction Inc.</h1>
            <p class="company-sub">
                Corporate Compensation &amp; Human Resources Division<br>
                Km. 12 Sasa, Davao City, Davao del Sur, Philippines<br>
                Tel: (082) 234-5678 | Email: hr@alibatonconstruction.com
            </p>
        </div>
        <div class="doc-badge-block">
            <h2 class="doc-type-title" id="print_doc_title">
                Benefits Administration Report
            </h2>
            <div class="doc-meta">
                <div><strong>Document Ref:</strong> ACI-HRB-{{ date('Ymd') }}-{{ rand(100, 999) }}</div>
                <div><strong>Date Generated:</strong> {{ date('F d, Y - h:i A') }}</div>
                <div><strong>Prepared By:</strong> {{ Auth::user()->name ?? 'HR Administrator' }}</div>
                <div><strong>Classification:</strong> STRICTLY CONFIDENTIAL</div>
            </div>
        </div>
    </div>

    <!-- FORMAL PRINT-ONLY EXECUTIVE AUDIT SUMMARY -->
    <div class="print-only" id="print_summary_all">
        <table class="formal-summary-box">
            <thead>
                <tr>
                    <th colspan="4" style="background:#000000 !important; color:#ffffff !important; font-size:8.5pt !important; text-align:center;">
                        EXECUTIVE SUMMARY &amp; AUDIT TOTALS
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:25%;"><strong>Total Benefit Records:</strong></td>
                    <td class="val" style="width:25%;">{{ $totalBenefits }}</td>
                    <td style="width:25%;"><strong>Total Active Benefits:</strong></td>
                    <td class="val" style="width:25%;">{{ $activeBenefits->count() }}</td>
                </tr>
                <tr>
                    <td><strong>HMO Policies Enrolled:</strong></td>
                    <td class="val">{{ $totalHmoEnrollees ?? 0 }} ({{ $activeHmoEnrollees ?? 0 }} Active)</td>
                    <td><strong>Total Monthly Benefit Value:</strong></td>
                    <td class="val">₱{{ number_format($totalBenefitAmount, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Active Monthly Remittance:</strong></td>
                    <td class="val">₱{{ number_format($activeBenefitAmount, 2) }}</td>
                    <td><strong>Cumulative Statutory Remittances:</strong></td>
                    <td class="val">₱{{ number_format($grandTotalGovContributions ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ===================================================
         SCREEN HEADER (VISIBLE IN BROWSER, HIDDEN ON PRINT)
    =================================================== -->
    <div class="page-header no-print">
        <div>
            <h1 class="page-title">
                Benefits Administration
            </h1>
            <p class="page-subtitle">
                Centralized HMO health policies, employee allowances monitoring, and statutory government contributions (SSS, PhilHealth, Pag-IBIG).
            </p>
        </div>

        <div class="header-actions">
            <!-- Real-time Status Badge -->
            <div class="live-status-pill" title="Auto-polling real-time updates every 25 seconds">
                <span class="pulse-dot"></span>
                <span id="live_status_text"><strong>Live Sync:</strong> Active</span>
                <span id="last_sync_time" style="color:#888; font-size:11px;">just now</span>
                <button type="button" onclick="triggerManualSync()" style="background:none; border:none; cursor:pointer; color:#111; font-weight:bold; padding:0 2px;" title="Refresh now">
                    ↻
                </button>
            </div>

            <a href="/benefits/export-contributions" class="btn-action dark" title="Download official government contributions CSV spreadsheet">
                ⬇ Export CSV
            </a>

            <button type="button" onclick="window.print()" class="btn-action outline" title="Generate formal printed report / PDF document">
                🖨 Print Official Report
            </button>

            <a href="/benefits/create" class="btn-action">
                + Add Benefit
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success no-print">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error no-print">
            {{ session('error') }}
        </div>
    @endif

    @if(isset($error))
        <div class="alert alert-error no-print">
            {{ $error }}
        </div>
    @endif

    <!-- REAL-TIME KPI MONITORING CARDS (SCREEN ONLY) -->
    <div class="monitoring-grid no-print">
        <div class="monitor-card">
            <div class="monitor-label">Total Benefit Records</div>
            <div class="monitor-value" id="kpi_total_benefits">{{ $totalBenefits }}</div>
            <div class="monitor-note">Active, pending &amp; inactive</div>
        </div>

        <div class="monitor-card success">
            <div class="monitor-label">Active Benefits</div>
            <div class="monitor-value" id="kpi_active_benefits">{{ $activeBenefits->count() }}</div>
            <div class="monitor-note">Currently in effect</div>
        </div>

        <div class="monitor-card warning">
            <div class="monitor-label">HMO Enrolled Policies</div>
            <div class="monitor-value" id="kpi_total_hmo">{{ $totalHmoEnrollees ?? 0 }}</div>
            <div class="monitor-note"><span id="kpi_active_hmo">{{ $activeHmoEnrollees ?? 0 }}</span> active coverages</div>
        </div>

        <div class="monitor-card dark">
            <div class="monitor-label">Total Monthly Benefit Value</div>
            <div class="monitor-value" id="kpi_total_amount">₱{{ number_format($totalBenefitAmount, 2) }}</div>
            <div class="monitor-note">Active: <span id="kpi_active_amount">₱{{ number_format($activeBenefitAmount, 2) }}</span></div>
        </div>
    </div>

    <!-- INTERACTIVE VISUAL DASHBOARDS (CHART.JS - SCREEN ONLY) -->
    <div class="charts-grid no-print">
        <!-- Chart 1: Benefit Distribution by Type -->
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3 class="chart-title">Benefits Distribution by Type</h3>
                    <p class="chart-subtitle">Allocation across allowances, health insurance, and subsidies</p>
                </div>
                <span class="tab-badge" id="chart1_total_badge">{{ count($benefitTypeBreakdown) }} Types</span>
            </div>
            <div class="chart-wrapper">
                <canvas id="benefitTypesChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Statutory Government Contributions Share -->
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3 class="chart-title">Government Contributions Share</h3>
                    <p class="chart-subtitle">SSS (5%), PhilHealth (2.5%), and Pag-IBIG (2%) remittances</p>
                </div>
                <span class="tab-badge" id="chart2_total_badge">₱{{ number_format($grandTotalGovContributions ?? 0, 2) }}</span>
            </div>
            <div class="chart-wrapper">
                <canvas id="govContributionsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- MODULE NAVIGATION TABS (SCREEN ONLY) -->
    <div class="tabs-bar no-print">
        <button type="button" id="tab_btn_all" class="tab-btn active" onclick="showBenefitTab('all')">
            <span>📋</span> All Benefits &amp; Allowances
            <span class="tab-badge" id="badge_count_all">{{ count($benefits) }}</span>
        </button>

        <button type="button" id="tab_btn_hmo" class="tab-btn" onclick="showBenefitTab('hmo')">
            <span>🏥</span> HMO Management
            <span class="tab-badge" id="badge_count_hmo">{{ $totalHmoEnrollees ?? 0 }}</span>
        </button>

        <button type="button" id="tab_btn_gov" class="tab-btn" onclick="showBenefitTab('gov')">
            <span>🏛️</span> Government Contribution Tracking
            <span class="tab-badge" id="badge_count_gov">{{ count($governmentContributions ?? []) }}</span>
        </button>
    </div>

    <!-- ==========================================
         TAB 1: ALL BENEFITS & ALLOWANCES
    =========================================== -->
    <div id="tab_content_all">
        <!-- Live Filter Toolbar (Hidden on print) -->
        <div class="toolbar-card no-print">
            <div class="search-input-group">
                <span class="search-icon">🔍</span>
                <input type="text" id="search_benefits" placeholder="Search by Employee ID, Name, Provider, or Benefit..." oninput="filterAllBenefits()">
            </div>

            <select id="filter_benefit_type" class="toolbar-select" onchange="filterAllBenefits()">
                <option value="">All Benefit Types</option>
                @foreach($benefitTypeBreakdown as $typeName => $bData)
                    <option value="{{ $typeName }}">{{ $typeName }}</option>
                @endforeach
            </select>

            <select id="filter_benefit_status" class="toolbar-select" onchange="filterAllBenefits()">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="inactive">Inactive</option>
            </select>

            <button type="button" onclick="resetAllBenefitsFilters()" class="btn-action outline" style="padding:8px 12px; font-size:12px;">
                Reset
            </button>

            <div class="records-count-indicator" id="filtered_count_all">
                Showing {{ count($benefits) }} of {{ count($benefits) }} records
            </div>
        </div>

        <div class="card">
            @if(count($benefits) > 0)
                <div class="table-wrapper">
                    <table id="table_benefits_all">
                        <thead>
                            <tr>
                                <th style="width:6%;">ID</th>
                                <th style="width:24%;">Employee Details</th>
                                <th style="width:18%;">Benefit Type</th>
                                <th style="width:18%;">Provider / Policy</th>
                                <th style="width:12%; text-align:right;">Amount (₱)</th>
                                <th style="width:14%;">Effective Period</th>
                                <th style="width:8%;">Status</th>
                                <th class="no-print" style="width:10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($benefits as $b)
                                @php
                                    $empId = $b['employee_id'] ?? '';
                                    $empName = $employeeMap[$empId]['name'] ?? '';
                                    $empDept = $employeeMap[$empId]['department'] ?? '';
                                    $bType = $b['benefit_type'] ?? '';
                                    $status = strtolower($b['status'] ?? 'active');
                                @endphp
                                <tr data-empid="{{ strtolower($empId) }}"
                                    data-empname="{{ strtolower($empName) }}"
                                    data-type="{{ strtolower($bType) }}"
                                    data-provider="{{ strtolower($b['provider'] ?? '') }}"
                                    data-status="{{ $status }}">
                                    <td><strong>#{{ $b['id'] ?? '-' }}</strong></td>
                                    <td>
                                        <strong>{{ $empId }}</strong>
                                        @if(!empty($empName))
                                            <div style="font-size:11px; color:#444;">{{ $empName }} &bull; {{ $empDept }}</div>
                                        @endif
                                    </td>
                                    <td><strong>{{ $bType }}</strong></td>
                                    <td>
                                        {{ $b['provider'] ?? 'Company Direct' }}
                                        @if(!empty($b['membership_number']))
                                            <div style="font-size:10px; color:#555;">Policy: {{ $b['membership_number'] }}</div>
                                        @endif
                                    </td>
                                    <td class="amount">₱{{ number_format((float)($b['amount'] ?? 0), 2) }}</td>
                                    <td>
                                        <div style="font-size:11px;">
                                            {{ !empty($b['start_date']) ? date('M d, Y', strtotime($b['start_date'])) : '—' }}
                                            to
                                            {{ !empty($b['end_date']) ? date('M d, Y', strtotime($b['end_date'])) : 'Continuous' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $status }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="actions no-print">
                                        <a href="/benefits/{{ $b['id'] }}/edit" class="edit-btn">Edit</a>
                                        <form action="/benefits/{{ $b['id'] }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this benefit record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">▣</div>
                    <h3>No Benefit Records</h3>
                    <p>There are currently no employee benefit records in the system.</p>
                    <a href="/benefits/create" class="btn-action no-print">+ Add First Benefit</a>
                </div>
            @endif
        </div>
    </div>

    <!-- ==========================================
         TAB 2: HMO MANAGEMENT
    =========================================== -->
    <div id="tab_content_hmo" style="display:none;">
        @php
            $nowTime = time();
            $expiringSoonCount = 0;
            $expiredCount = 0;
            foreach ($hmoBenefits ?? [] as $h) {
                if (!empty($h['end_date'])) {
                    $endTime = strtotime($h['end_date']);
                    if ($endTime < $nowTime) {
                        $expiredCount++;
                    } elseif ($endTime <= ($nowTime + (30 * 86400))) {
                        $expiringSoonCount++;
                    }
                }
            }
        @endphp

        <!-- HMO Health KPIs (Screen Only) -->
        <div class="monitoring-grid no-print">
            <div class="monitor-card">
                <div class="monitor-label">Total HMO Policies</div>
                <div class="monitor-value">{{ $totalHmoEnrollees ?? 0 }}</div>
                <div class="monitor-note">Registered health plans</div>
            </div>

            <div class="monitor-card success">
                <div class="monitor-label">Active Coverage</div>
                <div class="monitor-value">{{ $activeHmoEnrollees ?? 0 }}</div>
                <div class="monitor-note">In good standing</div>
            </div>

            <div class="monitor-card warning">
                <div class="monitor-label">Expiring in &le; 30 Days</div>
                <div class="monitor-value">{{ $expiringSoonCount }}</div>
                <div class="monitor-note">Needs renewal alert</div>
            </div>

            <div class="monitor-card {{ $expiredCount > 0 ? 'danger' : '' }}">
                <div class="monitor-label">Expired Policies</div>
                <div class="monitor-value">{{ $expiredCount }}</div>
                <div class="monitor-note">Overdue for update</div>
            </div>
        </div>

        <!-- HMO Live Search & Filter (Screen Only) -->
        <div class="toolbar-card no-print">
            <div class="search-input-group">
                <span class="search-icon">🔍</span>
                <input type="text" id="search_hmo" placeholder="Search HMO by Employee, Provider (Maxicare, PhilCare), Card #..." oninput="filterHmoBenefits()">
            </div>

            <select id="filter_hmo_status" class="toolbar-select" onchange="filterHmoBenefits()">
                <option value="">All Health Statuses</option>
                <option value="active">Active Coverages</option>
                <option value="expiring">Expiring Soon (&le;30 Days)</option>
                <option value="expired">Expired</option>
                <option value="inactive">Inactive</option>
            </select>

            <button type="button" onclick="resetHmoFilters()" class="btn-action outline" style="padding:8px 12px; font-size:12px;">
                Reset
            </button>

            <div class="records-count-indicator" id="filtered_count_hmo">
                Showing {{ count($hmoBenefits ?? []) }} policy record(s)
            </div>
        </div>

        <div class="card">
            <div class="card-header no-print">
                <div>
                    <h3 class="card-title">HMO Employee Policies &amp; Coverages</h3>
                    <p style="margin:3px 0 0; color:#777; font-size:12px;">Track policy numbers, coverage limits, and expiration alerts</p>
                </div>
                <a href="/benefits/create" class="btn-action" style="font-size:12px; padding:8px 14px;">
                    + Enroll Employee to HMO
                </a>
            </div>

            @if(count($hmoBenefits ?? []) > 0)
                <div class="table-wrapper">
                    <table id="table_benefits_hmo">
                        <thead>
                            <tr>
                                <th style="width:22%;">Employee</th>
                                <th style="width:16%;">HMO Provider</th>
                                <th style="width:16%;">Policy / Card #</th>
                                <th style="width:14%; text-align:right;">Annual Limit</th>
                                <th style="width:12%;">Effective Date</th>
                                <th style="width:12%;">Expiration Date</th>
                                <th style="width:8%;">Status</th>
                                <th class="no-print" style="width:8%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hmoBenefits as $hmo)
                                @php
                                    $empId = $hmo['employee_id'] ?? '';
                                    $empName = $employeeMap[$empId]['name'] ?? '';
                                    $isExpired = false;
                                    $isExpiringSoon = false;
                                    $daysRemaining = null;

                                    if (!empty($hmo['end_date'])) {
                                        $endTimestamp = strtotime($hmo['end_date']);
                                        $diffDays = ceil(($endTimestamp - $nowTime) / 86400);
                                        if ($diffDays < 0) {
                                            $isExpired = true;
                                        } elseif ($diffDays <= 30) {
                                            $isExpiringSoon = true;
                                            $daysRemaining = $diffDays;
                                        }
                                    }

                                    $hmoStatus = strtolower($hmo['status'] ?? 'active');
                                @endphp
                                <tr data-empid="{{ strtolower($empId) }}"
                                    data-empname="{{ strtolower($empName) }}"
                                    data-provider="{{ strtolower($hmo['provider'] ?? '') }}"
                                    data-card="{{ strtolower($hmo['membership_number'] ?? '') }}"
                                    data-status="{{ $hmoStatus }}"
                                    data-isexpired="{{ $isExpired ? '1' : '0' }}"
                                    data-isexpiring="{{ $isExpiringSoon ? '1' : '0' }}">
                                    <td>
                                        <strong>{{ $empId }}</strong>
                                        @if(!empty($empName))
                                            <div style="font-size:11px; color:#444;">{{ $empName }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $hmo['provider'] ?? 'Health Provider' }}</strong>
                                    </td>
                                    <td>
                                        <code>{{ $hmo['membership_number'] ?? 'Not Specified' }}</code>
                                    </td>
                                    <td class="amount">₱{{ number_format((float)($hmo['coverage'] ?? $hmo['amount'] ?? 0), 2) }}</td>
                                    <td>{{ !empty($hmo['start_date']) ? date('M d, Y', strtotime($hmo['start_date'])) : 'Immediate' }}</td>
                                    <td>
                                        @if(!empty($hmo['end_date']))
                                            @if($isExpired)
                                                <strong style="color:#b91c1c;">{{ date('M d, Y', strtotime($hmo['end_date'])) }}</strong>
                                            @elseif($isExpiringSoon)
                                                <strong style="color:#b45309;">{{ date('M d, Y', strtotime($hmo['end_date'])) }}</strong>
                                            @else
                                                {{ date('M d, Y', strtotime($hmo['end_date'])) }}
                                            @endif
                                        @else
                                            <span>Continuous</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($isExpired)
                                            <span class="status-badge status-danger">Expired</span>
                                        @elseif($isExpiringSoon)
                                            <span class="status-badge status-warning">Renews {{ $daysRemaining }}d</span>
                                        @else
                                            <span class="status-badge status-{{ $hmoStatus }}">{{ $hmoStatus }}</span>
                                        @endif
                                    </td>
                                    <td class="actions no-print">
                                        <a href="/benefits/{{ $hmo['id'] }}/edit" class="edit-btn">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">🏥</div>
                    <h3>No HMO Records Found</h3>
                    <p>Register employees with HMO providers (Maxicare, PhilCare, Medicard, etc.) to monitor health coverage.</p>
                    <a href="/benefits/create" class="btn-action no-print">+ Enroll Employee to HMO</a>
                </div>
            @endif
        </div>
    </div>

    <!-- ==========================================
         TAB 3: GOVERNMENT CONTRIBUTION TRACKING
    =========================================== -->
    <div id="tab_content_gov" style="display:none;">
        <!-- Statutory Summary Metric Cards (Screen Only) -->
        <div class="monitoring-grid no-print">
            <div class="monitor-card info">
                <div class="monitor-label">Total SSS Remitted (5%)</div>
                <div class="monitor-value" id="kpi_sss_total">₱{{ number_format($totalSssContributions ?? 0, 2) }}</div>
                <div class="monitor-note">Social Security System</div>
            </div>

            <div class="monitor-card success">
                <div class="monitor-label">PhilHealth Remitted (2.5%)</div>
                <div class="monitor-value" id="kpi_ph_total">₱{{ number_format($totalPhilHealthContributions ?? 0, 2) }}</div>
                <div class="monitor-note">Universal Health Coverage</div>
            </div>

            <div class="monitor-card danger">
                <div class="monitor-label">Pag-IBIG Remitted (2%)</div>
                <div class="monitor-value" id="kpi_pagibig_total">₱{{ number_format($totalPagibigContributions ?? 0, 2) }}</div>
                <div class="monitor-note">Home Development Mutual Fund</div>
            </div>

            <div class="monitor-card dark">
                <div class="monitor-label">Total Statutory Contributions</div>
                <div class="monitor-value" id="kpi_gov_total">₱{{ number_format($grandTotalGovContributions ?? 0, 2) }}</div>
                <div class="monitor-note">Cumulative employee deductions</div>
            </div>
        </div>

        <!-- Gov Toolbar (Screen Only) -->
        <div class="toolbar-card no-print">
            <div class="search-input-group">
                <span class="search-icon">🔍</span>
                <input type="text" id="search_gov" placeholder="Search by Employee ID, Name, or Payroll ID..." oninput="filterGovContributions()">
            </div>

            <input type="month" id="filter_gov_month" class="toolbar-select" onchange="filterGovContributions()" title="Filter by pay month">

            <button type="button" onclick="resetGovFilters()" class="btn-action outline" style="padding:8px 12px; font-size:12px;">
                Reset
            </button>

            <a href="/benefits/export-contributions" class="btn-action dark" style="padding:8px 14px; font-size:12px;">
                ⬇ Download Official CSV
            </a>

            <button type="button" onclick="window.print()" class="btn-action outline" style="padding:8px 14px; font-size:12px;">
                🖨 Print Official Remittance
            </button>

            <div class="records-count-indicator" id="filtered_count_gov">
                Showing {{ count($governmentContributions ?? []) }} payroll record(s)
            </div>
        </div>

        <div class="card">
            <div class="card-header no-print">
                <div>
                    <h3 class="card-title">Government Statutory Deduction History</h3>
                    <p style="margin:3px 0 0; color:#777; font-size:12px;">Real-time contribution audit per employee payroll computation</p>
                </div>
            </div>

            @if(count($governmentContributions ?? []) > 0)
                <div class="table-wrapper">
                    <table id="table_benefits_gov">
                        <thead>
                            <tr>
                                <th style="width:8%;">Payroll #</th>
                                <th style="width:12%;">Pay Date</th>
                                <th style="width:24%;">Employee Details</th>
                                <th style="width:14%; text-align:right;">Basic Salary</th>
                                <th style="width:13%; text-align:right;">SSS (5%)</th>
                                <th style="width:13%; text-align:right;">PhilHealth (2.5%)</th>
                                <th style="width:13%; text-align:right;">Pag-IBIG (2%)</th>
                                <th style="width:15%; text-align:right;">Total Remittance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($governmentContributions as $gc)
                                @php
                                    $empId = $gc['employee_id'] ?? '';
                                    $empName = $employeeMap[$empId]['name'] ?? '';
                                    $payMonth = !empty($gc['pay_date']) ? date('Y-m', strtotime($gc['pay_date'])) : '';
                                @endphp
                                <tr data-empid="{{ strtolower($empId) }}"
                                    data-empname="{{ strtolower($empName) }}"
                                    data-pid="{{ strtolower($gc['payroll_id'] ?? '') }}"
                                    data-paymonth="{{ $payMonth }}"
                                    data-sss="{{ (float)($gc['sss_deduction'] ?? 0) }}"
                                    data-ph="{{ (float)($gc['philhealth_deduction'] ?? 0) }}"
                                    data-pagibig="{{ (float)($gc['pagibig_deduction'] ?? 0) }}"
                                    data-total="{{ (float)($gc['total_gov_contribution'] ?? 0) }}">
                                    <td><strong>#{{ $gc['payroll_id'] }}</strong></td>
                                    <td>{{ !empty($gc['pay_date']) ? date('M d, Y', strtotime($gc['pay_date'])) : '—' }}</td>
                                    <td>
                                        <strong>{{ $empId }}</strong>
                                        @if(!empty($empName))
                                            <div style="font-size:11px; color:#444;">{{ $empName }}</div>
                                        @endif
                                    </td>
                                    <td class="amount">₱{{ number_format($gc['basic_salary'], 2) }}</td>
                                    <td class="amount">₱{{ number_format($gc['sss_deduction'], 2) }}</td>
                                    <td class="amount">₱{{ number_format($gc['philhealth_deduction'], 2) }}</td>
                                    <td class="amount">₱{{ number_format($gc['pagibig_deduction'], 2) }}</td>
                                    <td class="amount">₱{{ number_format($gc['total_gov_contribution'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <!-- Formal Audit Table Totals in Print -->
                        <tfoot>
                            <tr style="background:#f2f2f2; font-weight:bold;">
                                <td colspan="4" style="text-align:right; font-weight:800;">AUDIT TOTALS:</td>
                                <td class="amount" id="print_total_sss">₱{{ number_format($totalSssContributions ?? 0, 2) }}</td>
                                <td class="amount" id="print_total_ph">₱{{ number_format($totalPhilHealthContributions ?? 0, 2) }}</td>
                                <td class="amount" id="print_total_pagibig">₱{{ number_format($totalPagibigContributions ?? 0, 2) }}</td>
                                <td class="amount" id="print_total_grand" style="font-weight:900;">₱{{ number_format($grandTotalGovContributions ?? 0, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">📊</div>
                    <h3>No Contribution History</h3>
                    <p>Government statutory contributions will appear here once payroll computations are executed.</p>
                    <a href="/payrolls/create" class="btn-action no-print">+ Process Payroll</a>
                </div>
            @endif
        </div>
    </div>

    <!-- ===================================================
         FORMAL CERTIFICATION & SIGNATURES (PRINT ONLY)
    =================================================== -->
    <div class="print-only formal-signatories">
        <div class="sig-col">
            <div class="sig-caption">Prepared &amp; Verified By:</div>
            <div class="sig-line"></div>
            <div class="sig-name">{{ Auth::user()->name ?? 'HR Officer' }}</div>
            <div class="sig-designation">HR &amp; Benefits Officer</div>
        </div>

        <div class="sig-col">
            <div class="sig-caption">Certified Accurate By:</div>
            <div class="sig-line"></div>
            <div class="sig-name">MARIA ELENA ALIBATON</div>
            <div class="sig-designation">Finance &amp; Payroll Controller</div>
        </div>

        <div class="sig-col">
            <div class="sig-caption">Noted &amp; Approved By:</div>
            <div class="sig-line"></div>
            <div class="sig-name">ENGR. ROBERTO ALIBATON</div>
            <div class="sig-designation">President / General Manager</div>
        </div>
    </div>

    <div class="print-only formal-footer-note">
        CONFIDENTIAL INTERNAL RECORD &bull; ALIBATON CONSTRUCTION INC. &bull; GENERATED VIA AUTOMATED ENTERPRISE PAYROLL SYSTEM
    </div>

    <!-- Chart.js CDN for interactive visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        // ==========================================
        // 1. TAB NAVIGATION WITH PRINT TITLE SYNC
        // ==========================================
        let currentActiveTab = 'all';

        function showBenefitTab(tabName) {
            currentActiveTab = tabName;
            document.getElementById('tab_content_all').style.display = tabName === 'all' ? 'block' : 'none';
            document.getElementById('tab_content_hmo').style.display = tabName === 'hmo' ? 'block' : 'none';
            document.getElementById('tab_content_gov').style.display = tabName === 'gov' ? 'block' : 'none';

            document.getElementById('tab_btn_all').classList.toggle('active', tabName === 'all');
            document.getElementById('tab_btn_hmo').classList.toggle('active', tabName === 'hmo');
            document.getElementById('tab_btn_gov').classList.toggle('active', tabName === 'gov');

            // Dynamic formal title in print header
            const printTitle = document.getElementById('print_doc_title');
            if (printTitle) {
                if (tabName === 'all') {
                    printTitle.textContent = 'Employee Benefits & Allowances Report';
                } else if (tabName === 'hmo') {
                    printTitle.textContent = 'HMO Health Coverage & Policy Schedule';
                } else if (tabName === 'gov') {
                    printTitle.textContent = 'Statutory Government Contributions Summary';
                }
            }
        }

        // ==========================================
        // 2. LIVE FILTERING: ALL BENEFITS TAB
        // ==========================================
        function filterAllBenefits() {
            const query = (document.getElementById('search_benefits').value || '').trim().toLowerCase();
            const typeFilter = (document.getElementById('filter_benefit_type').value || '').trim().toLowerCase();
            const statusFilter = (document.getElementById('filter_benefit_status').value || '').trim().toLowerCase();

            const rows = document.querySelectorAll('#table_benefits_all tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const empid = row.getAttribute('data-empid') || '';
                const empname = row.getAttribute('data-empname') || '';
                const type = row.getAttribute('data-type') || '';
                const provider = row.getAttribute('data-provider') || '';
                const status = row.getAttribute('data-status') || '';

                const matchesQuery = !query || empid.includes(query) || empname.includes(query) || type.includes(query) || provider.includes(query);
                const matchesType = !typeFilter || type === typeFilter;
                const matchesStatus = !statusFilter || status === statusFilter;

                if (matchesQuery && matchesType && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const countIndicator = document.getElementById('filtered_count_all');
            if (countIndicator) {
                countIndicator.textContent = `Showing ${visibleCount} of ${rows.length} records`;
            }
        }

        function resetAllBenefitsFilters() {
            document.getElementById('search_benefits').value = '';
            document.getElementById('filter_benefit_type').value = '';
            document.getElementById('filter_benefit_status').value = '';
            filterAllBenefits();
        }

        // ==========================================
        // 3. LIVE FILTERING: HMO MANAGEMENT TAB
        // ==========================================
        function filterHmoBenefits() {
            const query = (document.getElementById('search_hmo').value || '').trim().toLowerCase();
            const statusFilter = (document.getElementById('filter_hmo_status').value || '').trim().toLowerCase();

            const rows = document.querySelectorAll('#table_benefits_hmo tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const empid = row.getAttribute('data-empid') || '';
                const empname = row.getAttribute('data-empname') || '';
                const provider = row.getAttribute('data-provider') || '';
                const card = row.getAttribute('data-card') || '';
                const status = row.getAttribute('data-status') || '';
                const isExpired = row.getAttribute('data-isexpired') === '1';
                const isExpiring = row.getAttribute('data-isexpiring') === '1';

                const matchesQuery = !query || empid.includes(query) || empname.includes(query) || provider.includes(query) || card.includes(query);

                let matchesStatus = true;
                if (statusFilter === 'active') {
                    matchesStatus = (status === 'active' && !isExpired);
                } else if (statusFilter === 'expiring') {
                    matchesStatus = isExpiring;
                } else if (statusFilter === 'expired') {
                    matchesStatus = isExpired;
                } else if (statusFilter === 'inactive') {
                    matchesStatus = (status === 'inactive');
                }

                if (matchesQuery && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const countIndicator = document.getElementById('filtered_count_hmo');
            if (countIndicator) {
                countIndicator.textContent = `Showing ${visibleCount} of ${rows.length} policy record(s)`;
            }
        }

        function resetHmoFilters() {
            document.getElementById('search_hmo').value = '';
            document.getElementById('filter_hmo_status').value = '';
            filterHmoBenefits();
        }

        // ==========================================
        // 4. LIVE FILTERING: GOV CONTRIBUTIONS TAB
        // ==========================================
        function filterGovContributions() {
            const query = (document.getElementById('search_gov').value || '').trim().toLowerCase();
            const monthFilter = (document.getElementById('filter_gov_month').value || '').trim();

            const rows = document.querySelectorAll('#table_benefits_gov tbody tr');
            let visibleCount = 0;

            let dynamicSss = 0;
            let dynamicPh = 0;
            let dynamicPagibig = 0;
            let dynamicTotal = 0;

            rows.forEach(row => {
                const empid = row.getAttribute('data-empid') || '';
                const empname = row.getAttribute('data-empname') || '';
                const pid = row.getAttribute('data-pid') || '';
                const paymonth = row.getAttribute('data-paymonth') || '';

                const matchesQuery = !query || empid.includes(query) || empname.includes(query) || pid.includes(query);
                const matchesMonth = !monthFilter || paymonth === monthFilter;

                if (matchesQuery && matchesMonth) {
                    row.style.display = '';
                    visibleCount++;
                    dynamicSss += parseFloat(row.getAttribute('data-sss') || 0);
                    dynamicPh += parseFloat(row.getAttribute('data-ph') || 0);
                    dynamicPagibig += parseFloat(row.getAttribute('data-pagibig') || 0);
                    dynamicTotal += parseFloat(row.getAttribute('data-total') || 0);
                } else {
                    row.style.display = 'none';
                }
            });

            const countIndicator = document.getElementById('filtered_count_gov');
            if (countIndicator) {
                countIndicator.textContent = `Showing ${visibleCount} of ${rows.length} payroll record(s)`;
            }

            // Update on-screen & print totals
            const sssFormatted = '₱' + dynamicSss.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            const phFormatted = '₱' + dynamicPh.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            const pagibigFormatted = '₱' + dynamicPagibig.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            const totalFormatted = '₱' + dynamicTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            if (monthFilter || query) {
                document.getElementById('kpi_sss_total').textContent = sssFormatted;
                document.getElementById('kpi_ph_total').textContent = phFormatted;
                document.getElementById('kpi_pagibig_total').textContent = pagibigFormatted;
                document.getElementById('kpi_gov_total').textContent = totalFormatted;

                document.getElementById('print_total_sss').textContent = sssFormatted;
                document.getElementById('print_total_ph').textContent = phFormatted;
                document.getElementById('print_total_pagibig').textContent = pagibigFormatted;
                document.getElementById('print_total_grand').textContent = totalFormatted;
            }
        }

        function resetGovFilters() {
            document.getElementById('search_gov').value = '';
            document.getElementById('filter_gov_month').value = '';
            filterGovContributions();
        }

        // ==========================================
        // 5. CHART.JS VISUALIZATIONS
        // ==========================================
        let benefitChartInstance = null;
        let govChartInstance = null;

        function initCharts() {
            if (typeof Chart === 'undefined') return;

            // Chart 1: Benefit Types
            const benefitTypesData = @json($benefitTypeBreakdown);
            const typeLabels = Object.keys(benefitTypesData);
            const typeAmounts = typeLabels.map(l => benefitTypesData[l].amount || 0);

            const ctx1 = document.getElementById('benefitTypesChart');
            if (ctx1) {
                benefitChartInstance = new Chart(ctx1, {
                    type: 'doughnut',
                    data: {
                        labels: typeLabels.length ? typeLabels : ['No Data'],
                        datasets: [{
                            data: typeAmounts.length ? typeAmounts : [1],
                            backgroundColor: ['#f4c400', '#111111', '#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#64748b'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: { boxWidth: 12, font: { size: 11 } }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(item) {
                                        return ' ' + item.label + ': ₱' + Number(item.raw).toLocaleString('en-US', {minimumFractionDigits: 2});
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Chart 2: Statutory Deductions
            const sssTotal = {{ (float)($totalSssContributions ?? 0) }};
            const phTotal = {{ (float)($totalPhilHealthContributions ?? 0) }};
            const pagibigTotal = {{ (float)($totalPagibigContributions ?? 0) }};

            const ctx2 = document.getElementById('govContributionsChart');
            if (ctx2) {
                govChartInstance = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: ['SSS (5%)', 'PhilHealth (2.5%)', 'Pag-IBIG (2%)'],
                        datasets: [{
                            label: 'Total Remitted (₱)',
                            data: [sssTotal, phTotal, pagibigTotal],
                            backgroundColor: ['#2563eb', '#10b981', '#dc2626'],
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(item) {
                                        return ' Total: ₱' + Number(item.raw).toLocaleString('en-US', {minimumFractionDigits: 2});
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(val) { return '₱' + val.toLocaleString(); }
                                }
                            }
                        }
                    }
                });
            }
        }

        // ==========================================
        // 6. REAL-TIME DATA POLLING (EVERY 25 SECONDS)
        // ==========================================
        let lastSyncDate = new Date();

        function updateSyncTimer() {
            const now = new Date();
            const elapsedSecs = Math.floor((now - lastSyncDate) / 1000);
            const syncLabel = document.getElementById('last_sync_time');
            if (syncLabel) {
                if (elapsedSecs < 5) {
                    syncLabel.textContent = 'just now';
                } else if (elapsedSecs < 60) {
                    syncLabel.textContent = `${elapsedSecs}s ago`;
                } else {
                    syncLabel.textContent = `${Math.floor(elapsedSecs / 60)}m ago`;
                }
            }
        }
        setInterval(updateSyncTimer, 3000);

        async function fetchRealtimeBenefits() {
            try {
                const response = await fetch('/benefits/data', {
                    headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) return;

                const res = await response.json();
                if (!res.success || !res.stats) return;

                const s = res.stats;
                lastSyncDate = new Date();
                updateSyncTimer();

                // Live update KPI cards
                const elTotal = document.getElementById('kpi_total_benefits');
                if (elTotal) elTotal.textContent = s.total_benefits;

                const elActive = document.getElementById('kpi_active_benefits');
                if (elActive) elActive.textContent = s.active_benefits;

                const elHmo = document.getElementById('kpi_total_hmo');
                if (elHmo) elHmo.textContent = s.total_hmo_enrollees;

                const elHmoActive = document.getElementById('kpi_active_hmo');
                if (elHmoActive) elHmoActive.textContent = s.active_hmo_enrollees;

                const elAmount = document.getElementById('kpi_total_amount');
                if (elAmount) elAmount.textContent = '₱' + Number(s.total_benefit_amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                const elActiveAmount = document.getElementById('kpi_active_amount');
                if (elActiveAmount) elActiveAmount.textContent = '₱' + Number(s.active_benefit_amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                const elSss = document.getElementById('kpi_sss_total');
                if (elSss) elSss.textContent = '₱' + Number(s.total_sss).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                const elPh = document.getElementById('kpi_ph_total');
                if (elPh) elPh.textContent = '₱' + Number(s.total_philhealth).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                const elPagibig = document.getElementById('kpi_pagibig_total');
                if (elPagibig) elPagibig.textContent = '₱' + Number(s.total_pagibig).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                const elGovTotal = document.getElementById('kpi_gov_total');
                if (elGovTotal) elGovTotal.textContent = '₱' + Number(s.grand_total_gov).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                // Update charts if present
                if (govChartInstance && s.total_sss !== undefined) {
                    govChartInstance.data.datasets[0].data = [s.total_sss, s.total_philhealth, s.total_pagibig];
                    govChartInstance.update();
                }

                if (benefitChartInstance && res.benefit_types) {
                    const keys = Object.keys(res.benefit_types);
                    if (keys.length) {
                        benefitChartInstance.data.labels = keys;
                        benefitChartInstance.data.datasets[0].data = keys.map(k => res.benefit_types[k].amount || 0);
                        benefitChartInstance.update();
                    }
                }

            } catch (err) {
                console.warn('Real-time benefits sync paused:', err.message);
            }
        }

        function triggerManualSync() {
            const btn = document.getElementById('last_sync_time');
            if (btn) btn.textContent = 'syncing...';
            fetchRealtimeBenefits();
        }

        // Auto poll every 25 seconds
        setInterval(fetchRealtimeBenefits, 25000);

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
        });
    </script>

@endsection
