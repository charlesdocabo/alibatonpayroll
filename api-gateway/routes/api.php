<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

// =========================
// EMPLOYEES
// =========================

Route::get('/employees', function () {
    return Http::get(
        'http://payroll-employee:8000/api/employees'
    )->json();
});

Route::post('/employees', function () {
    $response = Http::post(
        'http://payroll-employee:8000/api/employees',
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::get('/employees/{id}', function ($id) {
    $response = Http::get(
        "http://payroll-employee:8000/api/employees/{$id}"
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::put('/employees/{id}', function ($id) {
    $response = Http::put(
        "http://payroll-employee:8000/api/employees/{$id}",
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::patch('/employees/{id}', function ($id) {
    $response = Http::patch(
        "http://payroll-employee:8000/api/employees/{$id}",
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::delete('/employees/{id}', function ($id) {
    $response = Http::delete(
        "http://payroll-employee:8000/api/employees/{$id}"
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});
// =========================
// PAYROLL
// =========================

Route::get('/payrolls', function () {
    return Http::get(
        'http://payroll-service:8000/api/payrolls'
    )->json();
});

Route::post('/payrolls', function () {
    $response = Http::post(
        'http://payroll-service:8000/api/payrolls',
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::get('/payrolls/{id}', function ($id) {
    $response = Http::get(
        "http://payroll-service:8000/api/payrolls/{$id}"
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::put('/payrolls/{id}', function ($id) {
    $response = Http::put(
        "http://payroll-service:8000/api/payrolls/{$id}",
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::delete('/payrolls/{id}', function ($id) {
    $response = Http::delete(
        "http://payroll-service:8000/api/payrolls/{$id}"
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});
// =========================
// BENEFITS
// =========================

Route::get('/benefits', function () {
    return Http::get(
        'http://benefits-service:8000/api/benefits'
    )->json();
});

Route::post('/benefits', function () {
    $response = Http::post(
        'http://benefits-service:8000/api/benefits',
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::get('/benefits/{id}', function ($id) {
    $response = Http::get(
        "http://benefits-service:8000/api/benefits/{$id}"
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::put('/benefits/{id}', function ($id) {
    $response = Http::put(
        "http://benefits-service:8000/api/benefits/{$id}",
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::delete('/benefits/{id}', function ($id) {
    $response = Http::delete(
        "http://benefits-service:8000/api/benefits/{$id}"
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});
// =========================
// CLAIMS
// =========================

Route::get('/claims', function () {
    return Http::get(
        'http://claims-service:8000/api/claims'
    )->json();
});

Route::post('/claims', function () {
    $response = Http::post(
        'http://claims-service:8000/api/claims',
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::get('/claims/{id}', function ($id) {
    $response = Http::get(
        "http://claims-service:8000/api/claims/{$id}"
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::put('/claims/{id}', function ($id) {
    $response = Http::put(
        "http://claims-service:8000/api/claims/{$id}",
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::delete('/claims/{id}', function ($id) {
    $response = Http::delete(
        "http://claims-service:8000/api/claims/{$id}"
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::patch('/claims/{id}/approve', function ($id) {
    $response = Http::patch(
        "http://claims-service:8000/api/claims/{$id}/approve",
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::patch('/claims/{id}/reject', function ($id) {
    $response = Http::patch(
        "http://claims-service:8000/api/claims/{$id}/reject",
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::patch('/claims/{id}/return', function ($id) {
    $response = Http::patch(
        "http://claims-service:8000/api/claims/{$id}/return",
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});


// =========================
// INCENTIVES
// =========================

Route::get('/incentives', function () {
    return Http::get(
        'http://compensation-service:8000/api/incentives'
    )->json();
});

Route::post('/incentives', function () {
    $response = Http::post(
        'http://compensation-service:8000/api/incentives',
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::get('/incentives/{id}', function ($id) {
    $response = Http::get(
        "http://compensation-service:8000/api/incentives/{$id}"
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::put('/incentives/{id}', function ($id) {
    $response = Http::put(
        "http://compensation-service:8000/api/incentives/{$id}",
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::delete('/incentives/{id}', function ($id) {
    $response = Http::delete(
        "http://compensation-service:8000/api/incentives/{$id}"
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});
// =========================
// SALARY GRADES
// =========================

Route::get('/salary-grades', function () {
    return Http::get(
        'http://compensation-service:8000/api/salary-grades'
    )->json();
});

Route::post('/salary-grades', function () {
    $response = Http::post(
        'http://compensation-service:8000/api/salary-grades',
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::get('/salary-grades/{id}', function ($id) {
    $response = Http::get(
        "http://compensation-service:8000/api/salary-grades/{$id}"
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::put('/salary-grades/{id}', function ($id) {
    $response = Http::put(
        "http://compensation-service:8000/api/salary-grades/{$id}",
        request()->all()
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

Route::delete('/salary-grades/{id}', function ($id) {
    $response = Http::delete(
        "http://compensation-service:8000/api/salary-grades/{$id}"
    );

    return response()->json(
        $response->json(),
        $response->status()
    );
});

// =========================
// ANALYTICS
// =========================

Route::get('/analytics', function () {
    return Http::get('http://analytics-service:8000/api/analytics')->json();
});


