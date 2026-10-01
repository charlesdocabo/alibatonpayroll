<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClaimController;

Route::apiResource('claims', ClaimController::class);