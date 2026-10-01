<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profile - Alibaton Construction Inc.</title>

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

        .profile-wrapper {
            max-width: 1000px;
            margin: 0 auto;
        }

        .profile-header {
            margin-bottom: 25px;
        }

        .profile-header h1 {
            margin: 0;
            font-size: 30px;
            color: #111111;
        }

        .profile-header p {
            margin: 8px 0 0;
            color: #666666;
            font-size: 14px;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #e8f7e8;
            border: 1px solid #9bd19b;
            color: #246b24;
        }

        .alert-error {
            background: #fdeaea;
            border: 1px solid #e0a0a0;
            color: #9b1c1c;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 28px;
            margin-bottom: 24px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.07);
            border: 1px solid #e5e5e5;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 22px;
            padding-bottom: 16px;
            border-bottom: 1px solid #eeeeee;
        }

        .card-title {
            margin: 0;
            font-size: 20px;
            color: #111111;
        }

        .card-description {
            margin: 5px 0 0;
            color: #777777;
            font-size: 13px;
        }

        .role-badge {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            background: #f4c400;
            color: #111111;
            font-size: 12px;
            font-weight: bold;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 2px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: bold;
            color: #333333;
        }

        input {
            width: 100%;
            padding: 12px 13px;
            border: 1px solid #cccccc;
            border-radius: 7px;
            background: #ffffff;
            color: #111111;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus {
            border-color: #f4c400;
            box-shadow: 0 0 0 3px rgba(244, 196, 0, 0.18);
        }

        input[readonly] {
            background: #f3f3f3;
            color: #666666;
            cursor: not-allowed;
        }

        .field-note {
            margin-top: 6px;
            font-size: 12px;
            color: #777777;
        }

        .button-row {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
        }

        .primary-button {
            border: none;
            background: #f4c400;
            color: #111111;
            padding: 12px 20px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }

        .primary-button:hover {
            background: #dcae00;
            transform: translateY(-1px);
        }

        .secondary-button {
            border: 1px solid #cccccc;
            background: #ffffff;
            color: #333333;
            padding: 12px 20px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .secondary-button:hover {
            background: #f3f3f3;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 80px;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #555555;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
        }

        .password-toggle:hover {
            color: #111111;
        }

        .requirements {
            background: #fafafa;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 15px;
            margin-top: 18px;
        }

        .requirements-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 9px;
        }

        .requirement {
            font-size: 12px;
            margin: 5px 0;
            color: #666666;
        }

        .requirement.valid {
            color: #238023;
        }

        .security-note {
            background: #fff9dc;
            border-left: 4px solid #f4c400;
            padding: 13px 15px;
            margin-top: 18px;
            border-radius: 5px;
            font-size: 13px;
            color: #555555;
        }

        .info-box {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 22px;
        }

        .info-item {
            background: #f8f8f8;
            border-radius: 8px;
            padding: 14px;
            border: 1px solid #eeeeee;
        }

        .info-label {
            font-size: 11px;
            color: #777777;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 14px;
            font-weight: bold;
            color: #222222;
        }

        .danger-card {
            border: 1px solid #efc5c5;
        }

        .danger-title {
            color: #9b1c1c;
        }

        .danger-description {
            font-size: 13px;
            color: #666666;
            line-height: 1.5;
            margin-bottom: 18px;
        }

        .danger-button {
            border: none;
            background: #b42318;
            color: #ffffff;
            padding: 11px 18px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
        }

        .danger-button:hover {
            background: #8f1c14;
        }

        .error-list {
            margin: 0;
            padding-left: 20px;
        }

        .error-list li {
            margin-bottom: 5px;
        }

        @media (max-width: 800px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .info-box {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .card {
                padding: 20px;
            }

            .profile-header h1 {
                font-size: 25px;
            }

            .info-box {
                grid-template-columns: 1fr;
            }

            .card-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .button-row {
                justify-content: stretch;
                flex-direction: column;
            }

            .primary-button,
            .secondary-button,
            .danger-button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

@include('partials.sidebar')

<div class="main-content">

    <div class="profile-wrapper">

        <!-- PAGE HEADER -->

        <div class="profile-header">
            <h1>My Profile</h1>

            <p>
                Manage your account and personal information.
            </p>
        </div>


        <!-- SUCCESS MESSAGE -->

        @if(session('status') === 'profile-updated')

            <div class="alert alert-success">
                Your account profile has been updated successfully.
            </div>

        @endif


        @if(session('status') === 'employee-profile-updated')

            <div class="alert alert-success">
                Your personal information has been updated successfully.
            </div>

        @endif


        @if(session('status') === 'password-updated')

            <div class="alert alert-success">
                Your password has been changed successfully.
            </div>

        @endif


        <!-- ERROR MESSAGE -->

        @if($errors->any())

            <div class="alert alert-error">

                <strong>Please check the following:</strong>

                <ul class="error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif


        <!-- ACCOUNT INFORMATION -->

        <div class="card">

            <div class="card-header">

                <div>
                    <h2 class="card-title">
                        Account Information
                    </h2>

                    <p class="card-description">
                        Update the information associated with your login account.
                    </p>
                </div>

                <span class="role-badge">
                    {{ $user->role }}
                </span>

            </div>


            <form
                method="POST"
                action="{{ route('profile.update') }}"
            >

                @csrf
                @method('PATCH')


                <div class="form-grid">

                    <!-- NAME -->

                    <div class="form-group">

                        <label for="name">
                            Full Name
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            maxlength="255"
                        >

                    </div>


                    <!-- EMAIL -->

<div class="form-group">

    <label for="email">
        Email Address
    </label>

    <input
        id="email"
        type="email"
        name="email"
        value="{{ old('email', $user->email) }}"
        required
        maxlength="255"
        @if($user->role === 'Employee') readonly @endif
    >

    @if($user->role === 'Employee')
        <div class="field-note">
            Your email address cannot be changed directly.
            Employees must submit an Email Change Request for Admin approval.
        </div>
    @endif

</div>

                </div>


                <div class="button-row">

                    <button
                        type="submit"
                        class="primary-button"
                    >
                        Save Account Information
                    </button>

                </div>

            </form>

        </div>


        <!-- EMPLOYEE PERSONAL INFORMATION -->

        @if($user->role === 'Employee')

            <div class="card">

                <div class="card-header">

                    <div>
                        <h2 class="card-title">
                            Personal Information
                        </h2>
@if($user->role === 'Employee')

    <div class="card">
        <div class="card-header">
            <div>
                <h2>Request Email Change</h2>
                <p class="card-description">
                    If you no longer have access to your current Gmail,
                    you may request a new email address.
                </p>
            </div>

            <span class="status-badge warning">
                Admin Approval Required
            </span>
        </div>

        @if(session('status') === 'email-change-requested')
            <div class="alert alert-success">
                Your email change request has been submitted successfully.
                Please wait for Admin approval.
            </div>
        @endif

        @if($pendingEmailChangeRequest)

            <div class="info-box">
                <strong>Pending Email Change Request</strong>

                <p>
                    Your request is currently waiting for Admin review.
                </p>

                <div class="request-details">
                    <div>
                        <strong>Current Email:</strong>
                        {{ $pendingEmailChangeRequest->current_email }}
                    </div>

                    <div>
                        <strong>Requested Email:</strong>
                        {{ $pendingEmailChangeRequest->requested_email }}
                    </div>

                    <div>
                        <strong>Reason:</strong>
                        {{ $pendingEmailChangeRequest->reason }}
                    </div>

                    <div>
                        <strong>Submitted:</strong>
                        {{ $pendingEmailChangeRequest->created_at->format('M d, Y h:i A') }}
                    </div>
                </div>
            </div>

        @else

            <form
                method="POST"
                action="{{ route('profile.email-change-request.store') }}"
            >
                @csrf

                <div class="form-group">
                    <label for="current_email_display">
                        Current Email Address
                    </label>

                    <input
                        id="current_email_display"
                        type="email"
                        value="{{ $user->email }}"
                        readonly
                    >

                    <div class="field-note">
                        Your current email cannot be changed directly.
                    </div>
                </div>

                <div class="form-group">
                    <label for="requested_email">
                        New Gmail Address
                    </label>

                    <input
                        id="requested_email"
                        type="email"
                        name="requested_email"
                        value="{{ old('requested_email') }}"
                        placeholder="Enter your new Gmail address"
                        required
                        maxlength="255"
                    >

                    @error('requested_email')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="reason">
                        Reason for Email Change
                    </label>

                    <textarea
                        id="reason"
                        name="reason"
                        rows="4"
                        maxlength="1000"
                        placeholder="Explain why you need to change your email address."
                        required
                    >{{ old('reason') }}</textarea>

                    @error('reason')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                @error('email_change')
                    <div class="field-error">
                        {{ $message }}
                    </div>
                @enderror

                <button type="submit" class="primary-button">
                    Submit Email Change Request
                </button>
            </form>

        @endif
    </div>

@endif

                        <p class="card-description">
                            Update your personal employee information.
                        </p>
                    </div>

                    <span class="role-badge">
                        Employee
                    </span>

                </div>


                @if($employee)

                    <!-- READ-ONLY EMPLOYEE INFORMATION -->

                    <div class="info-box">

                        <div class="info-item">

                            <div class="info-label">
                                Employee ID
                            </div>

                            <div class="info-value">
                                {{ $employee['employee_id'] ?? 'N/A' }}
                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Position
                            </div>

                            <div class="info-value">
                                {{ $employee['position'] ?? 'N/A' }}
                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Department
                            </div>

                            <div class="info-value">
                                {{ $employee['department'] ?? 'N/A' }}
                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Status
                            </div>

                            <div class="info-value">
                                {{ ucfirst($employee['status'] ?? 'N/A') }}
                            </div>

                        </div>

                    </div>


                    <!-- EMPLOYEE PERSONAL FORM -->

                    <form
                        method="POST"
                        action="{{ route('profile.employee.update') }}"
                    >

                        @csrf
                        @method('PATCH')


                        <div class="form-grid">

                            <!-- FIRST NAME -->

                            <div class="form-group">

                                <label for="first_name">
                                    First Name
                                </label>

                                <input
                                    id="first_name"
                                    type="text"
                                    name="first_name"
                                    value="{{ old('first_name', $employee['first_name'] ?? '') }}"
                                    required
                                    maxlength="100"
                                >

                            </div>


                            <!-- LAST NAME -->

                            <div class="form-group">

                                <label for="last_name">
                                    Last Name
                                </label>

                                <input
                                    id="last_name"
                                    type="text"
                                    name="last_name"
                                    value="{{ old('last_name', $employee['last_name'] ?? '') }}"
                                    required
                                    maxlength="100"
                                >

                            </div>


                            <!-- PHONE -->

                            <div class="form-group">

                                <label for="phone">
                                    Phone Number
                                </label>

                                <input
                                    id="phone"
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', $employee['phone'] ?? '') }}"
                                    maxlength="30"
                                    placeholder="Enter phone number"
                                >

                            </div>


                            <!-- EMPLOYEE ID -->

                            <div class="form-group">

                                <label for="employee_id_display">
                                    Employee ID
                                </label>

                                <input
                                    id="employee_id_display"
                                    type="text"
                                    value="{{ $employee['employee_id'] ?? $user->employee_id }}"
                                    readonly
                                >

                                <div class="field-note">
                                    Employee ID cannot be changed from your profile.
                                </div>

                            </div>

                        </div>


                        <div class="security-note">
                            Your position, department, salary, government numbers,
                            employment status, and Employee ID are managed by
                            authorized HR/Admin personnel.
                        </div>


                        <div class="button-row">

                            <button
                                type="submit"
                                class="primary-button"
                            >
                                Save Personal Information
                            </button>

                        </div>

                    </form>

                @else

                    <div class="alert alert-error">
                        Your account is not currently linked to an employee record.
                        Please contact your administrator.
                    </div>

                @endif

            </div>

        @endif


        <!-- CHANGE PASSWORD -->

        <div class="card">

            <div class="card-header">

                <div>
                    <h2 class="card-title">
                        Change Password
                    </h2>

                    <p class="card-description">
                        Change your password using email OTP verification.
                    </p>
                </div>

            </div>


            <form
                method="POST"
                action="{{ route('profile.password.update') }}"
            >

                @csrf
                @method('PUT')


                <div class="form-grid">

                    <!-- CURRENT PASSWORD -->

                    <div class="form-group full">

                        <label for="current_password">
                            Current Password
                        </label>

                        <div class="password-wrapper">

                            <input
                                id="current_password"
                                type="password"
                                name="current_password"
                                required
                                autocomplete="current-password"
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword('current_password', this)"
                            >
                                Show
                            </button>

                        </div>

                    </div>


                    <!-- NEW PASSWORD -->

                    <div class="form-group">

                        <label for="password">
                            New Password
                        </label>

                        <div class="password-wrapper">

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword('password', this)"
                            >
                                Show
                            </button>

                        </div>

                    </div>


                    <!-- CONFIRM PASSWORD -->

                    <div class="form-group">

                        <label for="password_confirmation">
                            Confirm New Password
                        </label>

                        <div class="password-wrapper">

                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                minlength="8"
                                autocomplete="new-password"
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword('password_confirmation', this)"
                            >
                                Show
                            </button>

                        </div>

                    </div>

                </div>


                <!-- PASSWORD REQUIREMENTS -->

                <div class="requirements">

                    <div class="requirements-title">
                        Password Requirements
                    </div>

                    <div
                        id="req-length"
                        class="requirement"
                    >
                        [ ] At least 8 characters
                    </div>

                    <div
                        id="req-uppercase"
                        class="requirement"
                    >
                        [ ] At least one uppercase letter
                    </div>

                    <div
                        id="req-lowercase"
                        class="requirement"
                    >
                        [ ] At least one lowercase letter
                    </div>

                    <div
                        id="req-number"
                        class="requirement"
                    >
                        [ ] At least one number
                    </div>

                    <div
                        id="req-special"
                        class="requirement"
                    >
                        [ ] At least one special character
                        (@ $ ! % * ? &)
                    </div>

                    <div
                        id="req-match"
                        class="requirement"
                    >
                        [ ] Passwords match
                    </div>

                </div>


                <div class="security-note">
                    After submitting your new password, a 6-digit OTP will
                    be sent to your registered email address. The OTP is
                    valid for 60 seconds and has a maximum of 3 attempts.
                </div>


                <div class="button-row">

                    <button
                        type="submit"
                        class="primary-button"
                    >
                        Request OTP & Change Password
                    </button>

                </div>

            </form>

        </div>


        <!-- DELETE ACCOUNT -->

        <div class="card danger-card">

            <div class="card-header">

                <div>

                    <h2 class="card-title danger-title">
                        Delete Account
                    </h2>

                    <p class="card-description">
                        Permanently delete your account and sign out.
                    </p>

                </div>

            </div>


            <p class="danger-description">
                This action cannot be undone. You will be required to
                enter your current password before your account is deleted.
            </p>


            <form
                method="POST"
                action="{{ route('profile.destroy') }}"
                onsubmit="return confirmDeleteAccount();"
            >

                @csrf
                @method('DELETE')


                <div class="form-group">

                    <label for="delete_password">
                        Current Password
                    </label>

                    <div class="password-wrapper">

                        <input
                            id="delete_password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword('delete_password', this)"
                        >
                            Show
                        </button>

                    </div>

                </div>


                <div class="button-row">

                    <button
                        type="submit"
                        class="danger-button"
                    >
                        Delete Account
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

    /*
    |--------------------------------------------------------------------------
    | Password Visibility
    |--------------------------------------------------------------------------
    */

    function togglePassword(inputId, button) {

        const input = document.getElementById(inputId);

        if (!input) {
            return;
        }

        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = 'Hide';
        } else {
            input.type = 'password';
            button.textContent = 'Show';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Password Requirements
    |--------------------------------------------------------------------------
    */

    const passwordInput = document.getElementById('password');

    const confirmationInput =
        document.getElementById('password_confirmation');


    function updateRequirement(elementId, valid) {

        const element = document.getElementById(elementId);

        if (!element) {
            return;
        }

        if (valid) {
            element.classList.add('valid');

            element.textContent =
                element.textContent.replace('[ ]', '[OK]');
        } else {
            element.classList.remove('valid');

            element.textContent =
                element.textContent.replace('[OK]', '[ ]');
        }
    }


    function checkPasswordRequirements() {

        if (!passwordInput) {
            return;
        }

        const password = passwordInput.value;

        updateRequirement(
            'req-length',
            password.length >= 8
        );

        updateRequirement(
            'req-uppercase',
            /[A-Z]/.test(password)
        );

        updateRequirement(
            'req-lowercase',
            /[a-z]/.test(password)
        );

        updateRequirement(
            'req-number',
            /[0-9]/.test(password)
        );

        updateRequirement(
            'req-special',
            /[@$!%*?&]/.test(password)
        );

        if (confirmationInput) {

            updateRequirement(
                'req-match',
                password.length > 0 &&
                password === confirmationInput.value
            );

        }
    }


    if (passwordInput) {
        passwordInput.addEventListener(
            'input',
            checkPasswordRequirements
        );
    }


    if (confirmationInput) {
        confirmationInput.addEventListener(
            'input',
            checkPasswordRequirements
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Confirmation
    |--------------------------------------------------------------------------
    */

    function confirmDeleteAccount() {

        return confirm(
            'Are you sure you want to permanently delete your account? This action cannot be undone.'
        );
    }

</script>

</body>
</html>