<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Alibaton Construction Inc. | Payroll & Benefits System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f5f5;
            color: #111111;
        }

        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #111111;
            color: white;
            padding: 20px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo {
            width: 48px;
            height: 48px;
            background: #f4c400;
            color: #111111;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px;
        }

        .brand-text h1 {
            margin: 0;
            font-size: 20px;
        }

        .brand-text p {
            margin: 3px 0 0;
            color: #cccccc;
            font-size: 13px;
        }

        .nav {
            display: flex;
            gap: 10px;
        }

        .nav a {
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
        }

        .login {
            background: #f4c400;
            color: #111111;
        }

        .register {
            border: 1px solid #f4c400;
            color: #f4c400;
        }

        .hero {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 70px 7%;
        }

        .hero-content {
            max-width: 1100px;
            width: 100%;
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 60px;
            align-items: center;
        }

        .badge {
            display: inline-block;
            background: #fff3b0;
            color: #6b5500;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .hero h2 {
            font-size: 48px;
            line-height: 1.1;
            margin: 0 0 20px;
        }

        .hero h2 span {
            color: #dcae00;
        }

        .hero p {
            color: #555555;
            font-size: 17px;
            line-height: 1.7;
            max-width: 650px;
        }

        .hero-button {
            display: inline-block;
            margin-top: 20px;
            padding: 14px 24px;
            background: #f4c400;
            color: #111111;
            text-decoration: none;
            border-radius: 7px;
            font-weight: bold;
        }

        .card {
            background: white;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
            border-top: 5px solid #f4c400;
        }

        .card h3 {
            margin-top: 0;
            font-size: 22px;
        }

        .feature {
            display: flex;
            gap: 15px;
            padding: 16px 0;
            border-bottom: 1px solid #eeeeee;
        }

        .feature:last-child {
            border-bottom: none;
        }

        .feature-icon {
            width: 38px;
            height: 38px;
            background: #111111;
            color: #f4c400;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .feature strong {
            display: block;
            margin-bottom: 4px;
        }

        .feature small {
            color: #777777;
        }

        .footer {
            background: #111111;
            color: #aaaaaa;
            text-align: center;
            padding: 18px;
            font-size: 13px;
        }

        @media (max-width: 800px) {
            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .hero-content {
                grid-template-columns: 1fr;
            }

            .hero {
                padding: 45px 6%;
            }

            .hero h2 {
                font-size: 36px;
            }
        }
    </style>
</head>

<body>
<div class="page">

    <header class="header">
        <div class="brand">
            <div class="logo">AC</div>

            <div class="brand-text">
                <h1>Alibaton Construction Inc.</h1>
                <p>Payroll & Benefits System</p>
            </div>
        </div>

        <nav class="nav">
            <a href="{{ route('login') }}" class="login">Log in</a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="register">Register</a>
            @endif
        </nav>
    </header>

    <main class="hero">
        <div class="hero-content">

            <section>
                <span class="badge">ALIBATON CONSTRUCTION INC.</span>

                <h2>
                    Payroll & Benefits
                    <span>Management System</span>
                </h2>

                <p>
                    A centralized system designed to manage employee records,
                    payroll processing, employee benefits, claims,
                    incentives, and HR analytics efficiently and securely.
                </p>

                <a href="{{ route('login') }}" class="hero-button">
                    Access System
                </a>
            </section>

            <section class="card">
                <h3>System Features</h3>

                <div class="feature">
                    <div class="feature-icon">E</div>
                    <div>
                        <strong>Employee Management</strong>
                        <small>Manage employee information and records.</small>
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">P</div>
                    <div>
                        <strong>Payroll Management</strong>
                        <small>Process salaries, deductions, overtime and payroll records.</small>
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">B</div>
                    <div>
                        <strong>Benefits & Claims</strong>
                        <small>Manage employee benefits and reimbursement claims.</small>
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">A</div>
                    <div>
                        <strong>HR Analytics</strong>
                        <small>View workforce and payroll-related information.</small>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <footer class="footer">
        ALIBATON Construction Inc. © {{ date('Y') }} |
        Payroll & Benefits System
    </footer>

</div>
</body>
</html>

