@extends('layouts.app')

@section('title', 'Add Payroll - Alibaton Construction Inc.')

@section('styles')
<style>
    .page-header {
        margin-bottom: 25px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 28px;
    }

    .page-header p {
        margin: 6px 0 0;
        color: #666666;
        font-size: 14px;
    }

    .form-container {
        max-width: 1000px;
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
        font-size: 14px;
    }

    input {
        padding: 11px;
        border: 1px solid #cccccc;
        border-radius: 5px;
        font-size: 15px;
        width: 100%;
    }

    input:focus {
        outline: none;
        border-color: #f4c400;
        box-shadow: 0 0 0 2px rgba(244, 196, 0, 0.15);
    }

    .section-title {
        grid-column: 1 / -1;
        margin-top: 5px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f4c400;
        font-size: 18px;
        font-weight: bold;
    }

    .info-box {
        grid-column: 1 / -1;
        background: #fff8d6;
        border-left: 4px solid #f4c400;
        padding: 14px;
        border-radius: 5px;
        font-size: 14px;
        line-height: 1.5;
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

    .buttons {
        margin-top: 30px;
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 11px 20px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-size: 15px;
        cursor: pointer;
    }

    .save {
        background: #f4c400;
        color: #111111;
        font-weight: bold;
    }

    .save:hover {
        background: #dcae00;
    }

    .cancel {
        background: #111111;
        color: white;
    }

    .cancel:hover {
        background: #333333;
    }

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .section-title,
        .info-box {
            grid-column: auto;
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
        <h1>Add Payroll</h1>
        <p>Create a new employee payroll record</p>
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

        <form action="/payrolls" method="POST">

            @csrf

            <div class="form-grid">

                <div class="section-title">
                    Employee & Pay Information
                </div>

                <div class="info-box">
                    Payroll calculations such as overtime pay, SSS,
                    PhilHealth, Pag-IBIG, gross pay, total deductions,
                    and net pay are automatically calculated by the
                    Payroll Service.
                </div>

                <div class="form-group">
                    <label for="employee_id">Employee ID</label>

                    <input
                        type="text"
                        id="employee_id"
                        name="employee_id"
                        value="{{ old('employee_id') }}"
                        placeholder="Example: emp2"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="pay_date">Pay Date</label>

                    <input
                        type="date"
                        id="pay_date"
                        name="pay_date"
                        value="{{ old('pay_date', date('Y-m-d')) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="basic_salary">Basic Salary</label>

                    <input
                        type="number"
                        id="basic_salary"
                        name="basic_salary"
                        step="0.01"
                        min="0"
                        value="{{ old('basic_salary') }}"
                        placeholder="1500.00"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="overtime_hours">Overtime Hours</label>

                    <input
                        type="number"
                        id="overtime_hours"
                        name="overtime_hours"
                        step="0.01"
                        min="0"
                        value="{{ old('overtime_hours', 0) }}"
                        placeholder="0"
                    >
                </div>

                <div class="form-group">
                    <label for="allowances">Allowances</label>

                    <input
                        type="number"
                        id="allowances"
                        name="allowances"
                        step="0.01"
                        min="0"
                        value="{{ old('allowances', 0) }}"
                        placeholder="0.00"
                    >
                </div>

                <div class="form-group">
                    <label for="other_deductions">Other Deductions</label>

                    <input
                        type="number"
                        id="other_deductions"
                        name="other_deductions"
                        step="0.01"
                        min="0"
                        value="{{ old('other_deductions', 0) }}"
                        placeholder="0.00"
                    >
                </div>

            </div>

            <div class="buttons">

                <button type="submit" class="btn save">
                    Save Payroll
                </button>

                <a href="/payrolls" class="btn cancel">
                    Cancel
                </a>

            </div>

        </form>

    </div>

@endsection