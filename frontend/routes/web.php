<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmailChangeRequestController;
use App\Http\Controllers\AdminEmailChangeRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\BenefitsController;
use App\Http\Controllers\ClaimsController;
use App\Http\Controllers\IncentivesController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\SalaryGradesController;
use App\Http\Controllers\Auth\TwoFactorController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Two-Factor Authentication Routes
| Auth-required but NOT behind 'two-factor' middleware (to avoid redirect loop)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'no-cache'])->group(function () {
    // Login challenge step
    Route::get('/two-factor/challenge',  [TwoFactorController::class, 'challenge'])->name('two-factor.challenge');
    Route::post('/two-factor/verify',    [TwoFactorController::class, 'verify'])->name('two-factor.verify');

    // Setup wizard (generate secret + QR)
    Route::get('/two-factor/setup',      [TwoFactorController::class, 'setup'])->name('two-factor.setup');
    Route::post('/two-factor/enable',    [TwoFactorController::class, 'enable'])->name('two-factor.enable');

    // Management (status, disable)
    Route::get('/two-factor/manage',     [TwoFactorController::class, 'manage'])->name('two-factor.manage');
    Route::post('/two-factor/disable',   [TwoFactorController::class, 'disable'])->name('two-factor.disable');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'no-cache', 'active.user', \App\Http\Middleware\TwoFactorMiddleware::class])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Claims - ALL AUTHENTICATED USERS (Employees see only their own)
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Employee')->group(function () {
        Route::get('/my-claims', [ClaimsController::class, 'index'])
            ->name('my-claims.index');
        Route::get('/my-claims/create', [ClaimsController::class, 'create'])
            ->name('my-claims.create');
        Route::post('/my-claims', [ClaimsController::class, 'store'])
            ->name('my-claims.store');
    });


    /*
    |--------------------------------------------------------------------------
    | Profile - ALL AUTHENTICATED USERS
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    /*
     * Employee-only personal information update.
     *
     * Employees can update only:
     * - First Name
     * - Last Name
     * - Phone
     *
     * Employee ID is taken from the authenticated user account.
     */
    Route::patch('/profile/employee', [ProfileController::class, 'updateEmployeeProfile'])
        ->middleware('role:Employee')
        ->name('profile.employee.update');

    Route::post('/profile/email-change-request', [EmailChangeRequestController::class, 'store'])
        ->middleware('role:Employee')
        ->name('profile.email-change-request.store');

    /*
     * Password change with OTP verification.
     */
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

    Route::get('/profile/password/otp', [ProfileController::class, 'showPasswordOtp'])
        ->name('profile.password.otp');

    Route::post('/profile/password/otp', [ProfileController::class, 'verifyPasswordOtp'])
        ->name('profile.password.otp.verify');

    /*
     * Delete account.
     */
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | ADMIN + HR ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Admin,HR')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Employees
        |--------------------------------------------------------------------------
        */

        Route::get('/employees', [EmployeeController::class, 'index'])
            ->name('employees.index');

        Route::get('/employees/create', [EmployeeController::class, 'create'])
            ->name('employees.create');

        Route::post('/employees', [EmployeeController::class, 'store'])
            ->name('employees.store');

        Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])
            ->name('employees.edit');

        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])
            ->name('employees.update');

        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])
            ->name('employees.destroy');


        /*
        |--------------------------------------------------------------------------
        | Payroll
        |--------------------------------------------------------------------------
        */

        Route::get('/payrolls', [PayrollController::class, 'index'])
            ->name('payrolls.index');

        Route::get('/payrolls/create', [PayrollController::class, 'create'])
            ->name('payrolls.create');

        Route::post('/payrolls', [PayrollController::class, 'store'])
            ->name('payrolls.store');

        Route::get('/payrolls/{payroll}/edit', [PayrollController::class, 'edit'])
            ->name('payrolls.edit');

        Route::put('/payrolls/{payroll}', [PayrollController::class, 'update'])
            ->name('payrolls.update');

        Route::delete('/payrolls/{payroll}', [PayrollController::class, 'destroy'])
            ->name('payrolls.destroy');

        Route::get('/payrolls/{payroll}/payslip', [PayrollController::class, 'payslip'])
            ->name('payrolls.payslip');


        /*
        |--------------------------------------------------------------------------
        | Benefits
        |--------------------------------------------------------------------------
        */

        Route::get('/benefits', [BenefitsController::class, 'index'])
            ->name('benefits.index');

        Route::get('/benefits/create', [BenefitsController::class, 'create'])
            ->name('benefits.create');

        Route::get('/benefits/data', [BenefitsController::class, 'refreshData'])
            ->name('benefits.data');

        Route::get('/benefits/export-contributions', [BenefitsController::class, 'exportContributions'])
            ->name('benefits.export');

        Route::post('/benefits', [BenefitsController::class, 'store'])
            ->name('benefits.store');

        Route::get('/benefits/{benefit}/edit', [BenefitsController::class, 'edit'])
            ->name('benefits.edit');

        Route::put('/benefits/{benefit}', [BenefitsController::class, 'update'])
            ->name('benefits.update');

        Route::delete('/benefits/{benefit}', [BenefitsController::class, 'destroy'])
            ->name('benefits.destroy');


        /*
        |--------------------------------------------------------------------------
        | Claims
        |--------------------------------------------------------------------------
        */

        Route::get('/claims', [ClaimsController::class, 'index'])
            ->name('claims.index');

        Route::get('/claims/data', [ClaimsController::class, 'refreshData'])
            ->name('claims.data');

        Route::get('/claims/create', [ClaimsController::class, 'create'])
            ->name('claims.create');

        Route::post('/claims', [ClaimsController::class, 'store'])
            ->name('claims.store');

        Route::get('/claims/{claim}/edit', [ClaimsController::class, 'edit'])
            ->name('claims.edit');

        Route::put('/claims/{claim}', [ClaimsController::class, 'update'])
            ->name('claims.update');

        Route::delete('/claims/{claim}', [ClaimsController::class, 'destroy'])
            ->name('claims.destroy');

        Route::patch('/claims/{claim}/approve', [ClaimsController::class, 'approve'])
            ->name('claims.approve');

        Route::patch('/claims/{claim}/reject', [ClaimsController::class, 'reject'])
            ->name('claims.reject');

        Route::patch('/claims/{claim}/return', [ClaimsController::class, 'returnClaim'])
            ->name('claims.return');


        /*
        |--------------------------------------------------------------------------
        | Incentives
        |--------------------------------------------------------------------------
        */

        Route::get('/incentives', [IncentivesController::class, 'index'])
            ->name('incentives.index');

        Route::get('/incentives/data', [IncentivesController::class, 'refreshData'])
            ->name('incentives.data');

        Route::get('/incentives/driver-trips', [IncentivesController::class, 'driverTrips'])
            ->name('incentives.driver-trips');

        Route::get('/incentives/driver-trips/data', [IncentivesController::class, 'driverTripsData'])
            ->name('incentives.driver-trips.data');

        Route::get('/incentives/create', [IncentivesController::class, 'create'])
            ->name('incentives.create');

        Route::post('/incentives', [IncentivesController::class, 'store'])
            ->name('incentives.store');

        Route::get('/incentives/{incentive}/edit', [IncentivesController::class, 'edit'])
            ->name('incentives.edit');

        Route::put('/incentives/{incentive}', [IncentivesController::class, 'update'])
            ->name('incentives.update');

        Route::delete('/incentives/{incentive}', [IncentivesController::class, 'destroy'])
            ->name('incentives.destroy');


        /*
        |--------------------------------------------------------------------------
        | HR Analytics (Powered by Google Gemini 2.5 Flash)
        |--------------------------------------------------------------------------
        */

        Route::get('/analytics', [AnalyticsController::class, 'index'])
            ->name('analytics.index');

        Route::post('/analytics/gemini/ask', [AnalyticsController::class, 'askGemini'])
            ->name('analytics.gemini.ask');

        Route::post('/analytics/gemini/refresh', [AnalyticsController::class, 'refreshReport'])
            ->name('analytics.gemini.refresh');

        Route::post('/analytics/gemini/configure-key', [AnalyticsController::class, 'configureKey'])
            ->name('analytics.gemini.configure-key');

    });


    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Admin')->group(function () {
