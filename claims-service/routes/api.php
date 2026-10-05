<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClaimController;

Route::apiResource('claims', ClaimController::class);
Route::patch('claims/{id}/approve', [ClaimController::class, 'approve']);
Route::patch('claims/{id}/reject', [ClaimController::class, 'reject']);
Route::patch('claims/{id}/return', [ClaimController::class, 'returnForRevision']);