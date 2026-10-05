@extends('layouts.app')

@section('title', 'Incentives & Bonuses - Alibaton Construction Inc.')

@section('styles')
<style>
    .print-only {
        display: none !important;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        color: #111111;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-subtitle {
        margin: 6px 0 0;
        color: #666666;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #F4C400;
        color: #111111;
        padding: 10px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: 0.2s ease;
        white-space: nowrap;
    }

    .btn-add:hover {
        background: #DCAE00;
        transform: translateY(-1px);
    }

    .btn-add.dark {
        background: #111111;
        color: #ffffff;
    }

    .btn-add.dark:hover {
        background: #2a2a2a;
    }

    .btn-add.outline {
        background: #ffffff;
        color: #333333;
        border: 1px solid #cccccc;
    }

    .btn-add.outline:hover {
        background: #f9f9f9;
        border-color: #999999;
    }

    /* Live status badge */
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

    /* KPI Summary Grids */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .financial-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        border-left: 4px solid #F4C400;
        transition: 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 14px rgba(0, 0, 0, 0.07);
    }

    .stat-card.success {
        border-left-color: #2e7d32;
    }
    .stat-card.success .stat-value {
        color: #2e7d32;
    }

    .stat-card.warning {
        border-left-color: #DCAE00;
    }
    .stat-card.warning .stat-value {
        color: #b45309;
    }

    .stat-card.danger {
        border-left-color: #c62828;
    }
    .stat-card.danger .stat-value {
        color: #c62828;
    }

    .stat-card.dark {
        background: #111111;
        border-left-color: #F4C400;
    }
    .stat-card.dark .stat-label,
    .stat-card.dark .stat-note {
        color: #cccccc;
    }
    .stat-card.dark .stat-value {
        color: #F4C400;
    }

    .stat-label {
        color: #777777;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 9px;
    }

    .stat-value {
        color: #111111;
        font-size: 26px;
        font-weight: 800;
        line-height: 1.2;
    }

    .amount {
        font-family: Consolas, monospace;
    }

    /* Charts */
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

    /* Records Table */
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

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
        text-transform: capitalize;
    }

    .status-approved { background: #e8f5e9; color: #2e7d32; }
    .status-pending  { background: #fff8e1; color: #8a6d00; }
    .status-cancelled{ background: #ffebee; color: #c62828; }

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
    .btn-delete:hover { background: #dc2626; }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #777777;
    }

    /* Formal Print / PDF Styles */
    @media print {
        @page { size: A4 portrait; margin: 10mm 12mm 12mm 12mm; }

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
        .toolbar-card, .live-status-pill, .page-header, .stats-grid, .financial-grid, .charts-grid,
        .actions, th.no-print, td.no-print, .no-print, .alert, .btn-add {
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

        .print-only { display: block !important; }

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

        .company-sub { font-size: 8pt !important; color: #444444 !important; margin: 0; }

        .doc-type-title {
            font-size: 12pt !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            color: #000000 !important;
            margin: 0 0 4pt 0;
        }

        .doc-meta { font-size: 7.5pt !important; color: #444444 !important; line-height: 1.4; }

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

        .formal-summary-box td.val { font-weight: 700 !important; text-align: right !important; }

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

        .sig-col { width: 30%; text-align: center; }
        .sig-caption { font-size: 7.5pt !important; color: #444444 !important; margin-bottom: 35pt; text-align: left; text-transform: uppercase; font-weight: 600; }
        .sig-line { border-bottom: 1pt solid #000000 !important; margin-bottom: 3pt; }
        .sig-name { font-weight: 800 !important; font-size: 8.5pt !important; text-transform: uppercase; }
        .sig-designation { font-size: 7pt !important; color: #444444 !important; }
        .formal-footer-note { display: block !important; text-align: center; font-size: 6.5pt !important; color: #777777 !important; margin-top: 20pt; border-top: 0.5pt solid #cccccc; padding-top: 4pt; }
    }
</style>
@endsection

@section('content')

    <!-- FORMAL PRINT-ONLY DOCUMENT HEADER -->
    <div class="print-only formal-letterhead">
        <div>
            <h1 class="company-name">Alibaton Construction Inc.</h1>
            <p class="company-sub">
                Human Resources &amp; Corporate Compensation Division<br>
                Km. 12 Sasa, Davao City, Davao del Sur, Philippines
            </p>
        </div>
        <div style="text-align:right;">
            <h2 class="doc-type-title">Incentives &amp; Performance Bonus Audit</h2>
            <div class="doc-meta">
                <div><strong>Ref:</strong> ACI-INC-{{ date('Ymd') }}</div>
                <div><strong>Generated:</strong> {{ date('F d, Y - h:i A') }}</div>
                <div><strong>Auditor:</strong> {{ Auth::user()->name ?? 'Compensation Officer' }}</div>
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
                        EXECUTIVE SUMMARY &amp; INCENTIVES AUDIT TOTALS
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:25%;"><strong>Total Incentives Recorded:</strong></td>
                    <td class="val" style="width:25%;">{{ $totalIncentives ?? 0 }}</td>
                    <td style="width:25%;"><strong>Approved Incentives:</strong></td>
                    <td class="val" style="width:25%;">{{ $approvedIncentives ?? 0 }}</td>
                </tr>
                <tr>
                    <td><strong>Pending Incentives:</strong></td>
                    <td class="val">{{ $pendingIncentives ?? 0 }}</td>
                    <td><strong>Rejected / Cancelled:</strong></td>
                    <td class="val">{{ $rejectedIncentives ?? 0 }}</td>
                </tr>
                <tr>
                    <td><strong>Total Incentive Amount:</strong></td>
                    <td class="val">₱{{ number_format($totalIncentiveAmount ?? 0, 2) }}</td>
                    <td><strong>Total Approved Amount:</strong></td>
                    <td class="val">₱{{ number_format($approvedIncentiveAmount ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- SCREEN PAGE HEADER -->
    <div class="page-header no-print">
        <div>
            <h1 class="page-title">
                Incentives &amp; Bonuses
            </h1>
            <p class="page-subtitle">
                Monitor employee bonuses, driver trip commissions, performance rewards, and payroll approval status.
            </p>
        </div>

        <div class="header-actions">
            <!-- Real-time status badge -->
            <div class="live-status-pill" title="Auto-polling real-time updates every 25 seconds">
                <span class="pulse-dot"></span>
                <span id="live_status_text"><strong>Live Sync:</strong> Active</span>
                <span id="last_sync_time" style="color:#888; font-size:11px;">just now</span>
                <button type="button" onclick="triggerManualSync()" style="background:none; border:none; cursor:pointer; color:#111; font-weight:bold; padding:0 2px;" title="Refresh now">
                    ↻
                </button>
            </div>

            <a href="{{ url('/incentives/driver-trips') }}" class="btn-add dark">
                🚚 Driver Trip Incentives
            </a>

            <button type="button" onclick="window.print()" class="btn-add outline">
                🖨 Print Official Audit
            </button>

            <a href="{{ url('/incentives/create') }}" class="btn-add">
                + Add Incentive
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
    <div class="stats-grid no-print">
        <div class="stat-card">
            <div class="stat-label">Total Incentives</div>
            <div class="stat-value" id="kpi_total_incentives">{{ $totalIncentives ?? 0 }}</div>
        </div>

        <div class="stat-card success">
            <div class="stat-label">Approved</div>
            <div class="stat-value" id="kpi_approved_incentives">{{ $approvedIncentives ?? 0 }}</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-label">Pending Approval</div>
            <div class="stat-value" id="kpi_pending_incentives">{{ $pendingIncentives ?? 0 }}</div>
        </div>

        <div class="stat-card danger">
            <div class="stat-label">Cancelled / Rejected</div>
            <div class="stat-value" id="kpi_rejected_incentives">{{ $rejectedIncentives ?? 0 }}</div>
        </div>
    </div>

    <!-- FINANCIAL AUDIT CARDS -->
    <div class="financial-grid no-print">
        <div class="stat-card dark">
            <div class="stat-label">Total Incentive Amount</div>
            <div class="stat-value amount" id="kpi_total_amount">₱{{ number_format($totalIncentiveAmount ?? 0, 2) }}</div>
        </div>

        <div class="stat-card success">
            <div class="stat-label">Approved Payout</div>
            <div class="stat-value amount" id="kpi_approved_amount">₱{{ number_format($approvedIncentiveAmount ?? 0, 2) }}</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-label">Pending Payout</div>
            <div class="stat-value amount" id="kpi_pending_amount">₱{{ number_format($pendingIncentiveAmount ?? 0, 2) }}</div>
        </div>
    </div>

    <!-- INTERACTIVE VISUAL DASHBOARDS (CHART.JS) -->
    <div class="charts-grid no-print">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3 class="chart-title">Incentive Allocation by Type</h3>
                    <p class="chart-subtitle">Distribution across performance, trip, and attendance bonuses</p>
                </div>
            </div>
            <div class="chart-wrapper">
                <canvas id="incentiveTypesChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3 class="chart-title">Approval Status Breakdown</h3>
                    <p class="chart-subtitle">Approved vs Pending vs Cancelled ratio</p>
                </div>
            </div>
            <div class="chart-wrapper">
                <canvas id="statusBreakdownChart"></canvas>
            </div>
        </div>
    </div>

    <!-- LIVE SEARCH & FILTER TOOLBAR -->
    <div class="toolbar-card no-print">
        <div class="search-input-group">
            <span class="search-icon">🔍</span>
            <input type="text" id="search_incentives" placeholder="Search by Employee ID, Type, Reference, or Approver..." oninput="filterIncentivesTable()">
        </div>

        <select id="filter_status" class="toolbar-select" onchange="filterIncentivesTable()">
            <option value="">All Statuses</option>
            <option value="approved">Approved</option>
            <option value="pending">Pending</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <input type="month" id="filter_period" class="toolbar-select" onchange="filterIncentivesTable()" title="Filter by payroll period">

        <button type="button" onclick="resetIncentivesFilters()" class="btn-add outline" style="padding:8px 12px; font-size:12px;">
            Reset
        </button>

        <div style="margin-left:auto; font-size:12px; color:#777; font-weight:600;" id="incentives_count_indicator">
            Showing {{ count($incentives ?? []) }} record(s)
        </div>
    </div>

    <!-- INCENTIVE RECORDS TABLE -->
    <div class="records-card">
        <div class="records-header no-print">
            <div>
                <h2 class="records-title">Incentive Audit Ledger</h2>
                <p class="records-subtitle">Detailed record of individual employee incentives and payroll integration.</p>
            </div>
        </div>

        @if(count($incentives ?? []) > 0)
            <table class="records-table" id="table_incentives">
                <thead>
                    <tr>
                        <th style="width:7%;">ID</th>
                        <th style="width:14%;">Employee ID</th>
                        <th style="width:18%;">Incentive Type</th>
                        <th style="width:20%;">Description / Reference</th>
                        <th style="width:13%; text-align:right;">Amount (₱)</th>
                        <th style="width:10%;">Date</th>
                        <th style="width:10%;">Status</th>
                        <th class="no-print" style="width:8%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incentives as $item)
                        @php
                            $status = strtolower(trim($item['status'] ?? 'pending'));
                            $period = !empty($item['payroll_period']) ? date('Y-m', strtotime($item['payroll_period'])) : '';
                        @endphp
                        <tr data-empid="{{ strtolower($item['employee_id'] ?? '') }}"
                            data-type="{{ strtolower($item['incentive_type'] ?? '') }}"
                            data-desc="{{ strtolower($item['description'] ?? '') }}"
                            data-approver="{{ strtolower($item['approved_by'] ?? '') }}"
                            data-ref="{{ strtolower($item['trip_reference'] ?? '') }}"
                            data-status="{{ $status }}"
                            data-period="{{ $period }}"
                            data-amount="{{ (float)($item['amount'] ?? 0) }}">
                            <td><strong>#{{ $item['id'] }}</strong></td>
                            <td><strong>{{ $item['employee_id'] }}</strong></td>
                            <td>
                                <strong>{{ $item['incentive_type'] }}</strong>
                                @if(!empty($item['trip_reference']))
                                    <div style="font-size:11px; color:#2563eb;">Ref: {{ $item['trip_reference'] }}</div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $item['description'] ?: 'No notes provided' }}</div>
                                @if(!empty($item['approved_by']))
                                    <div style="font-size:11px; color:#777;">Approved By: {{ $item['approved_by'] }}</div>
                                @endif
                            </td>
                            <td class="amount" style="text-align:right;">₱{{ number_format((float)($item['amount'] ?? 0), 2) }}</td>
                            <td>{{ !empty($item['incentive_date']) ? date('M d, Y', strtotime($item['incentive_date'])) : '—' }}</td>
                            <td>
                                <span class="status-badge status-{{ $status }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="no-print">
                                <a href="{{ url('/incentives/' . $item['id'] . '/edit') }}" class="btn-edit">Edit</a>
                                <form action="{{ url('/incentives/' . $item['id']) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this incentive?');">
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
                <h3>No incentive records found</h3>
                <p>Click "Add Incentive" or import completed trips from "Driver Trip Incentives".</p>
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
            <div class="sig-caption">Verified By:</div>
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
        CONFIDENTIAL INCENTIVE PAYOUT AUDIT &bull; ALIBATON CONSTRUCTION INC. &bull; AUTOMATED ENTERPRISE PAYROLL SYSTEM
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        // ==========================================
        // 1. LIVE SEARCH & FILTERING
        // ==========================================
        function filterIncentivesTable() {
            const query = (document.getElementById('search_incentives').value || '').trim().toLowerCase();
            const statusFilter = (document.getElementById('filter_status').value || '').trim().toLowerCase();
            const periodFilter = (document.getElementById('filter_period').value || '').trim();

            const rows = document.querySelectorAll('#table_incentives tbody tr');
            let visible = 0;

            rows.forEach(r => {
                const empid = r.getAttribute('data-empid') || '';
                const type = r.getAttribute('data-type') || '';
                const desc = r.getAttribute('data-desc') || '';
                const approver = r.getAttribute('data-approver') || '';
                const ref = r.getAttribute('data-ref') || '';
                const status = r.getAttribute('data-status') || '';
                const period = r.getAttribute('data-period') || '';

                const matchesQuery = !query || empid.includes(query) || type.includes(query) || desc.includes(query) || approver.includes(query) || ref.includes(query);
                const matchesStatus = !statusFilter || status === statusFilter;
                const matchesPeriod = !periodFilter || period === periodFilter;

                if (matchesQuery && matchesStatus && matchesPeriod) {
                    r.style.display = '';
                    visible++;
                } else {
                    r.style.display = 'none';
                }
            });

            const countEl = document.getElementById('incentives_count_indicator');
            if (countEl) countEl.textContent = `Showing ${visible} of ${rows.length} record(s)`;
        }

        function resetIncentivesFilters() {
            document.getElementById('search_incentives').value = '';
            document.getElementById('filter_status').value = '';
            document.getElementById('filter_period').value = '';
            filterIncentivesTable();
        }

        // ==========================================
        // 2. CHART.JS VISUALIZATIONS
        // ==========================================
        let typesChartInstance = null;
        let statusChartInstance = null;

        function initCharts() {
            if (typeof Chart === 'undefined') return;

            const breakdown = @json($incentiveTypeBreakdown ?? []);
            const typeLabels = breakdown.map(b => b.type);
            const typeAmounts = breakdown.map(b => parseFloat(b.amount || 0));

            const ctx1 = document.getElementById('incentiveTypesChart');
            if (ctx1) {
                typesChartInstance = new Chart(ctx1, {
                    type: 'doughnut',
                    data: {
                        labels: typeLabels.length ? typeLabels : ['No Data'],
                        datasets: [{
                            data: typeAmounts.length ? typeAmounts : [1],
                            backgroundColor: ['#F4C400', '#111111', '#2563eb', '#10b981', '#f59e0b', '#8b5cf6'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'right', labels: { boxWidth: 12, font: { size: 11 } } },
                            tooltip: {
                                callbacks: {
                                    label: function(i) {
                                        return ' ' + i.label + ': ₱' + Number(i.raw).toLocaleString('en-US', {minimumFractionDigits: 2});
                                    }
                                }
                            }
                        }
                    }
                });
            }

            const approved = {{ $approvedIncentives ?? 0 }};
            const pending = {{ $pendingIncentives ?? 0 }};
            const rejected = {{ $rejectedIncentives ?? 0 }};

            const ctx2 = document.getElementById('statusBreakdownChart');
            if (ctx2) {
                statusChartInstance = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: ['Approved', 'Pending', 'Cancelled'],
                        datasets: [{
                            label: 'Count',
                            data: [approved, pending, rejected],
                            backgroundColor: ['#2e7d32', '#DCAE00', '#c62828'],
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
        }

        // ==========================================
        // 3. REAL-TIME AUTO POLLING (EVERY 25s)
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

        async function fetchRealtimeIncentives() {
            try {
                const response = await fetch('/incentives/data', {
                    headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) return;
                const res = await response.json();
                if (!res.success || !res.stats) return;

                const s = res.stats;
                lastSyncDate = new Date();
                updateSyncTimer();

                const elTotal = document.getElementById('kpi_total_incentives');
                if (elTotal) elTotal.textContent = s.total;

                const elAppr = document.getElementById('kpi_approved_incentives');
                if (elAppr) elAppr.textContent = s.approved_count;

                const elPend = document.getElementById('kpi_pending_incentives');
                if (elPend) elPend.textContent = s.pending_count;

                const elRej = document.getElementById('kpi_rejected_incentives');
                if (elRej) elRej.textContent = s.rejected_count;

                const elTotAmt = document.getElementById('kpi_total_amount');
                if (elTotAmt) elTotAmt.textContent = '₱' + Number(s.total_amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                const elApprAmt = document.getElementById('kpi_approved_amount');
                if (elApprAmt) elApprAmt.textContent = '₱' + Number(s.approved_amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                const elPendAmt = document.getElementById('kpi_pending_amount');
                if (elPendAmt) elPendAmt.textContent = '₱' + Number(s.pending_amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                // Update status chart
                if (statusChartInstance) {
                    statusChartInstance.data.datasets[0].data = [s.approved_count, s.pending_count, s.rejected_count];
                    statusChartInstance.update();
                }

                // Update type chart
                if (typesChartInstance && res.types) {
                    const keys = Object.keys(res.types);
                    if (keys.length) {
                        typesChartInstance.data.labels = keys;
                        typesChartInstance.data.datasets[0].data = keys.map(k => res.types[k].amount || 0);
                        typesChartInstance.update();
                    }
                }
            } catch (e) {
                console.warn('Incentives polling paused:', e.message);
            }
        }

        function triggerManualSync() {
            const btn = document.getElementById('last_sync_time');
            if (btn) btn.textContent = 'syncing...';
            fetchRealtimeIncentives();
        }

        setInterval(fetchRealtimeIncentives, 25000);

        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
        });
    </script>

@endsection
