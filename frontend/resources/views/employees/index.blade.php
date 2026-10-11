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

<!-- Employee Search and Filters -->
<form method="GET" action="{{ route('employees.index') }}"
      style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:20px; align-items:end;">

    <div style="flex:1; min-width:200px;">
        <label for="search" style="display:block; margin-bottom:6px; font-weight:bold;">
            Search Employee
        </label>
        <input
            type="text"
            id="search"
            name="search"
            value="{{ $search ?? request('search') }}"
            placeholder="Employee ID, name, or email"
            style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;"
        >
    </div>

    <div style="flex:1; min-width:160px;">
        <label for="department" style="display:block; margin-bottom:6px; font-weight:bold;">
            Department
        </label>
        <select
            id="department"
            name="department"
            style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;"
        >
            <option value="">All Departments</option>
            @foreach(($departments ?? []) as $dept)
                <option value="{{ $dept }}"
                    {{ ($department ?? request('department')) === $dept ? 'selected' : '' }}>
                    {{ $dept }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="flex:1; min-width:140px;">
        <label for="status" style="display:block; margin-bottom:6px; font-weight:bold;">
            Status
        </label>
        <select
            id="status"
            name="status"
            style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;"
        >
            <option value="">All Statuses</option>
            <option value="active" {{ ($status ?? request('status')) === 'active' ? 'selected' : '' }}>
                Active
            </option>
            <option value="inactive" {{ ($status ?? request('status')) === 'inactive' ? 'selected' : '' }}>
                Inactive
            </option>
        </select>
    </div>

    <button type="submit"
        style="background:#f4c400; color:#111; padding:11px 18px; border:0; border-radius:5px; font-weight:bold; cursor:pointer;">
        Search / Filter
    </button>

    <a href="{{ route('employees.index') }}"
       style="background:#eee; color:#111; padding:11px 18px; border-radius:5px; text-decoration:none;">
        Clear Filters
    </a>
</form>

<p style="margin:0 0 15px; color:#555;">
    Showing <strong>{{ $totalEmployees ?? count($employees) }}</strong> employee(s)
</p>

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

    
@if ($employees instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="pagination-container" style="margin-top: 20px;">
        <p>
            Showing {{ $employees->firstItem() ?? 0 }}
            to {{ $employees->lastItem() ?? 0 }}
            of {{ $employees->total() }} employees
        </p>

        {{ $employees->links() }}
    </div>
@endif


</div>


@endsection
