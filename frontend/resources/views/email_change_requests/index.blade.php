<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Email Change Requests - Alibaton</title>

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

        .page-wrapper {
            padding: 32px;
            max-width: 1500px;
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 25px;
        }

        .page-title {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
        }

        .page-description {
            margin: 8px 0 0;
            color: #666666;
            font-size: 14px;
        }

        .back-button {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 7px;
            background: #111111;
            color: #ffffff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
        }

        .back-button:hover {
            background: #333333;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .alert-success {
            background: #eaf7ed;
            border: 1px solid #b7dfc0;
            color: #176b2c;
        }

        .alert-error {
            background: #fff0f0;
            border: 1px solid #efb5b5;
            color: #a51d1d;
        }

        .card {
            background: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1050px;
        }

        th {
            background: #111111;
            color: #ffffff;
            padding: 14px 15px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        td {
            padding: 16px 15px;
            border-bottom: 1px solid #eeeeee;
            vertical-align: top;
            font-size: 13px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .employee-name {
            font-weight: 800;
            color: #111111;
        }

        .employee-email {
            margin-top: 4px;
            color: #666666;
            font-size: 12px;
        }

        .email-change {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .old-email {
            color: #777777;
        }

        .new-email {
            color: #111111;
            font-weight: 800;
        }

        .arrow {
            color: #999999;
            font-weight: 800;
        }

        .reason {
            max-width: 260px;
            line-height: 1.5;
            color: #444444;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff4c2;
            color: #765900;
        }

        .status-approved {
            background: #e6f6ea;
            color: #176b2c;
        }

        .status-rejected {
            background: #fde8e8;
            color: #a51d1d;
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 120px;
        }

        .approve-button,
        .reject-button {
            width: 100%;
            padding: 9px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 800;
        }

        .approve-button {
            background: #111111;
            color: #ffffff;
        }

        .approve-button:hover {
            background: #333333;
        }

        .reject-button {
            background: #f4c400;
            color: #111111;
        }

        .reject-button:hover {
            background: #dcae00;
        }

        .reviewed {
            color: #777777;
            font-size: 12px;
            line-height: 1.5;
        }

        .empty-state {
            padding: 50px 25px;
            text-align: center;
            color: #777777;
        }

        .empty-state strong {
            display: block;
            color: #111111;
            font-size: 16px;
            margin-bottom: 7px;
        }

        .pagination {
            padding: 18px;
            border-top: 1px solid #eeeeee;
        }

        .admin-note {
            margin-top: 6px;
            color: #555555;
            font-size: 12px;
            font-style: italic;
        }

        @media (max-width: 700px) {

            .page-wrapper {
                padding: 18px;
            }

            .page-header {
                flex-direction: column;
            }

            .page-title {
                font-size: 23px;
            }

        }

    </style>

</head>

<body>

    <div class="page-wrapper">

        <div class="page-header">

            <div>

                <h1 class="page-title">
                    Email Change Requests
                </h1>

                <p class="page-description">
                    Review employee requests to change their registered email address.
                    Approval is required before any email account information is changed.
                </p>

            </div>

            <a
                href="{{ route('dashboard') }}"
                class="back-button"
            >
                Back to Dashboard
            </a>

        </div>


        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-error">
                {{ session('error') }}
            </div>

        @endif


        @if($errors->any())

            <div class="alert alert-error">

                @foreach($errors->all() as $error)

                    <div>
                        {{ $error }}
                    </div>

                @endforeach

            </div>

        @endif


        <div class="card">

            @if($requests->count() > 0)

                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>

                                <th>
                                    Employee
                                </th>

                                <th>
                                    Email Change
                                </th>

                                <th>
                                    Reason
                                </th>

                                <th>
                                    Submitted
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($requests as $emailRequest)

                                <tr>

                                    <td>

                                        <div class="employee-name">
                                            {{ $emailRequest->user->name ?? 'Unknown Employee' }}
                                        </div>

                                        <div class="employee-email">
                                            Employee ID:
                                            {{ $emailRequest->user->employee_id ?? 'N/A' }}
                                        </div>

                                    </td>


                                    <td>

                                        <div class="email-change">

                                            <div class="old-email">
                                                {{ $emailRequest->current_email }}
                                            </div>

                                            <div class="arrow">
                                                ↓
                                            </div>

                                            <div class="new-email">
                                                {{ $emailRequest->requested_email }}
                                            </div>

                                        </div>

                                    </td>


                                    <td>

                                        <div class="reason">
                                            {{ $emailRequest->reason }}
                                        </div>

                                    </td>


                                    <td>

                                        <div>
                                            {{ $emailRequest->created_at->format('M d, Y') }}
                                        </div>

                                        <div class="employee-email">
                                            {{ $emailRequest->created_at->format('h:i A') }}
                                        </div>

                                    </td>


                                    <td>

                                        <span
                                            class="status status-{{ $emailRequest->status }}"
                                        >
                                            {{ ucfirst($emailRequest->status) }}
                                        </span>


                                        @if($emailRequest->admin_note)

                                            <div class="admin-note">
                                                {{ $emailRequest->admin_note }}
                                            </div>

                                        @endif

                                    </td>


                                    <td>

                                        @if($emailRequest->status === 'pending')

                                            <div class="actions">

                                                <form
                                                    method="POST"
                                                    action="{{ route('email-change-requests.approve', $emailRequest) }}"
                                                    onsubmit="return confirm('Approve this email change request? The employee account and Employee Service email will be updated.');"
                                                >

                                                    @csrf

                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="approve-button"
                                                    >
                                                        Approve
                                                    </button>

                                                </form>


                                                <form
                                                    method="POST"
                                                    action="{{ route('email-change-requests.reject', $emailRequest) }}"
                                                    onsubmit="return confirm('Reject this email change request?');"
                                                >

                                                    @csrf

                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="reject-button"
                                                    >
                                                        Reject
                                                    </button>

                                                </form>

                                            </div>

                                        @else

                                            <div class="reviewed">

                                                @if($emailRequest->reviewer)

                                                    Reviewed by:
                                                    <strong>
                                                        {{ $emailRequest->reviewer->name }}
                                                    </strong>

                                                    <br>

                                                @endif

                                                @if($emailRequest->reviewed_at)

                                                    {{ $emailRequest->reviewed_at->format('M d, Y h:i A') }}

                                                @endif

                                            </div>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                <div class="pagination">

                    {{ $requests->links() }}

                </div>

            @else

                <div class="empty-state">

                    <strong>
                        No Email Change Requests
                    </strong>

                    There are currently no email change requests to review.

                </div>

            @endif

        </div>

    </div>

</body>

</html>