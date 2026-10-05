<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Incentive | Alibaton Construction Inc.</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f5f5;
            color: #111111;
        }

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            padding: 30px;
        }

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
            min-height: 110px;
            resize: vertical;
        }

        input:focus,
        select:focus,
        textarea:focus {
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
            .main-content {
                margin-left: 220px;
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>

    @include('partials.sidebar')

    <div class="main-content">

        <div class="page-header">

            <h1 class="page-title">
                Edit Employee Incentive
            </h1>

            <p class="page-subtitle">
                Update the performance bonus or incentive information.
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
                Incentive Information
            </h2>


            <form action="/incentives/{{ $incentive['id'] }}" method="POST">

                @csrf
                @method('PUT')

                <div class="form-grid">

                    <div class="form-group">

                        <label for="employee_id">
                            Employee <span class="required">*</span>
                        </label>

                        @if(isset($employees) && count($employees) > 0)
                            <select id="employee_id" name="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp['employee_id'] ?? '' }}"
                                        {{ old('employee_id', $incentive['employee_id'] ?? '') === ($emp['employee_id'] ?? '') ? 'selected' : '' }}>
                                        {{ ($emp['employee_id'] ?? '') }} — {{ ($emp['first_name'] ?? '') }} {{ ($emp['last_name'] ?? '') }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input
                                type="text"
                                id="employee_id"
                                name="employee_id"
                                value="{{ old('employee_id', $incentive['employee_id'] ?? '') }}"
                                placeholder="Example: EMP001"
                                required
                            >
                        @endif

                        @error('employee_id')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label for="incentive_type">
                            Incentive Type <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="incentive_type"
                            name="incentive_type"
                            value="{{ old('incentive_type', $incentive['incentive_type'] ?? '') }}"
                            placeholder="Example: Performance Bonus"
                            required
                        >

                        @error('incentive_type')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="form-group full">

                        <label for="description">
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            placeholder="Enter details about the incentive..."
                        >{{ old('description', $incentive['description'] ?? '') }}</textarea>

                        @error('description')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label for="amount">
                            Incentive Amount <span class="required">*</span>
                        </label>

                        <input
                            type="number"
                            id="amount"
                            name="amount"
                            value="{{ old('amount', $incentive['amount'] ?? '') }}"
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

                        <label for="incentive_date">
                            Incentive Date <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            id="incentive_date"
                            name="incentive_date"
                            value="{{ old('incentive_date', isset($incentive['incentive_date']) ? date('Y-m-d', strtotime($incentive['incentive_date'])) : date('Y-m-d')) }}"
                            required
                        >

                        @error('incentive_date')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label for="payroll_period">
                            Applicable Payroll Period
                        </label>

                        <input
                            type="month"
                            id="payroll_period"
                            name="payroll_period"
                            value="{{ old('payroll_period', $incentive['payroll_period'] ?? '') }}"
                        >

                        @error('payroll_period')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label for="approved_by">
                            Approved By
                        </label>

                        <input
                            type="text"
                            id="approved_by"
                            name="approved_by"
                            value="{{ old('approved_by', $incentive['approved_by'] ?? '') }}"
                            placeholder="Approver name"
                        >

                        @error('approved_by')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label for="status">
                            Status <span class="required">*</span>
                        </label>

                        <select
                            id="status"
                            name="status"
                            required
                        >

                            <option value="pending"
                                {{ old('status', $incentive['status'] ?? '') === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="approved"
                                {{ old('status', $incentive['status'] ?? '') === 'approved' ? 'selected' : '' }}>
                                Approved
                            </option>

                            <option value="released"
                                {{ old('status', $incentive['status'] ?? '') === 'released' ? 'selected' : '' }}>
                                Released
                            </option>

                            <option value="cancelled"
                                {{ old('status', $incentive['status'] ?? '') === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>

                </div>


                <div class="form-actions">

                    <a href="/incentives" class="cancel-button">
                        Cancel
                    </a>

                    <button type="submit" class="save-button">
                        Update Incentive
                    </button>

                </div>

            </form>

        </div>

    </div>

</body>
</html>