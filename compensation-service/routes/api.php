<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SalaryGradeController;
use App\Http\Controllers\Api\IncentiveController;

Route::apiResource('salary-grades', SalaryGradeController::class);
Route::apiResource('incentives', IncentiveController::class);