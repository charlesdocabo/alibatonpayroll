@extends('layouts.app')

@section('content')
<style>
    .dispute-page {
        background: #f5f5f5;
        color: #111111;
        min-height: 100%;
        padding: 25px;
        font-family: Arial, Helvetica, sans-serif;
    }

    .dispute-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 22px;
    }

    .dispute-heading h3 {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 6px;
    }

    .dispute-heading p {
        font-size: 13px;
        color: #777777;
        margin: 0;
    }

    .dispute-card {
        background: #ffffff;
        border: 0;
        border-top: 4px solid #f4c400;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .dispute-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #eeeeee;
    }

    .dispute-card-header h5 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .dispute-card-body {
        padding: 24px;
    }

    .dispute-page .form-label {
        font-size: 13px;
        font-weight: 700;
        color: #333333;
        margin-bottom: 7px;
    }

    .dispute-page .form-control,
    .dispute-page .form-select {
        border: 1px solid #dcdcdc;
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 13px;
        color: #111111;
        background-color: #ffffff;
    }

    .dispute-page .form-control:focus,
    .dispute-page .form-select:focus {
        border-color: #c5a000;
        box-shadow: 0 0 0 3px rgba(244, 196, 0, 0.18);
    }

    .dispute-page .form-text {
        font-size: 12px;
        color: #777777;
    }

    .dispute-btn {
        display: inline-block;
        padding: 10px 17px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: 0.2s;
    }

    .dispute-btn-primary {
        background: #111111;
        color: #f4c400;
        border: 1px solid #111111;
    }

    .dispute-btn-primary:hover {
        background: #333333;
        color: #f4c400;
    }

    .dispute-btn-secondary {
        background: #ffffff;
        color: #333333;
        border: 1px solid #dcdcdc;
    }

    .dispute-btn-secondary:hover {
        background: #eeeeee;
        color: #111111;
    }

    .dispute-divider {
        border-color: #eeeeee;
        margin: 22px 0;
    }

    @media (max-width: 600px) {
        .dispute-page {
            padding: 16px;
        }

        .dispute-heading {
            align-items: flex-start;
        }

        .dispute-heading h3 {
            font-size: 19px;
        }

        .dispute-card-body {
            padding: 17px;
        }
    }
</style>

<div class="dispute-page">
    <div class="dispute-heading">
        <div>
            <h3>Submit Payroll Dispute</h3>
            <p>Report a concern about your salary or payroll computation.</p>
        </div>

        <a href="{{ route('my-payroll-disputes.index') }}"
           class="dispute-btn dispute-btn-secondary">
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please check the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="dispute-card">
        <div class="dispute-card-header">
            <h5>Dispute Details</h5>
        </div>

        <div class="dispute-card-body">
            <form method="POST"
                  action="{{ route('my-payroll-disputes.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="payroll_id" class="form-label">
                        Payroll Record <span class="text-danger">*</span>
                    </label>

                    <select name="payroll_id" id="payroll_id"
                            class="form-select" required>
                        <option value="">Select payroll record</option>

                        @forelse ($payrolls as $payroll)
                            <option value="{{ $payroll['id'] }}"
                                {{ old('payroll_id') == $payroll['id'] ? 'selected' : '' }}>
                                Payroll #{{ $payroll['id'] }}
                                — {{ $payroll['pay_date'] ?? 'Date unavailable' }}
                                — Net Pay: ₱{{ number_format((float) ($payroll['net_pay'] ?? 0), 2) }}
                            </option>
                        @empty
                            <option value="" disabled>
                                No payroll records available
                            </option>
                        @endforelse
                    </select>

                    @if (count($payrolls) === 0)
                        <small class="text-danger">
                            No payroll records are available for your account.
                            Please contact HR.
                        </small>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="dispute_type" class="form-label">
                        Dispute Type <span class="text-danger">*</span>
                    </label>

                    <select name="dispute_type" id="dispute_type"
                            class="form-select" required>
                        <option value="">Select dispute type</option>

                        @foreach ([
                            'salary' => 'Basic Salary',
                            'overtime' => 'Overtime Pay',
                            'deductions' => 'Government or Other Deductions',
                            'allowances' => 'Allowances',
                            'incentives' => 'Incentives or Bonus',
                            'other' => 'Other Payroll Concern',
                        ] as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('dispute_type') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label">
                        Subject <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="subject" id="subject"
                           class="form-control" maxlength="150"
                           value="{{ old('subject') }}"
                           placeholder="Briefly describe your concern"
                           required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">
                        Explanation <span class="text-danger">*</span>
                    </label>

                    <textarea name="description" id="description"
                              class="form-control" rows="4" maxlength="5000"
                              placeholder="Explain what appears incorrect and provide relevant details."
                              required>{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="claimed_amount" class="form-label">
                        Amount in Question (₱)
                    </label>

                    <input type="number" name="claimed_amount"
                           id="claimed_amount" class="form-control"
                           min="0" step="0.01"
                           value="{{ old('claimed_amount') }}"
                           placeholder="0.00">

                    <div class="form-text mt-1">
                        Optional. Enter the amount you believe needs review.
                    </div>
                </div>

                <hr class="dispute-divider">

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit"
                            class="dispute-btn dispute-btn-primary"
                            {{ count($payrolls) === 0 ? 'disabled' : '' }}>
                        Submit Dispute
                    </button>

                    <a href="{{ route('my-payroll-disputes.index') }}"
                       class="dispute-btn dispute-btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
