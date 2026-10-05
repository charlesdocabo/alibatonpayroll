@extends('layouts.app')

@section('title', 'Submit Claim - Alibaton Construction Inc.')

@section('styles')
<style>
    .page-header {
        margin-bottom: 25px;
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

    .form-card {
        max-width: 900px;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        padding: 28px;
    }

    .section-title {
        margin: 0 0 20px;
        font-size: 17px;
        font-weight: bold;
        border-bottom: 2px solid #f4c400;
        padding-bottom: 10px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    label {
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 7px;
    }

    .required {
        color: #dc3545;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #cccccc;
        border-radius: 6px;
        font-size: 14px;
        background: #ffffff;
        color: #111111;
        font-family: Arial, Helvetica, sans-serif;
    }

    textarea {
        min-height: 100px;
        resize: vertical;
    }

    input:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: #f4c400;
        box-shadow: 0 0 0 2px rgba(244, 196, 0, 0.15);
    }

    .hint {
        font-size: 12px;
        color: #777777;
        margin-top: 5px;
    }

    .error {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
    }

    .alert {
        background: #fdecea;
        color: #a12622;
        border: 1px solid #f1b8b5;
        padding: 13px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .form-actions {
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #eeeeee;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .cancel-button {
        display: inline-block;
        padding: 11px 18px;
        background: #eeeeee;
        color: #333333;
        text-decoration: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: bold;
    }

    .cancel-button:hover {
        background: #dddddd;
    }

    .save-button {
        padding: 11px 20px;
        background: #f4c400;
        color: #111111;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
    }

    .save-button:hover {
        background: #dcae00;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: auto;
        }

        .form-card {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .cancel-button,
        .save-button {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endsection

@section('content')

    @php
        $authUser = auth()->user();
        $isEmployee = $authUser && strtolower($authUser->role ?? '') === 'employee';
        $postUrl = $isEmployee ? '/my-claims' : '/claims';
        $cancelUrl = $isEmployee ? '/my-claims' : '/claims';
    @endphp

    <div class="page-header">
        <h1 class="page-title">
            Submit Reimbursement Claim
        </h1>
        <p class="page-subtitle">
            Submit a new claim request with description and supporting receipt or document.
        </p>
    </div>

    @if(session('error'))
        <div class="alert">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert">
            Please correct the errors below before submitting.
        </div>
    @endif

    <div class="form-card">

        <h2 class="section-title">
            Claim Details
        </h2>

        <form action="{{ $postUrl }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">

                {{-- EMPLOYEE ID --}}
                @if($isEmployee)
                    <div class="form-group">
                        <label>Employee ID</label>
                        <input
                            type="text"
                            value="{{ $authUser->employee_id ?? 'Self' }}"
                            disabled
                            style="background:#f9f9f9; color:#555555;"
                        >
                        <input type="hidden" name="employee_id" value="{{ $authUser->employee_id ?? $authUser->id }}">
                        <div class="hint">Submitted for your employee profile.</div>
                    </div>
                @else
                    <div class="form-group">
                        <label for="employee_id">
                            Employee ID <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="employee_id"
                            name="employee_id"
                            value="{{ old('employee_id') }}"
                            placeholder="Example: EMP001"
                            required
                        >
                        @error('employee_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                {{-- CLAIM TYPE --}}
                <div class="form-group">
                    <label for="claim_type">
                        Claim Type <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        id="claim_type"
                        name="claim_type"
                        list="claim_types_list"
                        value="{{ old('claim_type') }}"
                        placeholder="Select or enter claim type"
                        required
                    >
                    <datalist id="claim_types_list">
                        <option value="Medical Expense">
                        <option value="Travel & Transportation">
                        <option value="Fuel / Gas Reimbursement">
                        <option value="Meals & Entertainment">
                        <option value="Office Supplies / Equipment">
                        <option value="Communication / Internet Allowance">
                        <option value="Emergency Project Expenses">
                        <option value="Other Reimbursement">
                    </datalist>
                    @error('claim_type')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- CLAIM AMOUNT --}}
                <div class="form-group">
                    <label for="amount">
                        Claim Amount (₱) <span class="required">*</span>
                    </label>
                    <input
                        type="number"
                        id="amount"
                        name="amount"
                        value="{{ old('amount') }}"
                        min="0.01"
                        step="0.01"
                        placeholder="0.00"
                        required
                    >
                    @error('amount')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- CLAIM DATE --}}
                <div class="form-group">
                    <label for="claim_date">
                        Claim / Expense Date <span class="required">*</span>
                    </label>
                    <input
                        type="date"
                        id="claim_date"
                        name="claim_date"
                        value="{{ old('claim_date', date('Y-m-d')) }}"
                        required
                    >
                    @error('claim_date')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- STATUS (Only for Admin/HR) --}}
                @if(!$isEmployee)
                    <div class="form-group">
                        <label for="status">
                            Status <span class="required">*</span>
                        </label>
                        @php
                            $currentStatus = old('status', 'pending');
                        @endphp
                        <select id="status" name="status" required>
                            <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $currentStatus === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $currentStatus === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                {{-- SUPPORTING RECEIPT / DOCUMENT --}}
                <div class="form-group {{ $isEmployee ? '' : '' }}">
                    <label for="receipt">
                        Supporting Receipt / Document
                    </label>
                    <input
                        type="file"
                        id="receipt"
                        name="receipt"
                        accept=".jpg,.jpeg,.png,.pdf"
                    >
                    <div class="hint">Upload official receipt or receipt photo (JPG, PNG, PDF up to 5MB).</div>
                    @error('receipt')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DESCRIPTION / REASON --}}
                <div class="form-group full">
                    <label for="description">
                        Description / Justification <span class="required">*</span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        placeholder="Provide details about the business expense or reimbursement reason..."
                        required
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-actions">
                <a href="{{ $cancelUrl }}" class="cancel-button">
                    Cancel
                </a>
                <button type="submit" class="save-button">
                    Submit Claim
                </button>
            </div>

        </form>

    </div>

@endsection