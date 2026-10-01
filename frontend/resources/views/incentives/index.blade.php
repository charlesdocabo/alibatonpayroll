@extends('layouts.app')

@section('title', 'Incentives - Alibaton Construction Inc.')

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

    .btn-add {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #F4C400;
        color: #111111;
        padding: 11px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700;
        border: none;
        transition: 0.2s ease;
        white-space: nowrap;
    }

    .btn-add:hover {
        background: #DCAE00;
        color: #111111;
        transform: translateY(-1px);
    }

    /* Alerts */

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

    /* Section */

    .section-title {
        margin: 30px 0 15px;
        font-size: 18px;
        font-weight: 800;
        color: #111111;
    }

    /* Monitoring Cards */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .financial-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
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
        font-size: 27px;
        font-weight: 800;
        line-height: 1.2;
    }

    .amount {
        font-size: 23px;
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

    /* Breakdown */

    .breakdown-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        overflow-x: auto;
    }

    .breakdown-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 550px;
    }

    .breakdown-table th {
        background: #111111;
        color: #ffffff;
        padding: 13px 12px;
        text-align: left;
        font-size: 13px;
        white-space: nowrap;
    }

    .breakdown-table td {
        padding: 13px 12px;
        border-bottom: 1px solid #eeeeee;
        font-size: 14px;
    }

    .breakdown-table tbody tr:hover {
        background: #fafafa;
    }

    /* Records */

    .records-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        overflow-x: auto;
    }

    .records-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1050px;
    }

    .records-table th {
        background: #111111;
        color: #ffffff;
        padding: 13px 12px;
        text-align: left;
        font-size: 13px;
        white-space: nowrap;
    }

    .records-table td {
        padding: 13px 12px;
        border-bottom: 1px solid #eeeeee;
        font-size: 14px;
        vertical-align: middle;
    }

    .records-table tbody tr:hover {
        background: #fafafa;
    }

    /* Status */

    .status {
        display: inline-block;
        padding: 5px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .status-approved {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-pending {
        background: #fff8e1;
        color: #8a6d00;
    }

    .status-rejected {
        background: #ffebee;
        color: #c62828;
    }

    .status-other {
        background: #eeeeee;
        color: #555555;
    }

    /* Actions */

    .actions {
        display: flex;
        gap: 7px;
        align-items: center;
    }

    .actions form {
        margin: 0;
    }

    .btn-action {
        display: inline-block;
        padding: 7px 11px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .btn-edit {
        background: #f4f4f4;
        color: #111111;
    }

    .btn-edit:hover {
        background: #e5e5e5;
    }

    .btn-delete {
        background: #ffebee;
        color: #c62828;
    }

    .btn-delete:hover {
        background: #ffcdd2;
    }

    /* Empty State */

    .empty-state {
        text-align: center;
        padding: 45px 20px;
        color: #777777;
    }

    .empty-state h3 {
        margin: 0 0 8px;
        color: #111111;
        font-size: 17px;
    }

    .empty-state p {
        margin: 0;
        font-size: 14px;
    }

    /* Responsive */

    @media (max-width: 1100px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .financial-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 650px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-add {
            width: 100%;
        }

        .stats-grid {
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
        <h1 class="page-title">Employee Incentives</h1>

        <p class="page-subtitle">
            Manage employee bonuses, incentives, and performance rewards.
        </p>
    </div>

    <a href="{{ url('/incentives/create') }}" class="btn-add">
        + Add Incentive
    </a>
</div>

{{-- SUCCESS MESSAGE --}}

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- ERROR MESSAGE --}}

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
     INCENTIVE MONITORING
========================= --}}

<h2 class="section-title">Incentive Monitoring</h2>

<div class="stats-grid">

    <div class="stat-card highlight">
        <div class="stat-label">
            Total Incentives
        </div>

        <div class="stat-value">
            {{ $totalIncentives ?? 0 }}
        </div>
    </div>


    <div class="stat-card success">
        <div class="stat-label">
            Approved Incentives
        </div>

        <div class="stat-value">
            {{ $approvedIncentives ?? 0 }}
        </div>
    </div>


    <div class="stat-card warning">
        <div class="stat-label">
            Pending Incentives
        </div>

        <div class="stat-value">
            {{ $pendingIncentives ?? 0 }}
        </div>
    </div>


    <div class="stat-card danger">
        <div class="stat-label">
            Rejected Incentives
        </div>

        <div class="stat-value">
            {{ $rejectedIncentives ?? 0 }}
        </div>
    </div>

</div>


{{-- =========================
     FINANCIAL MONITORING
========================= --}}

<h2 class="section-title">Financial Monitoring</h2>

<div class="financial-grid">

    <div class="stat-card highlight">
        <div class="stat-label">
            Total Incentive Amount
        </div>

        <div class="stat-value amount">
            ₱{{ number_format($totalIncentiveAmount ?? 0, 2) }}
        </div>
    </div>


    <div class="stat-card success">
        <div class="stat-label">
            Approved Amount
        </div>

        <div class="stat-value amount">
            ₱{{ number_format($approvedIncentiveAmount ?? 0, 2) }}
        </div>
    </div>


    <div class="stat-card warning">
        <div class="stat-label">
            Pending Amount
        </div>

        <div class="stat-value amount">
            ₱{{ number_format($pendingIncentiveAmount ?? 0, 2) }}
        </div>
    </div>

</div>


{{-- =========================
     INCENTIVE TYPE BREAKDOWN
========================= --}}

<h2 class="section-title">
    Incentive Type Breakdown
</h2>

<div class="breakdown-card">

    @if(isset($incentiveTypeBreakdown) && $incentiveTypeBreakdown->count())

        <table class="breakdown-table">

            <thead>
                <tr>
                    <th>Incentive Type</th>
                    <th>Number of Records</th>
                    <th>Total Amount</th>
                </tr>
            </thead>

            <tbody>

                @foreach($incentiveTypeBreakdown as $breakdown)

                    <tr>

                        <td>
                            <strong>
                                {{ $breakdown['type'] ?? 'Unspecified' }}
                            </strong>
                        </td>

                        <td>
                            {{ $breakdown['count'] ?? 0 }}
                        </td>

                        <td>
                            ₱{{ number_format((float) ($breakdown['amount'] ?? 0), 2) }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="empty-state">
            <h3>No incentive breakdown available</h3>

            <p>
                Add an incentive record to see the breakdown.
            </p>
        </div>

    @endif

</div>


{{-- =========================
     INCENTIVE RECORDS
========================= --}}

<h2 class="section-title">
    Incentive Records
</h2>

<div class="records-card">

    @if(count($incentives ?? []))

        <table class="records-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Employee ID</th>
                    <th>Incentive Type</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Incentive Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @foreach($incentives as $incentive)

                    @php
                        $status = strtolower(
                            trim($incentive['status'] ?? '')
                        );
                    @endphp

                    <tr>

                        <td>
                            {{ $incentive['id'] ?? '-' }}
                        </td>


                        <td>
                            <strong>
                                {{ $incentive['employee_id'] ?? '-' }}
                            </strong>
                        </td>


                        <td>
                            {{ $incentive['incentive_type'] ?? '-' }}
                        </td>


                        <td>
                            {{ $incentive['description'] ?? '-' }}
                        </td>


                        <td>
                            <strong>
                                ₱{{ number_format(
                                    (float) ($incentive['amount'] ?? 0),
                                    2
                                ) }}
                            </strong>
                        </td>


                        <td>
                            {{ $incentive['incentive_date'] ?? '-' }}
                        </td>


                        <td>

                            <span class="status
                                @if($status === 'approved')
                                    status-approved
                                @elseif($status === 'pending')
                                    status-pending
                                @elseif($status === 'rejected')
                                    status-rejected
                                @else
                                    status-other
                                @endif
                            ">
                                {{ $incentive['status'] ?? 'Unknown' }}
                            </span>

                        </td>


                        <td>

                            <div class="actions">

                                <a
                                    href="{{ url('/incentives/' . ($incentive['id'] ?? '') . '/edit') }}"
                                    class="btn-action btn-edit"
                                >
                                    Edit
                                </a>


                                <form
                                    action="{{ url('/incentives/' . ($incentive['id'] ?? '')) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this incentive?');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn-action btn-delete"
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

        <div class="empty-state">

            <h3>
                No incentive records found
            </h3>

            <p>
                Click "Add Incentive" to create the first incentive record.
            </p>

        </div>

    @endif

</div>

@endsection

