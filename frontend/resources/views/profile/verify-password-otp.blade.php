<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verify Password Change - Alibaton Construction Inc.</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
            font-family: Arial, Helvetica, sans-serif;
            color: #111111;
        }

        .card {
            width: 100%;
            max-width: 450px;
            margin: 20px;
            background: #ffffff;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .logo {
            width: 55px;
            height: 55px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #111111;
            color: #F4C400;
            border-radius: 10px;
            font-size: 20px;
            font-weight: bold;
        }

        h1 {
            margin: 0;
            text-align: center;
            font-size: 24px;
        }

        .subtitle {
            margin: 10px 0 25px;
            text-align: center;
            color: #666666;
            font-size: 14px;
            line-height: 1.5;
        }

        .otp-input {
            width: 100%;
            padding: 15px;
            border: 1px solid #cccccc;
            border-radius: 8px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 8px;
            outline: none;
        }

        .otp-input:focus {
            border-color: #F4C400;
            box-shadow: 0 0 0 3px rgba(244, 196, 0, 0.20);
        }

        .timer-box {
            margin: 18px 0;
            padding: 12px;
            background: #fff8d6;
            border: 1px solid #F4C400;
            border-radius: 8px;
            text-align: center;
            font-size: 14px;
        }

        #timer {
            font-weight: bold;
            font-size: 18px;
        }

        .verify-button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #111111;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .verify-button:hover {
            background: #333333;
        }

        .verify-button:disabled {
            background: #999999;
            cursor: not-allowed;
        }

        .back-link {
            display: block;
            margin-top: 18px;
            text-align: center;
            color: #111111;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            margin-bottom: 15px;
            padding: 10px;
            background: #ffe5e5;
            border: 1px solid #e00000;
            border-radius: 7px;
            color: #b00000;
            font-size: 14px;
        }

        .success {
            margin-bottom: 15px;
            padding: 10px;
            background: #e8f7e8;
            border: 1px solid #4caf50;
            border-radius: 7px;
            color: #267326;
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="card">

    <div class="logo">
        AC
    </div>

    <h1>Verify Password Change</h1>

    <p class="subtitle">
        A 6-digit verification code has been sent to your registered email address.
        Enter the code below to continue changing your password.
    </p>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('status') === 'otp-sent')
        <div class="success">
            OTP sent successfully. Please check your email.
        </div>
    @endif

    <form method="POST" action="{{ route('profile.password.otp.verify') }}">
        @csrf

        <input
            type="text"
            name="otp"
            class="otp-input"
            maxlength="6"
            minlength="6"
            pattern="[0-9]{6}"
            inputmode="numeric"
            autocomplete="one-time-code"
            placeholder="000000"
            required
            autofocus
        >

        <div class="timer-box">
            OTP expires in
            <span id="timer">01:00</span>
        </div>

        <button
            type="submit"
            class="verify-button"
            id="verifyButton"
        >
            Verify OTP
        </button>
    </form>

    <a href="{{ route('profile.edit') }}" class="back-link">
        Cancel and return to Profile
    </a>

</div>

<script>
    let remainingSeconds = 60;

    const timer = document.getElementById('timer');
    const verifyButton = document.getElementById('verifyButton');

    const countdown = setInterval(function () {

        remainingSeconds--;

        const minutes = Math.floor(remainingSeconds / 60);
        const seconds = remainingSeconds % 60;

        timer.textContent =
            String(minutes).padStart(2, '0') + ':' +
            String(seconds).padStart(2, '0');

        if (remainingSeconds <= 0) {
            clearInterval(countdown);

            timer.textContent = '00:00';

            verifyButton.disabled = true;
            verifyButton.textContent = 'OTP Expired';
        }

    }, 1000);
</script>

</body>
</html>

