<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AttendanceController;

Route::apiResource('/employees', EmployeeController::class);
Route::apiResource('/departments', DepartmentController::class);

Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn']);
Route::put('/attendance/clock-out', [AttendanceController::class, 'clockOut']);
Route::get('/attendance/logs', [AttendanceController::class, 'logs']);
