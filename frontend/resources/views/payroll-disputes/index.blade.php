@extends('layouts.app')

@section('content')
<style>
    .payroll-disputes-page {
        background: #f5f5f5;
        color: #111111;
        min-height: calc(100vh - 70px);
        padding: 30px;
        border-radius: 12px;
    }

    .payroll-disputes-page .page-title {
        color: #111111;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .payroll-disputes-page .page-subtitle {
        color: #6b7280;
        font-size: 0.95rem;
    }

    .payroll-disputes-page .dispute-primary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #111111;
        color: #f4c400;
        border: 1px solid #111111;
        border-radius: 8px;
        padding: 11px 18px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .payroll-disputes-page .dispute-primary-btn:hover {
        background: #f4c400;
        color: #111111;
        border-color: #f4c400;
        transform: translateY(-1px);
    }

    .payroll-disputes-page .dispute-panel {
        background: #ffffff;
        border: 0;
        border-top: 4px solid #f4c400;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    }

    .payroll-disputes-page .dispute-panel-header {
        background: #ffffff;
        border-bottom: 1px solid #eeeeee;
        padding: 20px 24px;
    }

    .payroll-disputes-page .panel-title {
        color: #111111;
        font-weight: 750;
        font-size: 1.05rem;
        margin: 0;
    }

    .payroll-disputes-page .record-count {
        background: #fff8cf;
        color: #111111;
        border: 1px solid #f4c400;
        border-radius: 20px;
        padding: 7px 12px;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .payroll-disputes-page .table {
        margin-bottom: 0;
        color: #222222;
    }

    .payroll-disputes-page .table thead th {
        background: #111111;
        color: #ffffff;
        border: 0;
        padding: 15px 12px;
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.45px;
        white-space: nowrap;
    }

    .payroll-disputes-page .table tbody td {
        padding: 16px 12px;
        border-color: #eeeeee;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .payroll-disputes-page .table tbody tr {
        transition: background 0.15s ease;
    }

    .payroll-disputes-page .table tbody tr:hover {
        background: #fffdf0;
    }

    .payroll-disputes-page .reference-id {
        display: inline-block;
        background: #fff8cf;
        color: #111111;
        border: 1px solid #f4c400;
        border-radius: 6px;
        padding: 5px 8px;
        font-weight: 750;
        white-space: nowrap;
    }

    .payroll-disputes-page .dispute-subject {
        color: #111111;
        font-weight: 700;
        margin-bottom: 4px;
        overflow-wrap: anywhere;
    }

    .payroll-disputes-page .dispute-description {
        color: #6b7280;
        font-size: 0.82rem;
        line-height: 1.45;
    }

    .payroll-disputes-page .type-label {
        display: inline-block;
        background: #f3f4f6;
        color: #374151;
        border-radius: 6px;
        padding: 6px 9px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .payroll-disputes-page .status-badge {
        display: inline-block;
        border-radius: 20px;
        padding: 7px 11px;
        font-size: 0.77rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .payroll-disputes-page .status-pending {
        background: #fff1b8;
        color: #704f00;
    }

    .payroll-disputes-page .status-under-review {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .payroll-disputes-page .status-resolved {
        background: #dcfce7;
        color: #166534;
    }

    .payroll-disputes-page .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .payroll-disputes-page .status-default {
        background: #e5e7eb;
        color: #374151;
    }

    .payroll-disputes-page .dispute-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #ffffff;
        color: #111111;
        border: 1px solid #111111;
        border-radius: 7px;
        padding: 7px 12px;
        font-size: 0.82rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .payroll-disputes-page .dispute-action-btn:hover {
        background: #f4c400;
        border-color: #f4c400;
        color: #111111;
    }

    .payroll-disputes-page .empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .payroll-disputes-page .empty-icon {
        display: inline-flex;
        width: 76px;
        height: 76px;
        align-items: center;
        justify-content: center;
        background: #fff8cf;
        color: #111111;
        border-radius: 50%;
        margin-bottom: 18px;
    }

    .payroll-disputes-page .empty-title {
        color: #111111;
        font-weight: 800;
    }

    .payroll-disputes-page .empty-description {
        color: #6b7280;
        max-width: 430px;
        margin: 0 auto 22px;
    }

    .payroll-disputes-page .alert {
        border: 0;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    @media (max-width: 768px) {
        .payroll-disputes-page {
            padding: 18px 12px;
        }

        .payroll-disputes-page .dispute-panel-header {
            padding: 16px;
        }

        .payroll-disputes-page .table thead th,
        .payroll-disputes-page .table tbody td {
            padding: 12px 10px;
        }
    }
</style>

<div class="payroll-disputes-page">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="page-title mb-2">Payroll Disputes</h2>
            <p class="page-subtitle mb-0">
                @if ($isEmployee)
                    View and track your submitted payroll concerns.
                @else
                    Review and manage employee payroll concerns.
                @endif
            </p>
        </div>

        @if ($isEmployee)
            <a href="{{ route('my-payroll-disputes.create') }}"
               class="dispute-primary-btn">
                <i class="bi bi-plus-circle"></i>
                Submit Dispute
            </a>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-4" role="alert">
            <strong>Please check the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $items = is_array($disputes)
            ? ($disputes['data'] ?? $disputes)
            : [];
    @endphp

    <div class="dispute-panel">
        <div class="dispute-panel-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h5 class="panel-title">
                        <i class="bi bi-receipt me-2"></i>
                        Dispute Records
                    </h5>
                    <small class="text-muted">Submitted payroll concerns and their current status</small>
                </div>

                <span class="record-count">
                    {{ count($items) }} record(s)
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            @if (count($items) > 0)
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4">Reference</th>

                                @unless ($isEmployee)
                                    <th>Employee ID</th>
                                @endunless

                                <th>Subject</th>
                                <th>Type</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($items as $dispute)
                                @php
                                    $status = strtolower($dispute['status'] ?? 'pending');

                                    $statusClass = match ($status) {
                                        'resolved' => 'status-resolved',
                                        'rejected' => 'status-rejected',
                                        'under_review' => 'status-under-review',
                                        'pending' => 'status-pending',
                                        default => 'status-default',
                                    };
                                @endphp

                                <tr>
                                    <td class="ps-4">
                                        <span class="reference-id">
                                            #{{ $dispute['id'] ?? '—' }}
                                        </span>
                                    </td>

                                    @unless ($isEmployee)
                                        <td>
                                            {{ $dispute['employee_id'] ?? '—' }}
                                        </td>
                                    @endunless

                                    <td style="min-width: 210px;">
                                        <div class="dispute-subject">
                                            {{ $dispute['subject'] ?? 'Untitled dispute' }}
                                        </div>

                                        <div class="dispute-description">
                                            {{ \Illuminate\Support\Str::limit($dispute['description'] ?? '', 65) }}
                                        </div>
                                    </td>

                                    <td>
                                        <span class="type-label">
                                            {{ ucwords(str_replace('_', ' ', $dispute['dispute_type'] ?? 'other')) }}
                                        </span>
                                    </td>

                                    <td style="white-space: nowrap;">
                                        {{ isset($dispute['created_at']) ? \Illuminate\Support\Carbon::parse($dispute['created_at'])->format('M d, Y') : '—' }}
                                    </td>

                                    <td>
                                        <span class="status-badge {{ $statusClass }}">
                                            {{ ucwords(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </td>

                                    <td class="text-end pe-4">
                                        @if ($isEmployee)
                                            <a href="{{ route('my-payroll-disputes.show', $dispute['id']) }}"
                                               class="dispute-action-btn">
                                                <i class="bi bi-eye"></i>
                                                View
                                            </a>
                                        @else
                                            <a href="{{ route('payroll-disputes.show', $dispute['id']) }}"
                                               class="dispute-action-btn">
                                                <i class="bi bi-search"></i>
                                                Review
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-inbox" style="font-size: 2.2rem;"></i>
                    </div>

                    <h5 class="empty-title">No Payroll Disputes Yet</h5>

                    <p class="empty-description">
                        @if ($isEmployee)
                            You have not submitted a payroll dispute. Submit one to report a payroll concern.
                        @else
                            There are no payroll disputes to display at this time.
                        @endif
                    </p>

                    @if ($isEmployee)
                        <a href="{{ route('my-payroll-disputes.create') }}"
                           class="dispute-primary-btn">
                            <i class="bi bi-plus-circle"></i>
                            Submit Your First Dispute
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
