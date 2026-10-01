@extends('layouts.app')

@section('title', 'Edit Employee - Alibaton Construction Inc.')

@section('styles')
<style>
    .page-header {
        margin-bottom: 25px;
    }

    .page-header h1 {
        margin: 0 0 5px;
        font-size: 28px;
    }

    .page-header p {
        margin: 0;
        color: #666666;
    }

    .form-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        max-width: 1000px;
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
        font-weight: bold;
        margin-bottom: 7px;
        color: #111111;
    }

    input,
    select {
        width: 100%;
        padding: 11px;
        border: 1px solid #cccccc;
        border-radius: 5px;
        font-size: 15px;
        background: white;
    }

    input:focus,
    select:focus {
        outline: none;
        border-color: #f4c400;
        box-shadow: 0 0 0 2px rgba(244, 196, 0, 0.15);
    }

    .buttons {
        margin-top: 30px;
        display: flex;
        gap: 10px;
    }

    .btn {
        display: inline-block;
        padding: 11px 20px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-size: 15px;
        cursor: pointer;
        font-weight: bold;
    }

    .btn-save {
        background: #f4c400;
        color: #111111;
    }

    .btn-save:hover {
        background: #dcae00;
    }

    .btn-cancel {
        background: #111111;
        color: white;
    }

    .btn-cancel:hover {
        background: #333333;
    }

    .error-box {
        background: #ffe5e5;
        border: 1px solid #ff9999;
        color: #990000;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .error-box ul {
        margin: 5px 0 0 20px;
    }

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .buttons {
            flex-direction: column;
        }

        .btn {
            text-align: center;
        }
    }
</style>
@endsection

@section('content')

    <div class="page-header">
        <h1>Edit Employee</h1>
        <p>Update employee information</p>
    </div>

    @if ($errors->any())
        <div class="error-box">
            <strong>Please fix the following errors:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="error-box">
            {{ session('error') }}
        </div>
    @endif

    <div class="form-container">

        <form action="/employees/{{ $employee['id'] }}" method="POST">

            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label for="employee_id">
                        Employee ID
                    </label>

                    <input
                        type="text"
                        id="employee_id"
                        name="employee_id"
                        value="{{ old('employee_id', $employee['employee_id'] ?? '') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $employee['email'] ?? '') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="first_name">
                        First Name
                    </label>

                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        value="{{ old('first_name', $employee['first_name'] ?? '') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="last_name">
                        Last Name
                    </label>

                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        value="{{ old('last_name', $employee['last_name'] ?? '') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="phone">
                        Phone
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $employee['phone'] ?? '') }}"
                    >
                </div>

                <div class="form-group">
                    <label for="position">
                        Position
                    </label>

                    <input
                        type="text"
                        id="position"
                        name="position"
                        value="{{ old('position', $employee['position'] ?? '') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="department">
                        Department
                    </label>

                    <input
                        type="text"
                        id="department"
                        name="department"
                        value="{{ old('department', $employee['department'] ?? '') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="salary">
                        Salary
                    </label>

                    <input
                        type="number"
                        id="salary"
                        name="salary"
                        step="0.01"
                        min="0"
                        value="{{ old('salary', $employee['salary'] ?? '') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="sss">
                        SSS
                    </label>

                    <input
                        type="number"
                        id="sss"
                        name="sss"
                        step="0.01"
                        min="0"
                        value="{{ old('sss', $employee['sss'] ?? '') }}"
                    >
                </div>

                <div class="form-group">
                    <label for="philhealth">
                        PhilHealth
                    </label>

                    <input
                        type="number"
                        id="philhealth"
                        name="philhealth"
                        step="0.01"
                        min="0"
                        value="{{ old('philhealth', $employee['philhealth'] ?? '') }}"
                    >
                </div>

                <div class="form-group">
                    <label for="pagibig">
                        Pag-IBIG
                    </label>

                    <input
                        type="number"
                        id="pagibig"
                        name="pagibig"
                        step="0.01"
                        min="0"
                        value="{{ old('pagibig', $employee['pagibig'] ?? '') }}"
                    >
                </div>

                <div class="form-group">
                    <label for="status">
                        Status
                    </label>

                    @php
                        $currentStatus = old(
                            'status',
                            strtolower($employee['status'] ?? 'active')
                        );
                    @endphp

                    <select id="status" name="status" required>

                        <option value="active"
                            {{ $currentStatus === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ $currentStatus === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                    @error('status')
                        <div class="error-box">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

            <div class="buttons">

                <button type="submit" class="btn btn-save">
                    Save Changes
                </button>

                <a href="/employees" class="btn btn-cancel">
                    Cancel
                </a>

            </div>

        </form>

    </div>

@endsection