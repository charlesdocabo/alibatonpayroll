@extends('layouts.app')

@section('title', 'Add Employee - Alibaton Construction Inc.')

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

    .alert {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .form-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        max-width: 900px;
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
        margin-bottom: 7px;
        font-weight: bold;
        color: #111111;
    }

    input,
    select {
        width: 100%;
        padding: 11px;
        border: 1px solid #cccccc;
        border-radius: 5px;
        font-size: 14px;
        background: #ffffff;
    }

    input:focus,
    select:focus {
        outline: none;
        border-color: #f4c400;
        box-shadow: 0 0 0 2px rgba(244, 196, 0, 0.15);
    }

    .error {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
    }

    .buttons {
        margin-top: 25px;
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-primary {
        background: #f4c400;
        color: #111111;
    }

    .btn-primary:hover {
        background: #dcae00;
    }

    .btn-secondary {
        background: #dddddd;
        color: #111111;
    }

    .btn-secondary:hover {
        background: #cccccc;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-container {
            padding: 20px;
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
        <h1>Add Employee</h1>
        <p>Create a new employee record</p>
    </div>

    @if(session('error'))
        <div class="alert alert-error">
            <strong>Unable to save employee:</strong><br>
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="form-container">

        <form method="POST" action="/employees">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label for="employee_id">
                        Employee ID
                    </label>

                    <input
                        type="text"
                        id="employee_id"
                        name="employee_id"
                        value="{{ old('employee_id') }}"
                        placeholder="EMP002"
                        required
                    >

                    @error('employee_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="employee@example.com"
                        required
                    >

                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="first_name">
                        First Name
                    </label>

                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        value="{{ old('first_name') }}"
                        required
                    >

                    @error('first_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="last_name">
                        Last Name
                    </label>

                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        value="{{ old('last_name') }}"
                        required
                    >

                    @error('last_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">
                        Phone
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="09XXXXXXXXX"
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
                        value="{{ old('position') }}"
                        placeholder="Construction Worker"
                        required
                    >

                    @error('position')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="department">
                        Department
                    </label>

                    <input
                        type="text"
                        id="department"
                        name="department"
                        value="{{ old('department') }}"
                        placeholder="Construction"
                        required
                    >

                    @error('department')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="salary">
                        Monthly Salary
                    </label>

                    <input
                        type="number"
                        id="salary"
                        name="salary"
                        value="{{ old('salary') }}"
                        step="0.01"
                        min="0"
                        placeholder="18000"
                        required
                    >

                    @error('salary')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sss">
                        SSS Contribution
                    </label>

                    <input
                        type="number"
                        id="sss"
                        name="sss"
                        value="{{ old('sss', 0) }}"
                        step="0.01"
                        min="0"
                    >
                </div>

                <div class="form-group">
                    <label for="philhealth">
                        PhilHealth Contribution
                    </label>

                    <input
                        type="number"
                        id="philhealth"
                        name="philhealth"
                        value="{{ old('philhealth', 0) }}"
                        step="0.01"
                        min="0"
                    >
                </div>

                <div class="form-group">
                    <label for="pagibig">
                        Pag-IBIG Contribution
                    </label>

                    <input
                        type="number"
                        id="pagibig"
                        name="pagibig"
                        value="{{ old('pagibig', 0) }}"
                        step="0.01"
                        min="0"
                    >
                </div>

                <div class="form-group">
                    <label for="status">
                        Status
                    </label>

                    <select id="status" name="status" required>

                        <option value="active"
                            {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ old('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                    @error('status')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="buttons">

                <button type="submit" class="btn btn-primary">
                    Save Employee
                </button>

                <a href="/employees" class="btn btn-secondary">
                    Cancel
                </a>

            </div>

        </form>

    </div>

@endsection