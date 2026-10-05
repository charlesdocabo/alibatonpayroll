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
                    <label for="employee_select">Select Registered Employee</label>
                    <select id="employee_select" class="form-select" onchange="autoFillEmployee(this)">
                        <option value="">-- Choose Employee (Auto-fill Data) --</option>
                        @foreach($employees ?? [] as $emp)
                            <option value="{{ $emp['employee_id'] }}"
                                data-salary="{{ $emp['salary'] ?? 0 }}"
                                data-name="{{ ($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? '') }}"
                                data-dept="{{ $emp['department'] ?? '' }}"
                                data-pos="{{ $emp['position'] ?? '' }}"
                                data-allowance="{{ $employeeAllowances[$emp['employee_id']] ?? 0 }}"
                                data-incentive="{{ $employeeIncentives[$emp['employee_id']] ?? 0 }}"
                                {{ old('employee_id') == $emp['employee_id'] ? 'selected' : '' }}
                            >
                                {{ $emp['employee_id'] }} - {{ $emp['first_name'] ?? '' }} {{ $emp['last_name'] ?? '' }} ({{ $emp['position'] ?? 'Employee' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="employee_id">Employee ID <span style="color:#d9534f">*</span></label>
                    <input
                        type="text"
                        id="employee_id"
                        name="employee_id"
                        value="{{ old('employee_id') }}"
                        placeholder="Example: EMP001"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="pay_date">Pay Date <span style="color:#d9534f">*</span></label>
                    <input
                        type="date"
                        id="pay_date"
                        name="pay_date"
                        value="{{ old('pay_date', date('Y-m-d')) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="basic_salary">Basic Salary (₱) <span style="color:#d9534f">*</span></label>
                    <input
                        type="number"
                        id="basic_salary"
                        name="basic_salary"
                        step="0.01"
                        min="0"
                        value="{{ old('basic_salary') }}"
                        placeholder="0.00"
                        oninput="calculateLivePreview()"
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
                        oninput="calculateLivePreview()"
                    >
                </div>

                <div class="form-group">
                    <label for="allowances">Allowances (₱)</label>
                    <input
                        type="number"
                        id="allowances"
                        name="allowances"
                        step="0.01"
                        min="0"
                        value="{{ old('allowances', 0) }}"
                        placeholder="0.00"
                        oninput="calculateLivePreview()"
                    >
                </div>

                <div class="form-group">
                    <label for="incentives">Approved Incentives & Bonuses (₱)</label>
                    <input
                        type="number"
                        id="incentives"
                        name="incentives"
                        step="0.01"
                        min="0"
                        value="{{ old('incentives', 0) }}"
                        placeholder="0.00"
                        oninput="calculateLivePreview()"
                    >
                </div>

                <div class="form-group">
                    <label for="other_deductions">Other Deductions (₱)</label>
                    <input
                        type="number"
                        id="other_deductions"
                        name="other_deductions"
                        step="0.01"
                        min="0"
                        value="{{ old('other_deductions', 0) }}"
                        placeholder="0.00"
                        oninput="calculateLivePreview()"
                    >
                </div>

                <!-- LIVE COMPUTATION SUMMARY PREVIEW -->
                <div class="section-title" style="margin-top:20px;">
                    Automated Calculation Preview
                </div>

                <div class="info-box" style="background:#f9f9f9; border-left:4px solid #2e7d32; display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px;">
                    <div><strong>Overtime Pay:</strong><br><span id="preview_ot" style="font-size:16px; color:#111;">₱0.00</span></div>
                    <div><strong>Gross Pay:</strong><br><span id="preview_gross" style="font-size:16px; color:#111; font-weight:bold;">₱0.00</span></div>
                    <div><strong>SSS (5%):</strong><br><span id="preview_sss" style="font-size:16px; color:#c62828;">₱0.00</span></div>
                    <div><strong>PhilHealth (2.5%):</strong><br><span id="preview_philhealth" style="font-size:16px; color:#c62828;">₱0.00</span></div>
                    <div><strong>Pag-IBIG (2%):</strong><br><span id="preview_pagibig" style="font-size:16px; color:#c62828;">₱0.00</span></div>
                    <div><strong>Total Deductions:</strong><br><span id="preview_deductions" style="font-size:16px; color:#c62828; font-weight:bold;">₱0.00</span></div>
                    <div style="grid-column: 1 / -1; padding-top:8px; border-top:1px dashed #ccc;">
                        <strong>Estimated Net Salary:</strong>
                        <span id="preview_net" style="font-size:22px; color:#2e7d32; font-weight:800; margin-left:10px;">₱0.00</span>
                    </div>
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

    <script>
        function autoFillEmployee(selectEl) {
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            if (!selectedOpt || !selectedOpt.value) return;

            document.getElementById('employee_id').value = selectedOpt.value;

            const salary = parseFloat(selectedOpt.getAttribute('data-salary')) || 0;
            const allowance = parseFloat(selectedOpt.getAttribute('data-allowance')) || 0;
            const incentive = parseFloat(selectedOpt.getAttribute('data-incentive')) || 0;

            document.getElementById('basic_salary').value = salary.toFixed(2);
            document.getElementById('allowances').value = allowance.toFixed(2);
            document.getElementById('incentives').value = incentive.toFixed(2);

            calculateLivePreview();
        }

        function calculateLivePreview() {
            const basic = parseFloat(document.getElementById('basic_salary').value) || 0;
            const otHours = parseFloat(document.getElementById('overtime_hours').value) || 0;
            const allowances = parseFloat(document.getElementById('allowances').value) || 0;
            const incentives = parseFloat(document.getElementById('incentives').value) || 0;
            const otherDeductions = parseFloat(document.getElementById('other_deductions').value) || 0;

            // Overtime Pay formula: basic / 22 / 8 * 1.25 * otHours
            const otPay = (basic / 22 / 8) * 1.25 * otHours;

            // SSS (5%, cap 5000 to 35000)
            const sssBase = Math.min(Math.max(basic, 5000), 35000);
            const sss = sssBase * 0.05;

            // PhilHealth (2.5%, cap 10000 to 100000)
            const phBase = Math.min(Math.max(basic, 10000), 100000);
            const philhealth = phBase * 0.025;

            // Pag-IBIG (2%, cap 5000)
            const pagibigBase = Math.min(basic, 5000);
            const pagibig = pagibigBase * 0.02;

            const gross = basic + otPay + allowances + incentives;
            const totalDeductions = sss + philhealth + pagibig + otherDeductions;
            const net = gross - totalDeductions;

            document.getElementById('preview_ot').innerText = '₱' + otPay.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('preview_gross').innerText = '₱' + gross.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('preview_sss').innerText = '₱' + sss.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('preview_philhealth').innerText = '₱' + philhealth.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('preview_pagibig').innerText = '₱' + pagibig.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('preview_deductions').innerText = '₱' + totalDeductions.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('preview_net').innerText = '₱' + net.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        // Run preview on load if values exist
        document.addEventListener('DOMContentLoaded', function() {
            calculateLivePreview();
        });
    </script>

@endsection