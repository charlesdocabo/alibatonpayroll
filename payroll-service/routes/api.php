<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PayrollController;

Route::apiResource('payrolls', PayrollController::class);