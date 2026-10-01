<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Alibaton Construction Inc.')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')

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

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* MAIN CONTENT */

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
        }

        .topbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e5e5;
            padding: 17px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }

        .user-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
        }

        .logout-btn {
            border: none;
            background: #111111;
            color: #ffffff;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }

        .logout-btn:hover {
            background: #333333;
        }

        .content {
            padding: 30px;
        }

        /* RESPONSIVE */

        @media (max-width: 768px) {

            .main {
                margin-left: 260px;
                width: calc(100% - 260px);
            }

            .content {
                padding: 18px;
            }

            .topbar {
                padding: 15px 18px;
            }

            .user-name {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    {{-- SHARED SIDEBAR --}}
    @include('partials.sidebar')


    {{-- MAIN CONTENT --}}
    <main class="main">

        <header class="topbar">

            <h2>
                @yield('title', 'Dashboard')
            </h2>

            <div class="user-area">

                @auth

                    <span class="user-name">
                        {{ Auth::user()->name }}
                    </span>

                    <form method="POST"
                          action="{{ route('logout') }}">

                        @csrf

                        <button type="submit"
                                class="logout-btn">
                            Logout
                        </button>

                    </form>

                @endauth

            </div>

        </header>


        <section class="content">

            @yield('content')

        </section>

    </main>

</div>

</body>
</html>