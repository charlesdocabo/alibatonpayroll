<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClaimController;
use App\Http\Controllers\Api\PettyCashController;

Route::apiResource('claims', ClaimController::class);

Route::patch('claims/{id}/approve', [ClaimController::class, 'approve']);
Route::patch('claims/{id}/reject', [ClaimController::class, 'reject']);
Route::patch('claims/{id}/return', [ClaimController::class, 'returnForRevision']);

// Petty Cash endpoints
Route::get('petty-cash/balance', [PettyCashController::class, 'balance']);
Route::get('petty-cash/transactions', [PettyCashController::class, 'transactions']);
Route::post('petty-cash/add-funds', [PettyCashController::class, 'addFunds']);
Route::post('claims/{id}/pay', [PettyCashController::class, 'payClaim']);
