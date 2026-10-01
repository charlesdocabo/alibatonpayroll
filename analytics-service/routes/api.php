<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyticsController;

Route::get('/analytics', [AnalyticsController::class, 'index']);
Route::get('/analytics/{id}', [AnalyticsController::class, 'show']);
Route::post('/analytics', [AnalyticsController::class, 'store']);