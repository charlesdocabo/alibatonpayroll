<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PayrollController;
use App\Http\Controllers\Api\PayrollDisputeController;

Route::apiResource('payrolls', PayrollController::class);

Route::get('/payroll-disputes', [PayrollDisputeController::class, 'index']);
Route::post('/payroll-disputes', [PayrollDisputeController::class, 'store']);
Route::get('/payroll-disputes/{id}', [PayrollDisputeController::class, 'show']);
Route::put('/payroll-disputes/{id}', [PayrollDisputeController::class, 'update']);
