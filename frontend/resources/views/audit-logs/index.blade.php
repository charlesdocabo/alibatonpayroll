@extends('layouts.app')

@section('content')

<div style="max-width: 1200px; margin: 0 auto;">

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
            <h1 style="margin: 0; font-size: 28px;">
                Audit Logs
            </h1>

            <p style="
                margin: 6px 0 0;
                color: #666;
            ">
                Security activity and user account history.
            </p>
        </div>

        <div id="live-status" style="
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #2e7d32;
            font-weight: bold;
        ">

            <span style="
                width: 9px;
                height: 9px;
                background: #2e7d32;
                border-radius: 50%;
                display: inline-block;
            "></span>

            LIVE
        </div>

    </div>


    {{-- Filters --}}
    <div style="
        background: #ffffff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    ">

        <form method="GET"
              action="{{ route('audit-logs.index') }}"
              style="
                  display: grid;
                  grid-template-columns: 2fr 1fr 1fr auto auto;
                  gap: 12px;
                  align-items: end;
              ">

            {{-- Search --}}
            <div>

                <label style="
                    display: block;
                    margin-bottom: 6px;
                    font-size: 13px;
                    font-weight: bold;
                ">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="User, email, action, description..."
                    style="
                        width: 100%;
                        padding: 10px 12px;
                        border: 1px solid #ccc;
                        border-radius: 6px;
                        font-size: 14px;
                    "
                >

            </div>


            {{-- Action --}}
            <div>

                <label style="
                    display: block;
                    margin-bottom: 6px;
                    font-size: 13px;
                    font-weight: bold;
                ">
                    Action
                </label>

                <select
                    name="action"
                    style="
                        width: 100%;
                        padding: 10px 12px;
                        border: 1px solid #ccc;
                        border-radius: 6px;
                        font-size: 14px;
                        background: #fff;
                    "
                >

                    <option value="">
                        All Actions
                    </option>

                    @foreach($actions as $action)

                        <option
                            value="{{ $action }}"
                            {{ request('action') === $action ? 'selected' : '' }}
                        >
                            {{ str_replace('_', ' ', $action) }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Date --}}
            <div>

                <label style="
                    display: block;
                    margin-bottom: 6px;
                    font-size: 13px;
                    font-weight: bold;
                ">
                    Date
                </label>

                <input
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                    style="
                        width: 100%;
                        padding: 10px 12px;
                        border: 1px solid #ccc;
                        border-radius: 6px;
                        font-size: 14px;
                    "
                >

            </div>


            {{-- Filter Button --}}
            <button
                type="submit"
                style="
                    padding: 10px 18px;
                    border: none;
                    border-radius: 6px;
                    background: #f4c400;
                    color: #111;
                    font-weight: bold;
                    cursor: pointer;
                    white-space: nowrap;
                "
            >
                Filter
            </button>


            {{-- Reset --}}
            <a
                href="{{ route('audit-logs.index') }}"
                style="
                    padding: 10px 18px;
                    border-radius: 6px;
                    background: #111;
                    color: #fff;
                    text-decoration: none;
                    font-weight: bold;
                    text-align: center;
                    white-space: nowrap;
                "
            >
                Reset
            </a>

        </form>

    </div>


    {{-- Security Information --}}
    <div style="
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    ">


        {{-- Security Monitoring --}}
        <div style="
            background: #ffffff;
            padding: 18px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border-left: 4px solid #2e7d32;
        ">

            <div style="
                font-size: 12px;
                color: #777;
                text-transform: uppercase;
                font-weight: bold;
            ">
                Security Monitoring
            </div>

            <div style="
                margin-top: 5px;
                font-size: 20px;
                font-weight: bold;
            ">
                Active
            </div>

        </div>


        {{-- Records Per Page --}}
        <div style="
            background: #ffffff;
            padding: 18px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border-left: 4px solid #f4c400;
        ">

            <div style="
                font-size: 12px;
                color: #777;
                text-transform: uppercase;
                font-weight: bold;
            ">
                Records Per Page
            </div>

            <div style="
                margin-top: 5px;
                font-size: 20px;
                font-weight: bold;
            ">
                20
            </div>

        </div>


        {{-- Access Level --}}
        <div style="
            background: #ffffff;
            padding: 18px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border-left: 4px solid #111111;
        ">

            <div style="
                font-size: 12px;
                color: #777;
                text-transform: uppercase;
                font-weight: bold;
            ">
                Access Level
            </div>

            <div style="
                margin-top: 5px;
                font-size: 20px;
                font-weight: bold;
            ">
                Admin Only
            </div>

        </div>

    </div>


    {{-- Audit Table --}}
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
                        Date & Time
                    </th>

                    <th style="
                        padding: 15px;
                        text-align: left;
                    ">
                        User
                    </th>

                    <th style="
                        padding: 15px;
                        text-align: left;
                    ">
                        Action
                    </th>

                    <th style="
                        padding: 15px;
                        text-align: left;
                    ">
                        Description
                    </th>

                    <th style="
                        padding: 15px;
                        text-align: left;
                    ">
                        IP Address
                    </th>

                </tr>

            </thead>


            <tbody id="audit-log-body">

                @forelse($logs as $log)

                    <tr style="
                        border-bottom: 1px solid #eeeeee;
                    ">

                        {{-- Date --}}
                        <td style="padding: 15px;">
                            {{ $log->created_at->format('M d, Y h:i A') }}
                        </td>


                        {{-- User --}}
                        <td style="padding: 15px;">

                            @if($log->user)

                                <strong>
                                    {{ $log->user->name }}
                                </strong>

                                <div style="
                                    color: #777;
                                    font-size: 12px;
                                    margin-top: 3px;
                                ">
                                    {{ $log->user->email }}
                                </div>

                            @else

                                <span style="color: #888;">
                                    Deleted User
                                </span>

                            @endif

                        </td>


                        {{-- Action --}}
                        <td style="padding: 15px;">
                            {!! actionBadge($log->action) !!}
                        </td>


                        {{-- Description --}}
                        <td style="
                            padding: 15px;
                            color: #444;
                        ">
                            {{ $log->description }}
                        </td>


                        {{-- IP Address --}}
                        <td style="
                            padding: 15px;
                            font-family: monospace;
                        ">
                            {{ $log->ip_address ?? 'N/A' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            style="
                                padding: 30px;
                                text-align: center;
                                color: #777;
                            "
                        >
                            No audit logs found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    @if($logs->hasPages())

        <div style="margin-top: 20px;">
            {{ $logs->links() }}
        </div>

    @endif

</div>



{{-- Blade Helper --}}
@php

    function actionBadge($action)
    {
        $badges = [

            'USER_CREATED' => [
                'text' => 'User Created',
                'background' => '#e3f2fd',
                'color' => '#1565c0',
            ],

            'USER_ACTIVATED' => [
                'text' => 'User Activated',
                'background' => '#e8f5e9',
                'color' => '#2e7d32',
            ],

            'USER_DEACTIVATED' => [
                'text' => 'User Deactivated',
                'background' => '#fff3e0',
                'color' => '#ef6c00',
            ],

            'USER_DELETED' => [
                'text' => 'User Deleted',
                'background' => '#ffebee',
                'color' => '#c62828',
            ],

            'LOGIN_SUCCESS' => [
                'text' => 'Login Success',
                'background' => '#e8f5e9',
                'color' => '#2e7d32',
            ],

            'LOGIN_FAILED' => [
                'text' => 'Login Failed',
                'background' => '#ffebee',
                'color' => '#c62828',
            ],

            'LOGOUT' => [
                'text' => 'Logout',
                'background' => '#fff3e0',
                'color' => '#ef6c00',
            ],

            'PASSWORD_OTP_SENT' => [
                'text' => 'OTP Sent',
                'background' => '#e3f2fd',
                'color' => '#1565c0',
            ],

            'PASSWORD_OTP_FAILED' => [
                'text' => 'OTP Failed',
                'background' => '#ffebee',
                'color' => '#c62828',
            ],

            'PASSWORD_CHANGED' => [
                'text' => 'Password Changed',
                'background' => '#e8f5e9',
                'color' => '#2e7d32',
            ],

        ];


        $badge = $badges[$action] ?? [

            'text' => str_replace('_', ' ', $action),
            'background' => '#eeeeee',
            'color' => '#333333',

        ];


        return '
            <span style="
                background: '.$badge['background'].';
                color: '.$badge['color'].';
                padding: 6px 10px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: bold;
                white-space: nowrap;
            ">
                '.e($badge['text']).'
            </span>
        ';
    }

@endphp



<script>

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }



    function formatDate(dateString) {

        const date = new Date(dateString);

        return date.toLocaleString('en-PH', {

            month: 'short',
            day: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true

        });

    }



    function getActionBadge(action) {

        const badges = {

            USER_CREATED: {
                text: 'User Created',
                background: '#e3f2fd',
                color: '#1565c0'
            },

            USER_ACTIVATED: {
                text: 'User Activated',
                background: '#e8f5e9',
                color: '#2e7d32'
            },

            USER_DEACTIVATED: {
                text: 'User Deactivated',
                background: '#fff3e0',
                color: '#ef6c00'
            },

            USER_DELETED: {
                text: 'User Deleted',
                background: '#ffebee',
                color: '#c62828'
            },

            LOGIN_SUCCESS: {
                text: 'Login Success',
                background: '#e8f5e9',
                color: '#2e7d32'
            },

            LOGIN_FAILED: {
                text: 'Login Failed',
                background: '#ffebee',
                color: '#c62828'
            },

            LOGOUT: {
                text: 'Logout',
                background: '#fff3e0',
                color: '#ef6c00'
            },

            PASSWORD_OTP_SENT: {
                text: 'OTP Sent',
                background: '#e3f2fd',
                color: '#1565c0'
            },

            PASSWORD_OTP_FAILED: {
                text: 'OTP Failed',
                background: '#ffebee',
                color: '#c62828'
            },

            PASSWORD_CHANGED: {
                text: 'Password Changed',
                background: '#e8f5e9',
                color: '#2e7d32'
            }

        };


        const badge = badges[action] || {

            text: String(action || '').replace(/_/g, ' '),
            background: '#eeeeee',
            color: '#333333'

        };


        return `
            <span style="
                background: ${badge.background};
                color: ${badge.color};
                padding: 6px 10px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: bold;
                white-space: nowrap;
            ">
                ${escapeHtml(badge.text)}
            </span>
        `;

    }



    function updateAuditLogs() {

        const currentPage = {{ $logs->currentPage() }};

        const params = new URLSearchParams({
            page: currentPage
        });


        // Preserve Search filter
        @if(request('search'))

            params.append(
                'search',
                @json(request('search'))
            );

        @endif


        // Preserve Action filter
        @if(request('action'))

            params.append(
                'action',
                @json(request('action'))
            );

        @endif


        // Preserve Date filter
        @if(request('date'))

            params.append(
                'date',
                @json(request('date'))
            );

        @endif


        fetch(
            '{{ route('audit-logs.latest') }}?' + params.toString(),
            {
                headers: {
                    'Accept': 'application/json'
                }
            }
        )

        .then(response => {

            if (!response.ok) {
                throw new Error('Unable to load audit logs.');
            }

            return response.json();

        })

        .then(result => {

            if (!result.success) {
                return;
            }


            const tbody =
                document.getElementById('audit-log-body');


            if (!tbody) {
                return;
            }


            if (!result.data.length) {

                tbody.innerHTML = `
                    <tr>

                        <td
                            colspan="5"
                            style="
                                padding: 30px;
                                text-align: center;
                                color: #777;
                            "
                        >
                            No audit logs found.
                        </td>

                    </tr>
                `;

                return;
            }


            tbody.innerHTML = result.data.map(log => {


                const userName = log.user

                    ? `
                        <strong>
                            ${escapeHtml(log.user.name)}
                        </strong>

                        <div style="
                            color: #777;
                            font-size: 12px;
                            margin-top: 3px;
                        ">
                            ${escapeHtml(log.user.email)}
                        </div>
                    `

                    : `
                        <span style="
                            color: #888;
                        ">
                            Deleted User
                        </span>
                    `;


                return `
                    <tr style="
                        border-bottom: 1px solid #eeeeee;
                    ">


                        <td style="
                            padding: 15px;
                        ">
                            ${formatDate(log.created_at)}
                        </td>


                        <td style="
                            padding: 15px;
                        ">
                            ${userName}
                        </td>


                        <td style="
                            padding: 15px;
                        ">
                            ${getActionBadge(log.action)}
                        </td>


                        <td style="
                            padding: 15px;
                            color: #444;
                        ">
                            ${escapeHtml(log.description)}
                        </td>


                        <td style="
                            padding: 15px;
                            font-family: monospace;
                        ">
                            ${escapeHtml(
                                log.ip_address || 'N/A'
                            )}
                        </td>


                    </tr>
                `;

            }).join('');

        })


        .catch(error => {

            console.error(error);


            const status =
                document.getElementById('live-status');


            if (status) {

                status.innerHTML = `
                    <span style="
                        width: 9px;
                        height: 9px;
                        background: #c62828;
                        border-radius: 50%;
                        display: inline-block;
                    "></span>

                    OFFLINE
                `;


                status.style.color = '#c62828';

            }

        });

    }



    // Refresh ALL pagination pages every 2 seconds.
    // Current page and active filters are preserved.
    setInterval(updateAuditLogs, 2000);

</script>

@endsection