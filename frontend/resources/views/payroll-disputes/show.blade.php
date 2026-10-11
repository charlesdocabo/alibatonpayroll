@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();

    $isReviewer = in_array(
        strtolower($user->role ?? ''),
        ['admin', 'hr'],
        true
    );

    $status = strtolower($dispute['status'] ?? 'pending');

    $statusLabel = ucwords(str_replace('_', ' ', $status));

    $statusClass = match ($status) {
        'resolved' => 'pd-status-resolved',
        'rejected' => 'pd-status-rejected',
        'under_review' => 'pd-status-review',
        default => 'pd-status-pending',
    };
@endphp

<style>
    .pd-page {
        --pd-black: #171717;
        --pd-yellow: #facc15;
        --pd-muted: #737373;
        --pd-border: #e5e5e5;
        color: #262626;
    }

    .pd-page .pd-heading {
        color: var(--pd-black);
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .pd-page .pd-reference {
        color: var(--pd-muted);
        font-size: 0.9rem;
    }

    .pd-page .pd-btn-back {
        background: #fff;
        border: 1px solid #d4d4d4;
        color: #262626;
        font-weight: 600;
        border-radius: 8px;
        padding: 10px 16px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .pd-page .pd-btn-back:hover {
        background: #f5f5f5;
        border-color: #a3a3a3;
        color: #171717;
    }

    .pd-page .pd-card {
        background: #fff;
        border: 1px solid var(--pd-border);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
    }

    .pd-page .pd-card-header {
        background: var(--pd-black);
        color: #fff;
        padding: 18px 22px;
        border-bottom: 3px solid var(--pd-yellow);
    }

    .pd-page .pd-card-header h5 {
        font-weight: 700;
        margin: 0;
        font-size: 1rem;
    }

    .pd-page .pd-card-body {
        padding: 26px;
    }

    .pd-page .pd-label {
        color: #737373;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        display: block;
        margin-bottom: 7px;
    }

    .pd-page .pd-value {
        color: #262626;
        font-size: 0.98rem;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .pd-page .pd-detail {
        height: 100%;
        background: #fafafa;
        border: 1px solid #eeeeee;
        border-radius: 10px;
        padding: 16px;
    }

    .pd-page .pd-subject {
        color: #171717;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .pd-page .pd-description {
        color: #404040;
        line-height: 1.8;
        overflow-wrap: anywhere;
    }

    .pd-page .pd-amount {
        color: #171717;
        font-size: 1.25rem;
        font-weight: 800;
    }

    .pd-page .pd-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 0.76rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .pd-page .pd-status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .pd-page .pd-status-review {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .pd-page .pd-status-resolved {
        background: #dcfce7;
        color: #166534;
    }

    .pd-page .pd-status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .pd-page .pd-response {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-left: 4px solid var(--pd-yellow);
        border-radius: 9px;
        padding: 20px;
        color: #404040;
        line-height: 1.8;
        overflow-wrap: anywhere;
    }

    .pd-page .pd-no-response {
        background: #fafafa;
        border: 1px dashed #d4d4d4;
        border-radius: 9px;
        padding: 20px;
        color: #737373;
    }

    .pd-page .pd-form-label {
        color: #262626;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .pd-page .form-control,
    .pd-page .form-select {
        border: 1px solid #d4d4d4;
        border-radius: 8px;
        padding: 11px 13px;
        color: #262626;
    }

    .pd-page .form-control:focus,
    .pd-page .form-select:focus {
        border-color: #eab308;
        box-shadow: 0 0 0 3px rgba(250, 204, 21, 0.2);
    }

    .pd-page .pd-btn-save {
        background: var(--pd-yellow);
        border: 1px solid var(--pd-yellow);
        color: #171717;
        font-weight: 800;
        border-radius: 8px;
        padding: 11px 20px;
        transition: all 0.2s ease;
    }

    .pd-page .pd-btn-save:hover {
        background: #eab308;
        border-color: #eab308;
        color: #171717;
    }

    .pd-page .pd-section-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: var(--pd-yellow);
        color: #171717;
        font-weight: 900;
        margin-right: 10px;
        flex-shrink: 0;
    }

    @media (max-width: 576px) {
        .pd-page .pd-card-body {
            padding: 18px;
        }

        .pd-page .pd-card-header {
            padding: 16px 18px;
        }

        .pd-page .pd-btn-back {
            width: 100%;
            text-align: center;
        }

        .pd-page .pd-btn-save {
            width: 100%;
        }
    }
</style>

<div class="container-fluid py-4 pd-page">

    {{-- Page heading --}}
    <div class="d-flex flex-wrap justify-content-between
                align-items-center gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center mb-2">
                <span class="pd-section-icon">!</span>
                <h2 class="pd-heading mb-0">
                    Payroll Dispute Details
                </h2>
            </div>

            <p class="pd-reference mb-0">
                Reference #{{ $dispute['id'] ?? '—' }}
                <span class="mx-1">•</span>
                View dispute information and review status
            </p>
        </div>

        <a href="{{ $isReviewer
            ? route('payroll-disputes.index')
            : route('my-payroll-disputes.index') }}"
           class="pd-btn-back">
            &larr; Back to Disputes
        </a>
    </div>

    {{-- Success message --}}
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4"
             role="alert">
            <strong>Success!</strong>
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4"
             role="alert">
            <strong>Please check the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Dispute information --}}
    <div class="pd-card mb-4">

        <div class="pd-card-header d-flex flex-wrap
                    justify-content-between align-items-center gap-3">

            <h5>Submitted Concern</h5>

            <span class="pd-status {{ $statusClass }}">
                {{ $statusLabel }}
            </span>
        </div>

        <div class="pd-card-body">
            <div class="row g-3">

                <div class="col-md-6 col-xl-3">
                    <div class="pd-detail">
                        <span class="pd-label">Employee ID</span>
                        <div class="pd-value">
                            {{ $dispute['employee_id'] ?? '—' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="pd-detail">
                        <span class="pd-label">Payroll Record</span>
                        <div class="pd-value">
                            #{{ $dispute['payroll_id'] ?? '—' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="pd-detail">
                        <span class="pd-label">Dispute Type</span>
                        <div class="pd-value">
                            {{ ucwords(str_replace(
                                '_',
                                ' ',
                                $dispute['dispute_type'] ?? 'other'
                            )) }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="pd-detail">
                        <span class="pd-label">Amount in Question</span>
                        <div class="pd-amount">
                            @if (
                                isset($dispute['claimed_amount']) &&
                                $dispute['claimed_amount'] !== null
                            )
                                &#8369;{{ number_format(
                                    (float) $dispute['claimed_amount'],
                                    2
                                ) }}
                            @else
                                <span class="pd-value">
                                    Not specified
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <span class="pd-label">Subject</span>
                    <h5 class="pd-subject mt-1 mb-0">
                        {{ $dispute['subject'] ?? 'Untitled dispute' }}
                    </h5>
                </div>

                <div class="col-12">
                    <hr class="my-2">

                    <span class="pd-label">Explanation</span>

                    <p class="pd-description mb-0"
                       style="white-space: pre-wrap;">{{ $dispute['description'] ?? 'No explanation provided.' }}</p>
                </div>

                <div class="col-12 mt-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="pd-label mb-0">
                            Date Submitted:
                        </span>

                        <span class="pd-value">
                            {{ !empty($dispute['created_at'])
                                ? \Illuminate\Support\Carbon::parse(
                                    $dispute['created_at']
                                )->format('M d, Y h:i A')
                                : '—' }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- HR response --}}
    <div class="pd-card mb-4">

        <div class="pd-card-header">
            <h5>HR Response</h5>
        </div>

        <div class="pd-card-body">

            @if (!empty($dispute['hr_response']))

                <div class="pd-response mb-3"
                     style="white-space: pre-wrap;">{{ $dispute['hr_response'] }}</div>

                <div class="text-muted small">
                    <strong>Reviewed by:</strong>
                    {{ $dispute['reviewed_by'] ?? 'HR' }}

                    @if (!empty($dispute['reviewed_at']))
                        <span class="mx-1">•</span>

                        {{ \Illuminate\Support\Carbon::parse(
                            $dispute['reviewed_at']
                        )->format('M d, Y h:i A') }}
                    @endif
                </div>

            @else

                <div class="pd-no-response">
                    <strong class="d-block mb-1">
                        No response recorded yet
                    </strong>

                    <span>
                        The HR team has not added a response
                        to this payroll dispute.
                    </span>
                </div>

            @endif

        </div>
    </div>

    {{-- Reviewer actions: Admin and HR only --}}
    @if ($isReviewer)

        <div class="pd-card mb-4">

            <div class="pd-card-header">
                <h5>Review Payroll Dispute</h5>
            </div>

            <div class="pd-card-body">

                <p class="text-muted mb-4">
                    Review the employee's concern, update its status,
                    and provide a response explaining the decision
                    or action taken.
                </p>

                <form method="POST"
                      action="{{ route(
                          'payroll-disputes.update',
                          $dispute['id']
                      ) }}">

                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="status"
                               class="form-label pd-form-label">
                            Update Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                id="status"
                                class="form-select"
                                required>

                            @foreach ([
                                'under_review' => 'Under Review',
                                'resolved' => 'Resolved',
                                'rejected' => 'Rejected',
                            ] as $value => $label)

                                <option value="{{ $value }}"
                                    {{ old('status', $status) === $value
                                        ? 'selected'
                                        : '' }}>
                                    {{ $label }}
                                </option>

                            @endforeach

                        </select>

                        <small class="text-muted d-block mt-2">
                            Select the appropriate status for this dispute.
                        </small>
                    </div>

                    <div class="mb-4">
                        <label for="hr_response"
                               class="form-label pd-form-label">
                            HR Response
                            <span class="text-danger">*</span>
                        </label>

                        <textarea name="hr_response"
                                  id="hr_response"
                                  class="form-control"
                                  rows="5"
                                  maxlength="5000"
                                  placeholder="Explain the review result or the action taken."
                                  required>{{ old(
                                      'hr_response',
                                      $dispute['hr_response'] ?? ''
                                  ) }}</textarea>

                        <small class="text-muted d-block mt-2">
                            Provide a clear explanation for the employee.
                            Maximum 5,000 characters.
                        </small>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-2">

                        <a href="{{ $isReviewer
                            ? route('payroll-disputes.index')
                            : route('my-payroll-disputes.index') }}"
                           class="pd-btn-back">
                            Cancel
                        </a>

                        <button type="submit" class="pd-btn-save">
                            Save Review
                        </button>

                    </div>

                </form>

            </div>
        </div>

    @endif

</div>
@endsection
