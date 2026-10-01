@extends('layouts.app')

@section('content')

<div style="
    max-width: 1200px;
    margin: 0 auto;
">

    {{-- Header --}}
    <div style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 20px;
        flex-wrap: wrap;
    ">

        <div>
            <h1 style="
                margin: 0;
                font-size: 28px;
                color: #111111;
            ">
                User Management
            </h1>

            <p style="
                margin: 6px 0 0;
                color: #666666;
            ">
                Manage system accounts, user roles, and account status.
            </p>
        </div>

        <a
            href="{{ route('users.create') }}"
            style="
                background: #f4c400;
                color: #111111;
                text-decoration: none;
                padding: 12px 18px;
                border-radius: 7px;
                font-weight: bold;
                display: inline-block;
            "
        >
            + Create User
        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div style="
            background: #e8f5e9;
            color: #2e7d32;
            padding: 14px 18px;
            border-radius: 7px;
            margin-bottom: 20px;
            border-left: 4px solid #2e7d32;
        ">
            {{ session('success') }}
        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div style="
            background: #ffebee;
            color: #c62828;
            padding: 14px 18px;
            border-radius: 7px;
            margin-bottom: 20px;
            border-left: 4px solid #c62828;
        ">
            {{ session('error') }}
        </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div style="
            background: #ffebee;
            color: #c62828;
            padding: 14px 18px;
            border-radius: 7px;
            margin-bottom: 20px;
            border-left: 4px solid #c62828;
        ">

            <strong>
                Please correct the following:
            </strong>

            <ul style="
                margin-bottom: 0;
                margin-top: 8px;
            ">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Security Information --}}
    <div style="
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    ">

        {{-- Account Management --}}
        <div style="
            background: #ffffff;
            padding: 18px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border-left: 4px solid #111111;
        ">

            <div style="
                font-size: 12px;
                color: #777777;
                text-transform: uppercase;
                font-weight: bold;
            ">
                Account Management
            </div>

            <div style="
                margin-top: 5px;
                font-size: 19px;
                font-weight: bold;
                color: #111111;
            ">
                Admin Only
            </div>

        </div>


        {{-- Total Users --}}
        <div style="
            background: #ffffff;
            padding: 18px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border-left: 4px solid #f4c400;
        ">

            <div style="
                font-size: 12px;
                color: #777777;
                text-transform: uppercase;
                font-weight: bold;
            ">
                Total Accounts
            </div>

            <div style="
                margin-top: 5px;
                font-size: 19px;
                font-weight: bold;
                color: #111111;
            ">
                {{ $users->count() }}
            </div>

        </div>


        {{-- Security --}}
        <div style="
            background: #ffffff;
            padding: 18px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border-left: 4px solid #2e7d32;
        ">

            <div style="
                font-size: 12px;
                color: #777777;
                text-transform: uppercase;
                font-weight: bold;
            ">
                Security
            </div>

            <div style="
                margin-top: 5px;
                font-size: 19px;
                font-weight: bold;
                color: #2e7d32;
            ">
                Protected
            </div>

        </div>

    </div>


    {{-- Users Table --}}
    <div style="
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        overflow-x: auto;
    ">

        <table style="
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        ">

            <thead>

                <tr style="
                    background: #111111;
                    color: #ffffff;
                ">

                    <th style="
                        padding: 15px;
                        text-align: left;
                    ">
                        ID
                    </th>

                    <th style="
                        padding: 15px;
                        text-align: left;
                    ">
                        Name
                    </th>

                    <th style="
                        padding: 15px;
                        text-align: left;
                    ">
                        Email
                    </th>

                    <th style="
                        padding: 15px;
                        text-align: left;
                    ">
                        Role
                    </th>

                    <th style="
                        padding: 15px;
                        text-align: center;
                    ">
                        Status
                    </th>

                    <th style="
                        padding: 15px;
                        text-align: center;
                    ">
                        Action
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($users as $user)

                    <tr style="
                        border-bottom: 1px solid #eeeeee;
                    ">

                        {{-- ID --}}
                        <td style="
                            padding: 15px;
                            color: #555555;
                        ">
                            {{ $user->id }}
                        </td>


                        {{-- Name --}}
                        <td style="
                            padding: 15px;
                            font-weight: bold;
                            color: #111111;
                        ">
                            {{ $user->name }}

                            @if($user->id === auth()->id())

                                <div style="
                                    margin-top: 4px;
                                    font-size: 11px;
                                    color: #777777;
                                    font-weight: normal;
                                ">
                                    Current Account
                                </div>

                            @endif

                        </td>


                        {{-- Email --}}
                        <td style="
                            padding: 15px;
                            color: #444444;
                        ">
                            {{ $user->email }}
                        </td>


                        {{-- Role --}}
                        <td style="
                            padding: 15px;
                        ">

                            @if($user->role === 'Admin')

                                <span style="
                                    background: #111111;
                                    color: #ffffff;
                                    padding: 6px 10px;
                                    border-radius: 20px;
                                    font-size: 12px;
                                    font-weight: bold;
                                ">
                                    Admin
                                </span>

                            @elseif($user->role === 'HR')

                                <span style="
                                    background: #f4c400;
                                    color: #111111;
                                    padding: 6px 10px;
                                    border-radius: 20px;
                                    font-size: 12px;
                                    font-weight: bold;
                                ">
                                    HR
                                </span>

                            @else

                                <span style="
                                    background: #eeeeee;
                                    color: #333333;
                                    padding: 6px 10px;
                                    border-radius: 20px;
                                    font-size: 12px;
                                    font-weight: bold;
                                ">
                                    Employee
                                </span>

                            @endif

                        </td>


                        {{-- Status --}}
                        <td style="
                            padding: 15px;
                            text-align: center;
                        ">

                            @if($user->is_active)

                                <span style="
                                    background: #e8f5e9;
                                    color: #2e7d32;
                                    padding: 6px 10px;
                                    border-radius: 20px;
                                    font-size: 12px;
                                    font-weight: bold;
                                ">
                                    Active
                                </span>

                            @else

                                <span style="
                                    background: #ffebee;
                                    color: #c62828;
                                    padding: 6px 10px;
                                    border-radius: 20px;
                                    font-size: 12px;
                                    font-weight: bold;
                                ">
                                    Inactive
                                </span>

                            @endif

                        </td>


                        {{-- Actions --}}
                        <td style="
                            padding: 15px;
                            text-align: center;
                        ">

                            @if($user->id !== auth()->id())

                                {{-- Activate / Deactivate --}}
                                <form
                                    method="POST"
                                    action="{{ route('users.toggle-status', $user) }}"
                                    onsubmit="return confirmStatusChange('{{ $user->name }}', {{ $user->is_active ? 'true' : 'false' }});"
                                    style="
                                        display: inline;
                                    "
                                >

                                    @csrf
                                    @method('PATCH')

                                    @if($user->is_active)

                                        <button
                                            type="submit"
                                            style="
                                                background: #c62828;
                                                color: #ffffff;
                                                border: none;
                                                padding: 8px 12px;
                                                border-radius: 6px;
                                                cursor: pointer;
                                                font-weight: bold;
                                            "
                                        >
                                            Deactivate
                                        </button>

                                    @else

                                        <button
                                            type="submit"
                                            style="
                                                background: #2e7d32;
                                                color: #ffffff;
                                                border: none;
                                                padding: 8px 12px;
                                                border-radius: 6px;
                                                cursor: pointer;
                                                font-weight: bold;
                                            "
                                        >
                                            Activate
                                        </button>

                                    @endif

                                </form>


                                {{-- Delete --}}
                                <form
                                    method="POST"
                                    action="{{ route('users.destroy', $user) }}"
                                    onsubmit="return confirmDelete('{{ $user->name }}', '{{ $user->email }}');"
                                    style="
                                        display: inline;
                                        margin-left: 5px;
                                    "
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        style="
                                            background: #111111;
                                            color: #ffffff;
                                            border: none;
                                            padding: 8px 12px;
                                            border-radius: 6px;
                                            cursor: pointer;
                                            font-weight: bold;
                                        "
                                    >
                                        Delete
                                    </button>

                                </form>


                            @else

                                <span style="
                                    color: #888888;
                                    font-size: 13px;
                                    font-weight: bold;
                                ">
                                    Current Account
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            style="
                                padding: 30px;
                                text-align: center;
                                color: #777777;
                            "
                        >
                            No user accounts found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<script>

    function confirmStatusChange(name, isActive) {

        if (isActive) {

            return confirm(
                'Deactivate the account of "' +
                name +
                '"?\n\n' +
                'The user will no longer be able to log in.'
            );

        }

        return confirm(
            'Activate the account of "' +
            name +
            '"?\n\n' +
            'The user will be allowed to log in again.'
        );
    }



    function confirmDelete(name, email) {

        const firstConfirmation = confirm(
            'WARNING: You are about to permanently delete this user account.\n\n' +
            'Name: ' + name + '\n' +
            'Email: ' + email + '\n\n' +
            'This action cannot be undone.\n\n' +
            'Do you want to continue?'
        );

        if (!firstConfirmation) {
            return false;
        }


        const secondConfirmation = prompt(
            'For security, type DELETE to permanently delete this account.'
        );


        if (secondConfirmation !== 'DELETE') {

            alert(
                'Deletion cancelled. You must type DELETE exactly.'
            );

            return false;
        }


        return true;
    }

</script>

@endsection