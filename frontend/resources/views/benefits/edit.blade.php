@extends('layouts.app')

@section('title', 'Edit Benefit - Alibaton Construction Inc.')

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

    .update-button {
        padding: 11px 20px;
        background: #f4c400;
        color: #111111;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
    }

    .update-button:hover {
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
        .update-button {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endsection

@section('content')

    <div class="page-header">

        <h1 class="page-title">
            Edit Employee Benefit
        </h1>

        <p class="page-subtitle">
            Update the selected employee benefit record.
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

        <form
            action="/benefits/{{ $benefit['id'] }}"
            method="POST">

            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">

                    <label for="employee_id">
                        Employee ID <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="employee_id"
                        name="employee_id"
                        value="{{ old('employee_id', $benefit['employee_id'] ?? '') }}"
                        required
                    >

                    @error('employee_id')
                        <div class="error">
                            {{ $message }}
                        </div>
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
                        value="{{ old('benefit_type', $benefit['benefit_type'] ?? '') }}"
                        required
                    >

                    @error('benefit_type')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-group">

                    <label for="provider">
                        Provider
                    </label>

                    <input
                        type="text"
                        id="provider"
                        name="provider"
                        value="{{ old('provider', $benefit['provider'] ?? '') }}"
                    >

                    @error('provider')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-group">

                    <label for="amount">
                        Benefit Amount <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        id="amount"
                        name="amount"
                        value="{{ old('amount', $benefit['amount'] ?? '') }}"
                        min="0"
                        step="0.01"
                        required
                    >

                    @error('amount')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-group">

                    <label for="status">
                        Status <span class="required">*</span>
                    </label>

                    @php
                        $currentStatus = old(
                            'status',
                            strtolower($benefit['status'] ?? '')
                        );
                    @endphp

                    <select
                        id="status"
                        name="status"
                        required>

                        <option value="">
                            Select Status
                        </option>

                        <option value="active"
                            {{ $currentStatus === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ $currentStatus === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                        <option value="pending"
                            {{ $currentStatus === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                    </select>

                    @error('status')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

            <div class="form-actions">

                <a
                    href="/benefits"
                    class="cancel-button">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="update-button">
                    Update Benefit
                </button>

            </div>

        </form>

    </div>

@endsection