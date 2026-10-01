@extends('layouts.app')

@section('title', 'Salary Grades - Alibaton Construction Inc.')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 20px;
    }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #111111;
    }

    .page-subtitle {
        margin: 5px 0 0;
        color: #666666;
        font-size: 14px;
    }

    .btn-add {
        background: #F4C400;
        color: #111111;
        padding: 11px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700;
        white-space: nowrap;
        transition: 0.2s ease;
    }

    .btn-add:hover {
        background: #DCAE00;
        color: #111111;
    }

    .alert {
        padding: 13px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }

    .alert-error {
        background: #ffebee;
        color: #c62828;
        border: 1px solid #ffcdd2;
    }

    /* =========================
       MONITORING CARDS
    ========================= */

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .summary-grid.bottom {
        grid-template-columns: repeat(2, 1fr);
        margin-bottom: 25px;
    }

    .summary-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        border-top: 4px solid #F4C400;
    }

    .summary-card.highlight {
        border-top-color: #111111;
    }

    .summary-label {
        color: #777777;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .summary-value {
        color: #111111;
        font-size: 25px;
        font-weight: 800;
    }

    .summary-description {
        margin-top: 6px;
        color: #888888;
        font-size: 12px;
    }

    /* =========================
       RECORDS
    ========================= */

    .records-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        overflow-x: auto;
    }

    .records-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        gap: 15px;
    }

    .records-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #111111;
    }

    .records-subtitle {
        margin: 4px 0 0;
        color: #777777;
        font-size: 13px;
    }

    .records-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .records-table th {
        background: #111111;
        color: #ffffff;
        padding: 13px 12px;
        text-align: left;
        font-size: 13px;
        white-space: nowrap;
    }

    .records-table td {
        padding: 13px 12px;
        border-bottom: 1px solid #eeeeee;
        font-size: 14px;
        vertical-align: middle;
    }

    .records-table tbody tr:hover {
        background: #fafafa;
    }

    .grade-badge {
        display: inline-block;
        background: #fff8d6;
        color: #111111;
        border: 1px solid #F4C400;
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 700;
    }

    .salary-range {
        font-weight: 600;
        white-space: nowrap;
    }

    .description-cell {
        max-width: 300px;
        color: #555555;
    }

    /* =========================
       ACTION BUTTONS
    ========================= */

    .actions {
        display: flex;
        gap: 7px;
    }

    .btn-action {
        display: inline-block;
        padding: 7px 11px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .btn-edit {
        background: #f4f4f4;
        color: #111111;
    }

    .btn-edit:hover {
        background: #e5e5e5;
    }

    .btn-delete {
        background: #ffebee;
        color: #c62828;
    }

    .btn-delete:hover {
        background: #ffcdd2;
    }

    /* =========================
       EMPTY STATE
    ========================= */

    .empty-state {
        text-align: center;
        padding: 45px 20px;
        color: #777777;
    }

    .empty-state h3 {
        margin: 0 0 8px;
        color: #111111;
    }

    .empty-state p {
        margin: 0;
    }

    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 950px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .summary-grid.bottom {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 650px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .summary-grid,
        .summary-grid.bottom {
            grid-template-columns: 1fr;
        }

        .records-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Salary Grades</h1>

        <p class="page-subtitle">
            Manage salary ranges and compensation levels for employees.
        </p>
    </div>

    <a
        href="{{ url('/salary-grades/create') }}"
        class="btn-add"
    >
        + Add Salary Grade
    </a>
</div>


{{-- =========================
     ALERTS
========================= --}}

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

@if(isset($error))
    <div class="alert alert-error">
        {{ $error }}
    </div>
@endif


{{-- =========================
     SALARY GRADE MONITORING
========================= --}}

<div class="summary-grid">

    {{-- TOTAL GRADES --}}
    <div class="summary-card">
        <div class="summary-label">
            Total Salary Grades
        </div>

        <div class="summary-value">
            {{ $totalSalaryGrades ?? count($salaryGrades) }}
        </div>

        <div class="summary-description">
            Active compensation levels configured
        </div>
    </div>


    {{-- LOWEST MINIMUM --}}
    <div class="summary-card">
        <div class="summary-label">
            Lowest Minimum Salary
        </div>

        <div class="summary-value">
            ₱{{ number_format(
                $lowestMinimumSalary ?? 0,
                2
            ) }}
        </div>

        <div class="summary-description">
            Lowest starting salary range
        </div>
    </div>


    {{-- HIGHEST MAXIMUM --}}
    <div class="summary-card highlight">
        <div class="summary-label">
            Highest Maximum Salary
        </div>

        <div class="summary-value">
            ₱{{ number_format(
                $highestMaximumSalary ?? 0,
                2
            ) }}
        </div>

        <div class="summary-description">
            Highest salary ceiling configured
        </div>
    </div>

</div>


<div class="summary-grid bottom">

    {{-- AVERAGE MINIMUM --}}
    <div class="summary-card">
        <div class="summary-label">
            Average Minimum Salary
        </div>

        <div class="summary-value">
            ₱{{ number_format(
                $averageMinimumSalary ?? 0,
                2
            ) }}
        </div>

        <div class="summary-description">
            Average starting salary across grades
        </div>
    </div>


    {{-- AVERAGE MAXIMUM --}}
    <div class="summary-card highlight">
        <div class="summary-label">
            Average Maximum Salary
        </div>

        <div class="summary-value">
            ₱{{ number_format(
                $averageMaximumSalary ?? 0,
                2
            ) }}
        </div>

        <div class="summary-description">
            Average salary ceiling across grades
        </div>
    </div>

</div>


{{-- =========================
     SALARY GRADE RECORDS
========================= --}}

<div class="records-card">

    <div class="records-header">

        <div>
            <h2 class="records-title">
                Salary Grade Records
            </h2>

            <p class="records-subtitle">
                View and manage all configured salary grades.
            </p>
        </div>

    </div>


    @if(count($salaryGrades))

        <table class="records-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Salary Grade</th>
                    <th>Minimum Salary</th>
                    <th>Maximum Salary</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @foreach($salaryGrades as $salaryGrade)

                    <tr>

                        {{-- ID --}}
                        <td>
                            {{ $salaryGrade['id'] ?? '-' }}
                        </td>


                        {{-- GRADE --}}
                        <td>
                            <span class="grade-badge">
                                {{ $salaryGrade['grade_name'] ?? '-' }}
                            </span>
                        </td>


                        {{-- MINIMUM SALARY --}}
                        <td class="salary-range">
                            ₱{{ number_format(
                                (float) ($salaryGrade['minimum_salary'] ?? 0),
                                2
                            ) }}
                        </td>


                        {{-- MAXIMUM SALARY --}}
                        <td class="salary-range">
                            ₱{{ number_format(
                                (float) ($salaryGrade['maximum_salary'] ?? 0),
                                2
                            ) }}
                        </td>


                        {{-- DESCRIPTION --}}
                        <td class="description-cell">
                            {{ $salaryGrade['description'] ?? '-' }}
                        </td>


                        {{-- ACTIONS --}}
                        <td>

                            <div class="actions">

                                <a
                                    href="{{ url('/salary-grades/' . ($salaryGrade['id'] ?? '')) . '/edit' }}"
                                    class="btn-action btn-edit"
                                >
                                    Edit
                                </a>


                                <form
                                    action="{{ url('/salary-grades/' . ($salaryGrade['id'] ?? '')) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this salary grade?');"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn-action btn-delete"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="empty-state">

            <h3>
                No salary grades found
            </h3>

            <p>
                Click "Add Salary Grade" to create the first salary grade.
            </p>

        </div>

    @endif

</div>

@endsection