/*
|--------------------------------------------------------------------------
| Email Change Requests
|--------------------------------------------------------------------------
*/

Route::get('/email-change-requests', [AdminEmailChangeRequestController::class, 'index'])
    ->name('email-change-requests.index');

Route::patch('/email-change-requests/{emailChangeRequest}/approve', [AdminEmailChangeRequestController::class, 'approve'])
    ->name('email-change-requests.approve');

Route::patch('/email-change-requests/{emailChangeRequest}/reject', [AdminEmailChangeRequestController::class, 'reject'])
    ->name('email-change-requests.reject');

        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */

        Route::get('/users', [UserManagementController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserManagementController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserManagementController::class, 'store'])
            ->name('users.store');

        Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])
            ->name('users.destroy');


        /*
        |--------------------------------------------------------------------------
        | Audit Logs
        |--------------------------------------------------------------------------
        */

        Route::get('/audit-logs', [AuditLogController::class, 'index'])
            ->name('audit-logs.index');

        Route::get('/audit-logs/latest', [AuditLogController::class, 'latest'])
            ->name('audit-logs.latest');


        /*
        |--------------------------------------------------------------------------
        | Salary Grades
        |--------------------------------------------------------------------------
        */

        Route::get('/salary-grades', [SalaryGradesController::class, 'index'])
            ->name('salary-grades.index');

        Route::get('/salary-grades/data', [SalaryGradesController::class, 'refreshData'])
            ->name('salary-grades.data');

        Route::get('/salary-grades/create', [SalaryGradesController::class, 'create'])
            ->name('salary-grades.create');

        Route::post('/salary-grades', [SalaryGradesController::class, 'store'])
            ->name('salary-grades.store');

        Route::get('/salary-grades/{salaryGrade}/edit', [SalaryGradesController::class, 'edit'])
            ->name('salary-grades.edit');

        Route::put('/salary-grades/{salaryGrade}', [SalaryGradesController::class, 'update'])
            ->name('salary-grades.update');

        Route::delete('/salary-grades/{salaryGrade}', [SalaryGradesController::class, 'destroy'])
            ->name('salary-grades.destroy');

    });

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
