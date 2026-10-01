<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Change Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Update your password to keep your account secure.') }}
        </p>
    </header>

    @if (session('status') === 'password-updated')
        <div class="success-message">
            ✓ Password changed successfully.
        </div>
    @endif

    @if ($errors->updatePassword->any())
        <div class="error-message">
            <strong>Please fix the following:</strong>

            <ul>
                @foreach ($errors->updatePassword->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post"
          action="{{ route('profile.password.update') }}"
          class="password-form">

        @csrf
        @method('put')

        {{-- Current Password --}}
        <div class="form-group">
            <label for="current_password">
                Current Password
            </label>

            <div class="password-wrapper">
                <input
                    id="current_password"
                    name="current_password"
                    type="password"
                    autocomplete="current-password"
                    class="password-input"
                    required
                >

                <button
                    type="button"
                    class="password-toggle"
                    onclick="toggleProfilePassword('current_password', this)"
                    aria-label="Show current password"
                    title="Show password"
                >
                    👁
                </button>
            </div>
        </div>

        {{-- New Password --}}
        <div class="form-group">
            <label for="password">
                New Password
            </label>

            <div class="password-wrapper">
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="new-password"
                    class="password-input"
                    minlength="8"
                    required
                >

                <button
                    type="button"
                    class="password-toggle"
                    onclick="toggleProfilePassword('password', this)"
                    aria-label="Show new password"
                    title="Show password"
                >
                    👁
                </button>
            </div>

            {{-- Password Requirements --}}
            <div class="password-requirements">

                <div class="requirements-title">
                    Password Requirements
                </div>

                <div class="requirement" id="profile-requirement-length">
                    <span>○</span>
                    At least 8 characters
                </div>

                <div class="requirement" id="profile-requirement-uppercase">
                    <span>○</span>
                    At least 1 uppercase letter (A–Z)
                </div>

                <div class="requirement" id="profile-requirement-lowercase">
                    <span>○</span>
                    At least 1 lowercase letter (a–z)
                </div>

                <div class="requirement" id="profile-requirement-number">
                    <span>○</span>
                    At least 1 number (0–9)
                </div>

                <div class="requirement" id="profile-requirement-special">
                    <span>○</span>
                    At least 1 special character (@ $ ! % * ? &)
                </div>

                <div class="password-example">
                    Example:
                    <strong>Admin@12345</strong>
                </div>

            </div>
        </div>

        {{-- Confirm Password --}}
        <div class="form-group">
            <label for="password_confirmation">
                Confirm New Password
            </label>

            <div class="password-wrapper">
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    class="password-input"
                    minlength="8"
                    required
                >

                <button
                    type="button"
                    class="password-toggle"
                    onclick="toggleProfilePassword('password_confirmation', this)"
                    aria-label="Show password confirmation"
                    title="Show password"
                >
                    👁
                </button>
            </div>

            <div id="profile-password-match" class="password-match"></div>
        </div>

        <button type="submit" class="change-password-button">
            🔐 Change Password
        </button>

    </form>
</section>

<style>
    .password-form {
        margin-top: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 700;
        color: #111111;
    }

    .password-wrapper {
        position: relative;
    }

    .password-input {
        width: 100%;
        box-sizing: border-box;
        padding: 11px 48px 11px 12px;
        border: 1px solid #cccccc;
        border-radius: 7px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .password-input:focus {
        border-color: #f4c400;
        box-shadow: 0 0 0 3px rgba(244, 196, 0, 0.15);
    }

    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        cursor: pointer;
        font-size: 18px;
        color: #555555;
        padding: 5px;
        line-height: 1;
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
        padding: 14px 15px;
        background: #f5f5f5;
        border-left: 4px solid #f4c400;
        border-radius: 6px;
        font-size: 13px;
    }

    .requirements-title {
        font-weight: 700;
        color: #111111;
        margin-bottom: 8px;
    }

    .requirement {
        margin: 5px 0;
        color: #777777;
        transition: color 0.2s;
    }

    .requirement span {
        display: inline-block;
        width: 20px;
        font-weight: bold;
    }

    .requirement.valid {
        color: #16803c;
        font-weight: 600;
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
        min-height: 18px;
        margin-top: 7px;
        font-size: 12px;
    }

    .password-match.valid {
        color: #16803c;
        font-weight: 600;
    }

    .password-match.invalid {
        color: #a00000;
        font-weight: 600;
    }

    .change-password-button {
        width: 100%;
        border: none;
        background: #f4c400;
        color: #111111;
        padding: 13px;
        border-radius: 7px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
    }

    .change-password-button:hover {
        background: #dcae00;
    }

    .change-password-button:active {
        transform: scale(0.99);
    }

    .success-message {
        margin-top: 15px;
        padding: 12px 15px;
        background: #e6f7ed;
        color: #16803c;
        border-left: 4px solid #16803c;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .error-message {
        margin-top: 15px;
        padding: 12px 15px;
        background: #ffe5e5;
        color: #a00000;
        border-left: 4px solid #a00000;
        border-radius: 6px;
        font-size: 13px;
    }

    .error-message ul {
        margin: 8px 0 0 20px;
    }
</style>

<script>
    function toggleProfilePassword(inputId, button) {
        const input = document.getElementById(inputId);

        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = '🙈';
            button.setAttribute('aria-label', 'Hide password');
            button.setAttribute('title', 'Hide password');
        } else {
            input.type = 'password';
            button.textContent = '👁';
            button.setAttribute('aria-label', 'Show password');
            button.setAttribute('title', 'Show password');
        }
    }

    const profilePassword =
        document.getElementById('password');

    const profileConfirmation =
        document.getElementById('password_confirmation');

    const profileMatch =
        document.getElementById('profile-password-match');

    const profileRequirements = {
        length: document.getElementById(
            'profile-requirement-length'
        ),

        uppercase: document.getElementById(
            'profile-requirement-uppercase'
        ),

        lowercase: document.getElementById(
            'profile-requirement-lowercase'
        ),

        number: document.getElementById(
            'profile-requirement-number'
        ),

        special: document.getElementById(
            'profile-requirement-special'
        )
    };

    function updateProfileRequirement(element, valid) {
        const icon = element.querySelector('span');

        if (valid) {
            element.classList.add('valid');
            icon.textContent = '✓';
        } else {
            element.classList.remove('valid');
            icon.textContent = '○';
        }
    }

    function checkProfilePassword() {
        const password = profilePassword.value;

        updateProfileRequirement(
            profileRequirements.length,
            password.length >= 8
        );

        updateProfileRequirement(
            profileRequirements.uppercase,
            /[A-Z]/.test(password)
        );

        updateProfileRequirement(
            profileRequirements.lowercase,
            /[a-z]/.test(password)
        );

        updateProfileRequirement(
            profileRequirements.number,
            /[0-9]/.test(password)
        );

        updateProfileRequirement(
            profileRequirements.special,
            /[@$!%*?&]/.test(password)
        );

        checkProfilePasswordMatch();
    }

    function checkProfilePasswordMatch() {
        const password = profilePassword.value;
        const confirmation = profileConfirmation.value;

        if (confirmation === '') {
            profileMatch.textContent = '';
            profileMatch.className = 'password-match';
            return;
        }

        if (password === confirmation) {
            profileMatch.textContent = '✓ Passwords match.';
            profileMatch.className =
                'password-match valid';
        } else {
            profileMatch.textContent =
                '✕ Passwords do not match.';
            profileMatch.className =
                'password-match invalid';
        }
    }

    profilePassword.addEventListener(
        'input',
        checkProfilePassword
    );

    profileConfirmation.addEventListener(
        'input',
        checkProfilePasswordMatch
    );
</script>
