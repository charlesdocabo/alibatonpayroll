@extends('layouts.app')

@section('title', 'Claims - Alibaton Construction Inc.')

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
    }

    .page-subtitle {
        margin: 7px 0 0;
        color: #777777;
        font-size: 14px;
    }

    .add-button {
        display: inline-block;
        background: #f4c400;
        color: #111111;
        text-decoration: none;
        padding: 11px 18px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: bold;
        white-space: nowrap;
    }

    .add-button:hover {
        background: #dcae00;
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

    /* =========================
       CLAIMS MONITORING
    ========================= */

    .monitoring-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .monitor-card {
        background: #ffffff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #f4c400;
    }

    .monitor-label {
        font-size: 13px;
        color: #777777;
        margin-bottom: 8px;
    }

    .monitor-value {
        font-size: 26px;
        font-weight: 700;
        color: #111111;
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
        color: #ffffff;
    }

    .breakdown-card {
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 22px;
        overflow: hidden;
    }

    .breakdown-header {
        padding: 18px 22px;
        border-bottom: 1px solid #eeeeee;
    }

    .breakdown-header h2 {
        margin: 0;
        font-size: 17px;
        font-weight: bold;
    }

    .breakdown-header p {
        margin: 5px 0 0;
        color: #777777;
        font-size: 13px;
    }

    .breakdown-table-wrapper {
        overflow-x: auto;
    }

    .breakdown-table {
        width: 100%;
        border-collapse: collapse;
    }

    .breakdown-table th {
        background: #f5f5f5;
        color: #333333;
        text-align: left;
        padding: 12px 16px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .breakdown-table td {
        padding: 13px 16px;
        border-bottom: 1px solid #eeeeee;
        font-size: 14px;
    }

    .breakdown-table tbody tr:hover {
        background: #fffdf0;
    }

    .breakdown-amount {
        font-weight: bold;
    }

    /* =========================
       CLAIM RECORDS
    ========================= */

    .card {
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-header {
        padding: 20px 22px;
        border-bottom: 1px solid #eeeeee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-title {
        margin: 0;
        font-size: 17px;
        font-weight: bold;
    }

    .record-count {
        font-size: 13px;
        color: #777777;
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
        padding: 14px 16px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    td {
        padding: 15px 16px;
        border-bottom: 1px solid #eeeeee;
        font-size: 14px;
        vertical-align: middle;
    }

    tbody tr:hover {
        background: #fffdf0;
    }

    .employee-id {
        font-weight: bold;
    }

    .amount {
        font-weight: bold;
        white-space: nowrap;
    }

    .description {
        max-width: 260px;
        color: #555555;
    }

    .status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        text-transform: capitalize;
    }

    .status-approved {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-pending {
        background: #fff8d6;
        color: #856404;
    }

    .status-rejected {
        background: #fdecea;
        color: #a12622;
    }

    .status-default {
        background: #eeeeee;
        color: #555555;
    }

    .actions {
        white-space: nowrap;
    }

    .edit-button {
        display: inline-block;
        padding: 7px 11px;
        background: #f4c400;
        color: #111111;
        text-decoration: none;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
        margin-right: 5px;
    }

    .edit-button:hover {
        background: #dcae00;
    }

    .delete-button {
        background: #111111;
        color: #ffffff;
        border: none;
        padding: 7px 11px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
        cursor: pointer;
    }

    .delete-button:hover {
        background: #333333;
    }

    .empty-state {
        text-align: center;
        padding: 55px 20px;
        color: #777777;
    }

    .empty-icon {
        font-size: 42px;
        margin-bottom: 12px;
    }

    .empty-state h3 {
        margin: 0 0 8px;
        color: #333333;
    }

    .empty-state p {
        margin: 0 0 20px;
        font-size: 14px;
    }

    @media (max-width: 1100px) {
        .monitoring-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .add-button {
            width: 100%;
            text-align: center;
        }

        .monitoring-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')

    <div class="page-header">

        <div>
            <h1 class="page-title">
                Employee Claims
            </h1>

            <p class="page-subtitle">
                Manage employee reimbursement and claim requests.
            </p>
        </div>

        <a href="/claims/create" class="add-button">
            + Add Claim
        </a>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if(isset($error))
        <div class="alert alert-error">
            {{ $error }}
        </div>
    @endif

    {{-- =========================
         CLAIMS MONITORING
    ========================== --}}

    <div class="monitoring-grid">

        <div class="monitor-card">
            <div class="monitor-label">
                Total Claims
            </div>

            <div class="monitor-value">
                {{ $totalClaims }}
            </div>

            <div class="monitor-note">
                All claim records
            </div>
        </div>

        <div class="monitor-card">
            <div class="monitor-label">
                Approved Claims
            </div>

            <div class="monitor-value">
                {{ $approvedClaims->count() }}
            </div>

            <div class="monitor-note">
                Approved requests
            </div>
        </div>

        <div class="monitor-card">
            <div class="monitor-label">
                Pending Claims
            </div>

            <div class="monitor-value">
                {{ $pendingClaims->count() }}
            </div>

            <div class="monitor-note">
                Awaiting processing
            </div>
        </div>

        <div class="monitor-card">
            <div class="monitor-label">
                Rejected Claims
            </div>

            <div class="monitor-value">
                {{ $rejectedClaims->count() }}
            </div>

            <div class="monitor-note">
                Rejected requests
            </div>
        </div>

    </div>

    <div class="monitoring-grid">

        <div class="monitor-card dark">
            <div class="monitor-label">
                Total Claim Amount
            </div>

            <div class="monitor-value">
                ₱{{ number_format($totalClaimAmount, 2) }}
            </div>

            <div class="monitor-note">
                Total recorded claims
            </div>
        </div>

        <div class="monitor-card">
            <div class="monitor-label">
                Approved Claim Amount
            </div>

            <div class="monitor-value">
                ₱{{ number_format($approvedClaimAmount, 2) }}
            </div>

            <div class="monitor-note">
                Amount from approved claims
            </div>
        </div>

        <div class="monitor-card">
            <div class="monitor-label">
                Pending Claim Amount
            </div>

            <div class="monitor-value">
                ₱{{ number_format($pendingClaimAmount, 2) }}
            </div>

            <div class="monitor-note">
                Amount awaiting processing
            </div>
        </div>

    </div>

    {{-- =========================
         CLAIM TYPE BREAKDOWN
    ========================== --}}

    @if($claimTypeBreakdown->count() > 0)

        <div class="breakdown-card">

            <div class="breakdown-header">

                <h2>
                    Claim Type Breakdown
                </h2>

                <p>
                    Summary of claim records by claim type.
                </p>

            </div>

            <div class="breakdown-table-wrapper">

                <table class="breakdown-table">

                    <thead>
                        <tr>
                            <th>Claim Type</th>
                            <th>Records</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($claimTypeBreakdown as $type => $summary)

                            <tr>

                                <td>
                                    <strong>{{ $type }}</strong>
                                </td>

                                <td>
                                    {{ $summary['count'] }}
                                </td>

                                <td class="breakdown-amount">
                                    ₱{{ number_format($summary['amount'], 2) }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @endif

    {{-- =========================
         CLAIM RECORDS
    ========================== --}}

    <div class="card">

        <div class="card-header">

            <h2 class="card-title">
                Claim Records
            </h2>

            <span class="record-count">
                {{ count($claims) }} record(s)
            </span>

        </div>

        @if(count($claims) > 0)

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee ID</th>
                            <th>Claim Type</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Claim Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($claims as $claim)

                            <tr>

                                <td>
                                    {{ $claim['id'] ?? '-' }}
                                </td>

                                <td class="employee-id">
                                    {{ $claim['employee_id'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $claim['claim_type'] ?? '-' }}
                                </td>

                                <td class="description">
                                    {{ $claim['description'] ?? '-' }}
                                </td>

                                <td class="amount">
                                    ₱{{ number_format((float)($claim['amount'] ?? 0), 2) }}
                                </td>

                                <td>
                                    @if(!empty($claim['claim_date']))
                                        {{ date('M d, Y', strtotime($claim['claim_date'])) }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>

                                    @php
                                        $status = strtolower($claim['status'] ?? 'unknown');

                                        $statusClass = match ($status) {
                                            'approved' => 'status-approved',
                                            'pending' => 'status-pending',
                                            'rejected' => 'status-rejected',
                                            default => 'status-default',
                                        };
                                    @endphp

                                    <span class="status {{ $statusClass }}">
                                        {{ $status }}
                                    </span>

                                </td>

                                <td class="actions">

                                    <a
                                        href="/claims/{{ $claim['id'] }}/edit"
                                        class="edit-button">
                                        Edit
                                    </a>

                                    <form
                                        action="/claims/{{ $claim['id'] }}"
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this claim record?');">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="delete-button">
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">
                    ✓
                </div>

                <h3>
                    No Claim Records
                </h3>

                <p>
                    There are currently no employee claim records.
                </p>

                <a href="/claims/create" class="add-button">
                    + Add First Claim
                </a>

            </div>

        @endif

    </div>

@endsection
