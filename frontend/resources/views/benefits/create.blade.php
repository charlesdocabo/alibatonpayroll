@extends('layouts.app')

@section('title', 'Add Benefit - Alibaton Construction Inc.')

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

    label {
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 7px;
    }

    .required {
        color: #dc3545;
    }

    input,
    select {
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #cccccc;
        border-radius: 6px;
        font-size: 14px;
        background: #ffffff;
        color: #111111;
    }

    input:focus,
    select:focus {
        outline: none;
        border-color: #f4c400;
        box-shadow: 0 0 0 2px rgba(244, 196, 0, 0.15);
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

    <div class="page-header">

        <h1 class="page-title">
            Add Employee Benefit
        </h1>

        <p class="page-subtitle">
            Create a new benefit record for an employee.
        </p>

    </div>

    @if(session('error'))
        <div class="alert">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert">
            Please correct the errors below before saving.
        </div>
    @endif

    <div class="form-card">

        <h2 class="section-title">
            Benefit Information
        </h2>

        <form action="/benefits" method="POST">

            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label for="employee_select">Select Registered Employee</label>
                    <select id="employee_select" onchange="document.getElementById('employee_id').value = this.value">
                        <option value="">-- Choose Employee (Optional) --</option>
                        @foreach($employees ?? [] as $emp)
                            <option value="{{ $emp['employee_id'] }}" {{ old('employee_id') == $emp['employee_id'] ? 'selected' : '' }}>
                                {{ $emp['employee_id'] }} - {{ $emp['first_name'] ?? '' }} {{ $emp['last_name'] ?? '' }} ({{ $emp['department'] ?? 'General' }})
                            </option>
                        @endforeach
                    </select>
                </div>

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

                <div class="form-group">
                    <label for="benefit_type">
                        Benefit Type <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        id="benefit_type"
                        name="benefit_type"
                        value="{{ old('benefit_type') }}"
                        placeholder="Example: HMO / Health Insurance, Rice Allowance"
                        list="benefit_types_list"
                        required
                    >
                    <datalist id="benefit_types_list">
                        <option value="HMO / Health Insurance">
                        <option value="Medical Allowance">
                        <option value="Rice Subsidy">
                        <option value="Transportation Allowance">
                        <option value="Communication Allowance">
                        <option value="Life Insurance">
                    </datalist>
                    @error('benefit_type')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="provider">
                        Provider / Insurer
                    </label>
                    <input
                        type="text"
                        id="provider"
                        name="provider"
                        value="{{ old('provider') }}"
                        placeholder="Example: Maxicare, PhilCare, Medicard"
                    >
                    @error('provider')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="membership_number">
                        HMO / Policy Membership Number
                    </label>
                    <input
                        type="text"
                        id="membership_number"
                        name="membership_number"
                        value="{{ old('membership_number') }}"
                        placeholder="Example: HMO-88291039"
                    >
                    @error('membership_number')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="coverage">
                        HMO Coverage Limit / Amount (₱)
                    </label>
                    <input
                        type="number"
                        id="coverage"
                        name="coverage"
                        value="{{ old('coverage') }}"
                        min="0"
                        step="0.01"
                        placeholder="Example: 150000.00"
                    >
                    @error('coverage')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="amount">
                        Monthly Cost / Allowance Amount (₱) <span class="required">*</span>
                    </label>
                    <input
                        type="number"
                        id="amount"
                        name="amount"
                        value="{{ old('amount', 0) }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                        required
                    >
                    @error('amount')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_date">
                        Effective Date (Start)
                    </label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ old('start_date') }}"
                    >
                    @error('start_date')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_date">
                        Expiration Date (End)
                    </label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ old('end_date') }}"
                    >
                    @error('end_date')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">
                        Status <span class="required">*</span>
                    </label>
                    <select id="status" name="status" required>
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    @error('status')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="description">
                        Description / Coverage Details
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; min-height:80px;"
                        placeholder="Additional details regarding benefit package, tier, dependents, or policy notes..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-actions">

                <a href="/benefits" class="cancel-button">
                    Cancel
                </a>

                <button type="submit" class="save-button">
                    Save Benefit
                </button>

            </div>

        </form>

    </div>

@endsection