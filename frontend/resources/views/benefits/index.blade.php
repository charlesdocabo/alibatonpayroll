@extends('layouts.app')

@section('title', 'Benefits - Alibaton Construction Inc.')

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
       BENEFITS MONITORING
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
       MAIN RECORD CARD
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
        white-space: nowrap;
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

    .status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
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
            <h1 class="page-title">Employee Benefits</h1>

            <p class="page-subtitle">
                Manage employee benefit records and coverage.
            </p>
        </div>

        <a href="/benefits/create" class="add-button">
            + Add Benefit
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
         BENEFITS MONITORING
    ========================== --}}

    <div class="monitoring-grid">

        <div class="monitor-card">
            <div class="monitor-label">Total Benefits</div>

            <div class="monitor-value">
                {{ $totalBenefits }}
            </div>

            <div class="monitor-note">
                All benefit records
            </div>
        </div>

        <div class="monitor-card">
            <div class="monitor-label">Active Benefits</div>

            <div class="monitor-value">
                {{ $activeBenefits->count() }}
            </div>

            <div class="monitor-note">
                Currently active
            </div>
        </div>

        <div class="monitor-card">
            <div class="monitor-label">Pending Benefits</div>

            <div class="monitor-value">
                {{ $pendingBenefits->count() }}
            </div>

            <div class="monitor-note">
                Awaiting processing
            </div>
        </div>

        <div class="monitor-card">
            <div class="monitor-label">Inactive Benefits</div>

            <div class="monitor-value">
                {{ $inactiveBenefits->count() }}
            </div>

            <div class="monitor-note">
                Currently inactive
            </div>
        </div>

    </div>

    <div class="monitoring-grid">

        <div class="monitor-card dark">
            <div class="monitor-label">Total Benefit Amount</div>

            <div class="monitor-value">
                ₱{{ number_format($totalBenefitAmount, 2) }}
            </div>

            <div class="monitor-note">
                Total recorded amount
            </div>
        </div>

        <div class="monitor-card">
            <div class="monitor-label">Active Benefit Amount</div>

            <div class="monitor-value">
                ₱{{ number_format($activeBenefitAmount, 2) }}
            </div>

            <div class="monitor-note">
                Amount from active benefits
            </div>
        </div>

    </div>

    {{-- =========================
         BENEFIT TYPE BREAKDOWN
    ========================== --}}

    @if($benefitTypeBreakdown->count() > 0)

        <div class="breakdown-card">

            <div class="breakdown-header">

                <h2>Benefit Type Breakdown</h2>

                <p>
                    Summary of benefit records by benefit type.
                </p>

            </div>

            <div class="breakdown-table-wrapper">

                <table class="breakdown-table">

                    <thead>
                        <tr>
                            <th>Benefit Type</th>
                            <th>Records</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($benefitTypeBreakdown as $type => $summary)

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
         BENEFIT RECORDS
    ========================== --}}

    <div class="card">

        <div class="card-header">

            <h2 class="card-title">
                Benefit Records
            </h2>

            <span class="record-count">
                {{ count($benefits) }} record(s)
            </span>

        </div>

        @if(count($benefits) > 0)

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee ID</th>
                            <th>Benefit Type</th>
                            <th>Provider</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($benefits as $benefit)

                            <tr>

                                <td>
                                    {{ $benefit['id'] ?? '-' }}
                                </td>

                                <td class="employee-id">
                                    {{ $benefit['employee_id'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $benefit['benefit_type'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $benefit['provider'] ?? '-' }}
                                </td>

                                <td class="amount">
                                    ₱{{ number_format((float)($benefit['amount'] ?? 0), 2) }}
                                </td>

                                <td>

                                    @php
                                        $status = strtolower($benefit['status'] ?? 'unknown');

                                        $statusClass = match ($status) {
                                            'active' => 'status-active',
                                            'inactive' => 'status-inactive',
                                            'pending' => 'status-pending',
                                            default => 'status-default',
                                        };
                                    @endphp

                                    <span class="status {{ $statusClass }}">
                                        {{ $status }}
                                    </span>

                                </td>

                                <td class="actions">

                                    <a
                                        href="/benefits/{{ $benefit['id'] }}/edit"
                                        class="edit-button">
                                        Edit
                                    </a>

                                    <form
                                        action="/benefits/{{ $benefit['id'] }}"
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this benefit record?');">

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
                    ▣
                </div>

                <h3>No Benefit Records</h3>

                <p>
                    There are currently no employee benefit records.
                </p>

                <a href="/benefits/create" class="add-button">
                    + Add First Benefit
                </a>

            </div>

        @endif

    </div>

@endsection

