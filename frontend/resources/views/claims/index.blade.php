@extends('layouts.app')

@section('title', 'Claims & Reimbursement - Alibaton Construction Inc.')

@section('styles')
<style>
/* =====================================================
   PRINT-ONLY ELEMENTS (hidden on screen)
===================================================== */
.print-only { display: none !important; }

/* =====================================================
   LAYOUT
===================================================== */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    gap: 20px;
    flex-wrap: wrap;
}

.page-title  { margin: 0; font-size: 28px; font-weight: 700; }
.page-subtitle { margin: 7px 0 0; color: #777; font-size: 14px; }

.header-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

.add-button {
    display: inline-block;
    background: #f4c400;
    color: #111;
    text-decoration: none;
    padding: 11px 18px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: bold;
    white-space: nowrap;
}
.add-button:hover { background: #dcae00; }

.btn-print {
    display: inline-block;
    background: #111;
    color: #fff;
    border: none;
    padding: 11px 18px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    white-space: nowrap;
}
.btn-print:hover { background: #333; }

/* =====================================================
   LIVE SYNC BADGE
===================================================== */
.live-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #111;
    color: #fff;
    padding: 7px 13px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    white-space: nowrap;
}
.live-dot {
    width: 8px; height: 8px;
    background: #4caf50;
    border-radius: 50%;
    animation: pulse 1.5s infinite;
}
.live-dot.error-dot { background: #f44336; animation: none; }
@keyframes pulse {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:.4; transform:scale(1.4); }
}

/* =====================================================
   ALERTS
===================================================== */
.alert {
    padding: 13px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 14px;
}
.alert-success { background:#e8f5e9; color:#256029; border:1px solid #b7dfba; }
.alert-error   { background:#fdecea; color:#a12622; border:1px solid #f1b8b5; }

/* =====================================================
   KPI CARDS
===================================================== */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 14px;
    margin-bottom: 22px;
}
.kpi-card {
    background: #fff;
    border-radius: 10px;
    padding: 18px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border-top: 4px solid #f4c400;
}
.kpi-card.success { border-top-color: #2e7d32; }
.kpi-card.warning { border-top-color: #e6a800; }
.kpi-card.danger  { border-top-color: #c62828; }
.kpi-card.info    { border-top-color: #1565c0; }
.kpi-card.dark    { border-top-color: #111; }
.kpi-label { font-size: 12px; color: #777; margin-bottom: 8px; font-weight: bold; }
.kpi-value { font-size: 24px; font-weight: 700; color: #111; }
.kpi-sub   { margin-top: 6px; font-size: 12px; color: #888; }

.financial-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 22px;
}
.financial-grid .kpi-value { font-size: 18px; }

/* =====================================================
   CHARTS
===================================================== */
.charts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 22px;
}
.chart-card {
    background: #fff;
    border-radius: 10px;
    padding: 22px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}
.chart-title { margin: 0 0 5px; font-size: 16px; font-weight: bold; }
.chart-subtitle { margin: 0 0 18px; font-size: 12px; color: #777; }
.chart-container { position: relative; height: 230px; }

/* =====================================================
   FILTER TOOLBAR
===================================================== */
.filter-toolbar {
    background: #fff;
    border-radius: 10px;
    padding: 18px 22px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    margin-bottom: 22px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
}
.filter-toolbar input,
.filter-toolbar select {
    padding: 9px 13px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 13px;
    font-family: Arial, sans-serif;
    background: #fafafa;
    color: #111;
    flex: 1;
    min-width: 140px;
}
.filter-toolbar input:focus,
.filter-toolbar select:focus {
    outline: none;
    border-color: #f4c400;
    background: #fff;
}
.filter-reset {
    padding: 9px 16px;
    background: #eeeeee;
    color: #333;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: bold;
    cursor: pointer;
    white-space: nowrap;
}
.filter-reset:hover { background: #e0e0e0; }
.filter-count {
    font-size: 13px;
    color: #777;
    white-space: nowrap;
}

/* =====================================================
   MAIN CLAIMS TABLE CARD
===================================================== */
.card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 22px;
}
.card-header {
    padding: 18px 22px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.card-title { margin: 0; font-size: 17px; font-weight: bold; }
.record-count { font-size: 13px; color: #777; }
.table-wrapper { overflow-x: auto; }

table { width: 100%; border-collapse: collapse; }
th {
    background: #111;
    color: #fff;
    text-align: left;
    padding: 13px 14px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}
td {
    padding: 12px 14px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
    vertical-align: middle;
}
tbody tr:hover { background: #fffdf0; }
.employee-id  { font-weight: bold; font-family: monospace; font-size: 13px; }
.claim-amount { font-weight: bold; white-space: nowrap; }
.claim-desc   { max-width: 180px; color: #555; font-size: 13px; }

.status {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
    text-transform: capitalize;
    white-space: nowrap;
}
.status-approved { background:#e8f5e9; color:#2e7d32; }
.status-pending  { background:#fff8d6; color:#856404; }
.status-rejected { background:#fdecea; color:#a12622; }
.status-returned { background:#e3f2fd; color:#1565c0; }
.status-paid     { background:#f3e5f5; color:#6a1b9a; }
.status-default  { background:#eee; color:#555; }

.approver-info { font-size: 11px; color: #777; margin-top: 3px; line-height: 1.4; }
.return-reason { font-size: 11px; color: #1565c0; margin-top: 3px; font-style: italic; }

/* =====================================================
   ACTION BUTTONS
===================================================== */
.actions { white-space: nowrap; }
.btn-act {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: bold;
    border: none;
    cursor: pointer;
    text-decoration: none;
    margin: 1px;
    line-height: 1.4;
}
.btn-edit    { background:#f4f4f4; color:#111; }
.btn-edit:hover { background:#e5e5e5; }
.btn-approve { background:#e8f5e9; color:#2e7d32; }
.btn-approve:hover { background:#c8e6c9; }
.btn-reject  { background:#fdecea; color:#c62828; }
.btn-reject:hover { background:#ffcdd2; }
.btn-return  { background:#e3f2fd; color:#1565c0; }
.btn-return:hover { background:#bbdefb; }
.btn-delete  { background:#eee; color:#c62828; }
.btn-delete:hover { background:#ffcdd2; }

/* =====================================================
   APPROVAL MODALS
===================================================== */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}
.modal-overlay.active { display: flex; }
.modal-box {
    background: #fff;
    border-radius: 10px;
    padding: 28px;
    max-width: 460px;
    width: 100%;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}
.modal-title { margin: 0 0 16px; font-size: 18px; font-weight: bold; }
.modal-box label { font-size: 13px; font-weight: bold; display: block; margin-bottom: 6px; }
.modal-box textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    min-height: 90px;
    resize: vertical;
    font-family: Arial, sans-serif;
    box-sizing: border-box;
}
.modal-box textarea:focus { outline: none; border-color: #f4c400; }
.modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px; }
.modal-cancel          { padding: 9px 16px; background: #eee; color: #333; border: none; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; }
.modal-confirm-approve { padding: 9px 16px; background: #2e7d32; color: #fff; border: none; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; }
.modal-confirm-reject  { padding: 9px 16px; background: #c62828; color: #fff; border: none; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; }
.modal-confirm-return  { padding: 9px 16px; background: #1565c0; color: #fff; border: none; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; }

/* =====================================================
   EMPTY STATE
===================================================== */
.empty-state { text-align: center; padding: 55px 20px; color: #777; }
.empty-state h3 { margin: 0 0 8px; color: #333; }
.empty-state p  { margin: 0 0 20px; font-size: 14px; }

/* =====================================================
   RESPONSIVE
===================================================== */
@media (max-width: 1200px) {
    .kpi-grid       { grid-template-columns: repeat(3,1fr); }
    .financial-grid { grid-template-columns: repeat(2,1fr); }
    .charts-grid    { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
    .kpi-grid       { grid-template-columns: 1fr 1fr; }
    .financial-grid { grid-template-columns: 1fr 1fr; }
}

/* =====================================================
   FORMAL CORPORATE PRINT STYLES
===================================================== */
@media print {
    /* Hide all web chrome */
    nav, .sidebar, aside, .page-header, .kpi-grid, .financial-grid,
    .charts-grid, .filter-toolbar, .card-header .record-count,
    .actions, .modal-overlay, .btn-print, .live-badge,
    .alert, .add-button { display: none !important; }

    /* Show print-only elements */
    .print-only { display: block !important; }

    body { font-family: 'Times New Roman', serif; font-size: 11pt; color: #000; margin: 0; padding: 0; }
    .card { box-shadow: none; border-radius: 0; overflow: visible; margin: 0; }
    .card-header { display: flex !important; border-bottom: 2px solid #000; padding: 8px 0; }
    .card-title { font-size: 13pt; }
    .table-wrapper { overflow: visible; }
    table { page-break-inside: auto; font-size: 9pt; width: 100%; }
    thead { display: table-header-group; }
    th { background: #000 !important; color: #fff !important; padding: 8px 10px; font-size: 8pt; }
    td { padding: 7px 10px; border-bottom: 1px solid #ccc; }
    tbody tr:hover { background: transparent !important; }
    tr { page-break-inside: avoid; }
    .status { border: 1px solid #999; background: transparent !important; color: #000 !important; }

    /* Letterhead */
    .print-letterhead {
        display: block !important;
        text-align: center;
        border-bottom: 3px double #000;
        padding-bottom: 12px;
        margin-bottom: 14px;
    }
    .print-company-name { font-size: 18pt; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; }
    .print-company-sub  { font-size: 10pt; color: #333; margin-top: 2px; }
    .print-doc-title {
        display: block !important;
        text-align: center;
        margin: 12px 0 16px;
    }
    .print-doc-title h2 { font-size: 14pt; text-transform: uppercase; letter-spacing: 2px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 6px 0; margin: 0; }
    .print-doc-meta { font-size: 9pt; color: #444; margin-top: 6px; }

    /* Executive summary table */
    .print-summary-table {
        display: block !important;
        margin-bottom: 16px;
    }
    .print-summary-table table { border: 1px solid #000; font-size: 10pt; }
    .print-summary-table th { background: #333 !important; color: #fff !important; padding: 6px 10px; }
    .print-summary-table td { padding: 6px 10px; border: 1px solid #ccc; }

    /* Signatory block */
    .print-signatories {
        display: block !important;
        margin-top: 40px;
        page-break-inside: avoid;
    }
    .sig-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
    .sig-block { text-align: center; }
    .sig-line  { border-top: 1px solid #000; margin-top: 50px; padding-top: 5px; font-size: 10pt; font-weight: bold; }
    .sig-role  { font-size: 9pt; color: #444; }

    .print-footer {
        display: block !important;
        margin-top: 30px;
        text-align: center;
        font-size: 8pt;
        color: #666;
        border-top: 1px solid #ccc;
        padding-top: 8px;
    }
}
</style>
@endsection

@section('content')

{{-- =====================================================
     PRINT-ONLY FORMAL LETTERHEAD
===================================================== --}}
<div class="print-only print-letterhead">
    <div class="print-company-name">Alibaton Construction Inc.</div>
    <div class="print-company-sub">Alibaton, Iloilo City &nbsp;|&nbsp; HR & Finance Division</div>
</div>

<div class="print-only print-doc-title">
    <h2>Official Reimbursement &amp; Claims Audit Report</h2>
    <div class="print-doc-meta">
        Document Ref: ACI-CLM-{{ date('Ymd') }} &nbsp;|&nbsp;
        Generated: {{ date('F d, Y \a\t h:i A') }} &nbsp;|&nbsp;
        Prepared by: {{ auth()->user()->name ?? auth()->user()->email ?? 'System' }}
    </div>
</div>

<div class="print-only print-summary-table">
    <table>
        <thead>
            <tr>
                <th>Total Claims</th>
                <th>Pending</th>
                <th>Approved</th>
                <th>Rejected/Returned</th>
                <th>Total Claimed (₱)</th>
                <th>Approved Payout (₱)</th>
                <th>Pending Payout (₱)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>{{ $totalClaims }}</strong></td>
                <td>{{ ($pendingClaims ?? collect())->count() }}</td>
                <td>{{ ($approvedClaims ?? collect())->count() }}</td>
                <td>{{ ($rejectedClaims ?? collect())->count() + ($returnedClaims ?? collect())->count() }}</td>
                <td><strong>₱{{ number_format($totalClaimAmount ?? 0, 2) }}</strong></td>
                <td>₱{{ number_format($approvedClaimAmount ?? 0, 2) }}</td>
                <td>₱{{ number_format($pendingClaimAmount ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>
</div>


{{-- =====================================================
     PAGE HEADER (web only)
===================================================== --}}
<div class="page-header">
    <div>
        <h1 class="page-title">
            @if($isEmployee ?? false) My Claims @else Claims &amp; Reimbursement @endif
        </h1>
        <p class="page-subtitle">
            @if($isEmployee ?? false)
                Submit and track your reimbursement and claim requests.
            @else
                Real-time reimbursement monitoring, approval workflow &amp; claims history.
            @endif
        </p>
    </div>

    <div class="header-actions">
        <span class="live-badge">
            <span class="live-dot" id="liveDot"></span>
            <span id="liveStatus">LIVE</span>
            &nbsp;·&nbsp; <span id="liveTime">--:--:--</span>
        </span>
        <button type="button" class="btn-privacy-page-toggle is-masked" id="claimsPrivacyToggle" title="Toggle Confidential Mode (Mask/Unmask sensitive financial figures)" style="display:inline-flex;align-items:center;gap:6px;background:#111;color:#f4c400;border:1px solid #111;padding:8px 14px;border-radius:6px;cursor:pointer;font-size:13px;font-weight:bold;">
            🔒 <span>Masked</span>
        </button>
        <button class="btn-print" onclick="window.print()">🖨 Print / PDF</button>
        <a href="{{ ($isEmployee ?? false) ? '/my-claims/create' : '/claims/create' }}" class="add-button">
            + Submit Claim
        </a>
    </div>
</div>


{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif
@if(isset($error))
    <div class="alert alert-error">{{ $error }}</div>
@endif


{{-- =====================================================
     KPI CARDS — STATUS COUNTS
===================================================== --}}
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-label">Total Claims</div>
        <div class="kpi-value" id="kpi-total">{{ $totalClaims }}</div>
        <div class="kpi-sub">All records</div>
    </div>
    <div class="kpi-card warning">
        <div class="kpi-label">Pending Approval</div>
        <div class="kpi-value" id="kpi-pending">{{ ($pendingClaims ?? collect())->count() }}</div>
        <div class="kpi-sub">Awaiting review</div>
    </div>
    <div class="kpi-card success">
        <div class="kpi-label">Approved</div>
        <div class="kpi-value" id="kpi-approved">{{ ($approvedClaims ?? collect())->count() }}</div>
        <div class="kpi-sub">Approved requests</div>
    </div>
    <div class="kpi-card danger">
        <div class="kpi-label">Rejected</div>
        <div class="kpi-value" id="kpi-rejected">{{ ($rejectedClaims ?? collect())->count() }}</div>
        <div class="kpi-sub">Rejected requests</div>
    </div>
    <div class="kpi-card info">
        <div class="kpi-label">Returned</div>
        <div class="kpi-value" id="kpi-returned">{{ ($returnedClaims ?? collect())->count() }}</div>
        <div class="kpi-sub">For revision</div>
    </div>
</div>


{{-- =====================================================
     FINANCIAL KPI CARDS
===================================================== --}}
<div class="financial-grid">
    <div class="kpi-card dark">
        <div class="kpi-label">Total Claimed Amount</div>
        <div class="kpi-value confidential-amount" id="kpi-total-amount">₱{{ number_format($totalClaimAmount ?? 0, 2) }}</div>
    </div>
    <div class="kpi-card success">
        <div class="kpi-label">Approved Payout</div>
        <div class="kpi-value confidential-amount" id="kpi-approved-amount">₱{{ number_format($approvedClaimAmount ?? 0, 2) }}</div>
    </div>
    <div class="kpi-card warning">
        <div class="kpi-label">Pending Payout</div>
        <div class="kpi-value confidential-amount" id="kpi-pending-amount">₱{{ number_format($pendingClaimAmount ?? 0, 2) }}</div>
    </div>
    <div class="kpi-card danger">
        <div class="kpi-label">Rejected Amount</div>
        <div class="kpi-value confidential-amount" id="kpi-rejected-amount">₱0.00</div>
    </div>
</div>


{{-- =====================================================
     CHARTS
===================================================== --}}
<div class="charts-grid">

    {{-- Claim Type Distribution --}}
    <div class="chart-card">
        <div class="chart-title">Claim Type Distribution</div>
        <div class="chart-subtitle">Claims grouped by reimbursement category</div>
        <div class="chart-container">
            <canvas id="typeChart"></canvas>
        </div>
    </div>

    {{-- Approval Status Breakdown --}}
    <div class="chart-card">
        <div class="chart-title">Approval Status Breakdown</div>
        <div class="chart-subtitle">Claims count and financial summary by status</div>
        <div class="chart-container">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

</div>


{{-- =====================================================
     FILTER TOOLBAR
===================================================== --}}
<div class="filter-toolbar">
    <input type="text"   id="searchEmployee" placeholder="🔍 Search Employee ID..." oninput="filterTable()">
    <input type="text"   id="searchType"     placeholder="🔍 Search Claim Type..." oninput="filterTable()">
    <input type="text"   id="searchDesc"     placeholder="🔍 Search Description..." oninput="filterTable()">
    <select id="filterStatus" onchange="filterTable()">
        <option value="">All Statuses</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
        <option value="returned">Returned</option>
    </select>
    <input type="date" id="filterDateFrom" onchange="filterTable()" title="From date">
    <input type="date" id="filterDateTo"   onchange="filterTable()" title="To date">
    <button class="filter-reset" onclick="resetFilters()">✕ Reset</button>
    <span class="filter-count" id="filterCount">Showing all {{ count($claims) }} records</span>
</div>


{{-- =====================================================
     CLAIMS TABLE CARD
===================================================== --}}
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Claim Records</h2>
        <span class="record-count">{{ count($claims) }} record(s)</span>
    </div>

    @if(count($claims) > 0)
        <div class="table-wrapper">
            <table id="claimsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee ID</th>
                        <th>Claim Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Claim Date</th>
                        <th>Status</th>
                        <th>Approver / Notes</th>
                        <th class="actions">Actions</th>
                    </tr>
                </thead>
                <tbody id="claimsBody">
                    @foreach($claims as $claim)
                        @php
                            $status     = strtolower($claim['status'] ?? 'unknown');
                            $statusClass = match($status) {
                                'approved' => 'status-approved',
                                'pending'  => 'status-pending',
                                'rejected' => 'status-rejected',
                                'returned' => 'status-returned',
                                'paid'     => 'status-paid',
                                default    => 'status-default',
                            };
                            $isAdminOrHr = !($isEmployee ?? false);
                            $canApprove  = $isAdminOrHr && in_array($status, ['pending','returned']);
                            $canReturn   = $isAdminOrHr && $status === 'pending';
                            $canReject   = $isAdminOrHr && in_array($status, ['pending','approved']);
                            $claimDate   = !empty($claim['claim_date'])
                                ? date('Y-m-d', strtotime($claim['claim_date']))
                                : '';
                        @endphp

                        <tr data-employee="{{ strtolower($claim['employee_id'] ?? '') }}"
                            data-type="{{ strtolower($claim['claim_type'] ?? '') }}"
                            data-desc="{{ strtolower($claim['description'] ?? '') }}"
                            data-status="{{ $status }}"
                            data-date="{{ $claimDate }}">

                            <td>{{ $claim['id'] ?? '-' }}</td>

                            <td class="employee-id">{{ $claim['employee_id'] ?? '-' }}</td>

                            <td>{{ $claim['claim_type'] ?? '-' }}</td>

                            <td class="claim-desc" title="{{ $claim['description'] ?? '' }}">
                                {{ \Illuminate\Support\Str::limit($claim['description'] ?? '-', 60) }}
                            </td>

                            <td class="claim-amount confidential-amount">₱{{ number_format((float)($claim['amount'] ?? 0), 2) }}</td>

                            <td>
                                @if(!empty($claim['claim_date']))
                                    {{ date('M d, Y', strtotime($claim['claim_date'])) }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                <span class="status {{ $statusClass }}">{{ $status }}</span>
                            </td>

                            <td>
                                @if(!empty($claim['approved_by']))
                                    <div class="approver-info">
                                        👤 {{ $claim['approved_by'] }}
                                        @if(!empty($claim['approval_date']))
                                            <br>{{ date('M d, Y', strtotime($claim['approval_date'])) }}
                                        @elseif(!empty($claim['approved_at']))
                                            <br>{{ date('M d, Y', strtotime($claim['approved_at'])) }}
                                        @endif
                                    </div>
                                @endif
                                @if(!empty($claim['approval_notes']))
                                    <div class="approver-info">📝 {{ $claim['approval_notes'] }}</div>
                                @endif
                                @if(!empty($claim['return_reason']))
                                    <div class="return-reason">↩ {{ $claim['return_reason'] }}</div>
                                @endif
                                @if(!empty($claim['rejection_reason']))
                                    <div class="approver-info" style="color:#c62828;">✗ {{ $claim['rejection_reason'] }}</div>
                                @endif
                                @if(empty($claim['approved_by']) && empty($claim['return_reason']) && empty($claim['rejection_reason']))
                                    <span style="color:#ccc;">—</span>
                                @endif
                            </td>

                            <td class="actions">

                                {{-- APPROVE --}}
                                @if($canApprove)
                                    <button class="btn-act btn-approve"
                                        onclick="openModal('approve', {{ $claim['id'] }})">
                                        ✓ Approve
                                    </button>
                                @endif

                                {{-- REJECT --}}
                                @if($canReject)
                                    <button class="btn-act btn-reject"
                                        onclick="openModal('reject', {{ $claim['id'] }})">
                                        ✗ Reject
                                    </button>
                                @endif

                                {{-- RETURN --}}
                                @if($canReturn)
                                    <button class="btn-act btn-return"
                                        onclick="openModal('return', {{ $claim['id'] }})">
                                        ↩ Return
                                    </button>
                                @endif

                                {{-- EDIT --}}
                                @if($isAdminOrHr)
                                    <a href="/claims/{{ $claim['id'] }}/edit" class="btn-act btn-edit">
                                        Edit
                                    </a>
                                @endif

                                {{-- DELETE --}}
                                @if($isAdminOrHr)
                                    <form action="/claims/{{ $claim['id'] }}" method="POST"
                                          style="display:inline;"
                                          onsubmit="return confirm('Delete this claim record? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-act btn-delete">Delete</button>
                                    </form>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <h3>No Claim Records</h3>
            <p>There are currently no claim records to display.</p>
            <a href="{{ ($isEmployee ?? false) ? '/my-claims/create' : '/claims/create' }}" class="add-button">
                + Submit First Claim
            </a>
        </div>
    @endif
</div>


{{-- =====================================================
     PRINT SIGNATORY BLOCK
===================================================== --}}
<div class="print-only print-signatories">
    <div class="sig-grid">
        <div class="sig-block">
            <div class="sig-line">{{ auth()->user()->name ?? 'Prepared by' }}</div>
            <div class="sig-role">Prepared by — HR / Finance Staff</div>
        </div>
        <div class="sig-block">
            <div class="sig-line">&nbsp;</div>
            <div class="sig-role">Reviewed by — HR Manager</div>
        </div>
        <div class="sig-block">
            <div class="sig-line">&nbsp;</div>
            <div class="sig-role">Approved by — Finance Officer</div>
        </div>
    </div>
</div>

<div class="print-only print-footer">
    This document is computer-generated. Unauthorized reproduction or alteration is prohibited. &nbsp;·&nbsp;
    Alibaton Construction Inc. &nbsp;·&nbsp; {{ date('Y') }}
</div>


{{-- =====================================================
     APPROVAL MODALS
===================================================== --}}

{{-- APPROVE MODAL --}}
<div class="modal-overlay" id="modal-approve">
    <div class="modal-box">
        <h3 class="modal-title" style="color:#2e7d32;">✓ Approve Claim</h3>
        <form id="form-approve" method="POST">
            @csrf
            @method('PATCH')
            <label>Approval Notes (optional)</label>
            <textarea name="approval_notes" placeholder="Add any notes for this approval..."></textarea>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" onclick="closeModal('approve')">Cancel</button>
                <button type="submit" class="modal-confirm-approve">✓ Approve</button>
            </div>
        </form>
    </div>
</div>

{{-- REJECT MODAL --}}
<div class="modal-overlay" id="modal-reject">
    <div class="modal-box">
        <h3 class="modal-title" style="color:#c62828;">✗ Reject Claim</h3>
        <form id="form-reject" method="POST">
            @csrf
            @method('PATCH')
            <label>Reason for Rejection <span style="color:#c62828;">*</span></label>
            <textarea name="approval_notes" placeholder="Explain why this claim is being rejected..." required></textarea>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" onclick="closeModal('reject')">Cancel</button>
                <button type="submit" class="modal-confirm-reject">✗ Reject Claim</button>
            </div>
        </form>
    </div>
</div>

{{-- RETURN MODAL --}}
<div class="modal-overlay" id="modal-return">
    <div class="modal-box">
        <h3 class="modal-title" style="color:#1565c0;">↩ Return for Revision</h3>
        <form id="form-return" method="POST">
            @csrf
            @method('PATCH')
            <label>Reason for Return <span style="color:#c62828;">*</span></label>
            <textarea name="return_reason" placeholder="Explain what needs to be corrected or resubmitted..." required></textarea>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" onclick="closeModal('return')">Cancel</button>
                <button type="submit" class="modal-confirm-return">↩ Return Claim</button>
            </div>
        </form>
    </div>
</div>


{{-- =====================================================
     SCRIPTS
===================================================== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
// =====================================================
// INITIAL CHART DATA FROM PHP
// =====================================================
const initialTypeBreakdown = @json($claimTypeBreakdown ?? collect());
const initialStats = {
    pending_count:   {{ ($pendingClaims ?? collect())->count() }},
    approved_count:  {{ ($approvedClaims ?? collect())->count() }},
    rejected_count:  {{ ($rejectedClaims ?? collect())->count() }},
    returned_count:  {{ ($returnedClaims ?? collect())->count() }},
};

// =====================================================
// CHART.JS — CLAIM TYPE DOUGHNUT
// =====================================================
const typeLabels  = Object.keys(initialTypeBreakdown);
const typeCounts  = typeLabels.map(k => initialTypeBreakdown[k]?.count || 0);

const typeColors = [
    '#f4c400','#2e7d32','#c62828','#1565c0','#6a1b9a',
    '#e65100','#0277bd','#37474f','#558b2f','#ad1457'
];

const typeChart = new Chart(document.getElementById('typeChart'), {
    type: 'doughnut',
    data: {
        labels:   typeLabels.length ? typeLabels : ['No Data'],
        datasets: [{
            data:            typeLabels.length ? typeCounts : [1],
            backgroundColor: typeLabels.length ? typeColors.slice(0, typeLabels.length) : ['#eee'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'right', labels: { font: { size: 11 }, boxWidth: 14 } },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        const type = ctx.label;
                        const data = initialTypeBreakdown[type];
                        if (data) {
                            return ` ${ctx.raw} claims — ₱${parseFloat(data.amount || 0).toLocaleString('en-PH', {minimumFractionDigits:2})}`;
                        }
                        return ` ${ctx.raw} claims`;
                    }
                }
            }
        }
    }
});

// =====================================================
// CHART.JS — STATUS BAR CHART
// =====================================================
const statusChart = new Chart(document.getElementById('statusChart'), {
    type: 'bar',
    data: {
        labels: ['Pending', 'Approved', 'Rejected', 'Returned'],
        datasets: [{
            label: 'Number of Claims',
            data: [
                initialStats.pending_count,
                initialStats.approved_count,
                initialStats.rejected_count,
                initialStats.returned_count,
            ],
            backgroundColor: ['#fff8d6','#e8f5e9','#fdecea','#e3f2fd'],
            borderColor:     ['#e6a800','#2e7d32','#c62828','#1565c0'],
            borderWidth: 2,
            borderRadius: 5,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 },
                grid: { color: '#f0f0f0' }
            },
            x: { grid: { display: false } }
        }
    }
});

// =====================================================
// REAL-TIME POLLING — every 25 seconds
// =====================================================
function fmt(n) {
    return '₱' + parseFloat(n || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

function fetchRealtimeClaims() {
    fetch('/claims/data')
        .then(r => r.json())
        .then(data => {
            if (!data.success) throw new Error(data.error || 'Fetch failed');

            const s = data.stats;

            // Update KPI count cards
            document.getElementById('kpi-total').textContent    = s.total;
            document.getElementById('kpi-pending').textContent  = s.pending_count;
            document.getElementById('kpi-approved').textContent = s.approved_count;
            document.getElementById('kpi-rejected').textContent = s.rejected_count;
            document.getElementById('kpi-returned').textContent = s.returned_count;

            // Update financial cards
            document.getElementById('kpi-total-amount').textContent    = fmt(s.total_amount);
            document.getElementById('kpi-approved-amount').textContent = fmt(s.approved_amount);
            document.getElementById('kpi-pending-amount').textContent  = fmt(s.pending_amount);
            document.getElementById('kpi-rejected-amount').textContent = fmt(s.rejected_amount);

            // Re-apply privacy mask if active
            if (window.refreshPrivacyMask) window.refreshPrivacyMask();

            // Update status bar chart
            statusChart.data.datasets[0].data = [
                s.pending_count,
                s.approved_count,
                s.rejected_count,
                s.returned_count,
            ];
            statusChart.update('none');

            // Update type doughnut if types available
            if (data.types && Object.keys(data.types).length > 0) {
                const newLabels = Object.keys(data.types);
                const newCounts = newLabels.map(k => data.types[k]?.count || 0);
                typeChart.data.labels = newLabels;
                typeChart.data.datasets[0].data = newCounts;
                typeChart.data.datasets[0].backgroundColor = typeColors.slice(0, newLabels.length);
                typeChart.update('none');
            }

            // Update live badge
            const dot = document.getElementById('liveDot');
            dot.classList.remove('error-dot');
            document.getElementById('liveStatus').textContent = 'LIVE';
            document.getElementById('liveTime').textContent   = data.timestamp || new Date().toLocaleTimeString();
        })
        .catch(err => {
            const dot = document.getElementById('liveDot');
            dot.classList.add('error-dot');
            document.getElementById('liveStatus').textContent = 'OFFLINE';
            console.warn('Claims real-time fetch error:', err.message);
        });
}

// Initial fetch + interval
fetchRealtimeClaims();
setInterval(fetchRealtimeClaims, 25000);

// =====================================================
// LIVE FILTER / SEARCH
// =====================================================
function filterTable() {
    const empQ    = document.getElementById('searchEmployee').value.toLowerCase().trim();
    const typeQ   = document.getElementById('searchType').value.toLowerCase().trim();
    const descQ   = document.getElementById('searchDesc').value.toLowerCase().trim();
    const statusQ = document.getElementById('filterStatus').value.toLowerCase().trim();
    const dateFrom = document.getElementById('filterDateFrom').value;
    const dateTo   = document.getElementById('filterDateTo').value;

    const rows = document.querySelectorAll('#claimsBody tr');
    let visible = 0;

    rows.forEach(row => {
        const emp    = row.dataset.employee || '';
        const type   = row.dataset.type     || '';
        const desc   = row.dataset.desc     || '';
        const status = row.dataset.status   || '';
        const date   = row.dataset.date     || '';

        const empMatch    = !empQ    || emp.includes(empQ);
        const typeMatch   = !typeQ   || type.includes(typeQ);
        const descMatch   = !descQ   || desc.includes(descQ);
        const statusMatch = !statusQ || status === statusQ;
        const fromMatch   = !dateFrom || date >= dateFrom;
        const toMatch     = !dateTo   || date <= dateTo;

        if (empMatch && typeMatch && descMatch && statusMatch && fromMatch && toMatch) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('filterCount').textContent =
        `Showing ${visible} of ${rows.length} records`;
}

function resetFilters() {
    document.getElementById('searchEmployee').value = '';
    document.getElementById('searchType').value     = '';
    document.getElementById('searchDesc').value     = '';
    document.getElementById('filterStatus').value   = '';
    document.getElementById('filterDateFrom').value = '';
    document.getElementById('filterDateTo').value   = '';
    filterTable();
}

// =====================================================
// APPROVAL MODALS
// =====================================================
function openModal(type, claimId) {
    const urls = {
        approve: '/claims/' + claimId + '/approve',
        reject:  '/claims/' + claimId + '/reject',
        return:  '/claims/' + claimId + '/return'
    };
    document.getElementById('form-' + type).action = urls[type];
    document.getElementById('modal-' + type).classList.add('active');
}

function closeModal(type) {
    document.getElementById('modal-' + type).classList.remove('active');
}

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            overlay.classList.remove('active');
        }
    });
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(function(m) {
            m.classList.remove('active');
        });
    }
});
</script>

@endsection
