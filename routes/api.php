<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(["prefix" => "user", 'as' => 'user.'], function () {
    // UnAuthorized Routes
    Route::post('/login', [AuthController::class, "login"])->name('login');
    Route::post('/register', [AuthController::class, "register"])->name('register');

    // Authorized Routes
    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('/checkIn', [AttendanceController::class, "checkIn"])->name('checkIn');
        Route::post('/checkOut', [AttendanceController::class, "checkOut"])->name('checkOut');

    });
});
