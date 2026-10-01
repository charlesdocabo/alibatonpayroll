@extends('layouts.app')

@section('title', 'Create User')

@section('content')

<style>
    .create-user-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .create-user-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .create-user-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .create-user-header h2 {
        margin: 0 0 5px;
        color: #111111;
    }

    .create-user-header p {
        margin: 0;
        color: #777777;
        font-size: 13px;
    }

    .back-button {
        text-decoration: none;
        background: #eeeeee;
        color: #111111;
        padding: 10px 15px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: bold;
    }

    .back-button:hover {
        background: #dddddd;
    }

    .error-box {
        background: #ffe5e5;
        color: #a00000;
        padding: 15px;
        border-radius: 7px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .error-box ul {
        margin: 8px 0 0 20px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        font-weight: bold;
        font-size: 13px;
        color: #111111;
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: 11px;
        border: 1px solid #cccccc;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-input:focus,
    .form-select:focus {
        border-color: #f4c400;
        box-shadow: 0 0 0 3px rgba(244, 196, 0, 0.15);
    }

    .form-select {
        background: #ffffff;
        cursor: pointer;
    }

    .employee-help {
        margin-top: 6px;
        font-size: 12px;
        color: #777777;
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper .form-input {
        padding-right: 48px;
    }

    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        cursor: pointer;
        font-size: 16px;
        color: #555555;
        padding: 5px;
    }

    .password-toggle:hover {
        color: #111111;
    }

    .password-toggle:focus {
        outline: 2px solid #f4c400;
        border-radius: 4px;
    }

    .password-requirements {
        margin-top: 10px;
        padding: 13px 15px;
        background: #f5f5f5;
        border-left: 4px solid #f4c400;
        border-radius: 6px;
        font-size: 13px;
    }

    .password-requirements-title {
        font-weight: bold;
        color: #111111;
        margin-bottom: 8px;
    }

    .requirement {
        margin: 5px 0;
        color: #777777;
        transition: color 0.2s;
    }

    .requirement.valid {
        color: #16803c;
        font-weight: 600;
    }

    .requirement-icon {
        display: inline-block;
        width: 20px;
        font-weight: bold;
    }

    .password-example {
        margin-top: 10px;
        padding-top: 9px;
        border-top: 1px solid #dddddd;
        color: #555555;
    }

    .password-example strong {
        color: #111111;
    }

    .password-match {
        margin-top: 7px;
        font-size: 12px;
        min-height: 18px;
    }

    .password-match.valid {
        color: #16803c;
    }

    .password-match.invalid {
        color: #a00000;
    }

    .create-user-button {
        width: 100%;
        border: none;
        background: #f4c400;
        color: #111111;
        padding: 13px;
        border-radius: 7px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
    }

    .create-user-button:hover {
        background: #dcae00;
    }

    .create-user-button:active {
        transform: scale(0.99);
    }

    .hidden {
        display: none;
    }

    @media (max-width: 600px) {
        .create-user-container {
            max-width: 100%;
        }

        .create-user-card {
            padding: 20px;
        }

        .create-user-header {
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>

<div class="create-user-container">

    <div class="create-user-card">

        <div class="create-user-header">

            <div>
                <h2>Create User</h2>
                <p>Create a new system account.</p>
            </div>

            <a href="{{ route('users.index') }}" class="back-button">
                &larr; Back
            </a>

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

        <form
            method="POST"
            action="{{ route('users.store') }}"
            autocomplete="off"
        >

            @csrf

            <!-- NAME -->
            <div class="form-group">

                <label for="name" class="form-label">
                    Full Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    maxlength="255"
                    class="form-input"
                    placeholder="Enter full name"
                >

            </div>

            <!-- EMAIL -->
            <div class="form-group">

                <label for="email" class="form-label">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    maxlength="255"
                    class="form-input"
                    placeholder="Enter email address"
                >

            </div>

            <!-- ROLE -->
            <div class="form-group">

                <label for="role" class="form-label">
                    Role
                </label>

                <select
                    id="role"
                    name="role"
                    class="form-select"
                    required
                >
                    <option value="">-- Select Role --</option>

                    <option
                        value="Admin"
                        {{ old('role') === 'Admin' ? 'selected' : '' }}
                    >
                        Admin
                    </option>

                    <option
                        value="HR"
                        {{ old('role') === 'HR' ? 'selected' : '' }}
                    >
                        HR
                    </option>

                    <option
                        value="Employee"
                        {{ old('role') === 'Employee' ? 'selected' : '' }}
                    >
                        Employee
                    </option>

                </select>

            </div>

            <!-- EMPLOYEE RECORD -->
            <div
                class="form-group hidden"
                id="employeeGroup"
            >

                <label for="employee_id" class="form-label">
                    Employee Record
                </label>

                <select
                    id="employee_id"
                    name="employee_id"
                    class="form-select"
                >

                    <option value="">
                        -- Select Employee --
                    </option>

                    @foreach ($employees as $employee)

                        <option
                            value="{{ $employee['employee_id'] ?? '' }}"
                            {{ old('employee_id') === ($employee['employee_id'] ?? '') ? 'selected' : '' }}
                        >
                            {{ $employee['employee_id'] ?? 'N/A' }}
                            -
                            {{ $employee['first_name'] ?? '' }}
                            {{ $employee['last_name'] ?? '' }}
                        </option>

                    @endforeach

                </select>

                <div class="employee-help">
                    Select the employee record that will be linked to this account.
                </div>

            </div>

            <!-- PASSWORD -->
            <div class="form-group">

                <label for="password" class="form-label">
                    Password
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        minlength="8"
                        class="form-input"
                        autocomplete="new-password"
                        placeholder="Enter password"
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        id="passwordToggle"
                        onclick="togglePassword('password', 'passwordToggle')"
                        aria-label="Show password"
                        title="Show password"
                    >
                        Show
                    </button>

                </div>

                <div class="password-requirements">

                    <div class="password-requirements-title">
                        Password Requirements
                    </div>

                    <div
                        class="requirement"
                        id="requirement-length"
                    >
                        <span class="requirement-icon">o</span>
                        At least 8 characters
                    </div>

                    <div
                        class="requirement"
                        id="requirement-uppercase"
                    >
                        <span class="requirement-icon">o</span>
                        At least 1 uppercase letter (A-Z)
                    </div>

                    <div
                        class="requirement"
                        id="requirement-lowercase"
                    >
                        <span class="requirement-icon">o</span>
                        At least 1 lowercase letter (a-z)
                    </div>

                    <div
                        class="requirement"
                        id="requirement-number"
                    >
                        <span class="requirement-icon">o</span>
                        At least 1 number (0-9)
                    </div>

                    <div
                        class="requirement"
                        id="requirement-special"
                    >
                        <span class="requirement-icon">o</span>
                        At least 1 special character (@ $ ! % * ? &)
                    </div>

                    <div class="password-example">
                        Example:
                        <strong>Admin@12345</strong>
                    </div>

                </div>

            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="form-group">

                <label
                    for="password_confirmation"
                    class="form-label"
                >
                    Confirm Password
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        minlength="8"
                        class="form-input"
                        autocomplete="new-password"
                        placeholder="Confirm password"
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        id="confirmPasswordToggle"
                        onclick="togglePassword(
                            'password_confirmation',
                            'confirmPasswordToggle'
                        )"
                        aria-label="Show password"
                        title="Show password"
                    >
                        Show
                    </button>

                </div>

                <div
                    id="passwordMatch"
                    class="password-match"
                ></div>

            </div>

            <!-- SUBMIT -->
            <button
                type="submit"
                class="create-user-button"
            >
                Create User
            </button>

        </form>

    </div>

</div>

<script>

    /*
    |--------------------------------------------------------------------------
    | Show / Hide Password
    |--------------------------------------------------------------------------
    */

    function togglePassword(inputId, buttonId) {

        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);

        if (input.type === 'password') {

            input.type = 'text';

            button.textContent = 'Hide';
            button.setAttribute('aria-label', 'Hide password');
            button.setAttribute('title', 'Hide password');

        } else {

            input.type = 'password';

            button.textContent = 'Show';
            button.setAttribute('aria-label', 'Show password');
            button.setAttribute('title', 'Show password');

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Role / Employee Mapping
    |--------------------------------------------------------------------------
    */

    const roleInput = document.getElementById('role');
    const employeeGroup = document.getElementById('employeeGroup');
    const employeeInput = document.getElementById('employee_id');

    function updateEmployeeField() {

        if (roleInput.value === 'Employee') {

            employeeGroup.classList.remove('hidden');
            employeeInput.required = true;

        } else {

            employeeGroup.classList.add('hidden');
            employeeInput.required = false;
            employeeInput.value = '';

        }
    }

    roleInput.addEventListener('change', updateEmployeeField);

    updateEmployeeField();


    /*
    |--------------------------------------------------------------------------
    | Password Requirements
    |--------------------------------------------------------------------------
    */

    const passwordInput = document.getElementById('password');

    const requirements = {
        length: document.getElementById('requirement-length'),
        uppercase: document.getElementById('requirement-uppercase'),
        lowercase: document.getElementById('requirement-lowercase'),
        number: document.getElementById('requirement-number'),
        special: document.getElementById('requirement-special')
    };

    function updateRequirement(element, valid) {

        const icon = element.querySelector('.requirement-icon');

        if (valid) {

            element.classList.add('valid');
            icon.textContent = 'OK';

        } else {

            element.classList.remove('valid');
            icon.textContent = 'o';

        }
    }

    function checkPasswordRequirements() {

        const password = passwordInput.value;

        updateRequirement(
            requirements.length,
            password.length >= 8
        );

        updateRequirement(
            requirements.uppercase,
            /[A-Z]/.test(password)
        );

        updateRequirement(
            requirements.lowercase,
            /[a-z]/.test(password)
        );

        updateRequirement(
            requirements.number,
            /[0-9]/.test(password)
        );

        updateRequirement(
            requirements.special,
            /[@$!%*?&]/.test(password)
        );

        checkPasswordMatch();
    }

    passwordInput.addEventListener(
        'input',
        checkPasswordRequirements
    );


    /*
    |--------------------------------------------------------------------------
    | Password Confirmation
    |--------------------------------------------------------------------------
    */

    const confirmPasswordInput =
        document.getElementById('password_confirmation');

    const passwordMatch =
        document.getElementById('passwordMatch');

    function checkPasswordMatch() {

        const password = passwordInput.value;
        const confirmation = confirmPasswordInput.value;

        if (confirmation === '') {

            passwordMatch.textContent = '';
            passwordMatch.className = 'password-match';

            return;
        }

        if (password === confirmation) {

            passwordMatch.textContent = 'Passwords match.';
            passwordMatch.className =
                'password-match valid';

        } else {

            passwordMatch.textContent =
                'Passwords do not match.';

            passwordMatch.className =
                'password-match invalid';
        }
    }

    confirmPasswordInput.addEventListener(
        'input',
        checkPasswordMatch
    );

</script>

@endsection