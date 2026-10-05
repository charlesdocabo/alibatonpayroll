<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Salary Grade - Alibaton Construction Inc.</title>

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

        .page-header h1 {
            margin: 0;
            font-size: 28px;
        }

        .page-header p {
            margin: 6px 0 0;
            color: #777777;
            font-size: 14px;
        }

        .form-card {
            background: #ffffff;
            max-width: 850px;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        input,
        textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 14px;
            font-family: Arial, Helvetica, sans-serif;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #f4c400;
            box-shadow: 0 0 0 2px rgba(244, 196, 0, 0.15);
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .error {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }

        .alert-error {
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f1aeb5;
            padding: 13px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .button-row {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .update-button {
            background: #f4c400;
            color: #111111;
            border: none;
            padding: 11px 20px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }

        .update-button:hover {
            background: #dcae00;
        }

        .cancel-button {
            background: #eeeeee;
            color: #111111;
            text-decoration: none;
            padding: 11px 20px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
        }

        .cancel-button:hover {
            background: #dddddd;
        }

        .helper-text {
            color: #777777;
            font-size: 12px;
            margin-top: 5px;
        }

        .current-id {
            display: inline-block;
            background: #111111;
            color: #f4c400;
            padding: 5px 9px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 8px;
        }

        @media (max-width: 900px) {
            .main-content {
                margin-left: 220px;
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
</head>

<body>

    @include('partials.sidebar')

    <div class="main-content">

        <div class="page-header">

            <h1>Edit Salary Grade</h1>

            <p>
                Update the salary grade information.
            </p>

            <span class="current-id">
                ID: {{ $salaryGrade['id'] ?? '-' }}
            </span>

        </div>

        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                Please correct the following errors:

                <ul style="margin-bottom:0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">

            <form action="/salary-grades/{{ $salaryGrade['id'] }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">

                    <label for="grade_name">
                        Grade Name
                    </label>

                    <input type="text"
                           id="grade_name"
                           name="grade_name"
                           value="{{ old('grade_name', $salaryGrade['grade_name'] ?? '') }}"
                           placeholder="Example: Grade 3"
                           required>

                    @error('grade_name')
                        <div class="error">{{ $message }}</div>
                    @enderror

                </div>

                <div class="form-group">

                    <label for="effective_date">
                        Effective Date
                    </label>

                    <input type="date"
                           id="effective_date"
                           name="effective_date"
                           value="{{ old('effective_date', isset($salaryGrade['effective_date']) ? date('Y-m-d', strtotime($salaryGrade['effective_date'])) : '') }}">

                    <div class="helper-text">
                        The date this salary grade schedule takes effect.
                    </div>

                    @error('effective_date')
                        <div class="error">{{ $message }}</div>
                    @enderror

                </div>

                <div class="form-group">

                    <label for="description">
                        Description
                    </label>

                    <textarea id="description"
                              name="description"
                              placeholder="Describe the employee level or position covered by this salary grade.">{{ old('description', $salaryGrade['description'] ?? '') }}</textarea>

                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror

                </div>

                <div class="form-row">

                    <div class="form-group">

                        <label for="minimum_salary">
                            Minimum Salary
                        </label>

                        <input type="number"
                               id="minimum_salary"
                               name="minimum_salary"
                               value="{{ old('minimum_salary', $salaryGrade['minimum_salary'] ?? '') }}"
                               min="0"
                               step="0.01"
                               required>

                        <div class="helper-text">
                            Lowest salary allowed for this grade.
                        </div>

                        @error('minimum_salary')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="maximum_salary">
                            Maximum Salary
                        </label>

                        <input type="number"
                               id="maximum_salary"
                               name="maximum_salary"
                               value="{{ old('maximum_salary', $salaryGrade['maximum_salary'] ?? '') }}"
                               min="0"
                               step="0.01"
                               required>

                        <div class="helper-text">
                            Highest salary allowed for this grade.
                        </div>

                        @error('maximum_salary')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                <div class="button-row">

                    <button type="submit"
                            class="update-button">
                        Update Salary Grade
                    </button>

                    <a href="/salary-grades"
                       class="cancel-button">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>
</html>