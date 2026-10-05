@extends('layouts.app')

@section('title', 'Driver Trip Incentive Computation - Alibaton Construction Inc.')

@section('styles')
<style>
    .print-only {
        display: none !important;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
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
        color: #ffffff;
    }

    .btn-action.dark:hover {
        background: #2a2a2a;
    }

    .btn-action.outline {
        background: #ffffff;
        color: #333333;
        border: 1px solid #cccccc;
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

    .alert-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .alert-error   { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
    .alert-warning { background: #fff8e1; color: #8a6d00; border: 1px solid #ffe082; }

    /* KPI Summary Cards */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 20px;
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

    .summary-card.success { border-left-color: #2e7d32; }
    .summary-card.success .summary-value { color: #2e7d32; }

    .summary-card.dark {
        background: #111111;
        border-left-color: #F4C400;
    }
    .summary-card.dark .summary-label,
    .summary-card.dark .summary-note { color: #cccccc; }
    .summary-card.dark .summary-value { color: #F4C400; }

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

    /* Smart Rate Computation Calculator Panel */
    .calc-panel {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .calc-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 12px;
        margin-bottom: 16px;
    }

    .calc-title {
        font-size: 16px;
        font-weight: 800;
        color: #111111;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .calc-body {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 16px;
        align-items: center;
    }

    .preset-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .preset-btn {
        padding: 7px 12px;
        border: 1px solid #d5d5d5;
        border-radius: 6px;
        background: #fafafa;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.15s ease;
    }

    .preset-btn.active {
        background: #F4C400;
        border-color: #DCAE00;
        color: #111111;
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

    /* Table */
    .records-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 25px;
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
        vertical-align: top;
    }

    .records-table tbody tr:hover {
        background: #fffdf0;
    }

    .badge-linked {
        display: inline-block;
        padding: 4px 10px;
        background: #e8f5e9;
        color: #2e7d32;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
    }

    .badge-new {
        display: inline-block;
        padding: 4px 10px;
        background: #fff8e1;
        color: #8a6d00;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
    }

    /* Inline smart calculation form */
    .smart-form {
        background: #fafafa;
        border: 1px solid #e0e0e0;
        border-left: 4px solid #F4C400;
        border-radius: 8px;
        padding: 16px;
        margin-top: 10px;
    }

    .calc-preview-box {
        background: #ffffff;
        border: 1px dashed #d5d5d5;
        border-radius: 6px;
        padding: 10px 14px;
        margin: 10px 0 14px 0;
        font-size: 12px;
        color: #333333;
    }

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
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .sidebar, .topbar, .layout > .sidebar, .user-area, .header-actions,
        .toolbar-card, .live-status-pill, .page-header, .summary-grid, .calc-panel,
        th.no-print, td.no-print, .no-print, .alert, .btn-action, .smart-form {
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
                Fleet Logistics &amp; Driver Compensation Division<br>
                Km. 12 Sasa, Davao City, Davao del Sur, Philippines
            </p>
        </div>
        <div style="text-align:right;">
            <h2 class="doc-type-title">Driver Trip Incentive Computation Schedule</h2>
            <div class="doc-meta">
                <div><strong>Ref:</strong> ACI-FLT-{{ date('Ymd') }}</div>
                <div><strong>Generated:</strong> {{ date('F d, Y - h:i A') }}</div>
                <div><strong>Auditor:</strong> {{ Auth::user()->name ?? 'Fleet Dispatcher' }}</div>
                <div><strong>Classification:</strong> OFFICIAL FLEET PAYROLL RECORD</div>
            </div>
        </div>
    </div>

    <!-- FORMAL PRINT-ONLY SUMMARY TABLE -->
    @php
        $totalDistanceSum = 0;
        $eligibleCount = 0;
        $linkedCount = 0;
        foreach ($trips ?? [] as $t) {
            $totalDistanceSum += (float)($t['distance_km'] ?? 0);
            if ($t['already_linked'] ?? false) {
                $linkedCount++;
            } else {
                $eligibleCount++;
            }
        }
    @endphp
    <div class="print-only">
        <table class="formal-summary-box">
            <thead>
                <tr>
                    <th colspan="4" style="background:#000000 !important; color:#ffffff !important; text-align:center;">
                        FLEET TRIP INCENTIVE AUDIT SUMMARY
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:25%;"><strong>Total Completed Trips:</strong></td>
                    <td class="val" style="width:25%;">{{ count($trips ?? []) }}</td>
                    <td style="width:25%;"><strong>Eligible For Incentive:</strong></td>
                    <td class="val" style="width:25%;">{{ $eligibleCount }}</td>
                </tr>
                <tr>
                    <td><strong>Incentive Recorded Trips:</strong></td>
                    <td class="val">{{ $linkedCount }}</td>
                    <td><strong>Total Fleet Distance:</strong></td>
                    <td class="val">{{ number_format($totalDistanceSum, 1) }} km</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- PAGE HEADER (SCREEN ONLY) -->
    <div class="page-header no-print">
        <div>
            <h1 class="page-title">
                Driver Trip Incentive Computation
            </h1>
            <p class="page-subtitle">
                Automated rate-based incentive computation for completed trips imported from Fleet Logistics (microfleet).
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

            <a href="{{ url('/incentives') }}" class="btn-action outline">
                ← Back to Incentives
            </a>

            <button type="button" onclick="window.print()" class="btn-action outline">
                🖨 Print Official Audit
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success no-print">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error no-print">{{ session('error') }}</div>
    @endif
    @if(isset($error) && $error)
        <div class="alert alert-warning no-print">
            ⚠️ {{ $error }}
        </div>
    @endif

    <!-- REAL-TIME KPI MONITORING CARDS -->
    <div class="summary-grid no-print">
        <div class="summary-card">
            <div class="summary-label">Total Completed Trips</div>
            <div class="summary-value" id="kpi_total_trips">{{ count($trips ?? []) }}</div>
            <div class="summary-note">From fleet logistics</div>
        </div>

        <div class="summary-card success">
            <div class="summary-label">Eligible For Incentive</div>
            <div class="summary-value" id="kpi_eligible_trips">{{ $eligibleCount }}</div>
            <div class="summary-note">Pending creation</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Incentives Recorded</div>
            <div class="summary-value" id="kpi_linked_trips">{{ $linkedCount }}</div>
            <div class="summary-note">Integrated to payroll</div>
        </div>

        <div class="summary-card dark">
            <div class="summary-label">Total Distance Driven</div>
            <div class="summary-value" id="kpi_total_dist">{{ number_format($totalDistanceSum, 1) }} km</div>
            <div class="summary-note">Cumulative fleet haul</div>
        </div>
    </div>

    <!-- SMART RATE COMPUTATION ENGINE PANEL -->
    <div class="calc-panel no-print">
        <div class="calc-header">
            <h3 class="calc-title">
                ⚡ Automated Incentive Computation Engine
            </h3>
            <span style="font-size:12px; color:#666;">
                Select rate formula to auto-calculate trip incentives instantly
            </span>
        </div>

        <div class="calc-body">
            <div>
                <label style="font-size:12px; font-weight:700; color:#555; display:block; margin-bottom:6px;">Rate Computation Presets:</label>
                <div class="preset-buttons">
                    <button type="button" class="preset-btn active" id="preset_std" onclick="setRatePreset('standard', 150, 5.00)">
                        🚚 Standard Haul (₱150 + ₱5/km)
                    </button>
                    <button type="button" class="preset-btn" id="preset_long" onclick="setRatePreset('long', 300, 7.50)">
                        🚛 Provincial / Long Haul (₱300 + ₱7.50/km)
                    </button>
                    <button type="button" class="preset-btn" id="preset_heavy" onclick="setRatePreset('heavy', 500, 10.00)">
                        🏗️ Heavy Equipment (₱500 + ₱10/km)
                    </button>
                </div>
            </div>

            <div>
                <label style="font-size:12px; font-weight:700; color:#555; display:block; margin-bottom:6px;">Base Trip Allowance (₱):</label>
                <input type="number" id="calc_base_rate" value="150" step="10" min="0" oninput="updateActiveRates()" style="width:100%; padding:8px 10px; border:1px solid #ccc; border-radius:6px; font-weight:700;">
            </div>

            <div>
                <label style="font-size:12px; font-weight:700; color:#555; display:block; margin-bottom:6px;">Rate Per Kilometer (₱/km):</label>
                <input type="number" id="calc_km_rate" value="5.00" step="0.50" min="0" oninput="updateActiveRates()" style="width:100%; padding:8px 10px; border:1px solid #ccc; border-radius:6px; font-weight:700;">
            </div>
        </div>
    </div>

    <!-- LIVE SEARCH & FILTER TOOLBAR -->
    <div class="toolbar-card no-print">
        <div class="search-input-group">
            <span class="search-icon">🔍</span>
            <input type="text" id="search_trips" placeholder="Search by Trip #, Driver Fleet ID, License, or Distance..." oninput="filterTripsTable()">
        </div>

        <select id="filter_eligibility" class="toolbar-select" onchange="filterTripsTable()">
            <option value="">All Trip Records</option>
            <option value="eligible">Eligible Only (Not Yet Recorded)</option>
            <option value="recorded">Already Recorded</option>
        </select>

        <button type="button" onclick="document.getElementById('search_trips').value=''; document.getElementById('filter_eligibility').value=''; filterTripsTable();" class="btn-action outline" style="padding:8px 12px; font-size:12px;">
            Reset
        </button>

        <div style="margin-left:auto; font-size:12px; color:#777; font-weight:600;" id="trips_count_indicator">
            Showing {{ count($trips ?? []) }} completed trip(s)
        </div>
    </div>

    <!-- TRIPS LEDGER TABLE -->
    <div class="records-card">
        @if(count($trips ?? []) > 0)
            <table class="records-table" id="table_trips">
                <thead>
                    <tr>
                        <th style="width:14%;">Trip Number</th>
                        <th style="width:20%;">Driver (Fleet ID)</th>
                        <th style="width:14%;">Trip Distance</th>
                        <th style="width:16%;">Departed At</th>
                        <th style="width:16%;">Arrived At</th>
                        <th style="width:10%;">Status</th>
                        <th class="no-print" style="width:10%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trips as $trip)
                        @php
                            $dist = (float)($trip['distance_km'] ?? 0);
                            $isLinked = $trip['already_linked'] ?? false;
                        @endphp
                        <tr id="row-{{ $trip['trip_id'] }}"
                            data-tripno="{{ strtolower($trip['trip_number'] ?? '') }}"
                            data-driver="{{ strtolower($trip['employee_number'] ?? '') }}"
                            data-license="{{ strtolower($trip['license_number'] ?? '') }}"
                            data-dist="{{ $dist }}"
                            data-linked="{{ $isLinked ? '1' : '0' }}">
                            <td>
                                <strong>{{ $trip['trip_number'] ?? '-' }}</strong>
                            </td>
                            <td>
                                <strong>{{ $trip['employee_number'] ?? '—' }}</strong>
                                @if(!empty($trip['license_number']))
                                    <div style="font-size:11px; color:#666;">Lic: {{ $trip['license_number'] }}</div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $trip['distance_km'] !== null ? number_format($dist, 1) . ' km' : '—' }}</strong>
                            </td>
                            <td>{{ !empty($trip['departed_at']) ? date('M d, Y h:i A', strtotime($trip['departed_at'])) : '—' }}</td>
                            <td>{{ !empty($trip['arrived_at']) ? date('M d, Y h:i A', strtotime($trip['arrived_at'])) : '—' }}</td>
                            <td>
                                @if($isLinked)
                                    <span class="badge-linked">✓ Incentive Recorded</span>
                                @else
                                    <span class="badge-new">Eligible</span>
                                @endif
                            </td>
                            <td class="no-print">
                                @if($isLinked)
                                    <span style="font-size:12px; color:#666;">Recorded</span>
                                @else
                                    <button type="button" class="btn-action" style="padding:6px 12px; font-size:12px;"
                                        onclick="openSmartIncentiveForm({{ $trip['trip_id'] }}, '{{ addslashes($trip['trip_number'] ?? '') }}', '{{ addslashes($trip['employee_number'] ?? '') }}', {{ $dist }})">
                                        ⚡ Auto-Compute
                                    </button>
                                @endif

                                <!-- INLINE SMART COMPUTATION FORM -->
                                <div id="form-{{ $trip['trip_id'] }}" style="display:none;" class="smart-form">
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                                        <h4 style="margin:0; font-size:14px; font-weight:800;">
                                            Trip Incentive Computation — {{ $trip['trip_number'] ?? '' }}
                                        </h4>
                                        <button type="button" onclick="closeIncentiveForm({{ $trip['trip_id'] }})" style="background:none; border:none; cursor:pointer; font-weight:bold; font-size:16px;">&times;</button>
                                    </div>

                                    <!-- LIVE COMPUTATION BREAKDOWN BADGE -->
                                    <div class="calc-preview-box" id="calc_preview_{{ $trip['trip_id'] }}">
                                        Calculating rate...
                                    </div>

                                    <form action="{{ url('/incentives') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="incentive_type" value="Driver Trip Incentive">
                                        <input type="hidden" name="trip_id" value="{{ $trip['trip_id'] }}">
                                        <input type="hidden" name="trip_reference" value="{{ $trip['trip_number'] ?? '' }}">

                                        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:10px; margin-bottom:10px;">
                                            <div>
                                                <label style="font-size:11px; font-weight:bold; display:block; margin-bottom:4px;">Payroll Employee ID *</label>
                                                <select name="employee_id" required style="width:100%; padding:7px; border:1px solid #ccc; border-radius:5px; font-size:12px;">
                                                    <option value="">-- Choose Employee --</option>
                                                    @foreach($employees as $empId => $emp)
                                                        @php
                                                            $isMatch = ($empId === ($trip['employee_number'] ?? ''));
                                                        @endphp
                                                        <option value="{{ $empId }}" {{ $isMatch ? 'selected' : '' }}>
                                                            {{ $empId }} — {{ ($emp['first_name'] ?? '') }} {{ ($emp['last_name'] ?? '') }} ({{ $emp['department'] ?? 'Fleet' }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label style="font-size:11px; font-weight:bold; display:block; margin-bottom:4px;">Computed Incentive (₱) *</label>
                                                <input type="number" step="0.01" min="0" name="amount" id="amount_{{ $trip['trip_id'] }}" required style="width:100%; padding:7px; border:1px solid #ccc; border-radius:5px; font-size:13px; font-weight:bold; color:#111;">
                                            </div>

                                            <div>
                                                <label style="font-size:11px; font-weight:bold; display:block; margin-bottom:4px;">Incentive Date *</label>
                                                <input type="date" name="incentive_date" value="{{ date('Y-m-d') }}" required style="width:100%; padding:7px; border:1px solid #ccc; border-radius:5px; font-size:12px;">
                                            </div>
                                        </div>

                                        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:10px; margin-bottom:10px;">
                                            <div>
                                                <label style="font-size:11px; font-weight:bold; display:block; margin-bottom:4px;">Payroll Period</label>
                                                <input type="month" name="payroll_period" value="{{ date('Y-m') }}" style="width:100%; padding:7px; border:1px solid #ccc; border-radius:5px; font-size:12px;">
                                            </div>
                                            <div>
                                                <label style="font-size:11px; font-weight:bold; display:block; margin-bottom:4px;">Status</label>
                                                <select name="status" style="width:100%; padding:7px; border:1px solid #ccc; border-radius:5px; font-size:12px;">
                                                    <option value="approved">Approved</option>
                                                    <option value="pending">Pending</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label style="font-size:11px; font-weight:bold; display:block; margin-bottom:4px;">Approved By</label>
                                                <input type="text" name="approved_by" value="{{ Auth::user()->name ?? 'Fleet Supervisor' }}" style="width:100%; padding:7px; border:1px solid #ccc; border-radius:5px; font-size:12px;">
                                            </div>
                                        </div>

                                        <div style="margin-bottom:12px;">
                                            <label style="font-size:11px; font-weight:bold; display:block; margin-bottom:4px;">Notes / Description</label>
                                            <input type="text" name="description" id="desc_{{ $trip['trip_id'] }}"
                                                value="Driver trip incentive for trip {{ $trip['trip_number'] ?? '' }} (Fleet ID: {{ $trip['employee_number'] ?? '' }}), {{ number_format($dist, 1) }} km"
                                                style="width:100%; padding:7px; border:1px solid #ccc; border-radius:5px; font-size:12px;">
                                        </div>

                                        <div style="display:flex; justify-content:flex-end; gap:8px;">
                                            <button type="button" class="btn-action outline" style="padding:7px 12px; font-size:12px;" onclick="closeIncentiveForm({{ $trip['trip_id'] }})">Cancel</button>
                                            <button type="submit" class="btn-action" style="padding:7px 16px; font-size:12px;">✓ Confirm &amp; Save Incentive</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <h3>No completed trips found</h3>
                <p>
                    @if(isset($error) && $error)
                        Could not connect to the microfleet database.
                    @else
                        There are no completed trips in the fleet system yet.
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- FORMAL SIGNATORIES (PRINT ONLY) -->
    <div class="print-only formal-signatories">
        <div class="sig-col">
            <div class="sig-caption">Computed By:</div>
            <div class="sig-line"></div>
            <div class="sig-name">{{ Auth::user()->name ?? 'Fleet Dispatcher' }}</div>
            <div class="sig-designation">Fleet Operations Officer</div>
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
        CONFIDENTIAL DRIVER TRIP INCENTIVE AUDIT &bull; ALIBATON CONSTRUCTION INC. &bull; AUTOMATED ENTERPRISE PAYROLL SYSTEM
    </div>

    <script>
        // Active calculation formula
        let currentBaseRate = 150.00;
        let currentKmRate = 5.00;

        function setRatePreset(type, base, km) {
            currentBaseRate = base;
            currentKmRate = km;
            document.getElementById('calc_base_rate').value = base;
            document.getElementById('calc_km_rate').value = km.toFixed(2);

            document.querySelectorAll('.preset-btn').forEach(btn => btn.classList.remove('active'));
            if (type === 'standard') document.getElementById('preset_std').classList.add('active');
            else if (type === 'long') document.getElementById('preset_long').classList.add('active');
            else if (type === 'heavy') document.getElementById('preset_heavy').classList.add('active');
        }

        function updateActiveRates() {
            currentBaseRate = parseFloat(document.getElementById('calc_base_rate').value) || 0;
            currentKmRate = parseFloat(document.getElementById('calc_km_rate').value) || 0;
            document.querySelectorAll('.preset-btn').forEach(btn => btn.classList.remove('active'));
        }

        function openSmartIncentiveForm(tripId, tripNumber, driverNumber, distanceKm) {
            // Close other forms
            document.querySelectorAll('[id^="form-"]').forEach(el => el.style.display = 'none');

            const formEl = document.getElementById('form-' + tripId);
            if (!formEl) return;

            // Compute automated incentive
            const kmAmount = distanceKm * currentKmRate;
            const totalAmount = currentBaseRate + kmAmount;

            // Set amount input
            const amountInput = document.getElementById('amount_' + tripId);
            if (amountInput) amountInput.value = totalAmount.toFixed(2);

            // Update preview box
            const previewEl = document.getElementById('calc_preview_' + tripId);
            if (previewEl) {
                previewEl.innerHTML = `<strong>Auto-Computation Breakdown:</strong> Distance: <strong>${distanceKm.toFixed(1)} km</strong> &times; ₱${currentKmRate.toFixed(2)}/km (₱${kmAmount.toFixed(2)}) + Base Allowance ₱${currentBaseRate.toFixed(2)} = <strong style="color:#2e7d32; font-size:13px;">Total Incentive: ₱${totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</strong>`;
            }

            formEl.style.display = 'block';
            formEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function closeIncentiveForm(tripId) {
            const formEl = document.getElementById('form-' + tripId);
            if (formEl) formEl.style.display = 'none';
        }

        // Filter trips table
        function filterTripsTable() {
            const query = (document.getElementById('search_trips').value || '').trim().toLowerCase();
            const eligFilter = (document.getElementById('filter_eligibility').value || '').trim();
            const rows = document.querySelectorAll('#table_trips tbody tr');
            let visible = 0;

            rows.forEach(r => {
                const tripno = r.getAttribute('data-tripno') || '';
                const driver = r.getAttribute('data-driver') || '';
                const lic = r.getAttribute('data-license') || '';
                const isLinked = r.getAttribute('data-linked') === '1';

                const matchesQuery = !query || tripno.includes(query) || driver.includes(query) || lic.includes(query);
                let matchesElig = true;
                if (eligFilter === 'eligible') matchesElig = !isLinked;
                else if (eligFilter === 'recorded') matchesElig = isLinked;

                if (matchesQuery && matchesElig) {
                    r.style.display = '';
                    visible++;
                } else {
                    r.style.display = 'none';
                }
            });

            const countEl = document.getElementById('trips_count_indicator');
            if (countEl) countEl.textContent = `Showing ${visible} of ${rows.length} completed trip(s)`;
        }

        // Real-time polling
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

        async function fetchRealtimeTrips() {
            try {
                const res = await fetch('/incentives/driver-trips/data', { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();
                if (!data.success || !data.stats) return;

                const s = data.stats;
                lastSyncDate = new Date();
                updateSyncTimer();

                const elTot = document.getElementById('kpi_total_trips');
                if (elTot) elTot.textContent = s.total_trips;

                const elElig = document.getElementById('kpi_eligible_trips');
                if (elElig) elElig.textContent = s.eligible_trips;

                const elLinked = document.getElementById('kpi_linked_trips');
                if (elLinked) elLinked.textContent = s.linked_trips;

                const elDist = document.getElementById('kpi_total_dist');
                if (elDist) elDist.textContent = s.total_distance.toLocaleString() + ' km';
            } catch (e) {
                console.warn('Driver trips sync paused:', e.message);
            }
        }

        function triggerManualSync() {
            const btn = document.getElementById('last_sync_time');
            if (btn) btn.textContent = 'syncing...';
            fetchRealtimeTrips();
        }

        setInterval(fetchRealtimeTrips, 25000);
    </script>

@endsection
