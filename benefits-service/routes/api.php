<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BenefitController;

Route::apiResource('benefits', BenefitController::class);