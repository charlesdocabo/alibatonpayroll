@extends('layouts.app')

@section('title', 'Employees - Alibaton Construction Inc.')

@section('styles')

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 20px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 28px;
    }

    .page-header p {
        margin: 6px 0 0;
        color: #666666;
        font-size: 14px;
    }

    .add-button {
        background: #f4c400;
        color: #111111;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        text-decoration: none;
        white-space: nowrap;
    }

    .add-button:hover {
        background: #dcae00;
    }

    .alert {
        padding: 12px 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
    }

    .table-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #111111;
        color: white;
        padding: 13px;
        text-align: left;
        white-space: nowrap;
    }

    td {
        padding: 13px;
        border-bottom: 1px solid #dddddd;
        white-space: nowrap;
    }

    tr:hover {
        background: #fafafa;
    }

    .status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: bold;
    }

    .active-status {
        background: #e7f7e7;
        color: #218838;
    }

    .inactive-status {
        background: #f8d7da;
        color: #721c24;
    }

    .action-button {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 4px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: bold;
    }

    .edit-button {
        background: #f4c400;
        color: #111111;
    }

    .delete-button {
        background: #111111;
        color: white;
    }

    .edit-button:hover {
        background: #dcae00;
    }

    .delete-button:hover {
        background: #333333;
    }

    .empty {
        text-align: center;
        padding: 30px;
        color: #777777;
    }

    @media (max-width: 768px) {
        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .add-button {
            width: 100%;
            text-align: center;
        }
    }
</style>

@endsection

@section('content')

<div class="page-header">

    <div>
        <h1>Employees</h1>
        <p>Manage employee information</p>
    </div>

    <a href="/employees/create" class="add-button">
        + Add Employee
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

<div class="table-container">

    <table>

        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Position</th>
                <th>Department</th>
                <th>Salary</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

            @forelse($employees as $employee)

                <tr>

                    <td>
                        {{ $employee['employee_id'] ?? 'N/A' }}
                    </td>

                    <td>
                        {{ ($employee['first_name'] ?? '') . ' ' . ($employee['last_name'] ?? '') }}
                    </td>

                    <td>
                        {{ $employee['email'] ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $employee['position'] ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $employee['department'] ?? 'N/A' }}
                    </td>

                    <td class="confidential-amount">
                        ₱{{ number_format((float)($employee['salary'] ?? 0), 2) }}
                    </td>

                    <td>
                        @if(strtolower($employee['status'] ?? '') === 'active')

                            <span class="status active-status">
                                Active
                            </span>

                        @else

                            <span class="status inactive-status">
                                {{ ucfirst($employee['status'] ?? 'Inactive') }}
                            </span>

                        @endif
                    </td>

                    <td>

                   <a href="/employees/{{ $employee['employee_id'] }}/edit"
   class="action-button edit-button">
    Edit
</a>

<form action="/employees/{{ $employee['employee_id'] }}"
      method="POST"
                              style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="action-button delete-button"
                                    onclick="return confirm('Are you sure you want to delete this employee?')">
                                Delete
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="8" class="empty">
                        No employees found.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>


@endsection
